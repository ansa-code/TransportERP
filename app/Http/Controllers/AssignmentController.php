<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

       $assignments = Assignment::with(['client','vehicle','driver'])
    ->whereHas('client', function ($q) use ($search) {
        $q->where('client_name', 'like', "%{$search}%");
    })
    ->orWhereHas('vehicle', function ($q) use ($search) {
        $q->where('vehicle_number', 'like', "%{$search}%");
    })
    ->orWhereHas('driver', function ($q) use ($search) {
        $q->where('driver_name', 'like', "%{$search}%");
    })
    ->paginate(5)
    ->withQueryString();

        return view('assignments.index', compact(
            'assignments',
            'search'
        ));
    }


    public function create()
    {
        $clients = Client::all();
        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        return view('assignments.create', compact(
            'clients',
            'vehicles',
            'drivers'
        ));
    }


    public function store(Request $request)
    {
        Assignment::create($request->all());

        return redirect('/assignments')
            ->with('success', 'Assignment Added Successfully!');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $assignment = Assignment::findOrFail($id);

        return view('assignments.edit', compact('assignment'));
    }


    public function update(Request $request, string $id)
    {
        $assignment = Assignment::findOrFail($id);

        $assignment->update($request->all());

        return redirect('/assignments')
            ->with('success', 'Assignment Updated Successfully!');
    }


    public function destroy(string $id)
{
    $assignment = Assignment::findOrFail($id);

    $assignment->delete();

    return redirect('/assignments')
        ->with('success', 'Assignment Deleted Successfully!');
}
}
