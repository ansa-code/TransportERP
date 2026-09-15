<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Assignment;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TripController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | Trip Management
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $query = Trip::with([
            'assignment',
            'client',
            'vehicle',
            'driver',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search) {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'trip_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where(
                        'client_name',
                        'like',
                        "%{$search}%"
                    );
                })

                ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {

                    $vehicleQuery->where(function ($vehicleSearch) use ($search) {

                        $vehicleSearch
                            ->where(
                                'plate_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'vehicle_number',
                                'like',
                                "%{$search}%"
                            );

                    });

                })

                ->orWhereHas('driver', function ($driverQuery) use ($search) {
                    $driverQuery->where(
                        'driver_name',
                        'like',
                        "%{$search}%"
                    );
                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status) {

            $query->where(
                'status',
                $status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Trips
        |--------------------------------------------------------------------------
        */

        $trips = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'trips.index',
            compact(
                'trips',
                'search',
                'status'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Trip
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $assignments = Assignment::with([
            'client',
            'vehicle',
            'driver',
        ])
            ->whereIn('status', [
                'Planned',
                'Active',
            ])
            ->latest()
            ->get();

        $clients = Client::where(
            'status',
            'Active'
        )
            ->orderBy('client_name')
            ->get();

        $vehicles = Vehicle::whereNotIn(
            'status',
            [
                'Maintenance',
                'Inactive',
                'Archived',
            ]
        )
            ->orderBy('plate_number')
            ->get();

        $drivers = Driver::whereNotIn(
            'employment_status',
            [
                'Inactive',
                'Archived',
            ]
        )
            ->orderBy('driver_name')
            ->get();

        return view(
            'trips.create',
            compact(
                'assignments',
                'clients',
                'vehicles',
                'drivers'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Trip
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'client_id' => [
                'required',
                'exists:clients,id',
            ],

            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],

            'driver_id' => [
                'required',
                'exists:drivers,id',
            ],

            'trip_start' => [
                'required',
                'date',
            ],

            'trip_end' => [
                'nullable',
                'date',
                'after_or_equal:trip_start',
            ],

            'loading_point' => [
                'required',
                'string',
            ],

            'unloading_point' => [
                'required',
                'string',
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
            ],

            'freight_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'pod_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Planned',
                    'Assigned',
                    'In Transit',
                    'Delivered',
                    'Closed',
                    'Cancelled',
                ]),
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Trip Number
        |--------------------------------------------------------------------------
        */

        $validated['trip_no'] =
            $this->generateTripNumber();

        /*
        |--------------------------------------------------------------------------
        | POD Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('pod_file')) {

            $validated['pod_file'] =
                $request->file('pod_file')
                    ->store(
                        'trips/pod',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Trip
        |--------------------------------------------------------------------------
        */

        $trip = Trip::create($validated);

        $this->activityLogService->created(
            module: 'Trips',
            subject: $trip,
            description: "Created trip {$trip->trip_no}."
        );

        return redirect('/trips')
            ->with(
                'success',
                'Trip Added Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Trip Number
    |--------------------------------------------------------------------------
    */

    private function generateTripNumber()
    {
        $year = now()->format('Y');

        $prefix = "AST-TRP-{$year}-";

        $lastTrip = Trip::where(
            'trip_no',
            'like',
            "{$prefix}%"
        )
            ->orderByDesc('id')
            ->first();

        if ($lastTrip) {

            $lastNumber = (int) substr(
                $lastTrip->trip_no,
                -5
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }

        return $prefix . str_pad(
            $nextNumber,
            5,
            '0',
            STR_PAD_LEFT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Trip
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $trip = Trip::with([
            'assignment',
            'client',
            'vehicle',
            'driver',
        ])->findOrFail($id);

        return view(
            'trips.show',
            compact('trip')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Trip
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $trip = Trip::findOrFail($id);

        $assignments = Assignment::with([
            'client',
            'vehicle',
            'driver',
        ])
            ->whereIn('status', [
                'Planned',
                'Active',
            ])
            ->latest()
            ->get();

        $clients = Client::where(
            'status',
            'Active'
        )
            ->orderBy('client_name')
            ->get();

        $vehicles = Vehicle::whereNotIn(
            'status',
            [
                'Maintenance',
                'Inactive',
                'Archived',
            ]
        )
            ->orderBy('plate_number')
            ->get();

        $drivers = Driver::whereNotIn(
            'employment_status',
            [
                'Inactive',
                'Archived',
            ]
        )
            ->orderBy('driver_name')
            ->get();

        return view(
            'trips.edit',
            compact(
                'trip',
                'assignments',
                'clients',
                'vehicles',
                'drivers'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Trip
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {
        $trip = Trip::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Closed Trip Protection
        |--------------------------------------------------------------------------
        */

        if (
            $trip->status === 'Closed' &&
            !$request->boolean('reopen')
        ) {

            return back()
                ->with(
                    'error',
                    'Closed trips cannot be edited without reopen permission.'
                );
        }

        $validated = $request->validate([

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'client_id' => [
                'required',
                'exists:clients,id',
            ],

            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],

            'driver_id' => [
                'required',
                'exists:drivers,id',
            ],

            'trip_start' => [
                'required',
                'date',
            ],

            'trip_end' => [
                'nullable',
                'date',
                'after_or_equal:trip_start',
            ],

            'loading_point' => [
                'required',
                'string',
            ],

            'unloading_point' => [
                'required',
                'string',
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
            ],

            'freight_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'pod_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Planned',
                    'Assigned',
                    'In Transit',
                    'Delivered',
                    'Closed',
                    'Cancelled',
                ]),
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | POD Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('pod_file')) {

            $validated['pod_file'] =
                $request->file('pod_file')
                    ->store(
                        'trips/pod',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Trip
        |--------------------------------------------------------------------------
        */

        $oldValues = $trip->getAttributes();

        $trip->update($validated);

        $this->activityLogService->updated(
            module: 'Trips',
            subject: $trip,
            oldValues: $oldValues,
            newValues: $trip->getAttributes(),
            description: "Updated trip {$trip->trip_no}."
        );

        return redirect('/trips')
            ->with(
                'success',
                'Trip Updated Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Trip
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $trip = Trip::findOrFail($id);

        $oldValues = $trip->getAttributes();
        $tripNo = $trip->trip_no;

        $this->activityLogService->deleted(
            module: 'Trips',
            subject: $trip,
            oldValues: $oldValues,
            description: "Deleted trip {$tripNo}."
        );

        $trip->delete();

        return redirect('/trips')
            ->with(
                'success',
                'Trip Deleted Successfully!'
            );
    }
}