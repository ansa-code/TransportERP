<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\Assignment;
use App\Models\Fuel;
use App\Models\Maintenance;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payroll;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index', [

            'vehicles' => Vehicle::count(),

            'drivers' => Driver::count(),

            'clients' => Client::count(),

            'vendors' => Vendor::count(),

            'assignments' => Assignment::count(),

            'fuels' => Fuel::count(),

            'maintenances' => Maintenance::count(),

            'expenses' => Expense::sum('amount'),

            'invoices' => Invoice::sum('amount'),

            'payrolls' => Payroll::sum('net_salary'),

        ]);
    }
}