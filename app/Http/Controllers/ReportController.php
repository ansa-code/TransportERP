<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Assignment;
use App\Models\Client;
use App\Models\Driver;
use App\Models\DriverAdvance;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Fuel;
use App\Models\FuelRecovery;
use App\Models\Invoice;
use App\Models\Leave;
use App\Models\Maintenance;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\TrafficFine;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reports Center
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Selected Period
        |--------------------------------------------------------------------------
        */

        $period = $request->input('period', 'monthly');

        if (!in_array($period, [
            'daily',
            'weekly',
            'monthly',
            'quarterly',
            'yearly',
            'custom',
        ], true)) {
            $period = 'monthly';
        }

        [$startDate, $endDate] = $this->resolveDateRange(
            $period,
            $request->input('start_date'),
            $request->input('end_date')
        );

        // FRD: reports support date + relevant entity/status filters.
        $filters = [
            'vehicle_id' => $request->filled('vehicle_id') ? (int) $request->input('vehicle_id') : null,
            'driver_id' => $request->filled('driver_id') ? (int) $request->input('driver_id') : null,
            'client_id' => $request->filled('client_id') ? (int) $request->input('client_id') : null,
            'vendor_id' => $request->filled('vendor_id') ? (int) $request->input('vendor_id') : null,
            'assignment_id' => $request->filled('assignment_id') ? (int) $request->input('assignment_id') : null,
            'status' => $request->filled('status') ? $request->input('status') : null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Master Data For Filters
        |--------------------------------------------------------------------------
        */

        $vehicles = Vehicle::query()
            ->when($filters['vehicle_id'] ?? null, fn ($q, $id) => $q->where('id', $id))
            ->orderBy('plate_number')
            ->get([
                'id',
                'plate_number',
                'vehicle_type',
                'category',
                'status',
            ]);

        $drivers = Driver::query()
            ->orderBy('driver_name')
            ->get([
                'id',
                'driver_name',
                'status',
                'employment_status',
            ]);

        $clients = Client::query()
            ->orderBy('client_name')
            ->get([
                'id',
                'client_name',
                'company_name',
                'status',
            ]);

        $vendors = Vendor::query()
            ->orderBy('vendor_name')
            ->get([
                'id',
                'vendor_name',
                'company_name',
                'status',
            ]);

        $assignments = Assignment::query()
            ->orderByDesc('id')
            ->get([
                'id',
                'assignment_no',
                'client_id',
                'vehicle_id',
                'driver_id',
                'start_date',
                'end_date',
                'rate',
                'rate_basis',
                'freight_amount',
                'status',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Fleet KPIs
        |--------------------------------------------------------------------------
        */

        $totalVehicles = Vehicle::count();

        $vehicleStatusCounts = Vehicle::query()
            ->selectRaw('status, COUNT(*) as total')
            ->whereNotNull('status')
            ->groupBy('status')
            ->pluck('total', 'status');

        $activeVehicles = (int) (
            $vehicleStatusCounts['Active']
            ?? $vehicleStatusCounts['active']
            ?? 0
        );

        $inactiveVehicles = (int) (
            $vehicleStatusCounts['Inactive']
            ?? $vehicleStatusCounts['inactive']
            ?? 0
        );

        $maintenanceVehicles = (int) (
            $vehicleStatusCounts['Under Maintenance']
            ?? $vehicleStatusCounts['Maintenance']
            ?? $vehicleStatusCounts['maintenance']
            ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Active Assignments
        |--------------------------------------------------------------------------
        */

        $activeAssignmentStatuses = [
            'Active',
            'Assigned',
            'In Progress',
        ];

        $activeAssignments = Assignment::query()
            ->whereIn('status', $activeAssignmentStatuses)
            ->count();

        $assignedVehicles = Vehicle::query()
            ->whereHas('assignments', function ($query) use (
                $activeAssignmentStatuses
            ) {
                $query->whereIn(
                    'status',
                    $activeAssignmentStatuses
                );
            })
            ->count();

        $idleVehicles = max(
            0,
            $activeVehicles
            - $assignedVehicles
            - $maintenanceVehicles
        );

        /*
        |--------------------------------------------------------------------------
        | Driver KPIs
        |--------------------------------------------------------------------------
        */

                  $totalDrivers = Driver::count();

$activeDrivers = Driver::query()
    ->where(function ($query) {
        $query->where('status', 'Active')
            ->orWhere('employment_status', 'Active');
    })
    ->count();

$onLeaveDrivers = Leave::query()
    ->whereDate(
        'start_date',
        '<=',
        Carbon::today()->toDateString()
    )
    ->whereDate(
        'end_date',
        '>=',
        Carbon::today()->toDateString()
    )
    ->distinct('driver_id')
    ->count('driver_id');

/*
|--------------------------------------------------------------------------
| Revenue
|--------------------------------------------------------------------------
*/

$revenue = (float) Invoice::query()
    ->whereBetween('invoice_date', [
        $startDate->toDateString(),
        $endDate->toDateString(),
    ])
    ->whereNotIn('status', ['Cancelled'])
    ->sum('total_amount');

/*
|--------------------------------------------------------------------------
| Expenses
|--------------------------------------------------------------------------
*/

$expenseTotal = (float) Expense::query()
    ->whereBetween('expense_date', [
        $startDate->toDateString(),
        $endDate->toDateString(),
    ])
    ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Payroll
        |--------------------------------------------------------------------------
        */

        $payrollTotal = (float) Payroll::query()
            ->whereBetween('salary_month', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum('net_salary');

        /*
        |--------------------------------------------------------------------------
        | Fuel
        |--------------------------------------------------------------------------
        */

        $fuelTotal = (float) Fuel::query()
            ->whereBetween('fuel_date', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->sum('total_amount');

        $reimbursableFuel = (float) Fuel::query()
            ->whereBetween('fuel_date', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->where('reimbursable', true)
            ->sum('reimbursement_amount');

        /*
        |--------------------------------------------------------------------------
        | Maintenance
        |--------------------------------------------------------------------------
        */

        $maintenanceTotal = (float) Maintenance::query()
            ->whereBetween('maintenance_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum('total_cost');

        /*
        |--------------------------------------------------------------------------
        | Traffic Fines
        |--------------------------------------------------------------------------
        */

        $trafficFineTotal = (float) TrafficFine::query()
            ->whereBetween('fine_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Invoice Outstanding
        |--------------------------------------------------------------------------
        */

        $outstandingInvoices = (float) Invoice::query()
            ->where('balance', '>', 0)
            ->sum('balance');

        $overdueBalance = (float) Invoice::query()
            ->where('balance', '>', 0)
            ->whereDate(
                'due_date',
                '<',
                Carbon::today()->toDateString()
            )
            ->sum('balance');

        /*
        |--------------------------------------------------------------------------
        | Payment / Recovery
        |--------------------------------------------------------------------------
        */

        $paymentsReceived = (float) Payment::query()
            ->whereBetween('payment_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum('amount');

        $allocatedPayments = (float) Payment::query()
            ->whereBetween('payment_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum(
                \DB::raw(
                    'amount - COALESCE(unallocated_amount, 0)'
                )
            );

        /*
        |--------------------------------------------------------------------------
        | Net Management Result
        |--------------------------------------------------------------------------
        */

        $netProfit = $revenue
            - $expenseTotal
            - $payrollTotal
            - $fuelTotal
            - $maintenanceTotal
            - $trafficFineTotal;

        /*
        |--------------------------------------------------------------------------
        | Period Information
        |--------------------------------------------------------------------------
        */

        $periodData = [
            'period' => $period,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'start_label' => $startDate->format('d M Y'),
            'end_label' => $endDate->format('d M Y'),
        ];

        /*
        |--------------------------------------------------------------------------
        | Complete Report Dataset
        |--------------------------------------------------------------------------
        */

        $reportData = $this->buildReportData(
            $startDate,
            $endDate,
            $filters
        );

        /*
        |--------------------------------------------------------------------------
        | Reports Center View
        |--------------------------------------------------------------------------
        */

        return view('reports.index', [
            /*
            | Period
            */
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodData' => $periodData,

            /*
            | Master Filters
            */
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'clients' => $clients,
            'vendors' => $vendors,
            'assignments' => $assignments,
            'filters' => $filters,

            /*
            | Fleet
            */
            'totalVehicles' => $totalVehicles,
            'activeVehicles' => $activeVehicles,
            'inactiveVehicles' => $inactiveVehicles,
            'assignedVehicles' => $assignedVehicles,
            'maintenanceVehicles' => $maintenanceVehicles,
            'idleVehicles' => $idleVehicles,

            /*
            | Drivers / Operations
            */
            'totalDrivers' => $totalDrivers,
            'activeDrivers' => $activeDrivers,
            'onLeaveDrivers' => $onLeaveDrivers,
            'activeAssignments' => $activeAssignments,

            /*
            | Finance
            */
            'revenue' => $revenue,
            'expenses' => $expenseTotal,
            'payroll' => $payrollTotal,
            'netProfit' => $netProfit,
            'outstandingInvoices' => $outstandingInvoices,
            'overdueBalance' => $overdueBalance,
            'paymentsReceived' => $paymentsReceived,
            'allocatedPayments' => $allocatedPayments,

            /*
            | Fuel / Maintenance / Compliance
            */
            'fuelCost' => $fuelTotal,
            'reimbursableFuel' => $reimbursableFuel,
            'maintenanceCost' => $maintenanceTotal,
            'trafficFines' => $trafficFineTotal,

            /*
            | Full Report Data
            */
            'reportData' => $reportData,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Report Date Range
    |--------------------------------------------------------------------------
    */

    private function resolveDateRange(
        string $period,
        ?string $customStart = null,
        ?string $customEnd = null
    ): array {
        $today = Carbon::today();

        switch ($period) {
            case 'daily':
                $start = $today->copy()->startOfDay();
                $end = $today->copy()->endOfDay();
                break;

            case 'weekly':
                $start = $today->copy()->startOfWeek();
                $end = $today->copy()->endOfWeek();
                break;

            case 'quarterly':
                $start = $today->copy()->startOfQuarter();
                $end = $today->copy()->endOfQuarter();
                break;

            case 'yearly':
                $start = $today->copy()->startOfYear();
                $end = $today->copy()->endOfYear();
                break;

            case 'custom':
                try {
                    $start = $customStart
                        ? Carbon::parse($customStart)->startOfDay()
                        : $today->copy()->startOfMonth();

                    $end = $customEnd
                        ? Carbon::parse($customEnd)->endOfDay()
                        : $today->copy()->endOfDay();
                } catch (\Throwable $e) {
                    $start = $today->copy()->startOfMonth();
                    $end = $today->copy()->endOfDay();
                }
                break;

            case 'monthly':
            default:
                $start = $today->copy()->startOfMonth();
                $end = $today->copy()->endOfMonth();
                break;
        }

        if ($end->lt($start)) {
            $end = $start->copy()->endOfDay();
        }

        return [$start, $end];
    }
    /*
    |--------------------------------------------------------------------------
    | Build Complete Report Dataset
    |--------------------------------------------------------------------------
    */

    private function buildReportData(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return [
            // RPT-001 — RPT-008 Executive / Management
            'executive_kpi' => $this->executiveKpi($startDate, $endDate, $filters),
            'revenue_expense_trend' => $this->revenueExpenseTrend($startDate, $endDate, $filters),
            'profit_trend' => $this->profitTrend($startDate, $endDate, $filters),
            'cash_receivable_summary' => $this->cashReceivableSummary($startDate, $endDate, $filters),
            'outstanding_reimbursement' => $this->outstandingReimbursementSummary($startDate, $endDate, $filters),
            'top_low_profit_vehicles' => $this->topLowProfitVehicles($startDate, $endDate, $filters),
            'top_clients' => $this->topClients($startDate, $endDate, $filters),
            'risk_expiry_summary' => $this->documentExpiryCenter(),

            // RPT-009 — RPT-020 Fleet
            'vehicles' => $this->vehicleMaster($filters),
            'vehicle_master' => $this->vehicleMaster($filters),
            'fleet_status_summary' => $this->fleetStatusSummary($filters),
            'fleet_type_summary' => $this->fleetTypeSummary($filters),
            'vehicle_utilisation' => $this->vehicleUtilisation($startDate, $endDate, $filters),
            'idle_vehicle' => $this->idleVehicle($startDate, $endDate, $filters),
            'vehicle_revenue' => $this->vehicleRevenue($startDate, $endDate, $filters),
            'vehicle_expense' => $this->vehicleExpense($startDate, $endDate, $filters),
            'vehicle_profitability' => $this->vehicleProfitability($startDate, $endDate, $filters),
            'fuel_by_vehicle' => $this->fuelByVehicle($startDate, $endDate, $filters),
            'maintenance_by_vehicle' => $this->maintenanceByVehicle($startDate, $endDate, $filters),
            'fine_history_by_vehicle' => $this->fineHistoryByVehicle($startDate, $endDate, $filters),
            'fleet_health' => $this->fleetHealth($startDate, $endDate, $filters),

            // RPT-021 — RPT-030 Drivers / HR
            'drivers' => $this->driverMaster($filters),
            'driver_master' => $this->driverMaster($filters),
            'driver_status_summary' => $this->driverStatusSummary($filters),
            'assignment_trip_history' => $this->assignmentSummary($startDate, $endDate, $filters),
            'leave_summary' => $this->leaveSummary($startDate, $endDate, $filters),
            'salary_report' => $this->payrollSummary($startDate, $endDate, $filters),
            'advance_summary' => $this->advanceSummary($startDate, $endDate, $filters),
            'outstanding_advance' => $this->outstandingAdvance($filters),
            'driver_fine_deduction' => $this->driverFineDeduction($startDate, $endDate, $filters),
            'driver_expiry_summary' => $this->driverExpirySummary(),

            // RPT-031 — RPT-039 Clients
            'clients' => $this->clientMaster($filters),
            'client_master' => $this->clientMaster($filters),
            'client_contract_expiry' => $this->clientExpirySummary(),
            'client_revenue_summary' => $this->clientRevenueSummary($startDate, $endDate, $filters),
            'client_expense' => $this->clientExpense($startDate, $endDate, $filters),
            'client_profitability' => $this->clientProfitability($startDate, $endDate, $filters),
            'client_outstanding' => $this->clientOutstanding($filters),
            'client_ledger' => $this->paymentSummary($startDate, $endDate, $filters),
            'client_fuel_reimbursement' => $this->clientFuelReimbursement($startDate, $endDate, $filters),
            'top_low_margin_clients' => $this->topClients($startDate, $endDate, $filters),

            // RPT-040 — RPT-045 Vendors
            'vendors' => $this->vendorSummary(),
            'vendor_master' => $this->vendorSummary(),
            'vendor_vehicle' => $this->vendorVehicleReport($startDate, $endDate, $filters),
            'vendor_rate' => $this->vendorRateReport($filters),
            'vendor_payable' => $this->vendorPayableReport($startDate, $endDate, $filters),
            'vendor_ledger' => $this->vendorLedgerReport($startDate, $endDate, $filters),
            'workshop_spend' => $this->workshopSpend($startDate, $endDate, $filters),

            // RPT-046 — RPT-054 Operations
            'assignment_summary' => $this->assignmentSummary($startDate, $endDate, $filters),
            'active_assignments' => $this->assignmentStatusReport($startDate, $endDate, $filters, ['Active','Assigned','In Progress']),
            'completed_assignments' => $this->assignmentStatusReport($startDate, $endDate, $filters, ['Completed']),
            'trip_summary' => $this->tripSummary($startDate, $endDate, $filters),
            'trip_status' => $this->tripStatusSummary($startDate, $endDate, $filters),
            'trip_profitability' => $this->tripProfitability($startDate, $endDate, $filters),
            'route_profitability' => $this->routeProfitability($startDate, $endDate, $filters),
            'vehicle_driver_history' => $this->vehicleDriverHistory($startDate, $endDate, $filters),
            'operational_performance' => $this->operationalPerformance($startDate, $endDate, $filters),
            'cancelled_trips' => $this->cancelledTrips($startDate, $endDate, $filters),

            // RPT-055 — RPT-063 Fuel / Maintenance
            'fuel_summary' => $this->fuelSummary($startDate, $endDate, $filters),
            'fuel_consumption' => $this->fuelConsumption($startDate, $endDate, $filters),
            'fuel_trend' => $this->fuelTrend($startDate, $endDate, $filters),
            'reimbursable_fuel' => $this->reimbursableFuelReport($startDate, $endDate, $filters),
            'fuel_recovery_ageing' => $this->fuelRecoveryAgeingReport($startDate, $endDate, $filters),
            'maintenance_summary' => $this->maintenanceSummary($startDate, $endDate, $filters),
            'maintenance_cost_trend' => $this->maintenanceTrend($startDate, $endDate, $filters),
            'vehicle_downtime' => $this->vehicleDowntime($startDate, $endDate, $filters),
            'preventive_maintenance_due' => $this->preventiveMaintenanceDue(),

            // RPT-064 — RPT-075 Finance
            'invoice_summary' => $this->invoiceSummary($startDate, $endDate, $filters),
            'invoice_ageing' => $this->invoiceAgeing($filters),
            'outstanding_invoices' => $this->outstandingInvoiceReport($filters),
            'payment_summary' => $this->paymentSummary($startDate, $endDate, $filters),
            'payment_allocation' => $this->paymentAllocationReport($startDate, $endDate, $filters),
            'expense_summary' => $this->expenseSummary($startDate, $endDate, $filters),
            'expense_by_category' => $this->expenseByCategory($startDate, $endDate, $filters),
            'payroll_summary' => $this->payrollSummary($startDate, $endDate, $filters),
            'payroll_deduction' => $this->payrollDeductionReport($startDate, $endDate, $filters),
            'revenue_report' => $this->invoiceSummary($startDate, $endDate, $filters),
            'monthly_pnl' => $this->profitTrend($startDate, $endDate, $filters),
            'client_vehicle_profitability' => $this->clientVehicleProfitability($startDate, $endDate, $filters),
            'vat_summary' => $this->vatSummary($startDate, $endDate, $filters),

            // RPT-076 — RPT-082 Compliance / Documents
            'documents' => $this->allDocumentsReport($filters),
            'document_expiry' => $this->documentExpiryCenter(),
            'expiry_7_days' => $this->expiryWindow(7),
            'expiry_15_days' => $this->expiryWindow(15),
            'expiry_30_days' => $this->expiryWindow(30),
            'expired_documents' => $this->expiredDocuments(),
            'missing_documents' => $this->missingDocuments(),
            'expiry_by_type' => $this->documentExpiryCenter(),
            'vehicle_expiry_summary' => $this->vehicleExpirySummary(),
            'client_expiry_summary' => $this->clientExpirySummary(),

            // RPT-083 — RPT-087 Audit
            'user_activity' => $this->userActivity($startDate, $endDate),
            'record_change_history' => $this->recordChangeHistory($startDate, $endDate),
            'archived_records' => $this->archivedRecords($startDate, $endDate),
            'payment_activity' => $this->paymentActivity($startDate, $endDate, $filters),
            'permission_login_activity' => $this->permissionLoginActivity($startDate, $endDate),
            'record_counts' => $this->recordCounts(),

            // Compatibility keys used by the existing Reports View.
            'expiry_center' => $this->documentExpiryCenter(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Executive - Revenue / Expense Trend
    |--------------------------------------------------------------------------
    */

    private function revenueExpenseTrend(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $results = [];

        $cursor = $startDate->copy()->startOfMonth();
        $lastMonth = $endDate->copy()->startOfMonth();

        while ($cursor->lte($lastMonth)) {
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();

            if ($monthStart->lt($startDate)) {
                $monthStart = $startDate->copy();
            }

            if ($monthEnd->gt($endDate)) {
                $monthEnd = $endDate->copy();
            }

            $revenue = (float) Invoice::query()
                ->whereBetween('invoice_date', [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString(),
                ])
                ->whereNotIn('status', ['Cancelled'])
                ->sum('total_amount');

            $expenses = (float) Expense::query()
                ->whereBetween('expense_date', [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString(),
                ])
                ->sum('amount');

            $payroll = (float) Payroll::query()
                ->whereBetween('salary_month', [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString(),
                ])
                ->sum('net_salary');

            $fuel = (float) Fuel::query()
                ->whereBetween('fuel_date', [
                    $monthStart->copy()->startOfDay(),
                    $monthEnd->copy()->endOfDay(),
                ])
                ->sum('total_amount');

            $maintenance = (float) Maintenance::query()
                ->whereBetween('maintenance_date', [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString(),
                ])
                ->sum('total_cost');

            $fines = (float) TrafficFine::query()
                ->whereBetween('fine_date', [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString(),
                ])
                ->sum('amount');

            $totalCost = $expenses
                + $payroll
                + $fuel
                + $maintenance
                + $fines;

            $results[] = [
                'label' => $cursor->format('M Y'),
                'revenue' => $revenue,
                'expenses' => $expenses,
                'payroll' => $payroll,
                'fuel' => $fuel,
                'maintenance' => $maintenance,
                'fines' => $fines,
                'total_cost' => $totalCost,
                'profit' => $revenue - $totalCost,
            ];

            $cursor->addMonth();
        }

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | Executive - Profit Trend
    |--------------------------------------------------------------------------
    */

    private function profitTrend(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return collect(
            $this->revenueExpenseTrend(
                $startDate,
                $endDate
            )
        )
            ->map(function ($row) {
                return [
                    'label' => $row['label'],
                    'revenue' => $row['revenue'],
                    'cost' => $row['total_cost'],
                    'profit' => $row['profit'],
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Fleet - Vehicle Type
    |--------------------------------------------------------------------------
    */

    private function fleetTypeSummary(array $filters = []): array
    {
        return Vehicle::query()
            ->selectRaw(
                "COALESCE(vehicle_type, 'Unknown') as vehicle_type,
                 COUNT(*) as total"
            )
            ->groupBy('vehicle_type')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                return [
                    'vehicle_type' => $row->vehicle_type,
                    'total' => (int) $row->total,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Fleet - Status
    |--------------------------------------------------------------------------
    */

    private function fleetStatusSummary(array $filters = []): array
    {
        return Vehicle::query()
            ->selectRaw(
                "COALESCE(status, 'Unknown') as status,
                 COUNT(*) as total"
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                return [
                    'status' => $row->status,
                    'total' => (int) $row->total,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Fleet - Utilisation
    |--------------------------------------------------------------------------
    */

    private function vehicleUtilisation(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $vehicles = Vehicle::query()
            ->withCount([
                'assignments' => function ($query) use (
                    $startDate,
                    $endDate
                ) {
                    $query->whereDate(
                        'start_date',
                        '<=',
                        $endDate->toDateString()
                    )
                    ->where(function ($q) use ($startDate) {
                        $q->whereNull('end_date')
                            ->orWhereDate(
                                'end_date',
                                '>=',
                                $startDate->toDateString()
                            );
                    });
                },
            ])
            ->orderBy('plate_number')
            ->get([
                'id',
                'plate_number',
                'vehicle_type',
                'category',
                'status',
            ]);

        return $vehicles
            ->map(function ($vehicle) {
                return [
                    'vehicle_id' => $vehicle->id,
                    'plate_number' => $vehicle->plate_number,
                    'vehicle_type' => $vehicle->vehicle_type,
                    'category' => $vehicle->category,
                    'status' => $vehicle->status,
                    'assignments' => (int) $vehicle->assignments_count,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Driver Status
    |--------------------------------------------------------------------------
    */

    private function driverStatusSummary(array $filters = []): array
    {
        return Driver::query()
            ->selectRaw(
                "COALESCE(status, 'Unknown') as status,
                 COUNT(*) as total"
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                return [
                    'status' => $row->status,
                    'total' => (int) $row->total,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Driver Expiry
    |--------------------------------------------------------------------------
    */

    private function driverExpirySummary(): array
    {
        $today = Carbon::today();
        $limit = $today->copy()->addDays(30);

        $drivers = Driver::query()
            ->get([
                'id',
                'driver_name',
                'license_expiry',
                'visa_expiry',
                'status',
            ]);

        $results = [];

        foreach ($drivers as $driver) {
            if ($driver->license_expiry) {
                $expiry = Carbon::parse(
                    $driver->license_expiry
                );

                if ($expiry->lte($limit)) {
                    $results[] = [
                        'entity' => $driver->driver_name,
                        'document' => 'Driving Licence',
                        'expiry_date' => $expiry->toDateString(),
                        'days_remaining' => $expiry->isPast()
                            ? 0
                            : $today->diffInDays($expiry),
                        'status' => $driver->status,
                    ];
                }
            }

            if ($driver->visa_expiry) {
                $expiry = Carbon::parse(
                    $driver->visa_expiry
                );

                if ($expiry->lte($limit)) {
                    $results[] = [
                        'entity' => $driver->driver_name,
                        'document' => 'Visa',
                        'expiry_date' => $expiry->toDateString(),
                        'days_remaining' => $expiry->isPast()
                            ? 0
                            : $today->diffInDays($expiry),
                        'status' => $driver->status,
                    ];
                }
            }
        }

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Summary
    |--------------------------------------------------------------------------
    */

    private function leaveSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Leave::query()
            ->with('driver:id,driver_name')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate(
                    'start_date',
                    '<=',
                    $endDate->toDateString()
                )
                ->whereDate(
                    'end_date',
                    '>=',
                    $startDate->toDateString()
                );
            })
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($leave) {
                return [
                    'driver' => $leave->driver?->driver_name,
                    'leave_type' => $leave->leave_type,
                    'start_date' => optional(
                        $leave->start_date
                    )->format('d M Y'),
                    'end_date' => optional(
                        $leave->end_date
                    )->format('d M Y'),
                    'approval_status' => $leave->approval_status,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Driver Advance Summary
    |--------------------------------------------------------------------------
    */

    private function advanceSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return DriverAdvance::query()
            ->with('driver:id,driver_name')
            ->whereBetween('advance_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('advance_date')
            ->get()
            ->map(function ($advance) {
                return [
                    'driver' => $advance->driver?->driver_name,
                    'advance_date' => optional(
                        $advance->advance_date
                    )->format('d M Y'),
                    'amount' => (float) $advance->amount,
                    'status' => $advance->status,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Client Revenue
    |--------------------------------------------------------------------------
    */

    private function clientRevenueSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $clients = Client::query()
            ->orderBy('client_name')
            ->get([
                'id',
                'client_name',
                'company_name',
                'status',
            ]);

        return $clients
            ->map(function ($client) use (
                $startDate,
                $endDate
            ) {
                $revenue = (float) Invoice::query()
                    ->where('client_id', $client->id)
                    ->whereBetween('invoice_date', [
                        $startDate->toDateString(),
                        $endDate->toDateString(),
                    ])
                    ->whereNotIn('status', ['Cancelled'])
                    ->sum('total_amount');

                return [
                    'client_id' => $client->id,
                    'client_name' => $client->client_name,
                    'company_name' => $client->company_name,
                    'revenue' => $revenue,
                ];
                   })
            ->values()
            ->all();
    }
          /*
|--------------------------------------------------------------------------
| Clients - Profitability
|--------------------------------------------------------------------------
*/

private function clientProfitability(
    Carbon $startDate,
    Carbon $endDate,
    array $filters = []
): array {
    $clients = Client::query()
        ->when($filters['client_id'] ?? null, fn ($q, $id) => $q->where('id', $id))
        ->orderBy('client_name')
        ->get();

    return $clients->map(function ($client) use (
        $startDate,
        $endDate
    ) {
        $revenue = (float) Invoice::query()
            ->where('client_id', $client->id)
            ->whereBetween('invoice_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->whereNotIn('status', ['Cancelled'])
            ->sum('total_amount');

        $fuelCost = (float) Fuel::query()
            ->where('client_id', $client->id)
            ->whereBetween('fuel_date', [
                $startDate,
                $endDate,
            ])
            ->sum('total_amount');

        $expenseCost = (float) Expense::query()
            ->where('client_id', $client->id)
            ->whereBetween('expense_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum('amount');

        $profit = $revenue - $fuelCost - $expenseCost;

        $margin = $revenue > 0
            ? ($profit / $revenue) * 100
            : 0;

        return [
            'client_id' => $client->id,
            'client_name' => $client->client_name,
            'company_name' => $client->company_name,
            'revenue' => $revenue,
            'fuel_cost' => $fuelCost,
            'expense_cost' => $expenseCost,
            'cost' => $fuelCost + $expenseCost,
            'profit' => $profit,
            'margin' => $margin,
        ];
    })
    ->values()
    ->all();
}
                        
                /*
    |--------------------------------------------------------------------------
    | Operations - Assignment Summary
    |--------------------------------------------------------------------------
    */

    private function assignmentSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Assignment::query()
            ->with([
                'client:id,client_name',
                'vehicle:id,plate_number,vehicle_type',
                'driver:id,driver_name',
            ])
            ->whereDate(
                'start_date',
                '<=',
                $endDate->toDateString()
            )
            ->where(function ($query) use ($startDate) {
                $query->whereNull('end_date')
                    ->orWhereDate(
                        'end_date',
                        '>=',
                        $startDate->toDateString()
                    );
            })
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($assignment) {
                return [
                    'assignment_no' => $assignment->assignment_no,
                    'client' => $assignment->client?->client_name,
                    'vehicle' => $assignment->vehicle?->plate_number,
                    'vehicle_type' => $assignment->vehicle?->vehicle_type,
                    'driver' => $assignment->driver?->driver_name,
                    'start_date' => $assignment->start_date
                        ? Carbon::parse(
                            $assignment->start_date
                        )->format('d M Y')
                        : null,
                    'end_date' => $assignment->end_date
                        ? Carbon::parse(
                            $assignment->end_date
                        )->format('d M Y')
                        : null,
                    'rate' => (float) $assignment->rate,
                    'rate_basis' => $assignment->rate_basis,
                    'freight_amount' => (float) $assignment->freight_amount,
                    'status' => $assignment->status,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Operations - Trip Summary
    |--------------------------------------------------------------------------
    */

    private function tripSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Trip::query()
            ->with([
                'assignment:id,assignment_no',
                'client:id,client_name',
                'vehicle:id,plate_number,vehicle_type',
                'driver:id,driver_name',
            ])
            ->whereDate(
                'trip_start',
                '<=',
                $endDate->toDateString()
            )
            ->where(function ($query) use ($startDate) {
                $query->whereNull('trip_end')
                    ->orWhereDate(
                        'trip_end',
                        '>=',
                        $startDate->toDateString()
                    );
            })
            ->orderByDesc('trip_start')
            ->get()
            ->map(function ($trip) {
                return [
                    'trip_no' => $trip->trip_no,
                    'assignment_no' => $trip->assignment?->assignment_no,
                    'client' => $trip->client?->client_name,
                    'vehicle' => $trip->vehicle?->plate_number,
                    'vehicle_type' => $trip->vehicle?->vehicle_type,
                    'driver' => $trip->driver?->driver_name,
                    'trip_start' => $trip->trip_start
                        ? Carbon::parse(
                            $trip->trip_start
                        )->format('d M Y H:i')
                        : null,
                    'trip_end' => $trip->trip_end
                        ? Carbon::parse(
                            $trip->trip_end
                        )->format('d M Y H:i')
                        : null,
                    'rate' => (float) $trip->rate,
                    'freight_amount' => (float) $trip->freight_amount,
                    'status' => $trip->status,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Fuel - Detailed Summary
    |--------------------------------------------------------------------------
    */

    private function fuelSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Fuel::query()
            ->with([
                'vehicle:id,plate_number,vehicle_type',
                'driver:id,driver_name',
                'client:id,client_name',
                'assignment:id,assignment_no',
                'trip:id,trip_no',
            ])
            ->whereBetween('fuel_date', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->orderByDesc('fuel_date')
            ->get()
            ->map(function ($fuel) {
                return [
                    'fuel_entry_no' => $fuel->fuel_entry_no,
                    'date' => $fuel->fuel_date
                        ? Carbon::parse(
                            $fuel->fuel_date
                        )->format('d M Y H:i')
                        : null,
                    'vehicle' => $fuel->vehicle?->plate_number,
                    'vehicle_type' => $fuel->vehicle?->vehicle_type,
                    'driver' => $fuel->driver?->driver_name,
                    'client' => $fuel->client?->client_name,
                    'assignment_no' => $fuel->assignment?->assignment_no,
                    'trip_no' => $fuel->trip?->trip_no,
                    'liters' => (float) $fuel->liters,
                    'unit_price' => (float) $fuel->price_per_liter,
                    'total_cost' => (float) $fuel->total_amount,
                    'odometer' => $fuel->odometer,
                    'paid_by' => $fuel->paid_by,
                    'reimbursable' => (bool) $fuel->reimbursable,
                    'reimbursement_amount' => (float) (
                        $fuel->reimbursement_amount ?? 0
                    ),
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Fuel - By Vehicle
    |--------------------------------------------------------------------------
    */

    private function fuelByVehicle(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $fuels = Fuel::query()
            ->with('vehicle:id,plate_number,vehicle_type')
            ->whereBetween('fuel_date', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->get([
                'id',
                'vehicle_id',
                'liters',
                'total_amount',
                'reimbursement_amount',
            ]);

        return $fuels
            ->groupBy('vehicle_id')
            ->map(function ($rows) {
                $vehicle = $rows->first()->vehicle;

                return [
                    'vehicle' => $vehicle?->plate_number,
                    'vehicle_type' => $vehicle?->vehicle_type,
                    'liters' => (float) $rows->sum('liters'),
                    'fuel_cost' => (float) $rows->sum('total_amount'),
                    'reimbursement' => (float) $rows->sum(
                        'reimbursement_amount'
                    ),
                    'entries' => $rows->count(),
                ];
            })
            ->sortByDesc('fuel_cost')
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Fuel - Monthly Trend
    |--------------------------------------------------------------------------
    */

    private function fuelTrend(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $rows = Fuel::query()
            ->whereBetween('fuel_date', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->get([
                'fuel_date',
                'liters',
                'total_amount',
                'reimbursement_amount',
            ])
            ->groupBy(function ($fuel) {
                return Carbon::parse(
                    $fuel->fuel_date
                )->format('Y-m');
            });

        return $rows
            ->map(function ($fuels, $month) {
                $date = Carbon::createFromFormat(
                    'Y-m',
                    $month
                );

                return [
                    'label' => $date->format('M Y'),
                    'liters' => (float) $fuels->sum('liters'),
                    'fuel_cost' => (float) $fuels->sum(
                        'total_amount'
                    ),
                    'reimbursement' => (float) $fuels->sum(
                        'reimbursement_amount'
                    ),
                ];
            })
            ->sortKeys()
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Maintenance - Detailed Summary
    |--------------------------------------------------------------------------
    */

    private function maintenanceSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Maintenance::query()
            ->with(
                'vehicle:id,plate_number,vehicle_type'
            )
            ->whereBetween('maintenance_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('maintenance_date')
            ->get()
            ->map(function ($maintenance) {
                return [
                    'maintenance_no' => $maintenance->maintenance_no,
                    'vehicle' => $maintenance->vehicle?->plate_number,
                    'vehicle_type' => $maintenance->vehicle?->vehicle_type,
                    'maintenance_date' => $maintenance->maintenance_date
                        ? Carbon::parse(
                            $maintenance->maintenance_date
                        )->format('d M Y')
                        : null,
                    'repair_type' => $maintenance->repair_type,
                    'workshop' => $maintenance->workshop,
                    'parts_cost' => (float) $maintenance->parts_cost,
                    'labour_cost' => (float) $maintenance->labour_cost,
                    'other_cost' => (float) $maintenance->other_cost,
                    'total_cost' => (float) $maintenance->total_cost,
                    'odometer' => $maintenance->odometer,
                    'status' => $maintenance->status,
                    'next_service_date' =>
                        $maintenance->next_service_date
                            ? Carbon::parse(
                                $maintenance->next_service_date
                            )->format('d M Y')
                            : null,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Maintenance - By Vehicle
    |--------------------------------------------------------------------------
    */

    private function maintenanceByVehicle(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $maintenance = Maintenance::query()
            ->with(
                'vehicle:id,plate_number,vehicle_type'
            )
            ->whereBetween('maintenance_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->get([
                'id',
                'vehicle_id',
                'total_cost',
            ]);

        return $maintenance
            ->groupBy('vehicle_id')
            ->map(function ($rows) {
                $vehicle = $rows->first()->vehicle;

                return [
                    'vehicle' => $vehicle?->plate_number,
                    'vehicle_type' => $vehicle?->vehicle_type,
                    'jobs' => $rows->count(),
                    'maintenance_cost' => (float) $rows->sum(
                        'total_cost'
                    ),
                ];
            })
            ->sortByDesc('maintenance_cost')
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice Summary
    |--------------------------------------------------------------------------
    */

    private function invoiceSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Invoice::query()
            ->with('client:id,client_name')
            ->whereBetween('invoice_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('invoice_date')
            ->get()
            ->map(function ($invoice) {
                return [
                    'invoice_no' => $invoice->invoice_no
                        ?? $invoice->invoice_number,
                    'client' => $invoice->client?->client_name,
                    'invoice_date' => $invoice->invoice_date
                        ? Carbon::parse(
                            $invoice->invoice_date
                        )->format('d M Y')
                        : null,
                    'due_date' => $invoice->due_date
                        ? Carbon::parse(
                            $invoice->due_date
                        )->format('d M Y')
                        : null,
                    'source' => $invoice->source,
                    'subtotal' => (float) $invoice->subtotal,
                    'vat_amount' => (float) $invoice->vat_amount,
                    'fuel_reimbursement' => (float) (
                        $invoice->fuel_reimbursement ?? 0
                    ),
                    'other_reimbursement' => (float) (
                        $invoice->other_reimbursement ?? 0
                    ),
                    'total' => (float) $invoice->total_amount,
                    'paid' => (float) $invoice->paid_amount,
                    'balance' => (float) $invoice->balance,
                    'status' => $invoice->status,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice Ageing
    |--------------------------------------------------------------------------
    */

    private function invoiceAgeing(array $filters = []): array
    {
        $today = Carbon::today();

        $buckets = [
            'Current' => 0,
            '1-30 Days' => 0,
            '31-60 Days' => 0,
            '61-90 Days' => 0,
            '90+ Days' => 0,
        ];

        $invoices = Invoice::query()
            ->where('balance', '>', 0)
            ->get([
                'id',
                'due_date',
                'balance',
            ]);

        foreach ($invoices as $invoice) {
            if (!$invoice->due_date) {
                continue;
            }

            $dueDate = Carbon::parse(
                $invoice->due_date
            );

            if ($dueDate->gte($today)) {
                $buckets['Current'] += (float) $invoice->balance;
                continue;
            }

            $days = $dueDate->diffInDays($today);

            if ($days <= 30) {
                $buckets['1-30 Days'] += (float) $invoice->balance;
            } elseif ($days <= 60) {
                $buckets['31-60 Days'] += (float) $invoice->balance;
            } elseif ($days <= 90) {
                $buckets['61-90 Days'] += (float) $invoice->balance;
            } else {
                $buckets['90+ Days'] += (float) $invoice->balance;
            }
        }

        return collect($buckets)
            ->map(function ($amount, $bucket) {
                return [
                    'bucket' => $bucket,
                    'amount' => (float) $amount,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Summary
    |--------------------------------------------------------------------------
    */

    private function paymentSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Payment::query()
            ->with(['client:id,client_name','allocations'])
            ->whereBetween('payment_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('payment_date')
            ->get()
            ->map(function ($payment) {
                $allocated = (float) $payment->allocations->sum(
                    'allocated_amount'
                );

                return [
                    'payment_no' => $payment->payment_no,
                    'client' => $payment->client?->client_name,
                    'payment_date' => $payment->payment_date
                        ? Carbon::parse(
                            $payment->payment_date
                        )->format('d M Y')
                        : null,
                    'amount' => (float) $payment->amount,
                    'allocated' => $allocated,
                    'unallocated' => (float) (
                        $payment->unallocated_amount ?? 0
                    ),
                    'payment_method' => $payment->payment_method,
                    'reference' => $payment->reference,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Expense Summary
    |--------------------------------------------------------------------------
    */

    private function expenseSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Expense::query()
            ->with([
                'vehicle:id,plate_number',
                'driver:id,driver_name',
                'client:id,client_name',
            ])
            ->whereBetween('expense_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('expense_date')
            ->get()
            ->map(function ($expense) {
                return [
                    'expense_no' => $expense->expense_no,
                    'date' => $expense->expense_date
                        ? Carbon::parse(
                            $expense->expense_date
                        )->format('d M Y')
                        : null,
                    'category' => $expense->category,
                    'vehicle' => $expense->vehicle?->plate_number,
                    'driver' => $expense->driver?->driver_name,
                    'client' => $expense->client?->client_name,
                    'amount' => (float) $expense->amount,
                    'payment_status' => $expense->payment_status,
                    'reimbursable' => (bool) $expense->reimbursable,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Expense By Category
    |--------------------------------------------------------------------------
    */

    private function expenseByCategory(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $query = Expense::query()
            ->whereBetween('expense_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ]);

        $query = $this->filtered($query, $filters);

        return $query
            ->selectRaw(
                "COALESCE(category, 'Other') as category,
                 SUM(amount) as total"
            )
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                return [
                    'category' => $row->category,
                    'total' => (float) $row->total,
                ];
            })
            ->values()
            ->all();
    }

    
      /*--------------------------------------------------------------------------
    | Payroll Summary
    |--------------------------------------------------------------------------
    */

    private function payrollSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return Payroll::query()
            ->with('driver:id,driver_name')
            ->whereBetween('salary_month', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('salary_month')
            ->get()
            ->map(function ($payroll) {
                return [
                    'driver' => $payroll->driver?->driver_name,
                    'salary_month' => $payroll->salary_month
                        ? Carbon::parse(
                            $payroll->salary_month
                        )->format('M Y')
                        : null,
                    'basic_salary' => (float) $payroll->basic_salary,
                    'allowance' => (float) $payroll->allowance,
                    'overtime' => (float) $payroll->overtime,
                    'total_deductions' => (float) (
                        $payroll->total_deductions ?? 0
                    ),
                    'net_salary' => (float) $payroll->net_salary,
                    'payment_status' => $payroll->payment_status,
                    'status' => $payroll->status,
                ];
            })
            ->values()
            ->all();
    }
    /*
    |--------------------------------------------------------------------------
    | Driver Fine / Deduction Report
    |--------------------------------------------------------------------------
    */

    private function driverFineDeduction(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $table = (new Payroll)->getTable();

        if (!Schema::hasColumn($table, 'fine_deduction')) {
            return [];
        }

        $query = Payroll::query()
            ->with('driver:id,driver_name')
            ->whereBetween('salary_month', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->when($filters['driver_id'] ?? null, function ($q, $id) {
                $q->where('driver_id', $id);
            });

        $rows = $query
            ->orderByDesc('salary_month')
            ->get();

        return $rows
            ->map(function ($payroll) use ($table) {
                $fineDeduction = (float) ($payroll->fine_deduction ?? 0);

                if ($fineDeduction <= 0) {
                    return null;
                }

                $reason = null;

                if (Schema::hasColumn($table, 'fine_deduction_reason')) {
                    $reason = $payroll->fine_deduction_reason;
                }

                return [
                    'driver' => $payroll->driver?->driver_name,
                    'salary_month' => $payroll->salary_month
                        ? Carbon::parse($payroll->salary_month)->format('M Y')
                        : null,
                    'fine_deduction' => $fineDeduction,
                    'reason' => $reason,
                    'payment_status' => $payroll->payment_status,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Summary
    |--------------------------------------------------------------------------
    */

    private function vendorSummary(): array
    {
        return Vendor::query()
            ->orderBy('vendor_name')
            ->get([
                'id',
                'vendor_name',
                'company_name',
                'contact',
                'status',
                'payment_terms',
                'payment_terms_days',
            ])
            ->map(function ($vendor) {
                return [
                    'vendor_id' => $vendor->id,
                    'vendor_name' => $vendor->vendor_name,
                    'company_name' => $vendor->company_name,
                    'contact' => $vendor->contact,
                    'status' => $vendor->status,
                    'payment_terms' => $vendor->payment_terms,
                    'payment_terms_days' => $vendor->payment_terms_days,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Vehicle Expiry Summary
    |--------------------------------------------------------------------------
    */

    private function vehicleExpirySummary(): array
    {
        $today = Carbon::today();
        $limit = $today->copy()->addDays(30);

        $vehicles = Vehicle::query()
            ->orderBy('plate_number')
            ->get([
                'id',
                'plate_number',
                'vehicle_type',
                'insurance_expiry',
                'registration_expiry',
                'status',
            ]);

        $results = [];

        foreach ($vehicles as $vehicle) {
            if ($vehicle->insurance_expiry) {
                $expiry = Carbon::parse(
                    $vehicle->insurance_expiry
                );

                if ($expiry->lte($limit)) {
                    $results[] = [
                        'entity' => $vehicle->plate_number,
                        'document' => 'Insurance',
                        'expiry_date' => $expiry->toDateString(),
                        'days_remaining' => $expiry->isPast()
                            ? 0
                            : $today->diffInDays($expiry),
                        'status' => $vehicle->status,
                    ];
                }
            }

            if ($vehicle->registration_expiry) {
                $expiry = Carbon::parse(
                    $vehicle->registration_expiry
                );

                if ($expiry->lte($limit)) {
                    $results[] = [
                        'entity' => $vehicle->plate_number,
                        'document' => 'Registration',
                        'expiry_date' => $expiry->toDateString(),
                        'days_remaining' => $expiry->isPast()
                            ? 0
                            : $today->diffInDays($expiry),
                        'status' => $vehicle->status,
                    ];
                }
            }
        }

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | Client Expiry Summary
    |--------------------------------------------------------------------------
    */

    private function clientExpirySummary(): array
    {
        $today = Carbon::today();
        $limit = $today->copy()->addDays(30);

        $clients = Client::query()
            ->orderBy('client_name')
            ->get([
                'id',
                'client_name',
                'trade_licence_expiry',
                'status',
            ]);

        $results = [];

        foreach ($clients as $client) {
            if (!$client->trade_licence_expiry) {
                continue;
            }

            $expiry = Carbon::parse(
                $client->trade_licence_expiry
            );

            if ($expiry->lte($limit)) {
                $results[] = [
                    'entity' => $client->client_name,
                    'document' => 'Trade Licence',
                    'expiry_date' => $expiry->toDateString(),
                    'days_remaining' => $expiry->isPast()
                        ? 0
                        : $today->diffInDays($expiry),
                    'status' => $client->status,
                ];
            }
        }

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | Vehicle Profitability
    |--------------------------------------------------------------------------
    */

    private function vehicleProfitability(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        $vehicles = Vehicle::query()
            ->orderBy('plate_number')
            ->get([
                'id',
                'plate_number',
                'vehicle_type',
                'category',
            ]);

        $results = [];

        foreach ($vehicles as $vehicle) {
            /*
            |--------------------------------------------------------------------------
            | Direct Vehicle Revenue
            |--------------------------------------------------------------------------
            */

            $revenue = (float) Assignment::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereBetween('start_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])
                ->sum('freight_amount');

            /*
            |--------------------------------------------------------------------------
            | Direct Vehicle Costs
            |--------------------------------------------------------------------------
            */

            $fuel = (float) Fuel::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereBetween('fuel_date', [
                    $startDate->copy()->startOfDay(),
                    $endDate->copy()->endOfDay(),
                ])
                ->sum('total_amount');

            $maintenance = (float) Maintenance::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereBetween('maintenance_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])
                ->sum('total_cost');

            $fines = (float) TrafficFine::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereBetween('fine_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])
                ->sum('amount');

            $expenses = (float) Expense::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereBetween('expense_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])
                ->sum('amount');

            $directCost = $fuel
                + $maintenance
                + $fines
                + $expenses;

            /*
            |--------------------------------------------------------------------------
            | Allocation Governance
            |--------------------------------------------------------------------------
            |
            | Driver salary, insurance, registration, instalments and
            | shared overheads are NOT allocated automatically because
            | the FRD requires final finance sign-off for these rules.
            |
            */

            $profit = $revenue - $directCost;

            $margin = $revenue > 0
                ? ($profit / $revenue) * 100
                : 0;

            $results[] = [
                'vehicle_id' => $vehicle->id,
                'plate_number' => $vehicle->plate_number,
                'vehicle_type' => $vehicle->vehicle_type,
                'category' => $vehicle->category,
                'revenue' => $revenue,
                'fuel' => $fuel,
                'maintenance' => $maintenance,
                'fines' => $fines,
                'expenses' => $expenses,
                'cost' => $directCost,
                'profit' => $profit,
                'margin' => round($margin, 2),
            ];
        }

        return collect($results)
            ->sortByDesc('profit')
            ->values()
            ->all();
    }


    private function executiveKpi(Carbon $s, Carbon $e, array $f): array
    {
        $revenue = $this->invoiceRevenue($s, $e, $f);
        $cost = $this->sumWithFilters(Expense::query(), 'expense_date', 'amount', $s, $e, $f)
            + $this->sumWithFilters(Payroll::query(), 'salary_month', 'net_salary', $s, $e, $f)
            + $this->sumWithFilters(Fuel::query(), 'fuel_date', 'total_amount', $s, $e, $f)
            + $this->sumWithFilters(Maintenance::query(), 'maintenance_date', 'total_cost', $s, $e, $f)
            + $this->sumWithFilters(TrafficFine::query(), 'fine_date', 'amount', $s, $e, $f);
        return ['revenue'=>$revenue,'total_cost'=>$cost,'net_profit'=>$revenue-$cost,'report_basis'=>'Management/direct-cost basis; Finance allocation rules require sign-off.'];
    }

    private function cashReceivableSummary(Carbon $s, Carbon $e, array $f): array
    {
        $payments = $this->paymentSummary($s,$e,$f);
        return [['payments_received'=>(float)collect($payments)->sum('amount'),'allocated_payments'=>(float)collect($payments)->sum('allocated'),'outstanding_invoices'=>(float)$this->filtered(Invoice::query(),$f)->where('balance','>',0)->sum('balance'),'overdue_balance'=>(float)$this->filtered(Invoice::query(),$f)->where('balance','>',0)->whereDate('due_date','<',Carbon::today())->sum('balance')]];
    }

    private function outstandingReimbursementSummary(Carbon $s, Carbon $e, array $f): array
    {
        $q=Fuel::query()->whereBetween('fuel_date',[$s->startOfDay(),$e->endOfDay()])->where('reimbursable',true);
        $q=$this->filtered($q,$f);
        return $q->get(['id','fuel_entry_no','fuel_date','vehicle_id','client_id','reimbursement_amount','total_amount'])->map(fn($x)=>['fuel_id'=>$x->id,'fuel_entry_no'=>$x->fuel_entry_no,'fuel_date'=>$x->fuel_date,'vehicle_id'=>$x->vehicle_id,'client_id'=>$x->client_id,'reimbursement_amount'=>(float)($x->reimbursement_amount??0),'fuel_cost'=>(float)$x->total_amount])->values()->all();
    }

    private function topLowProfitVehicles(Carbon $s, Carbon $e, array $f): array
    {
        return $this->vehicleProfitability($s,$e,$f);
    }

    private function topClients(Carbon $s, Carbon $e, array $f): array
    {
        return $this->clientProfitability($s,$e,$f);
    }

    private function vehicleMaster(array $f): array
    {
        return $this->filtered(Vehicle::query(),$f)->orderBy('plate_number')->get()->map(fn($v)=>['vehicle_id'=>$v->id,'plate_number'=>$v->plate_number,'vehicle_type'=>$v->vehicle_type,'category'=>$v->category,'status'=>$v->status])->all();
    }

    private function driverMaster(array $f): array
    {
        return $this->filtered(Driver::query(),$f)->orderBy('driver_name')->get()->map(fn($d)=>['driver_id'=>$d->id,'driver_name'=>$d->driver_name,'status'=>$d->status,'employment_status'=>$d->employment_status])->all();
    }

    private function clientMaster(array $f): array
    {
        return $this->filtered(Client::query(),$f)->orderBy('client_name')->get()->map(fn($c)=>['client_id'=>$c->id,'client_name'=>$c->client_name,'company_name'=>$c->company_name,'status'=>$c->status])->all();
    }

    private function idleVehicle(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->vehicleUtilisation($s,$e,$f))->filter(fn($r)=>(int)$r['assignments']===0)->values()->all();
    }

    private function vehicleRevenue(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->vehicleProfitability($s,$e,$f))->map(fn($r)=>['vehicle'=>$r['plate_number'],'revenue'=>$r['revenue']])->all();
    }

    private function vehicleExpense(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->vehicleProfitability($s,$e,$f))->map(fn($r)=>['vehicle'=>$r['plate_number'],'fuel'=>$r['fuel'],'maintenance'=>$r['maintenance'],'fines'=>$r['fines'],'expenses'=>$r['expenses'],'cost'=>$r['cost']])->all();
    }

    private function fineHistoryByVehicle(Carbon $s, Carbon $e, array $f): array
    {
        return TrafficFine::query()->with('vehicle:id,plate_number')->whereBetween('fine_date',[$s->toDateString(),$e->toDateString()])->when($f['vehicle_id']??null,fn($q,$id)=>$q->where('vehicle_id',$id))->get()->map(fn($x)=>['vehicle'=>$x->vehicle?->plate_number,'fine_date'=>$x->fine_date,'amount'=>(float)$x->amount,'status'=>$x->status??null])->all();
    }

    private function fleetHealth(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->vehicleProfitability($s,$e,$f))->sortByDesc('cost')->values()->all();
    }

    private function outstandingAdvance(array $f): array
    {
        return DriverAdvance::query()->with('driver:id,driver_name')->when($f['driver_id']??null,fn($q,$id)=>$q->where('driver_id',$id))->get()->map(fn($x)=>['driver'=>$x->driver?->driver_name,'advance_date'=>$x->advance_date,'amount'=>(float)$x->amount,'deducted'=>(float)($x->deducted_amount??0),'remaining'=>(float)($x->remaining_amount??max(0,(float)$x->amount-(float)($x->deducted_amount??0))),'status'=>$x->status])->filter(fn($x)=>$x['remaining']>0)->values()->all();
    }

    private function clientExpense(Carbon $s, Carbon $e, array $f): array
    {
        return $this->expenseSummary($s,$e,$f);
    }

    private function clientOutstanding(array $f): array
    {
        return Invoice::query()->with('client:id,client_name')->where('balance','>',0)->when($f['client_id']??null,fn($q,$id)=>$q->where('client_id',$id))->orderByDesc('balance')->get()->map(fn($i)=>['invoice_no'=>$i->invoice_no,'client'=>$i->client?->client_name,'due_date'=>$i->due_date,'balance'=>(float)$i->balance,'status'=>$i->status])->all();
    }

    private function clientFuelReimbursement(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->fuelSummary($s,$e,$f))->filter(fn($x)=>$x['reimbursable'])->values()->all();
    }

    private function vendorVehicleReport(Carbon $s, Carbon $e, array $f): array
    {
        return [];
    }

    private function vendorRateReport(array $f): array
    {
        return $this->vendorSummary();
    }

    private function vendorPayableReport(Carbon $s, Carbon $e, array $f): array
    {
        return [];
    }

    private function vendorLedgerReport(Carbon $s, Carbon $e, array $f): array
    {
        return [];
    }

    private function workshopSpend(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->maintenanceSummary($s,$e,$f))->groupBy('workshop')->map(fn($r,$w)=>['workshop'=>$w,'jobs'=>$r->count(),'spend'=>(float)$r->sum('total_cost')])->sortByDesc('spend')->values()->all();
    }

    private function assignmentStatusReport(Carbon $s, Carbon $e, array $f, array $statuses): array
    {
        return collect($this->assignmentSummary($s,$e,$f))->whereIn('status',$statuses)->values()->all();
    }

    private function tripStatusSummary(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->tripSummary($s,$e,$f))->groupBy('status')->map(fn($r,$status)=>['status'=>$status,'total'=>$r->count()])->values()->all();
    }

    private function tripProfitability(Carbon $s, Carbon $e, array $f): array
    {
        $trips = Trip::query()
            ->when($f['vehicle_id'] ?? null, fn ($q, $id) => $q->where('vehicle_id', $id))
            ->when($f['driver_id'] ?? null, fn ($q, $id) => $q->where('driver_id', $id))
            ->when($f['client_id'] ?? null, fn ($q, $id) => $q->where('client_id', $id))
            ->when($f['assignment_id'] ?? null, fn ($q, $id) => $q->where('assignment_id', $id))
            ->when($f['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->whereBetween('trip_start', [$s, $e])
            ->get();

        return $trips->map(function ($trip) use ($s, $e) {
            $fuel = (float) Fuel::query()
                ->where('trip_id', $trip->id)
                ->whereBetween('fuel_date', [$s, $e])
                ->sum('total_amount');

            $expense = (float) Expense::query()
                ->where('trip_id', $trip->id)
                ->whereBetween('expense_date', [$s->toDateString(), $e->toDateString()])
                ->sum('amount');

            $freight = (float) $trip->freight_amount;
            $cost = $fuel + $expense;

            return [
                'trip_id' => $trip->id,
                'trip_no' => $trip->trip_no,
                'assignment_id' => $trip->assignment_id,
                'client_id' => $trip->client_id,
                'vehicle_id' => $trip->vehicle_id,
                'driver_id' => $trip->driver_id,
                'trip_start' => $trip->trip_start,
                'trip_end' => $trip->trip_end,
                'loading_point' => $trip->loading_point,
                'unloading_point' => $trip->unloading_point,
                'status' => $trip->status,
                'freight_amount' => $freight,
                'fuel' => $fuel,
                'expenses' => $expense,
                'cost' => $cost,
                'profit' => $freight - $cost,
                'basis' => 'Direct trip costs; allocated driver cost and other shared allocations require Finance sign-off.',
            ];
        })->values()->all();
    }

    private function routeProfitability(Carbon $s, Carbon $e, array $f): array
    {
        $table=(new Trip)->getTable();
        foreach(['route','route_name','route_code'] as $column){
            if(Schema::hasColumn($table,$column)){
                return collect($this->tripProfitability($s,$e,$f))->groupBy(fn($r)=>$r[$column]??'Unknown')->map(fn($r,$route)=>['route'=>$route,'revenue'=>(float)$r->sum('freight_amount'),'cost'=>(float)$r->sum('cost'),'profit'=>(float)$r->sum('profit')])->sortByDesc('profit')->values()->all();
            }
        }
        return [];
    }

    private function vehicleDriverHistory(Carbon $s, Carbon $e, array $f): array
    {
        return $this->assignmentSummary($s,$e,$f);
    }

    private function operationalPerformance(Carbon $s, Carbon $e, array $f): array
    {
        return $this->tripStatusSummary($s,$e,$f);
    }

    private function cancelledTrips(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->tripSummary($s,$e,$f))->filter(fn($r)=>strtolower((string)$r['status'])==='cancelled')->values()->all();
    }

    private function fuelConsumption(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->fuelByVehicle($s,$e,$f))->map(function($r){$liters=(float)$r['liters'];$r['cost_per_liter']=$liters>0?round($r['fuel_cost']/$liters,2):0;return $r;})->values()->all();
    }

    private function reimbursableFuelReport(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->fuelSummary($s,$e,$f))->filter(fn($r)=>$r['reimbursable'])->values()->all();
    }

    private function fuelRecoveryAgeingReport(Carbon $s, Carbon $e, array $f): array
    {
        if(!Schema::hasTable((new FuelRecovery)->getTable())) return [];
        return FuelRecovery::query()->with(['fuel','invoice'])->get()->map(function($r){
            $date=$r->created_at ? Carbon::parse($r->created_at) : Carbon::today();
            return ['fuel_id'=>$r->fuel_id,'invoice_id'=>$r->invoice_id,'age_days'=>$date->diffInDays(Carbon::today()),'created_at'=>$date->format('d M Y')];
        })->values()->all();
    }

    private function maintenanceTrend(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->maintenanceSummary($s,$e,$f))->groupBy(fn($r)=>Carbon::parse($r['maintenance_date'])->format('Y-m'))->map(fn($r,$m)=>['label'=>Carbon::createFromFormat('Y-m',$m)->format('M Y'),'jobs'=>$r->count(),'cost'=>(float)$r->sum('total_cost')])->sortKeys()->values()->all();
    }

    private function vehicleDowntime(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->maintenanceSummary($s,$e,$f))->groupBy('vehicle')->map(fn($r,$v)=>['vehicle'=>$v,'maintenance_jobs'=>$r->count(),'maintenance_cost'=>(float)$r->sum('total_cost')])->values()->all();
    }

    private function preventiveMaintenanceDue(): array
    {
        return Maintenance::query()->with('vehicle:id,plate_number')->whereNotNull('next_service_date')->whereDate('next_service_date','<=',Carbon::today())->orderBy('next_service_date')->get()->map(fn($m)=>['maintenance_no'=>$m->maintenance_no,'vehicle'=>$m->vehicle?->plate_number,'next_service_date'=>$m->next_service_date])->all();
    }

    private function outstandingInvoiceReport(array $f): array
    {
        return $this->clientOutstanding($f);
    }

    private function paymentAllocationReport(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->paymentSummary($s,$e,$f))->map(fn($r)=>['payment_no'=>$r['payment_no'],'client'=>$r['client'],'amount'=>$r['amount'],'allocated'=>$r['allocated'],'unallocated'=>$r['unallocated']])->all();
    }

    private function payrollDeductionReport(Carbon $s, Carbon $e, array $f): array
    {
        return collect($this->payrollSummary($s,$e,$f))->map(fn($r)=>['driver'=>$r['driver'],'salary_month'=>$r['salary_month'],'total_deductions'=>$r['total_deductions'],'net_salary'=>$r['net_salary']])->all();
    }

    private function clientVehicleProfitability(Carbon $s, Carbon $e, array $f): array
    {
        return ['clients'=>$this->clientProfitability($s,$e,$f),'vehicles'=>$this->vehicleProfitability($s,$e,$f),'basis'=>'Direct attributable costs only; shared payroll/vehicle/vendor allocations require Finance sign-off.'];
    }

    private function allDocumentsReport(array $f): array
    {
        if(!Schema::hasTable((new Document)->getTable())) return [];
        return Document::query()->latest()->get()->map(fn($d)=>['id'=>$d->id,'document_type'=>$d->document_type??null,'documentable_type'=>$d->documentable_type??null,'documentable_id'=>$d->documentable_id??null,'expiry_date'=>$d->expiry_date??null,'created_at'=>$d->created_at?->format('d M Y')])->all();
    }

    private function expiryWindow(int $days): array
    {
        $today=Carbon::today();$limit=$today->copy()->addDays($days);
        return collect($this->documentExpiryCenter())->filter(function($r)use($today,$limit){$x=Carbon::parse($r['expiry_date']);return !$x->isPast() && $x->lte($limit);})->values()->all();
    }

    private function expiredDocuments(): array
    {
        return collect($this->documentExpiryCenter())->filter(fn($r)=>Carbon::parse($r['expiry_date'])->isPast())->values()->all();
    }

    private function missingDocuments(): array
    {
        $out=[];
        foreach(Vehicle::query()->get(['plate_number','insurance_expiry','registration_expiry']) as $v){if(!$v->insurance_expiry)$out[]=['entity'=>$v->plate_number,'document'=>'Insurance'];if(!$v->registration_expiry)$out[]=['entity'=>$v->plate_number,'document'=>'Registration'];}
        foreach(Driver::query()->get(['driver_name','license_expiry','visa_expiry']) as $d){if(!$d->license_expiry)$out[]=['entity'=>$d->driver_name,'document'=>'Driving Licence'];if(!$d->visa_expiry)$out[]=['entity'=>$d->driver_name,'document'=>'Visa'];}
        foreach(Client::query()->get(['client_name','trade_licence_expiry']) as $c){if(!$c->trade_licence_expiry)$out[]=['entity'=>$c->client_name,'document'=>'Trade Licence'];}
        return $out;
    }

    private function archivedRecords(Carbon $s, Carbon $e): array
    {
        if(!Schema::hasTable((new ActivityLog)->getTable())) return [];
        return ActivityLog::query()->with('user:id,name')->whereBetween('created_at',[$s,$e])->whereIn('action',['archived','archive','restored','restore'])->latest()->get()->map(fn($x)=>['date'=>$x->created_at?->format('d M Y H:i'),'user'=>$x->user?->name,'action'=>$x->action,'module'=>$x->module,'description'=>$x->description,'subject_type'=>$x->subject_type,'subject_id'=>$x->subject_id])->all();
    }

    private function permissionLoginActivity(Carbon $s, Carbon $e): array
    {
        return ActivityLog::query()->with('user:id,name')->whereBetween('created_at',[$s,$e])->whereIn('action',['login','logout','failed_login','permission_changed','role_changed'])->latest()->get()->map(fn($x)=>['date'=>$x->created_at?->format('d M Y H:i'),'user'=>$x->user?->name,'action'=>$x->action,'module'=>$x->module,'description'=>$x->description])->all();
    }

    private function paymentActivity(Carbon $s, Carbon $e, array $f): array
    {
        return $this->paymentSummary($s,$e,$f);
    }

    private function sumWithFilters($query,string $dateColumn,string $sumColumn,Carbon $s,Carbon $e,array $f): float
    {
        $query=$this->dateRange($query,$dateColumn,$s,$e);
        return (float)$this->filtered($query,$f)->sum($sumColumn);
    }

    private function invoiceRevenue(Carbon $s, Carbon $e, array $f): float
    {
        return (float)$this->filtered($this->dateRange(Invoice::query(),'invoice_date',$s,$e),$f)->whereNotIn('status',['Cancelled'])->sum('total_amount');
    }

    private function dateRange($query,string $column,Carbon $s,Carbon $e)
    {
        return $query->whereBetween($column,[$s->toDateString(),$e->toDateString()]);
    }

    private function filtered($query,array $f)
    {
        $table=$query->getModel()->getTable();
        $apply=function($column,$value)use(&$query,$table){if($value!==null && $value!=='' && Schema::hasColumn($table,$column)){$query->where($column,$value);}};
        $apply('vehicle_id',$f['vehicle_id']??null);
        $apply('driver_id',$f['driver_id']??null);
        $apply('client_id',$f['client_id']??null);
        $apply('vendor_id',$f['vendor_id']??null);
        $apply('assignment_id',$f['assignment_id']??null);
        $apply('status',$f['status']??null);
        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Record Counts / Audit Summary
    |--------------------------------------------------------------------------
    */

    private function recordCounts(): array
    {
        return [
            'vehicles' => Vehicle::count(),
            'drivers' => Driver::count(),
            'clients' => Client::count(),
            'vendors' => Vendor::count(),
            'assignments' => Assignment::count(),
            'trips' => Trip::count(),
            'fuels' => Fuel::count(),
            'maintenance' => Maintenance::count(),
            'expenses' => Expense::count(),
            'invoices' => Invoice::count(),
            'payments' => Payment::count(),
            'payroll' => Payroll::count(),
            'driver_advances' => DriverAdvance::count(),
            'leaves' => Leave::count(),
            'traffic_fines' => TrafficFine::count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Complete Document Expiry Center
    |--------------------------------------------------------------------------
    */

    private function documentExpiryCenter(): array
    {
        return collect([
            ...$this->vehicleExpirySummary(),
            ...$this->driverExpirySummary(),
            ...$this->clientExpirySummary(),
        ])
            ->sortBy('days_remaining')
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Final Report Dataset Override
    |--------------------------------------------------------------------------
    |
    | This keeps the complete report data available to the Reports Center.
    |
    */

    private function reportData(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        return $this->buildReportData($startDate, $endDate, $filters);
    }

    
    /*
    |--------------------------------------------------------------------------
    | RPT-068 — VAT Summary
    |--------------------------------------------------------------------------
    */

    private function vatSummary(
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        if (!Schema::hasTable((new Invoice)->getTable())) {
            return [];
        }

        $table = (new Invoice)->getTable();

        if (!Schema::hasColumn($table, 'vat_amount')) {
            return [];
        }

        $query = Invoice::query()
            ->with('client:id,client_name')
            ->whereBetween('invoice_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->whereNotIn('status', ['Cancelled']);

        $query = $this->filtered($query, $filters);

        return $query
            ->latest('invoice_date')
            ->get()
            ->map(function ($invoice) {
                return [
                    'invoice_no' => $invoice->invoice_no,
                    'invoice_date' => $invoice->invoice_date
                        ? Carbon::parse($invoice->invoice_date)->toDateString()
                        : null,
                    'client' => $invoice->client?->client_name,
                    'subtotal' => (float) ($invoice->subtotal ?? 0),
                    'vat_percent' => (float) ($invoice->vat_percent ?? 0),
                    'vat_amount' => (float) ($invoice->vat_amount ?? 0),
                    'total_amount' => (float) ($invoice->total_amount ?? 0),
                    'status' => $invoice->status,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | RPT-083 — User Activity
    |--------------------------------------------------------------------------
    */

    private function userActivity(Carbon $startDate, Carbon $endDate): array
    {
        if (!Schema::hasTable((new ActivityLog)->getTable())) {
            return [];
        }

        return ActivityLog::query()
            ->with('user:id,name')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'date' => $log->created_at?->format('d M Y H:i'),
                    'user' => $log->user?->name,
                    'action' => $log->action,
                    'module' => $log->module,
                    'description' => $log->description,
                    'ip_address' => $log->ip_address,
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | RPT-084 — Record Change History
    |--------------------------------------------------------------------------
    */

    private function recordChangeHistory(Carbon $startDate, Carbon $endDate): array
    {
        if (!Schema::hasTable((new ActivityLog)->getTable())) {
            return [];
        }

        return ActivityLog::query()
            ->with('user:id,name')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('action', ['created', 'updated', 'deleted', 'archived', 'restored', 'archive', 'restore'])
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'date' => $log->created_at?->format('d M Y H:i'),
                    'user' => $log->user?->name,
                    'action' => $log->action,
                    'module' => $log->module,
                    'description' => $log->description,
                    'subject_type' => $log->subject_type,
                    'subject_id' => $log->subject_id,
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                ];
            })
            ->values()
            ->all();
    }


}
