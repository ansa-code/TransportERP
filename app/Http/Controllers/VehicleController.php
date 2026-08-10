<?php

namespace App\Http\Controllers;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Requests\StoreVehicleRequest;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $vehicles = Vehicle::where('vehicle_number', 'like', "%$search%")
            ->orWhere('plate_number', 'like', "%$search%")
            ->orWhere('brand', 'like', "%$search%")
            ->orWhere('model', 'like', "%$search%")
            ->paginate(5);

        return view('vehicles.index', compact('vehicles', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        Vehicle::create([
            'vehicle_number' => $request->vehicle_number,
            'plate_number' => $request->plate_number,
            'vehicle_type' => $request->vehicle_type,
            'brand' => $request->brand,
            'model' => $request->model,
            'manufacture_year' => $request->manufacture_year,
            'capacity' => $request->capacity,
            'refrigerated' => $request->has('refrigerated'),
            'status' => $request->status,
            'insurance_expiry' => $request->insurance_expiry,
            'registration_expiry' => $request->registration_expiry,
            'notes' => $request->notes,
        ]);

        return redirect('/vehicles')->with('success', 'Vehicle Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateVehicleRequest $request, string $id)
{
    $vehicle = Vehicle::findOrFail($id);

    $vehicle->update($request->all());

    return redirect('/vehicles')->with('success', 'Vehicle Updated Successfully!');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $vehicle->delete();

        return redirect('/vehicles')->with('success', 'Vehicle Deleted Successfully!');
    }
}