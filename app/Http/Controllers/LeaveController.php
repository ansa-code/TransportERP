<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Leave;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $approvalStatus = $request->approval_status;
        $leaveType = $request->leave_type;

        $leaves = Leave::with(['driver', 'replacementDriver'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('leave_type', 'like', "%{$search}%")
                        ->orWhere('approval_status', 'like', "%{$search}%")
                        ->orWhereHas('driver', function ($driverQuery) use ($search) {
                            $driverQuery->where('driver_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('replacementDriver', function ($replacementQuery) use ($search) {
                            $replacementQuery->where('driver_name', 'like', "%{$search}%");
                        });

                });
            })
            ->when($approvalStatus, function ($query) use ($approvalStatus) {
                $query->where('approval_status', $approvalStatus);
            })
            ->when($leaveType, function ($query) use ($leaveType) {
                $query->where('leave_type', $leaveType);
            })
            ->latest('start_date')
            ->paginate(10)
            ->withQueryString();

        return view('leaves.index', compact(
            'leaves',
            'search',
            'approvalStatus',
            'leaveType'
        ));
    }

    public function create()
    {
        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        return view('leaves.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => [
                'required',
                'exists:drivers,id',
                Rule::exists('drivers', 'id')->where(function ($query) {
                    $query->where('status', 'Active');
                }),
            ],

            'leave_type' => [
                'required',
                Rule::in([
                    'Annual',
                    'Sick',
                    'Emergency',
                    'Unpaid',
                    'Other',
                ]),
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'approval_status' => [
                'required',
                Rule::in([
                    'Pending',
                    'Approved',
                    'Rejected',
                    'Cancelled',
                ]),
            ],

            'replacement_driver_id' => [
                'nullable',
                'exists:drivers,id',
            ],

            'approval_notes' => [
                'nullable',
                'string',
            ],
        ]);

        if (
            !empty($validated['replacement_driver_id']) &&
            (int) $validated['replacement_driver_id'] === (int) $validated['driver_id']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'replacement_driver_id' =>
                        'Replacement Driver cannot be the same as the main driver.',
                ]);
        }

        if (
            $validated['approval_status'] === 'Rejected' &&
            empty(trim($validated['approval_notes'] ?? ''))
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'approval_notes' =>
                        'Approval Notes are required when the leave is rejected.',
                ]);
        }

        if (!empty($validated['replacement_driver_id'])) {

            $replacementDriver = Driver::find($validated['replacement_driver_id']);

            if (!$replacementDriver || $replacementDriver->status !== 'Active') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'replacement_driver_id' =>
                            'Replacement Driver must be an active driver.',
                    ]);
            }

            if (
                $validated['approval_status'] === 'Approved' &&
                $this->driverHasApprovedLeave(
                    $replacementDriver->id,
                    $validated['start_date'],
                    $validated['end_date']
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'replacement_driver_id' =>
                            'Replacement Driver is not available during the selected leave dates.',
                    ]);
            }
        }

        $leave = Leave::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->created(
            module: 'Leave',
            subject: $leave,
            description: "Created leave record for driver ID {$leave->driver_id}.",
            newValues: $leave->toArray()
        );

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave Added Successfully!');
    }

    public function show($id)
    {
        $leave = Leave::with([
            'driver',
            'replacementDriver',
        ])->findOrFail($id);

        return view('leaves.show', compact('leave'));
    }

    private function driverHasApprovedLeave(
        int $driverId,
        string $startDate,
        string $endDate,
        ?int $ignoreLeaveId = null
    ): bool {
        return Leave::where('driver_id', $driverId)
            ->where('approval_status', 'Approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query
                    ->whereDate('start_date', '<=', $endDate)
                    ->whereDate('end_date', '>=', $startDate);
            })
            ->when($ignoreLeaveId, function ($query) use ($ignoreLeaveId) {
                $query->where('id', '!=', $ignoreLeaveId);
            })
            ->exists();
    }

    public function edit($id)
    {
        $leave = Leave::findOrFail($id);

        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        return view('leaves.edit', compact(
            'leave',
            'drivers'
        ));
    }

    public function update(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Old Values For Audit Trail
        |--------------------------------------------------------------------------
        */

        $oldValues = $leave->getAttributes();

        $validated = $request->validate([
            'driver_id' => [
                'required',
                'exists:drivers,id',
                Rule::exists('drivers', 'id')->where(function ($query) {
                    $query->where('status', 'Active');
                }),
            ],

            'leave_type' => [
                'required',
                Rule::in([
                    'Annual',
                    'Sick',
                    'Emergency',
                    'Unpaid',
                    'Other',
                ]),
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'approval_status' => [
                'required',
                Rule::in([
                    'Pending',
                    'Approved',
                    'Rejected',
                    'Cancelled',
                ]),
            ],

            'replacement_driver_id' => [
                'nullable',
                'exists:drivers,id',
            ],

            'approval_notes' => [
                'nullable',
                'string',
            ],
        ]);

        if (
            !empty($validated['replacement_driver_id']) &&
            (int) $validated['replacement_driver_id'] === (int) $validated['driver_id']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'replacement_driver_id' =>
                        'Replacement Driver cannot be the same as the main driver.',
                ]);
        }

        if (
            $validated['approval_status'] === 'Rejected' &&
            empty(trim($validated['approval_notes'] ?? ''))
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'approval_notes' =>
                        'Approval Notes are required when the leave is rejected.',
                ]);
        }

        if (!empty($validated['replacement_driver_id'])) {

            $replacementDriver = Driver::find(
                $validated['replacement_driver_id']
            );

            if (
                !$replacementDriver ||
                $replacementDriver->status !== 'Active'
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'replacement_driver_id' =>
                            'Replacement Driver must be an active driver.',
                    ]);
            }

            if (
                $validated['approval_status'] === 'Approved' &&
                $this->driverHasApprovedLeave(
                    $replacementDriver->id,
                    $validated['start_date'],
                    $validated['end_date'],
                    $leave->id
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'replacement_driver_id' =>
                            'Replacement Driver is not available during the selected leave dates.',
                    ]);
            }
        }

        $leave->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->updated(
            module: 'Leave',
            subject: $leave,
            oldValues: $oldValues,
            newValues: $leave->getAttributes(),
            description: "Updated leave record for driver ID {$leave->driver_id}."
        );

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave Updated Successfully!');
    }

    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Values Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues = $leave->getAttributes();
        $driverId = $leave->driver_id;

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->deleted(
            module: 'Leave',
            subject: $leave,
            oldValues: $oldValues,
            description: "Deleted leave record for driver ID {$driverId}."
        );

        $leave->delete();

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave Deleted Successfully!');
    }
}