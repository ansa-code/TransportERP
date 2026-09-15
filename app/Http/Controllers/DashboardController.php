<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Fuel;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\Payroll;
use App\Models\Trip;
use App\Models\Vehicle;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Main Counts
        |--------------------------------------------------------------------------
        */

        $totalVehicles = Vehicle::count();

        $activeVehicles = Vehicle::whereRaw('LOWER(status) = ?', ['active'])->count();

        $inactiveVehicles = Vehicle::whereRaw('LOWER(status) = ?', ['inactive'])->count();

        $assignedVehicles = Vehicle::whereRaw('LOWER(status) = ?', ['assigned'])->count();

        $idleVehicles = Vehicle::whereRaw('LOWER(status) = ?', ['idle'])->count();

        $maintenanceVehicles = Vehicle::whereRaw(
            'LOWER(status) IN (?, ?)',
            ['under-maintenance', 'under maintenance']
        )->count();

        $totalDrivers = Driver::count();

        $activeDrivers = Driver::whereRaw('LOWER(status) = ?', ['active'])->count();

        $onLeaveDrivers = Driver::whereRaw(
            'LOWER(status) IN (?, ?)',
            ['on leave', 'on-leave']
        )->count();

        $activeAssignments = Assignment::whereRaw(
            'LOWER(status) IN (?, ?)',
            ['active', 'ongoing']
        )->count();

        $activeTrips = Trip::whereRaw(
            'LOWER(status) IN (?, ?)',
            ['active', 'ongoing']
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Current Month Finance
        |--------------------------------------------------------------------------
        */

        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $monthlyRevenue = Invoice::whereBetween(
            'invoice_date',
            [$monthStart->toDateString(), $monthEnd->toDateString()]
        )->sum('total_amount');

        $monthlyExpenses = Expense::whereBetween(
            'expense_date',
            [$monthStart->toDateString(), $monthEnd->toDateString()]
        )->sum('amount');

        $monthlyPayroll = Payroll::whereBetween(
            'salary_month',
            [$monthStart->toDateString(), $monthEnd->toDateString()]
        )->sum('net_salary');

        $monthlyProfit = $monthlyRevenue
            - $monthlyExpenses
            - $monthlyPayroll;

        /*
        |--------------------------------------------------------------------------
        | Invoice Outstanding / Overdue
        |--------------------------------------------------------------------------
        */

        $outstandingInvoices = Invoice::where('balance', '>', 0)
            ->count();

        $outstandingBalance = Invoice::where('balance', '>', 0)
            ->sum('balance');

        $overdueBalance = Invoice::where('balance', '>', 0)
            ->whereDate('due_date', '<', $now->toDateString())
            ->sum('balance');

        /*
        |--------------------------------------------------------------------------
        | Fuel
        |--------------------------------------------------------------------------
        */

        $reimbursableFuel = Fuel::where('reimbursable', true)
            ->sum('reimbursement_amount');

        /*
        |--------------------------------------------------------------------------
        | Expiring Documents
        |--------------------------------------------------------------------------
        */

        $today = $now->copy()->startOfDay();

        $expiry7 = $now->copy()->addDays(7)->endOfDay();
        $expiry15 = $now->copy()->addDays(15)->endOfDay();
        $expiry30 = $now->copy()->addDays(30)->endOfDay();

        $documentsExpiring7 = 0;
        $documentsExpiring15 = 0;
        $documentsExpiring30 = 0;

        /*
        | Driver documents
        */

        $driverExpiries = Driver::query()
            ->where(function ($query) {
                $query->whereNotNull('license_expiry')
                    ->orWhereNotNull('visa_expiry');
            })
            ->get(['license_expiry', 'visa_expiry']);

        foreach ($driverExpiries as $driver) {
            foreach ([
                $driver->license_expiry,
                $driver->visa_expiry,
            ] as $expiry) {
                if (!$expiry) {
                    continue;
                }

                $expiryDate = Carbon::parse($expiry)->endOfDay();

                if ($expiryDate->between($today, $expiry7)) {
                    $documentsExpiring7++;
                }

                if ($expiryDate->between($today, $expiry15)) {
                    $documentsExpiring15++;
                }

                if ($expiryDate->between($today, $expiry30)) {
                    $documentsExpiring30++;
                }
            }
        }

        /*
        | Vehicle documents
        */

        $vehicleExpiries = Vehicle::query()
            ->where(function ($query) {
                $query->whereNotNull('insurance_expiry')
                    ->orWhereNotNull('registration_expiry');
            })
            ->get(['insurance_expiry', 'registration_expiry']);

        foreach ($vehicleExpiries as $vehicle) {
            foreach ([
                $vehicle->insurance_expiry,
                $vehicle->registration_expiry,
            ] as $expiry) {
                if (!$expiry) {
                    continue;
                }

                $expiryDate = Carbon::parse($expiry)->endOfDay();

                if ($expiryDate->between($today, $expiry7)) {
                    $documentsExpiring7++;
                }

                if ($expiryDate->between($today, $expiry15)) {
                    $documentsExpiring15++;
                }

                if ($expiryDate->between($today, $expiry30)) {
                    $documentsExpiring30++;
                }
            }
        }

        /*
        | Client trade licence
        */

        $clientExpiries = Client::query()
            ->whereNotNull('trade_licence_expiry')
            ->pluck('trade_licence_expiry');

        foreach ($clientExpiries as $expiry) {
            $expiryDate = Carbon::parse($expiry)->endOfDay();

            if ($expiryDate->between($today, $expiry7)) {
                $documentsExpiring7++;
            }

            if ($expiryDate->between($today, $expiry15)) {
                $documentsExpiring15++;
            }

            if ($expiryDate->between($today, $expiry30)) {
                $documentsExpiring30++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly Trend - Last 6 Months
        |--------------------------------------------------------------------------
        */

        $trendLabels = [];
        $revenueTrend = [];
        $expenseTrend = [];
        $profitTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = $now->copy()
                ->subMonths($i)
                ->startOfMonth();

            $end = $start->copy()->endOfMonth();

            $revenue = Invoice::whereBetween(
                'invoice_date',
                [$start->toDateString(), $end->toDateString()]
            )->sum('total_amount');

            $expenses = Expense::whereBetween(
                'expense_date',
                [$start->toDateString(), $end->toDateString()]
            )->sum('amount');

            $payroll = Payroll::whereBetween(
                'salary_month',
                [$start->toDateString(), $end->toDateString()]
            )->sum('net_salary');

            $profit = $revenue - $expenses - $payroll;

            $trendLabels[] = $start->format('M Y');
            $revenueTrend[] = (float) $revenue;
            $expenseTrend[] = (float) ($expenses + $payroll);
            $profitTrend[] = (float) $profit;
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
                ['active', 'ongoing']
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
                'message' => 'Outstanding overdue balance requires attention.',
                'value' => 'AED ' . number_format($overdueBalance, 2),
            ]);
        }

        if ($documentsExpiring7 > 0) {
            $criticalAlerts->push([
                'type' => 'document',
                'title' => 'Documents expiring soon',
                'message' => 'Documents are expiring within 7 days.',
                'value' => $documentsExpiring7 . ' document(s)',
            ]);
        }

        if ($maintenanceVehicles > 0) {
            $criticalAlerts->push([
                'type' => 'maintenance',
                'title' => 'Vehicles under maintenance',
                'message' => 'Vehicles currently unavailable for operations.',
                'value' => $maintenanceVehicles . ' vehicle(s)',
            ]);
        }

        if ($reimbursableFuel > 0) {
            $criticalAlerts->push([
                'type' => 'fuel',
                'title' => 'Reimbursable fuel',
                'message' => 'Fuel reimbursement requires recovery tracking.',
                'value' => 'AED ' . number_format($reimbursableFuel, 2),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
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
        ));
    }
}