<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $maintenances = Maintenance::whereHas('vehicle', function ($q) use ($search) {

            $q->where('vehicle_number', 'like', "%$search%");

        })->paginate(5);

        return view('maintenances.index', compact(
            'maintenances',
            'search'
        ));
    }

    public function create()
    {
        $vehicles = Vehicle::all();

        return view('maintenances.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        Maintenance::create($request->all());

        return redirect('/maintenances')
            ->with('success','Maintenance Record Added Successfully!');
    }

    public function edit($id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $vehicles = Vehicle::all();

        return view('maintenances.edit', compact(
            'maintenance',
            'vehicles'
        ));
    }

    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $maintenance->update($request->all());

        return redirect('/maintenances')
            ->with('success','Maintenance Updated Successfully!');
    }

    public function destroy($id)
    {
        Maintenance::findOrFail($id)->delete();

        return redirect('/maintenances')
            ->with('success','Maintenance Deleted Successfully!');
    }
}