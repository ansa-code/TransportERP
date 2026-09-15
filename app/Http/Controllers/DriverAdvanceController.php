<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverAdvance;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class DriverAdvanceController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $advances = DriverAdvance::with(['driver', 'approvedBy'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('advance_no', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%")
                        ->orWhereHas('driver', function ($driverQuery) use ($search) {
                            $driverQuery->where('driver_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('advance_date')
            ->paginate(10)
            ->withQueryString();

        return view('driver_advances.index', compact(
            'advances',
            'search',
            'status'
        ));
    }

    public function create()
    {
        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        $users = User::orderBy('name')->get();

        return view('driver_advances.create', compact(
            'drivers',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => [
                'required',
                'exists:drivers,id',
            ],

            'advance_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'reason' => [
                'nullable',
                'string',
            ],

            'approved_by' => [
                'nullable',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'in:Pending,Approved,Partially Deducted,Fully Deducted,Rejected',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Approval Rules
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'Approved' && empty($validated['approved_by'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'approved_by' => 'Approved By is required when the advance is Approved.',
                ]);
        }

        if ($validated['status'] === 'Rejected') {
            $validated['approved_by'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | New Advance Starts With Zero Deduction
        |--------------------------------------------------------------------------
        */

        $validated['deducted_amount'] = 0;

        $validated['remaining_amount'] = $validated['amount'];

        /*
        |--------------------------------------------------------------------------
        | Generate Advance Number
        |--------------------------------------------------------------------------
        */

        $validated['advance_no'] = $this->generateAdvanceNumber();

        $advance = DriverAdvance::create($validated);

        $this->activityLogService->created(
            module: 'Driver Advances',
            subject: $advance,
            description: "Created driver advance {$advance->advance_no}."
        );

        return redirect()
            ->route('driver-advances.index')
            ->with('success', 'Driver Advance Added Successfully!');
    }

    public function show($id)
    {
        $advance = DriverAdvance::with([
            'driver',
            'approvedBy',
        ])->findOrFail($id);

        return view('driver_advances.show', compact('advance'));
    }

    public function edit($id)
    {
        $advance = DriverAdvance::findOrFail($id);

        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        $users = User::orderBy('name')->get();

        return view('driver_advances.edit', compact(
            'advance',
            'drivers',
            'users'
        ));
    }

    public function update(Request $request, $id)
    {
        $advance = DriverAdvance::findOrFail($id);

        $validated = $request->validate([
            'driver_id' => [
                'required',
                'exists:drivers,id',
            ],

            'advance_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'reason' => [
                'nullable',
                'string',
            ],

            'approved_by' => [
                'nullable',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'in:Pending,Approved,Partially Deducted,Fully Deducted,Rejected',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Approval Rules
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'Approved' && empty($validated['approved_by'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'approved_by' => 'Approved By is required when the advance is Approved.',
                ]);
        }

        if ($validated['status'] === 'Rejected') {
            $validated['approved_by'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Protect Existing Payroll Deduction
        |--------------------------------------------------------------------------
        */

        $existingDeducted = (float) $advance->deducted_amount;

        $newAmount = (float) $validated['amount'];

        if ($existingDeducted > $newAmount) {
            return back()
                ->withInput()
                ->withErrors([
                    'amount' => 'Advance amount cannot be less than the amount already deducted.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Recalculate Remaining Amount
        |--------------------------------------------------------------------------
        */

        $remainingAmount = $newAmount - $existingDeducted;

        $validated['deducted_amount'] = $existingDeducted;

        $validated['remaining_amount'] = $remainingAmount;

        /*
        |--------------------------------------------------------------------------
        | Automatic Deduction Status
        |--------------------------------------------------------------------------
        */

        if ($remainingAmount <= 0) {
            $validated['remaining_amount'] = 0;
            $validated['status'] = 'Fully Deducted';
        } elseif ($existingDeducted > 0) {
            $validated['status'] = 'Partially Deducted';
        }

        /*
        |--------------------------------------------------------------------------
        | Rejected Advance Cannot Have Deduction
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'Rejected' && $existingDeducted > 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'status' => 'An advance with payroll deductions cannot be rejected.',
                ]);
        }

        $oldValues = $advance->getAttributes();

        $advance->update($validated);

        $this->activityLogService->updated(
            module: 'Driver Advances',
            subject: $advance,
            oldValues: $oldValues,
            newValues: $advance->getAttributes(),
            description: "Updated driver advance {$advance->advance_no}."
        );

        return redirect()
            ->route('driver-advances.index')
            ->with('success', 'Driver Advance Updated Successfully!');
    }

    public function destroy($id)
    {
        $advance = DriverAdvance::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Protect Payroll History
        |--------------------------------------------------------------------------
        */

        if ((float) $advance->deducted_amount > 0) {
            return redirect()
                ->route('driver-advances.index')
                ->withErrors([
                    'delete' => 'A driver advance with payroll deductions cannot be deleted because it is part of payroll history.',
                ]);
        }

        $oldValues = $advance->getAttributes();

        $advanceNo = $advance->advance_no;

        $this->activityLogService->deleted(
            module: 'Driver Advances',
            subject: $advance,
            oldValues: $oldValues,
            description: "Deleted driver advance {$advanceNo}."
        );

        $advance->delete();

        return redirect()
            ->route('driver-advances.index')
            ->with('success', 'Driver Advance Deleted Successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Advance Number
    |--------------------------------------------------------------------------
    */

    private function generateAdvanceNumber()
    {
        $year = date('Y');

        $lastAdvance = DriverAdvance::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastAdvance
            ? ((int) substr($lastAdvance->advance_no, -5)) + 1
            : 1;

        return 'AST-ADV-' . $year . '-' .
            str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}