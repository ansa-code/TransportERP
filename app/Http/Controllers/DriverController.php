<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $drivers = Driver::where('driver_name', 'like', "%$search%")
        ->orWhere('cnic', 'like', "%$search%")
        ->orWhere('phone', 'like', "%$search%")
        ->paginate(5);

    return view('drivers.index', compact('drivers', 'search'));
}

    public function create()
    {
        return view('drivers.create');
    }

   public function store(Request $request)
{
    Driver::create([

        'driver_name' => $request->driver_name,

        'cnic' => $request->cnic,

        'license_number' => $request->license_number,

        'license_expiry' => $request->license_expiry,

        'phone' => $request->phone,

        'address' => $request->address,

        'date_of_birth' => $request->date_of_birth,

        'joining_date' => $request->joining_date,

        'status' => $request->status,

        'notes' => $request->notes,

    ]);

    return redirect('/drivers')->with('success', 'Driver Added Successfully!');
}

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
{
    $driver = Driver::findOrFail($id);

    return view('drivers.edit', compact('driver'));
}

    public function update(Request $request, string $id)
{
    $driver = Driver::findOrFail($id);

    $driver->update($request->all());

    return redirect('/drivers')
        ->with('success','Driver Updated Successfully!');
}

    public function destroy(string $id)
{
    $driver = Driver::findOrFail($id);

    $driver->delete();

    return redirect('/drivers')
        ->with('success','Driver Deleted Successfully!');
}
}