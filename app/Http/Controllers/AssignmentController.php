<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $clientId = $request->client;
        $vehicleType = $request->vehicle_type;
        $rateBasis = $request->rate_type;

        $baseQuery = Assignment::with(['client', 'vehicle', 'driver']);

        if ($search) {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('assignment_no', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('client_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function ($q) use ($search) {
                        $q->where(function ($vehicleQuery) use ($search) {
                            $vehicleQuery->where('plate_number', 'like', "%{$search}%")
                                ->orWhere('vehicle_number', 'like', "%{$search}%");
                        });
                    })
                    ->orWhereHas('driver', function ($q) use ($search) {
                        $q->where('driver_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($clientId) {
            $baseQuery->where('client_id', $clientId);
        }

        if ($vehicleType) {
            $baseQuery->whereHas('vehicle', function ($query) use ($vehicleType) {
                $query->where('vehicle_type', $vehicleType);
            });
        }

        if ($rateBasis) {
            $baseQuery->where('rate_basis', $rateBasis);
        }

        $assignments = (clone $baseQuery)->latest()->paginate(10)->withQueryString();
        $plannedAssignments = (clone $baseQuery)->where('status', 'Planned')->latest()->get();
        $activeAssignments = (clone $baseQuery)->where('status', 'Active')->latest()->get();
        $suspendedAssignments = (clone $baseQuery)->where('status', 'Suspended')->latest()->get();
        $completedAssignments = (clone $baseQuery)->where('status', 'Completed')->latest()->get();
        $cancelledAssignments = (clone $baseQuery)->where('status', 'Cancelled')->latest()->get();

        $clients = Client::where('status', 'Active')->orderBy('client_name')->get();

        $vehicleTypes = Vehicle::query()
            ->whereNotNull('vehicle_type')
            ->where('vehicle_type', '!=', '')
            ->select('vehicle_type')
            ->distinct()
            ->orderBy('vehicle_type')
            ->pluck('vehicle_type');

        $rateBases = Assignment::query()
            ->whereNotNull('rate_basis')
            ->where('rate_basis', '!=', '')
            ->select('rate_basis')
            ->distinct()
            ->orderBy('rate_basis')
            ->pluck('rate_basis');

        return view('assignments.index', compact(
            'assignments',
            'search',
            'clientId',
            'vehicleType',
            'rateBasis',
            'clients',
            'vehicleTypes',
            'rateBases',
            'plannedAssignments',
            'activeAssignments',
            'suspendedAssignments',
            'completedAssignments',
            'cancelledAssignments'
        ));
    }

    public function create()
    {
        $clients = Client::where('status', 'Active')->orderBy('client_name')->get();

        $vehicles = Vehicle::whereNotIn('status', [
            'Maintenance', 'Inactive', 'Archived',
        ])->orderBy('plate_number')->get();

        $drivers = Driver::whereNotIn('employment_status', [
            'Inactive', 'Archived',
        ])->orderBy('driver_name')->get();

        return view('assignments.create', compact('clients', 'vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'driver_id' => ['required', 'exists:drivers,id'],

            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],

            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'rate_basis' => [
                'required',
                Rule::in(['Per Day', 'Per Month', 'Per Trip', 'Fixed', 'Custom']),
            ],

            'fuel_rule' => [
    'nullable',
    Rule::in([
        'Included',
        'AL SHAQRA Cost',
        'Client Reimbursable',
        'Client Direct',
    ]),
],
            'status' => [
                'nullable',
                Rule::in(['Planned', 'Active', 'Suspended', 'Completed', 'Cancelled']),
            ],

            'remarks' => ['nullable', 'string'],
            'pickup_location' => ['nullable', 'string'],
            'drop_location' => ['nullable', 'string'],

            'loading_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date', 'after_or_equal:loading_date'],

            'freight_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'notes' => ['nullable', 'string'],
        ], [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'The selected client is invalid or no longer exists.',
            'vehicle_id.required' => 'Please select a vehicle.',
            'vehicle_id.exists' => 'The selected vehicle is invalid or no longer exists.',
            'driver_id.required' => 'Please select a driver.',
            'driver_id.exists' => 'The selected driver is invalid or no longer exists.',

            'start_date.required' => 'Please select the assignment start date.',
            'start_date.date' => 'Please enter a valid start date.',
            'end_date.required' => 'Please select the assignment end date.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after' => 'The assignment end date must be after the start date.',

            'rate.required' => 'Please enter the assignment rate.',
            'rate.numeric' => 'The rate must be a valid number.',
            'rate.min' => 'The rate cannot be negative.',
            'rate.max' => 'The rate cannot exceed 99,999,999.99.',
            'rate.regex' => 'The rate can have up to 8 digits before the decimal and 2 decimal places.',

            'rate_basis.required' => 'Please select a rate basis.',
            'rate_basis.in' => 'Please select a valid rate basis.',
            'fuel_rule.in' => 'Please select a valid fuel rule.',
            'status.in' => 'Please select a valid assignment status.',

            'loading_date.date' => 'Please enter a valid loading date.',
            'delivery_date.date' => 'Please enter a valid delivery date.',
            'delivery_date.after_or_equal' => 'The delivery date cannot be before the loading date.',

            'freight_amount.numeric' => 'Freight amount must be a valid number.',
            'freight_amount.min' => 'Freight amount cannot be negative.',
            'freight_amount.max' => 'Freight amount cannot exceed 99,999,999.99.',
            'freight_amount.regex' => 'Freight amount can have up to 8 digits before the decimal and 2 decimal places.',
        ]);

        $validated['assignment_no'] = $this->generateAssignmentNumber();
        $validated['status'] = $validated['status'] ?? 'Planned';

        $assignment = Assignment::create($validated);

        $this->activityLogService->created(
            module: 'Assignments',
            subject: $assignment,
            description: "Created assignment {$assignment->assignment_no}."
        );

        return redirect('/assignments')->with('success', 'Assignment Added Successfully!');
    }

    private function generateAssignmentNumber()
    {
        $year = now()->format('Y');
        $prefix = "AST-ASG-{$year}-";

        $lastAssignment = Assignment::where('assignment_no', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastAssignment
            ? (int) substr($lastAssignment->assignment_no, -5) + 1
            : 1;

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function show(string $id)
    {
        $assignment = Assignment::with(['client', 'vehicle', 'driver'])->findOrFail($id);

        return view('assignments.show', compact('assignment'));
    }

    // PART 2 CONTINUES BELOW
    public function edit(string $id)
    {
        $assignment = Assignment::findOrFail($id);

        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

        $vehicles = Vehicle::whereNotIn('status', [
            'Maintenance', 'Inactive', 'Archived',
        ])->orderBy('plate_number')->get();

        $drivers = Driver::whereNotIn('employment_status', [
            'Inactive', 'Archived',
        ])->orderBy('driver_name')->get();

        return view('assignments.edit', compact(
            'assignment',
            'clients',
            'vehicles',
            'drivers'
        ));
    }

    public function update(Request $request, string $id)
    {
        $assignment = Assignment::findOrFail($id);

        if (
            $assignment->status === 'Completed' &&
            !$request->boolean('reopen')
        ) {
            return back()->with(
                'error',
                'Completed assignments cannot be edited without reopen permission.'
            );
        }

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'driver_id' => ['required', 'exists:drivers,id'],

            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],

            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'rate_basis' => [
                'required',
                Rule::in(['Per Day', 'Per Month', 'Per Trip', 'Fixed', 'Custom']),
            ],

            'fuel_rule' => [
    'nullable',
    Rule::in([
        'Included',
        'AL SHAQRA Cost',
        'Client Reimbursable',
        'Client Direct',
    ]),
],

            'status' => [
                'required',
                Rule::in(['Planned', 'Active', 'Suspended', 'Completed', 'Cancelled']),
            ],

            'remarks' => ['nullable', 'string'],
            'pickup_location' => ['nullable', 'string'],
            'drop_location' => ['nullable', 'string'],

            'loading_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date', 'after_or_equal:loading_date'],

            'freight_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'notes' => ['nullable', 'string'],
        ], [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'The selected client is invalid or no longer exists.',
            'vehicle_id.required' => 'Please select a vehicle.',
            'vehicle_id.exists' => 'The selected vehicle is invalid or no longer exists.',
            'driver_id.required' => 'Please select a driver.',
            'driver_id.exists' => 'The selected driver is invalid or no longer exists.',

            'start_date.required' => 'Please select the assignment start date.',
            'start_date.date' => 'Please enter a valid start date.',
            'end_date.required' => 'Please select the assignment end date.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after' => 'The assignment end date must be after the start date.',

            'rate.required' => 'Please enter the assignment rate.',
            'rate.numeric' => 'The rate must be a valid number.',
            'rate.min' => 'The rate cannot be negative.',
            'rate.max' => 'The rate cannot exceed 99,999,999.99.',
            'rate.regex' => 'The rate can have up to 8 digits before the decimal and 2 decimal places.',

            'rate_basis.required' => 'Please select a rate basis.',
            'rate_basis.in' => 'Please select a valid rate basis.',
            'fuel_rule.in' => 'Please select a valid fuel rule.',
            'status.required' => 'Please select an assignment status.',
            'status.in' => 'Please select a valid assignment status.',

            'loading_date.date' => 'Please enter a valid loading date.',
            'delivery_date.date' => 'Please enter a valid delivery date.',
            'delivery_date.after_or_equal' => 'The delivery date cannot be before the loading date.',

            'freight_amount.numeric' => 'Freight amount must be a valid number.',
            'freight_amount.min' => 'Freight amount cannot be negative.',
            'freight_amount.max' => 'Freight amount cannot exceed 99,999,999.99.',
            'freight_amount.regex' => 'Freight amount can have up to 8 digits before the decimal and 2 decimal places.',
        ]);

        $oldValues = $assignment->getAttributes();

        $assignment->update($validated);

        $this->activityLogService->updated(
            module: 'Assignments',
            subject: $assignment,
            oldValues: $oldValues,
            newValues: $assignment->getAttributes(),
            description: "Updated assignment {$assignment->assignment_no}."
        );

        return redirect('/assignments')->with(
            'success',
            'Assignment Updated Successfully!'
        );
    }

    public function destroy(string $id)
    {
        $assignment = Assignment::findOrFail($id);

        $oldValues = $assignment->getAttributes();
        $assignmentNo = $assignment->assignment_no;

        $this->activityLogService->deleted(
            module: 'Assignments',
            subject: $assignment,
            oldValues: $oldValues,
            description: "Deleted assignment {$assignmentNo}."
        );

        $assignment->delete();

        return redirect('/assignments')->with(
            'success',
            'Assignment Deleted Successfully!'
        );
    }
}