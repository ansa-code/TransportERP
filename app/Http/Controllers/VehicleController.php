<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display vehicle inventory.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $vehicles = Vehicle::with('assignments.driver')

            // Search
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('vehicle_number', 'like', "%{$search}%")
                        ->orWhere('vehicle_code', 'like', "%{$search}%")
                        ->orWhere('plate_number', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");

                });

            })

            // Truck Type filter
            ->when($request->vehicle_type, function ($query) use ($request) {

                $query->where(
                    'vehicle_type',
                    $request->vehicle_type
                );

            })

            // Ownership filter
            ->when($request->ownership_type, function ($query) use ($request) {

                $query->where(
                    'ownership_type',
                    $request->ownership_type
                );

            })

            // Status filter
            ->when($request->status, function ($query) use ($request) {

                $query->where(
                    'status',
                    $request->status
                );

            })

            // Capacity filter
            ->when($request->capacity, function ($query) use ($request) {

                if ($request->capacity === 'small') {

                    $query->where('load_capacity', '<', 5);

                } elseif ($request->capacity === 'medium') {

                    $query->whereBetween(
                        'load_capacity',
                        [5, 15]
                    );

                } elseif ($request->capacity === 'large') {

                    $query->where(
                        'load_capacity',
                        '>',
                        15
                    );
                }

            })

            ->latest()
            ->get();

        return view(
            'vehicles.index',
            compact('vehicles', 'search')
        );
    }

    /**
     * Show create vehicle form.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store new vehicle.
     */
    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create(
            $request->validated()
        );

        $this->activityLogService->created(
            module: 'Vehicles',
            subject: $vehicle,
            description: 'Created a new vehicle record.'
        );

        return redirect('/vehicles')
            ->with(
                'success',
                'Vehicle Added Successfully!'
            );
    }

    /**
     * Display vehicle profile.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::with([
            'assignments.driver'
        ])->findOrFail($id);

        return view(
            'vehicles.show',
            compact('vehicle')
        );
    }

    /**
     * Show edit vehicle form.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        return view(
            'vehicles.edit',
            compact('vehicle')
        );
    }

    /**
     * Update vehicle.
     */
    public function update(
        UpdateVehicleRequest $request,
        string $id
    ) {
        $vehicle = Vehicle::findOrFail($id);

        $oldValues = $vehicle->getAttributes();

        $vehicle->update(
            $request->validated()
        );

        $this->activityLogService->updated(
            module: 'Vehicles',
            subject: $vehicle,
            oldValues: $oldValues,
            newValues: $vehicle->getAttributes(),
            description: 'Updated vehicle record.'
        );

        return redirect('/vehicles')
            ->with(
                'success',
                'Vehicle Updated Successfully!'
            );
    }

    /**
     * Delete vehicle.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $oldValues = $vehicle->getAttributes();

        $vehicleName =
            $vehicle->plate_number
            ?? $vehicle->vehicle_number
            ?? $vehicle->vehicle_code
            ?? 'Vehicle';

        $this->activityLogService->deleted(
            module: 'Vehicles',
            subject: $vehicle,
            oldValues: $oldValues,
            description: "Deleted vehicle record: {$vehicleName}."
        );

        $vehicle->delete();

        return redirect('/vehicles')
            ->with(
                'success',
                'Vehicle Deleted Successfully!'
            );
    }
}