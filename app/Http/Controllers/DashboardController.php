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

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [

            'vehicles' => Vehicle::count(),

            'drivers' => Driver::count(),

            'clients' => Client::count(),

            'vendors' => Vendor::count(),

            'assignments' => Assignment::count(),

            'fuels' => Fuel::count(),

            'maintenances' => Maintenance::count(),

            'expenses' => Expense::sum('amount'),

            'fuelAmount' => Fuel::sum('total_amount'),

            'maintenanceCost' => Maintenance::sum('cost'),

        ]);
    }
}