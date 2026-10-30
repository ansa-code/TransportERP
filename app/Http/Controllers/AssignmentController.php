<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Leave;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;

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

        // Load all matching records; no pagination.
        $assignments = (clone $baseQuery)->latest()->get();

        $plannedAssignments = (clone $baseQuery)
            ->where('status', 'Planned')->latest()->get();

        $assignedAssignments = (clone $baseQuery)
            ->where('status', 'Assigned')->latest()->get();

        $inTransitAssignments = (clone $baseQuery)
            ->where('status', 'In Transit')->latest()->get();

        $deliveredAssignments = (clone $baseQuery)
            ->where('status', 'Delivered')->latest()->get();

        $closedAssignments = (clone $baseQuery)
            ->where('status', 'Closed')->latest()->get();

        $cancelledAssignments = (clone $baseQuery)
            ->where('status', 'Cancelled')->latest()->get();

        $suspendedAssignments = (clone $baseQuery)
            ->where('status', 'Suspended')->latest()->get();

        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

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
            'assignedAssignments',
            'inTransitAssignments',
            'deliveredAssignments',
            'closedAssignments',
            'cancelledAssignments',
            'suspendedAssignments'
        ));
    }

    public function create()
    {
        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

        $vehicles = Vehicle::whereNotIn('status', [
            'Maintenance',
            'Inactive',
            'Archived',
        ])->orderBy('plate_number')->get();

        $drivers = Driver::whereNotIn('employment_status', [
            'Inactive',
            'Archived',
        ])->orderBy('driver_name')->get();

        return view('assignments.create', compact(
            'clients',
            'vehicles',
            'drivers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->assignmentValidationRules(false),
            $this->assignmentValidationMessages()
        );

        $validated['status'] = $validated['status'] ?? 'Planned';

        $pendingLeaves = $this->checkLeaveConflicts(
            $validated,
            $request,
            'create'
        );

        if ($pendingLeaves instanceof RedirectResponse) {
            return $pendingLeaves;
        }

        $this->validateAssignmentConflicts($validated);

        $validated['assignment_no'] = $this->generateAssignmentNumber();

        $assignment = Assignment::create($validated);

        session()->forget('pending_sick_leave_confirmation');

        $description = "Created assignment {$assignment->assignment_no}.";

        if ($pendingLeaves->isNotEmpty()) {
            $leaveTypes = $pendingLeaves->pluck('leave_type')
                ->unique()
                ->implode(', ');

            $description .= " Admin confirmed assignment despite pending leave conflict(s): {$leaveTypes}.";
        }

        $this->activityLogService->created(
            module: 'Assignments',
            subject: $assignment,
            description: $description
        );

        return redirect('/assignments')
            ->with('success', 'Assignment Added Successfully!');
    }

    private function assignmentValidationRules(bool $isUpdate): array
    {
        return [
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
                Rule::in([
                    'Per Day',
                    'Per Month',
                    'Per Trip',
                    'Fixed',
                    'Custom',
                ]),
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
                $isUpdate ? 'required' : 'nullable',
                Rule::in([
                    'Planned',
                    'Assigned',
                    'In Transit',
                    'Delivered',
                    'Closed',
                    'Cancelled',
                    'Suspended',
                ]),
            ],

            'remarks' => ['nullable', 'string'],
            'pickup_location' => ['nullable', 'string'],
            'drop_location' => ['nullable', 'string'],
            'loading_date' => ['nullable', 'date'],
            'delivery_date' => [
                'nullable',
                'date',
                'after_or_equal:loading_date',
            ],

            'freight_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'notes' => ['nullable', 'string'],
        ];
    }

    private function assignmentValidationMessages(): array
    {
        return [
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
        ];
    }
    /**
     * Validate Leave conflicts against assignment dates.
     */
    private function checkLeaveConflicts(
        array $data,
        Request $request,
        string $context
    ) {
        $status = $data['status'] ?? 'Planned';

        if (in_array($status, ['Closed', 'Cancelled'], true)) {
            session()->forget('pending_sick_leave_confirmation');
            return collect();
        }

        $overlappingLeaves = Leave::query()
            ->where('driver_id', $data['driver_id'])
            ->whereIn('leave_type', [
                'Annual',
                'Sick',
                'Emergency',
                'Unpaid',
                'Other',
            ])
            ->whereIn('approval_status', [
                'Pending',
                'Approved',
                'Rejected',
                'Cancelled',
            ])
            ->whereDate('start_date', '<=', $data['end_date'])
            ->whereDate('end_date', '>=', $data['start_date'])
            ->orderBy('start_date')
            ->get();

        $approvedLeaves = $overlappingLeaves
            ->where('approval_status', 'Approved');

        if ($approvedLeaves->isNotEmpty()) {
            session()->forget('pending_sick_leave_confirmation');

            $leaveDetails = $approvedLeaves->map(function ($leave) {
                return $leave->leave_type . ' Leave ('
                    . $leave->start_date->format('d M Y')
                    . ' to '
                    . $leave->end_date->format('d M Y')
                    . ')';
            })->implode('; ');

            throw ValidationException::withMessages([
                'driver_id' => 'This driver has Approved Leave covering the selected assignment dates: '
                    . $leaveDetails
                    . '. You cannot assign this driver during approved leave. Please choose another driver or change the assignment dates.',
            ]);
        }

        $pendingLeaves = $overlappingLeaves
            ->where('approval_status', 'Pending')
            ->values();

        if ($pendingLeaves->isEmpty()) {
            session()->forget('pending_sick_leave_confirmation');
            return collect();
        }

        $currentLeaveIds = $pendingLeaves->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->sort()
            ->values()
            ->all();

        $confirmation = session('pending_sick_leave_confirmation');

        $confirmationMatches =
            is_array($confirmation)
            && ($confirmation['context'] ?? null) === $context
            && (int) ($confirmation['driver_id'] ?? 0) === (int) $data['driver_id']
            && ($confirmation['start_date'] ?? null) === $data['start_date']
            && ($confirmation['end_date'] ?? null) === $data['end_date']
            && ($confirmation['leave_ids'] ?? []) === $currentLeaveIds;

        $adminConfirmed = $request->boolean('confirm_pending_sick_leave');

        if (!$adminConfirmed || !$confirmationMatches) {
            $warningDetails = $pendingLeaves->map(function ($leave) {
                return [
                    'leave_type' => $leave->leave_type,
                    'start_date' => $leave->start_date->format('d M Y'),
                    'end_date' => $leave->end_date->format('d M Y'),
                ];
            })->values()->all();

            session()->put('pending_sick_leave_confirmation', [
                'context' => $context,
                'driver_id' => (int) $data['driver_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'leave_ids' => $currentLeaveIds,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('pending_sick_leave_warning', $warningDetails);
        }

        return $pendingLeaves;
    }

    private function validateAssignmentConflicts(
        array $data,
        ?int $ignoreId = null
    ): void {
        // Exact duplicate check.
        $duplicateQuery = Assignment::query()
            ->where('client_id', $data['client_id'])
            ->where('vehicle_id', $data['vehicle_id'])
            ->where('driver_id', $data['driver_id'])
            ->whereDate('start_date', $data['start_date'])
            ->whereDate('end_date', $data['end_date'])
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'Cancelled');
            });

        if ($ignoreId !== null) {
            $duplicateQuery->where('id', '!=', $ignoreId);
        }

        if ($duplicateQuery->exists()) {
            throw ValidationException::withMessages([
                'client_id' => 'This trip assignment already exists for the selected driver, vehicle, and client.',
            ]);
        }

        // Active resource-reserving statuses.
        $overlapQuery = Assignment::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereIn('status', [
                        'Planned',
                        'Assigned',
                        'In Transit',
                        'Delivered',
                        'Suspended',
                    ]);
            })
            ->whereDate('start_date', '<=', $data['end_date'])
            ->whereDate('end_date', '>=', $data['start_date']);

        if ($ignoreId !== null) {
            $overlapQuery->where('id', '!=', $ignoreId);
        }

        $driverConflict = (clone $overlapQuery)
            ->where('driver_id', $data['driver_id'])
            ->exists();

        if ($driverConflict) {
            throw ValidationException::withMessages([
                'driver_id' => 'This driver already has an assignment that overlaps with the selected dates. Please choose another driver or change the dates.',
            ]);
        }

        $vehicleConflict = (clone $overlapQuery)
            ->where('vehicle_id', $data['vehicle_id'])
            ->exists();

        if ($vehicleConflict) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'This vehicle already has an assignment that overlaps with the selected dates. Please choose another vehicle or change the dates.',
            ]);
        }
    }

    private function generateAssignmentNumber()
    {
        $year = now()->format('Y');
        $prefix = "AST-ASG-{$year}-";

        $lastAssignment = Assignment::where(
            'assignment_no',
            'like',
            "{$prefix}%"
        )->orderByDesc('id')->first();

        $nextNumber = $lastAssignment
            ? (int) substr($lastAssignment->assignment_no, -5) + 1
            : 1;

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function show(string $id)
    {
        $assignment = Assignment::with(['client', 'vehicle', 'driver'])
            ->findOrFail($id);

        return view('assignments.show', compact('assignment'));
    }

    public function edit(string $id)
    {
        $assignment = Assignment::findOrFail($id);

        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

        $vehicles = Vehicle::whereNotIn('status', [
            'Maintenance',
            'Inactive',
            'Archived',
        ])->orderBy('plate_number')->get();

        $drivers = Driver::whereNotIn('employment_status', [
            'Inactive',
            'Archived',
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

        $validated = $request->validate(
            $this->assignmentValidationRules(true),
            $this->assignmentValidationMessages()
        );

        $pendingLeaves = $this->checkLeaveConflicts(
            $validated,
            $request,
            'update:' . $assignment->id
        );

        if ($pendingLeaves instanceof RedirectResponse) {
            return $pendingLeaves;
        }

        $this->validateAssignmentConflicts(
            $validated,
            (int) $assignment->id
        );

        $oldValues = $assignment->getAttributes();

        $assignment->update($validated);

        session()->forget('pending_sick_leave_confirmation');

        $description = "Updated assignment {$assignment->assignment_no}.";

        if ($pendingLeaves->isNotEmpty()) {
            $leaveTypes = $pendingLeaves->pluck('leave_type')
                ->unique()
                ->implode(', ');

            $description .= " Admin confirmed update despite pending leave conflict(s): {$leaveTypes}.";
        }

        $this->activityLogService->updated(
            module: 'Assignments',
            subject: $assignment,
            oldValues: $oldValues,
            newValues: $assignment->getAttributes(),
            description: $description
        );

        return redirect('/assignments')->with(
            'success',
            'Assignment Updated Successfully!'
        );
    }

    /**
     * Mark a Closed assignment as Done.
     */
    public function markDone(string $id)
    {
        $assignment = Assignment::findOrFail($id);

        if ($assignment->status !== 'Closed') {
            return redirect('/assignments')->with(
                'error',
                'Only Closed assignments can be marked Done.'
            );
        }

        if ($assignment->done_at !== null) {
            return redirect('/assignments')->with(
                'info',
                'This assignment is already marked Done.'
            );
        }

        $oldValues = $assignment->getAttributes();

        $assignment->done_at = now();
        $assignment->save();

        $this->activityLogService->updated(
            module: 'Assignments',
            subject: $assignment,
            oldValues: $oldValues,
            newValues: $assignment->getAttributes(),
            description: "Marked assignment {$assignment->assignment_no} as Done."
        );

        return redirect('/assignments')->with(
            'success',
            'Assignment marked Done successfully!'
        );
    }

    /**
     * Undo the Done mark on a Closed assignment.
     */
    public function undoDone(string $id)
    {
        $assignment = Assignment::findOrFail($id);

        if ($assignment->status !== 'Closed') {
            return redirect('/assignments')->with(
                'error',
                'Only Closed assignments can have their Done mark removed.'
            );
        }

        if ($assignment->done_at === null) {
            return redirect('/assignments')->with(
                'info',
                'This assignment is not marked Done.'
            );
        }

        $oldValues = $assignment->getAttributes();

        $assignment->done_at = null;
        $assignment->save();

        $this->activityLogService->updated(
            module: 'Assignments',
            subject: $assignment,
            oldValues: $oldValues,
            newValues: $assignment->getAttributes(),
            description: "Removed Done mark from assignment {$assignment->assignment_no}."
        );

        return redirect('/assignments')->with(
            'success',
            'Done mark removed successfully!'
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