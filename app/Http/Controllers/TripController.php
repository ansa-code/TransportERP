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

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('trip_no', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('client_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where(function ($vehicleSearch) use ($search) {
                            $vehicleSearch
                                ->where('plate_number', 'like', "%{$search}%")
                                ->orWhere('vehicle_number', 'like', "%{$search}%");
                        });
                    })
                    ->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery->where('driver_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $trips = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('trips.index', compact('trips', 'search', 'status'));
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
            ->whereIn('status', ['Planned', 'Active'])
            ->latest()
            ->get();

        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

        $vehicles = Vehicle::whereNotIn('status', [
            'Maintenance',
            'Inactive',
            'Archived',
        ])
            ->orderBy('plate_number')
            ->get();

        $drivers = Driver::whereNotIn('employment_status', [
            'Inactive',
            'Archived',
        ])
            ->orderBy('driver_name')
            ->get();

        return view('trips.create', compact(
            'assignments',
            'clients',
            'vehicles',
            'drivers'
        ));
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
               function ($attribute, $value, $fail) {
    if (!$value) {
        return;
    }

    $end = \Carbon\Carbon::parse($value);

    if ($end->lt(today())) {
        $fail('Trip end date must be today or a future date.');
    }
},
            ],

            'loading_point' => [
                'nullable',
                'required_with:unloading_point',
                'string',
                'max:255',
                'regex:/\S/',
            ],

            'unloading_point' => [
                'nullable',
                'required_with:loading_point',
                'string',
                'max:255',
                'regex:/\S/',
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'freight_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
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
        ], [
            'trip_start.required' => 'Trip start date and time is required.',
            'trip_start.date' => 'Please enter a valid trip start date and time.',
            'trip_end.date' => 'Please enter a valid trip end date and time.',
            'trip_end.after_or_equal' => 'Trip end date and time cannot be earlier than the trip start.',
            'loading_point.required_with' => 'Please enter both loading and unloading points, or leave both empty.',
            'unloading_point.required_with' => 'Please enter both loading and unloading points, or leave both empty.',
            'loading_point.regex' => 'Please enter a valid loading point.',
            'unloading_point.regex' => 'Please enter a valid unloading point.',
            'loading_point.max' => 'Loading point cannot exceed 255 characters.',
            'unloading_point.max' => 'Unloading point cannot exceed 255 characters.',
            'rate.required' => 'Rate is required.',
            'rate.numeric' => 'Rate must be a valid number.',
            'rate.min' => 'Rate must be zero or greater.',
            'rate.max' => 'Rate exceeds the maximum allowed business limit.',
            'rate.regex' => 'Rate can have up to 8 digits before the decimal and 2 decimal places.',
            'freight_amount.required' => 'Freight amount is required.',
            'freight_amount.numeric' => 'Freight amount must be a valid number.',
            'freight_amount.min' => 'Freight amount must be zero or greater.',
            'freight_amount.max' => 'Freight amount exceeds the maximum allowed business limit.',
            'freight_amount.regex' => 'Freight amount can have up to 8 digits before the decimal and 2 decimal places.',
            'pod_file.mimes' => 'POD file must be a PDF, JPG, JPEG or PNG file.',
            'pod_file.max' => 'POD file size cannot exceed 5 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Trip Number
        |--------------------------------------------------------------------------
        */

        $validated['trip_no'] = $this->generateTripNumber();

        /*
        |--------------------------------------------------------------------------
        | POD Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('pod_file')) {
            $validated['pod_file'] = $request->file('pod_file')
                ->store('trips/pod', 'public');
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
            ->with('success', 'Trip Added Successfully!');
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

        $lastTrip = Trip::where('trip_no', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        if ($lastTrip) {
            $lastNumber = (int) substr($lastTrip->trip_no, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
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

        return view('trips.show', compact('trip'));
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
            ->whereIn('status', ['Planned', 'Active'])
            ->latest()
            ->get();

        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

        $vehicles = Vehicle::whereNotIn('status', [
            'Maintenance',
            'Inactive',
            'Archived',
        ])
            ->orderBy('plate_number')
            ->get();

        $drivers = Driver::whereNotIn('employment_status', [
            'Inactive',
            'Archived',
        ])
            ->orderBy('driver_name')
            ->get();

        return view('trips.edit', compact(
            'trip',
            'assignments',
            'clients',
            'vehicles',
            'drivers'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Trip
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, string $id)
    {
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
            return back()->with(
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
                function ($attribute, $value, $fail) {
    if (!$value) {
        return;
    }

    $end = \Carbon\Carbon::parse($value);

    if ($end->lt(today())) {
        $fail('Trip end date must be today or a future date.');
    }
},
            ],

            'loading_point' => [
                'nullable',
                'required_with:unloading_point',
                'string',
                'max:255',
                'regex:/\S/',
            ],

            'unloading_point' => [
                'nullable',
                'required_with:loading_point',
                'string',
                'max:255',
                'regex:/\S/',
            ],

            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'freight_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
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
        ], [
            'trip_start.required' => 'Trip start date and time is required.',
            'trip_start.date' => 'Please enter a valid trip start date and time.',
            'trip_end.date' => 'Please enter a valid trip end date and time.',
            'trip_end.after_or_equal' => 'Trip end date and time cannot be earlier than the trip start.',
            'loading_point.required_with' => 'Please enter both loading and unloading points, or leave both empty.',
            'unloading_point.required_with' => 'Please enter both loading and unloading points, or leave both empty.',
            'loading_point.regex' => 'Please enter a valid loading point.',
            'unloading_point.regex' => 'Please enter a valid unloading point.',
            'loading_point.max' => 'Loading point cannot exceed 255 characters.',
            'unloading_point.max' => 'Unloading point cannot exceed 255 characters.',
            'rate.required' => 'Rate is required.',
            'rate.numeric' => 'Rate must be a valid number.',
            'rate.min' => 'Rate must be zero or greater.',
            'rate.max' => 'Rate exceeds the maximum allowed business limit.',
            'rate.regex' => 'Rate can have up to 8 digits before the decimal and 2 decimal places.',
            'freight_amount.required' => 'Freight amount is required.',
            'freight_amount.numeric' => 'Freight amount must be a valid number.',
            'freight_amount.min' => 'Freight amount must be zero or greater.',
            'freight_amount.max' => 'Freight amount exceeds the maximum allowed business limit.',
            'freight_amount.regex' => 'Freight amount can have up to 8 digits before the decimal and 2 decimal places.',
            'pod_file.mimes' => 'POD file must be a PDF, JPG, JPEG or PNG file.',
            'pod_file.max' => 'POD file size cannot exceed 5 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | POD Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('pod_file')) {
            $validated['pod_file'] = $request->file('pod_file')
                ->store('trips/pod', 'public');
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
            ->with('success', 'Trip Updated Successfully!');
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
            ->with('success', 'Trip Deleted Successfully!');
    }
}