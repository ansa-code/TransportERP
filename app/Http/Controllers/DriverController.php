<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Driver;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $visaType = $request->input('visa_type');
        $passportCustody = $request->input('passport_custody');
        $status = $request->input('status');

        /*
        |--------------------------------------------------------------------------
        | DRIVER RECORDS
        |--------------------------------------------------------------------------
        */

        $drivers = Driver::query()

            ->with([
                'assignments' => function ($query) {
                    $query
                        ->with('vehicle')
                        ->where('status', 'Active')
                        ->whereDate('start_date', '<=', Carbon::today())
                        ->where(function ($q) {
                            $q->whereNull('end_date')
                                ->orWhereDate('end_date', '>=', Carbon::today());
                        })
                        ->latest('start_date');
                },
            ])

            // Search
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('driver_name', 'like', "%{$search}%")
                        ->orWhere('driver_code', 'like', "%{$search}%")
                        ->orWhere('cnic', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%")
                        ->orWhere('emirates_id', 'like', "%{$search}%")
                        ->orWhere('license_number', 'like', "%{$search}%");
                });
            })

            // Visa Type Filter
            ->when($visaType, function ($query, $visaType) {
                $query->where('visa_type', $visaType);
            })

            // Passport Custody Filter
            ->when(
                $passportCustody !== null && $passportCustody !== '',
                function ($query) use ($passportCustody) {
                    $query->where(
                        'passport_held_by_company',
                        $passportCustody === 'company' ? 1 : 0
                    );
                }
            )

            // Employment Status Filter
            ->when($status, function ($query, $status) {
                $query->where('employment_status', $status);
            })

            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DRIVER DASHBOARD STATS
        |--------------------------------------------------------------------------
        */

        $totalDrivers = Driver::count();

        /*
        |--------------------------------------------------------------------------
        | COMPANY VISA
        |--------------------------------------------------------------------------
        |
        | FRD separates:
        | - Visa Type
        | - Visa Provided By
        |
        | Therefore "Company Visa" is based on visa_provided_by.
        |
        */

        $companyVisaDrivers = Driver::where(
            'visa_provided_by',
            'Company'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | OWN VISA
        |--------------------------------------------------------------------------
        |
        | Keep this based on the actual Visa Type field for now.
        | We do not assume that "Sponsor" automatically means "Own Visa".
        |
        */

        $ownVisaDrivers = Driver::where(
            'visa_type',
            'Own Visa'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | PASSPORT HELD
        |--------------------------------------------------------------------------
        */

        $passportHeldDrivers = Driver::where(
            'passport_held_by_company',
            1
        )->count();

        return view('drivers.index', compact(
            'drivers',
            'search',
            'visaType',
            'passportCustody',
            'status',
            'totalDrivers',
            'companyVisaDrivers',
            'ownVisaDrivers',
            'passportHeldDrivers'
        ));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_code' => 'required|string|max:255|unique:drivers,driver_code',
            'driver_name' => 'required|string|min:2|max:150',
            'cnic' => 'nullable|string|max:255',

            'license_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:drivers,license_number',
            ],

            'license_expiry' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',

            'passport_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:drivers,passport_number',
            ],

            'passport_held_by_company' => 'required|boolean',

            'visa_type' => 'nullable|string|max:255',
            'visa_provided_by' => 'nullable|string|max:255',
            'visa_expiry' => 'nullable|date',

            'emirates_id' => [
                'nullable',
                'string',
                'max:255',
                'unique:drivers,emirates_id',
            ],

            'basic_salary' => 'required|numeric|min:0',
            'assigned_vehicle_id' => 'nullable|integer',

            'employment_status' => [
                'required',
                'in:Active,On Leave,Inactive,Archived',
            ],

            'remarks' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $driver = Driver::create($validated);

        $this->activityLogService->created(
            module: 'Drivers',
            subject: $driver,
            description: "Created driver record: {$driver->driver_name}."
        );

        return redirect('/drivers')
            ->with('success', 'Driver Added Successfully!');
    }

    public function show(string $id)
    {
        $driver = Driver::findOrFail($id);

        return view('drivers.show', compact('driver'));
    }

    public function edit(string $id)
    {
        $driver = Driver::findOrFail($id);

        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);

        $validated = $request->validate([
            'driver_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'driver_code')->ignore($driver->id),
            ],

            'driver_name' => 'required|string|min:2|max:150',
            'cnic' => 'nullable|string|max:255',

            'license_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('drivers', 'license_number')->ignore($driver->id),
            ],

            'license_expiry' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',

            'passport_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('drivers', 'passport_number')->ignore($driver->id),
            ],

            'passport_held_by_company' => 'required|boolean',

            'visa_type' => 'nullable|string|max:255',
            'visa_provided_by' => 'nullable|string|max:255',
            'visa_expiry' => 'nullable|date',

            'emirates_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('drivers', 'emirates_id')->ignore($driver->id),
            ],

            'basic_salary' => 'required|numeric|min:0',
            'assigned_vehicle_id' => 'nullable|integer',

            'employment_status' => [
                'required',
                'in:Active,On Leave,Inactive,Archived',
            ],

            'remarks' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $driver->getAttributes();

        $driver->update($validated);

        $this->activityLogService->updated(
            module: 'Drivers',
            subject: $driver,
            oldValues: $oldValues,
            newValues: $driver->getAttributes(),
            description: "Updated driver record: {$driver->driver_name}."
        );

        return redirect('/drivers')
            ->with('success', 'Driver Updated Successfully!');
    }

    public function destroy(string $id)
    {
        $driver = Driver::findOrFail($id);

        $oldValues = $driver->getAttributes();
        $driverName = $driver->driver_name;

        $this->activityLogService->deleted(
            module: 'Drivers',
            subject: $driver,
            oldValues: $oldValues,
            description: "Deleted driver record: {$driverName}."
        );

        $driver->delete();

        return redirect('/drivers')
            ->with('success', 'Driver Deleted Successfully!');
    }
}