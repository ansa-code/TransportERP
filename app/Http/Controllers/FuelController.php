<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $fuels = Fuel::whereHas('vehicle', function ($q) use ($search) {
            $q->where('vehicle_number', 'like', "%$search%");
        })
        ->orWhereHas('driver', function ($q) use ($search) {
            $q->where('driver_name', 'like', "%$search%");
        })
        ->paginate(5);

        return view('fuels.index', compact('fuels', 'search'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        return view('fuels.create', compact('vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        Fuel::create($request->all());

        return redirect('/fuels')->with('success','Fuel Record Added Successfully!');
    }

   public function edit($id)
{
    $fuel = Fuel::findOrFail($id);

    $vehicles = Vehicle::all();

    $drivers = Driver::all();

    return view('fuels.edit', compact(
        'fuel',
        'vehicles',
        'drivers'
    ));
}

    public function update(Request $request, $id)
{
    $fuel = Fuel::findOrFail($id);

    $fuel->update($request->all());

    return redirect('/fuels')
        ->with('success','Fuel Updated Successfully!');
}

    public function destroy($id)
{
    Fuel::findOrFail($id)->delete();

    return redirect('/fuels')
        ->with('success','Fuel Deleted Successfully!');
}
}