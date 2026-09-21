<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Fuel;
use App\Models\Invoice;
use App\Models\Payroll;
use use\App\Models\Trip;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Main Counts
        |--------------------------------------------------------------------------
        |
        | Combine vehicle, driver, assignment and trip status counts
        | into ONE database round-trip.
        |
        */

        $statusRows = DB::select("
            SELECT 'vehicles' AS entity, LOWER(status) AS status, COUNT(*) AS total
            FROM vehicles
            GROUP BY LOWER(status)

            UNION ALL

            SELECT 'drivers' AS entity, LOWER(status) AS status, COUNT(*) AS total
            FROM drivers
            GROUP BY LOWER(status)

            UNION ALL

            SELECT 'assignments' AS entity, LOWER(status) AS status, COUNT(*) AS total
            FROM assignments
            GROUP BY LOWER(status)

            UNION ALL

            SELECT 'trips' AS entity, LOWER(status) AS status, COUNT(*) AS total
            FROM trips
            GROUP BY LOWER(status)
        ");

        $statusCounts = collect($statusRows)->keyBy(function ($row) {
            return $row->entity . '|' . $row->status;
        });

        $statusTotal = function (string $entity): int {
            return $this->statusTotal($GLOBALS['statusCounts'] ?? collect(), $entity);
        };

        $vehicleStatusCounts = $statusCounts->filter(
            fn ($row) => $row->entity === 'vehicles'
        );

        $driverStatusCounts = $statusCounts->filter(
            fn ($row) => $row->entity === 'drivers'
        );

        $assignmentStatusCounts = $statusCounts->filter(
            fn ($row) => $row->entity === 'assignments'
        );

        $tripStatusCounts = $statusCounts->filter(
            fn ($row) => $row->entity === 'trips'
        );

        $getStatus = function ($collection, string $status): int {
            $row = $collection->first(
                fn ($item) => $item->status === $status
            );

            return (int) ($row->total ?? 0);
        };

        $totalVehicles = (int) $vehicleStatusCounts->sum('total');

        $activeVehicles = $getStatus($vehicleStatusCounts, 'active');
        $inactiveVehicles = $getStatus($vehicleStatusCounts, 'inactive');
        $assignedVehicles = $getStatus($vehicleStatusCounts, 'assigned');
        $idleVehicles = $getStatus($vehicleStatusCounts, 'idle');

        $maintenanceVehicles =
            $getStatus($vehicleStatusCounts, 'under-maintenance')
            + $getStatus($vehicleStatusCounts, 'under maintenance');

        $totalDrivers = (int) $driverStatusCounts->sum('total');

        $activeDrivers = $getStatus($driverStatusCounts, 'active');

        $onLeaveDrivers =
            $getStatus($driverStatusCounts, 'on leave')
            + $getStatus($driverStatusCounts, 'on-leave');

        $activeAssignments =
            $getStatus($assignmentStatusCounts, 'active')
            + $getStatus($assignmentStatusCounts, 'ongoing');

        $activeTrips =
            $getStatus($tripStatusCounts, 'active')
            + $getStatus($tripStatusCounts, 'ongoing');

        /*
        |--------------------------------------------------------------------------
        | Current Month Finance
        |--------------------------------------------------------------------------
        */

        $monthStart = $now->copy()->startOfMonth()->toDateString();
        $monthEnd = $now->copy()->endOfMonth()->toDateString();

        $monthlyFinanceRows = DB::select("
            SELECT 'revenue' AS metric, COALESCE(SUM(total_amount), 0) AS total
            FROM invoices
            WHERE invoice_date BETWEEN ? AND ?

            UNION ALL

            SELECT 'expenses' AS metric, COALESCE(SUM(amount), 0) AS total
            FROM expenses
            WHERE expense_date BETWEEN ? AND ?

            UNION ALL

            SELECT 'payroll' AS metric, COALESCE(SUM(net_salary), 0) AS total
            FROM payrolls
            WHERE salary_month BETWEEN ? AND ?
        ", [
            $monthStart,
            $monthEnd,
            $monthStart,
            $monthEnd,
            $monthStart,
            $monthEnd,
        ]);

        $monthlyFinance = collect($monthlyFinanceRows)->keyBy('metric');

        $monthlyRevenue = (float) ($monthlyFinance['revenue']->total ?? 0);
        $monthlyExpenses = (float) ($monthlyFinance['expenses']->total ?? 0);
        $monthlyPayroll = (float) ($monthlyFinance['payroll']->total ?? 0);

        $monthlyProfit =
            $monthlyRevenue
            - $monthlyExpenses
            - $monthlyPayroll;

        /*
        |--------------------------------------------------------------------------
        | Invoice Outstanding / Overdue
        |--------------------------------------------------------------------------
        |
        | One query instead of two.
        |
        */

        $invoiceSummary = DB::selectOne("
            SELECT
                SUM(CASE WHEN balance > 0 THEN 1 ELSE 0 END) AS invoice_count,
                SUM(CASE WHEN balance > 0 THEN balance ELSE 0 END) AS outstanding_balance,
                SUM(
                    CASE
                        WHEN balance > 0
                        AND due_date IS NOT NULL
                        AND due_date < ?
                        THEN balance
                        ELSE 0
                    END
                ) AS overdue_balance
            FROM invoices
        ", [
            $now->toDateString(),
        ]);

        $outstandingInvoices =
            (int) ($invoiceSummary->invoice_count ?? 0);

        $outstandingBalance =
            (float) ($invoiceSummary->outstanding_balance ?? 0);

        $overdueBalance =
            (float) ($invoiceSummary->overdue_balance ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Fuel
        |--------------------------------------------------------------------------
        */

        $reimbursableFuel = Fuel::where(
            'reimbursable',
            true
        )->sum('reimbursement_amount');

        /*
        |--------------------------------------------------------------------------
        | Expiring Documents
        |--------------------------------------------------------------------------
        |
        | Driver + Vehicle + Client expiry calculations in ONE query.
        |
        */

        $today = $now->copy()->startOfDay();
        $expiry7 = $now->copy()->addDays(7)->endOfDay();
        $expiry15 = $now->copy()->addDays(15)->endOfDay();
        $expiry30 = $now->copy()->addDays(30)->endOfDay();

        $expirySummary = DB::selectOne("
            SELECT
                SUM(
                    CASE
                        WHEN expiry_date BETWEEN ? AND ?
                        THEN 1 ELSE 0
                    END
                ) AS expiring_7,

                SUM(
                    CASE
                        WHEN expiry_date BETWEEN ? AND ?
                        THEN 1 ELSE 0
                    END
                ) AS expiring_15,

                SUM(
                    CASE
                        WHEN expiry_date BETWEEN ? AND ?
                        THEN 1 ELSE 0
                    END
                ) AS expiring_30

            FROM (
                SELECT license_expiry AS expiry_date
                FROM drivers
                WHERE license_expiry IS NOT NULL

                UNION ALL

                SELECT visa_expiry AS expiry_date
                FROM drivers
                WHERE visa_expiry IS NOT NULL

                UNION ALL

                SELECT insurance_expiry AS expiry_date
                FROM vehicles
                WHERE insurance_expiry IS NOT NULL

                UNION ALL

                SELECT registration_expiry AS expiry_date
                FROM vehicles
                WHERE registration_expiry IS NOT NULL

                UNION ALL

                SELECT trade_licence_expiry AS expiry_date
                FROM clients
                WHERE trade_licence_expiry IS NOT NULL
            ) AS expiry_dates
        ", [
            $today,
            $expiry7,

            $today,
            $expiry15,

            $today,
            $expiry30,
        ]);

        $documentsExpiring7 =
            (int) ($expirySummary->expiring_7 ?? 0);

        $documentsExpiring15 =
            (int) ($expirySummary->expiring_15 ?? 0);

        $documentsExpiring30 =
            (int) ($expirySummary->expiring_30 ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Monthly Trend - Last 6 Months
        |--------------------------------------------------------------------------
        |
        | Revenue + Expenses + Payroll in ONE database round-trip.
        |
        */

        $trendLabels = [];
        $revenueTrend = [];
        $expenseTrend = [];
        $profitTrend = [];

        $trendStart = $now->copy()
            ->subMonths(5)
            ->startOfMonth()
            ->toDateString();

        $trendEnd = $now->copy()
            ->endOfMonth()
            ->toDateString();

        $trendRows = DB::select("
            SELECT
                'revenue' AS metric,
                DATE_FORMAT(invoice_date, '%Y-%m') AS month,
                SUM(total_amount) AS total
            FROM invoices
            WHERE invoice_date BETWEEN ? AND ?
            GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')

            UNION ALL

            SELECT
                'expenses' AS metric,
                DATE_FORMAT(expense_date, '%Y-%m') AS month,
                SUM(amount) AS total
            FROM expenses
            WHERE expense_date BETWEEN ? AND ?
            GROUP BY DATE_FORMAT(expense_date, '%Y-%m')

            UNION ALL

            SELECT
                'payroll' AS metric,
                DATE_FORMAT(salary_month, '%Y-%m') AS month,
                SUM(net_salary) AS total
            FROM payrolls
            WHERE salary_month BETWEEN ? AND ?
            GROUP BY DATE_FORMAT(salary_month, '%Y-%m')
        ", [
            $trendStart,
            $trendEnd,
            $trendStart,
            $trendEnd,
            $trendStart,
            $trendEnd,
        ]);

        $trendData = collect($trendRows);

        for ($i = 5; $i >= 0; $i--) {
            $start = $now->copy()
                ->subMonths($i)
                ->startOfMonth();

            $monthKey = $start->format('Y-m');

            $revenue = (float) (
                $trendData
                    ->where('metric', 'revenue')
                    ->where('month', $monthKey)
                    ->sum('total')
            );

            $expenses = (float) (
                $trendData
                    ->where('metric', 'expenses')
                    ->where('month', $monthKey)
                    ->sum('total')
            );

            $payroll = (float) (
                $trendData
                    ->where('metric', 'payroll')
                    ->where('month', $monthKey)
                    ->sum('total')
            );

            $profit =
                $revenue
                - $expenses
                - $payroll;

            $trendLabels[] = $start->format('M Y');

            $revenueTrend[] = $revenue;

            $expenseTrend[] =
                $expenses + $payroll;

            $profitTrend[] = $profit;
        }

        /*
        |--------------------------------------------------------------------------
        | Recent Active Operations
        |--------------------------------------------------------------------------
        */

        $activeOperations = Assignment::with([
            'client',
            'vehicle',
            'driver',
        ])
            ->whereRaw(
                'LOWER(status) IN (?, ?)',
                [
                    'active',
                    'ongoing',
                ]
            )
            ->latest()
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Critical Alerts
        |--------------------------------------------------------------------------
        */

        $criticalAlerts = collect();

        if ($overdueBalance > 0) {
            $criticalAlerts->push([
                'type' => 'finance',
                'title' => 'Overdue invoices',
                'message' =>
                    'Outstanding overdue balance requires attention.',
                'value' =>
                    'AED ' .
                    number_format(
                        $overdueBalance,
                        2
                    ),
            ]);
        }

        if ($documentsExpiring7 > 0) {
            $criticalAlerts->push([
                'type' => 'document',
                'title' => 'Documents expiring soon',
                'message' =>
                    'Documents are expiring within 7 days.',
                'value' =>
                    $documentsExpiring7 .
                    ' document(s)',
            ]);
        }

        if ($maintenanceVehicles > 0) {
            $criticalAlerts->push([
                'type' => 'maintenance',
                'title' =>
                    'Vehicles under maintenance',
                'message' =>
                    'Vehicles currently unavailable for operations.',
                'value' =>
                    $maintenanceVehicles .
                    ' vehicle(s)',
            ]);
        }

        if ($reimbursableFuel > 0) {
            $criticalAlerts->push([
                'type' => 'fuel',
                'title' =>
                    'Reimbursable fuel',
                'message' =>
                    'Fuel reimbursement requires recovery tracking.',
                'value' =>
                    'AED ' .
                    number_format(
                        $reimbursableFuel,
                        2
                    ),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard',
            compact(
                'totalVehicles',
                'activeVehicles',
                'inactiveVehicles',
                'assignedVehicles',
                'idleVehicles',
                'maintenanceVehicles',

                'totalDrivers',
                'activeDrivers',
                'onLeaveDrivers',

                'activeAssignments',
                'activeTrips',

                'monthlyRevenue',
                'monthlyExpenses',
                'monthlyPayroll',
                'monthlyProfit',

                'outstandingInvoices',
                'outstandingBalance',
                'overdueBalance',

                'reimbursableFuel',

                'documentsExpiring7',
                'documentsExpiring15',
                'documentsExpiring30',

                'trendLabels',
                'revenueTrend',
                'expenseTrend',
                'profitTrend',

                'activeOperations',
                'criticalAlerts'
            )
        );
    }

    private function statusTotal($collection, string $entity): int
    {
        return (int) $collection
            ->filter(fn ($row) => $row->entity === $entity)
            ->sum('total');
    }
}