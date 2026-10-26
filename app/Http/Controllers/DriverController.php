<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Driver;
use App\Models\Vehicle;
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
            ->when($visaType, function ($query, $visaType) {
                $query->where('visa_type', $visaType);
            })
            ->when(
                $passportCustody !== null && $passportCustody !== '',
                function ($query) use ($passportCustody) {
                    $query->where(
                        'passport_held_by_company',
                        $passportCustody === 'company' ? 1 : 0
                    );
                }
            )
            ->when($status, function ($query, $status) {
                $query->where('employment_status', $status);
            })
            ->latest()
            ->get();

        $totalDrivers = Driver::count();

        $companyVisaDrivers = Driver::where(
            'visa_provided_by',
            'Company'
        )->count();

        $ownVisaDrivers = Driver::where(
            'visa_type',
            'Own Visa'
        )->count();

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
        $vehicles = Vehicle::orderBy('plate_number')->get();

        return view('drivers.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'driver_code' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:drivers,driver_code',
                ],

                'driver_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:150',
                ],

                'cnic' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:drivers,cnic',
                ],

                'license_number' => [
                    'nullable',
                    'string',
                    'max:255',
                    'unique:drivers,license_number',
                ],

                'license_expiry' => [
                    'required',
                    'date',
                    'after:today',
                ],

                'phone' => [
    'nullable',
    'string',
    'max:30',
    'regex:/^\+?[0-9\s().-]+$/',
    'unique:drivers,phone',
    function ($attribute, $value, $fail) {
        if ($value === null || trim($value) === '') {
            return;
        }

        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) < 7 || strlen($digits) > 15) {
            $fail('Mobile number must contain between 7 and 15 digits, including the country code.');
        }
    },
],

                'nationality' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'passport_number' => [
                    'nullable',
                    'string',
                    'max:255',
                    'unique:drivers,passport_number',
                ],

                'passport_held_by_company' => [
                    'required',
                    'boolean',
                ],

                'visa_type' => [
                    'nullable',
                    Rule::in([
                        'Temporary',
                        'Permanent',
                        'Other',
                    ]),
                ],

                'visa_provided_by' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'visa_expiry' => [
                    'nullable',
                    'date',
                    'after:today',
                ],

                'emirates_id' => [
                    'nullable',
                    'string',
                    'max:255',
                    'unique:drivers,emirates_id',
                ],

                'basic_salary' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:99999999.99',
                ],

                'assigned_vehicle_id' => [
                    'nullable',
                    'integer',
                    'exists:vehicles,id',
                ],

                'employment_status' => [
                    'required',
                    'in:Active,On Leave,Inactive,Archived',
                ],

                'remarks' => [
                    'nullable',
                    'string',
                ],

                'address' => [
                    'nullable',
                    'string',
                ],

                'date_of_birth' => [
                    'nullable',
                    'date',
                    'before_or_equal:' . now()->subYears(18)->toDateString(),
                ],

                'joining_date' => [
                    'nullable',
                    'date',
                ],

                'status' => [
                    'nullable',
                    'string',
                ],

                'notes' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                'driver_code.required' => 'Driver ID / Code is required. Please fill this field.',
                'driver_code.unique' => 'Driver ID / Code has already been taken. Please enter a different Driver ID / Code.',
                'driver_code.string' => 'Driver ID / Code must be a valid text value.',
                'driver_code.max' => 'Driver ID / Code may not be longer than 255 characters.',

                'driver_name.required' => 'Driver Name is required. Please fill this field.',
                'driver_name.min' => 'Driver Name must be at least 2 characters.',
                'driver_name.max' => 'Driver Name may not be longer than 150 characters.',

                'cnic.required' => 'CNIC is required. Please fill this field.',
                'cnic.unique' => 'CNIC has already been taken. Please enter a different CNIC.',
                'cnic.string' => 'CNIC must be a valid text value.',
                'cnic.max' => 'CNIC may not be longer than 255 characters.',

                'license_number.unique' => 'Licence Number has already been taken. Please enter a different Licence Number.',
                'license_number.string' => 'Licence Number must be a valid text value.',
                'license_number.max' => 'Licence Number may not be longer than 255 characters.',
                'license_expiry.date' => 'Licence Expiry must be a valid date.',
                'license_expiry.required' => 'Licence Expiry is required. Please select an expiry date.',
                'license_expiry.after' => 'Licence expiry date must be in the future.',

                'phone.string' => 'Mobile number must be a valid text value.',
                'phone.max' => 'Mobile number is too long.',
                'phone.regex' => 'Please enter a valid international mobile number.',

                'nationality.string' => 'Nationality must be a valid text value.',
                'nationality.max' => 'Nationality may not be longer than 255 characters.',

                'passport_number.unique' => 'Passport Number has already been taken. Please enter a different Passport Number.',
                'passport_number.string' => 'Passport Number must be a valid text value.',
                'passport_number.max' => 'Passport Number may not be longer than 255 characters.',

                'passport_held_by_company.required' => 'Passport Held by Company is required. Please select an option.',
                'passport_held_by_company.boolean' => 'Passport Held by Company must be Yes or No.',

                'visa_type.in' => 'Please select a valid Visa Type from the dropdown.',

                'visa_provided_by.string' => 'Visa Provided By must be a valid value.',
                'visa_provided_by.max' => 'Visa Provided By may not be longer than 255 characters.',

                'visa_expiry.date' => 'Visa Expiry must be a valid date.',
                'visa_expiry.after' => 'Visa expiry date must be in the future.',

                'emirates_id.unique' => 'Emirates ID has already been taken. Please enter a different Emirates ID.',
                'emirates_id.string' => 'Emirates ID must be a valid text value.',
                'emirates_id.max' => 'Emirates ID may not be longer than 255 characters.',

                'basic_salary.required' => 'Basic Salary is required. Please enter the basic salary.',
                'basic_salary.numeric' => 'Basic Salary must be a valid number.',
                'basic_salary.min' => 'Basic Salary cannot be less than 0.',
                'basic_salary.max' => 'Basic Salary cannot exceed 99,999,999.99.',

                'assigned_vehicle_id.integer' => 'Assigned Vehicle must be a valid vehicle.',
                'assigned_vehicle_id.exists' => 'The selected Assigned Vehicle does not exist.',

                'employment_status.required' => 'Employment Status is required. Please select an option.',
                'employment_status.in' => 'Please select a valid Employment Status.',

                'date_of_birth.before_or_equal' => 'Driver must be at least 18 years old.',
                'date_of_birth.date' => 'Date of Birth must be a valid date.',
                'joining_date.date' => 'Joining Date must be a valid date.',

                'status.string' => 'Status must be a valid value.',
                'remarks.string' => 'Remarks must be valid text.',
                'address.string' => 'Address must be valid text.',
                'notes.string' => 'Notes must be valid text.',

                'phone.unique' => 'This mobile number has already been taken. Please enter a different mobile number.',
            ]
        );

        $driver = Driver::create($validated);

        $this->activityLogService->created(
            module: 'Drivers',
            subject: $driver,
            description: "Created driver record: {$driver->driver_name}."
        );

        return redirect('/drivers')
            ->with('success', 'Driver Added Successfully!');
    }

    // PART 2 CONTINUES BELOW
    public function show(string $id)
    {
        $driver = Driver::findOrFail($id);

        return view('drivers.show', compact('driver'));
    }

    public function edit(string $id)
    {
        $driver = Driver::findOrFail($id);

        $vehicles = Vehicle::orderBy('plate_number')->get();

        return view('drivers.edit', compact('driver', 'vehicles'));
    }

    public function update(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);

        $validated = $request->validate(
            [
                'driver_code' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('drivers', 'driver_code')->ignore($driver->id),
                ],

                'driver_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:150',
                ],

                'cnic' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('drivers', 'cnic')->ignore($driver->id),
                ],

                'license_number' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('drivers', 'license_number')->ignore($driver->id),
                ],

                'license_expiry' => [
                    'required',
                    'date',
                    'after:today',
                ],

                'phone' => [
    'nullable',
    'string',
    'max:30',
    'regex:/^\+?[0-9\s().-]+$/',
    Rule::unique('drivers', 'phone')->ignore($driver->id),
    function ($attribute, $value, $fail) {
        if ($value === null || trim($value) === '') {
            return;
        }

        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) < 7 || strlen($digits) > 15) {
            $fail('Mobile number must contain between 7 and 15 digits, including the country code.');
        }
    },
],

                'nationality' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'passport_number' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('drivers', 'passport_number')->ignore($driver->id),
                ],

                'passport_held_by_company' => [
                    'required',
                    'boolean',
                ],

                'visa_type' => [
                    'nullable',
                    Rule::in([
                        'Temporary',
                        'Permanent',
                        'Other',
                    ]),
                ],

                'visa_provided_by' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'visa_expiry' => [
                    'nullable',
                    'date',
                    'after:today',
                ],

                'emirates_id' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('drivers', 'emirates_id')->ignore($driver->id),
                ],

                'basic_salary' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:99999999.99',
                ],

                'assigned_vehicle_id' => [
                    'nullable',
                    'integer',
                    'exists:vehicles,id',
                ],

                'employment_status' => [
                    'required',
                    'in:Active,On Leave,Inactive,Archived',
                ],

                'remarks' => [
                    'nullable',
                    'string',
                ],

                'address' => [
                    'nullable',
                    'string',
                ],

                'date_of_birth' => [
                    'nullable',
                    'date',
                    'before_or_equal:' . now()->subYears(18)->toDateString(),
                ],

                'joining_date' => [
                    'nullable',
                    'date',
                ],

                'status' => [
                    'nullable',
                    'string',
                ],

                'notes' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                'driver_code.required' => 'Driver ID / Code is required. Please fill this field.',
                'driver_code.unique' => 'Driver ID / Code has already been taken. Please enter a different Driver ID / Code.',
                'driver_code.string' => 'Driver ID / Code must be a valid text value.',
                'driver_code.max' => 'Driver ID / Code may not be longer than 255 characters.',

                'driver_name.required' => 'Driver Name is required. Please fill this field.',
                'driver_name.min' => 'Driver Name must be at least 2 characters.',
                'driver_name.max' => 'Driver Name may not be longer than 150 characters.',

                'cnic.required' => 'CNIC is required. Please fill this field.',
                'cnic.unique' => 'CNIC has already been taken. Please enter a different CNIC.',
                'cnic.string' => 'CNIC must be a valid text value.',
                'cnic.max' => 'CNIC may not be longer than 255 characters.',

                'license_number.unique' => 'Licence Number has already been taken. Please enter a different Licence Number.',
                'license_number.string' => 'Licence Number must be a valid text value.',
                'license_number.max' => 'Licence Number may not be longer than 255 characters.',
                'license_expiry.date' => 'Licence Expiry must be a valid date.',
                'license_expiry.required' => 'Licence Expiry is required. Please select an expiry date.',
                'license_expiry.after' => 'Licence expiry date must be in the future.',

                'phone.string' => 'Mobile number must be a valid text value.',
                'phone.max' => 'Mobile number is too long.',
                'phone.regex' => 'Please enter a valid international mobile number.',

                'nationality.string' => 'Nationality must be a valid text value.',
                'nationality.max' => 'Nationality may not be longer than 255 characters.',

                'passport_number.unique' => 'Passport Number has already been taken. Please enter a different Passport Number.',
                'passport_number.string' => 'Passport Number must be a valid text value.',
                'passport_number.max' => 'Passport Number may not be longer than 255 characters.',

                'passport_held_by_company.required' => 'Passport Held by Company is required. Please select an option.',
                'passport_held_by_company.boolean' => 'Passport Held by Company must be Yes or No.',

                'visa_type.in' => 'Please select a valid Visa Type from the dropdown.',

                'visa_provided_by.string' => 'Visa Provided By must be a valid value.',
                'visa_provided_by.max' => 'Visa Provided By may not be longer than 255 characters.',

                'visa_expiry.date' => 'Visa Expiry must be a valid date.',
                'visa_expiry.after' => 'Visa expiry date must be in the future.',

                'emirates_id.unique' => 'Emirates ID has already been taken. Please enter a different Emirates ID.',
                'emirates_id.string' => 'Emirates ID must be a valid text value.',
                'emirates_id.max' => 'Emirates ID may not be longer than 255 characters.',

                'basic_salary.required' => 'Basic Salary is required. Please enter the basic salary.',
                'basic_salary.numeric' => 'Basic Salary must be a valid number.',
                'basic_salary.min' => 'Basic Salary cannot be less than 0.',
                'basic_salary.max' => 'Basic Salary cannot exceed 99,999,999.99.',

                'assigned_vehicle_id.integer' => 'Assigned Vehicle must be a valid vehicle.',
                'assigned_vehicle_id.exists' => 'The selected Assigned Vehicle does not exist.',

                'employment_status.required' => 'Employment Status is required. Please select an option.',
                'employment_status.in' => 'Please select a valid Employment Status.',

                'date_of_birth.before_or_equal' => 'Driver must be at least 18 years old.',
                'date_of_birth.date' => 'Date of Birth must be a valid date.',
                'joining_date.date' => 'Joining Date must be a valid date.',

                'status.string' => 'Status must be a valid value.',
                'remarks.string' => 'Remarks must be valid text.',
                'address.string' => 'Address must be valid text.',
                'notes.string' => 'Notes must be valid text.',

                'phone.unique' => 'This mobile number has already been taken. Please enter a different mobile number.',
            ]
        );

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