<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Driver;
use App\Models\DriverAdvance;
use App\Models\TrafficFine;
use App\Models\PayrollDeductionAllocation;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayrollController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;
        $paymentStatus = $request->payment_status;

        $payrolls = Payroll::with('driver')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('salary_month', 'like', "%{$search}%")
                        ->orWhereHas('driver', function ($driverQuery) use ($search) {
                            $driverQuery->where(
                                'driver_name',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('payrolls.index', compact(
            'payrolls',
            'search',
            'paymentStatus'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        $advances = DriverAdvance::where('remaining_amount', '>', 0)
            ->whereIn('status', ['Approved', 'Partially Deducted'])
            ->orderBy('advance_date')
            ->get();

        $fines = TrafficFine::where('payment_status', 'Paid')
            ->where('deduct_from_driver', true)
            ->whereNotNull('driver_id')
            ->orderBy('fine_date')
            ->get();

        return view('payrolls.create', compact(
            'drivers',
            'advances',
            'fines'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $this->validatePayroll($request);

        $this->validateDuplicatePayroll(
            $validated['driver_id'],
            $validated['salary_month']
        );

        $this->validateOtherDeduction($validated);
        $this->validatePaidDetails($validated);

        $advanceAllocations = $validated['advance_allocations'] ?? [];
        $fineAllocations = $validated['fine_allocations'] ?? [];

        $this->validateAllocationTotals(
            $validated,
            $advanceAllocations,
            $fineAllocations
        );

        $totalDeductions =
            (float) ($validated['visa_deduction'] ?? 0)
            + (float) ($validated['fine_deduction'] ?? 0)
            + (float) ($validated['advance_deduction'] ?? 0)
            + (float) ($validated['other_deduction'] ?? 0);

        $netSalary =
            (float) $validated['basic_salary']
            + (float) ($validated['allowance'] ?? 0)
            + (float) ($validated['overtime'] ?? 0)
            - $totalDeductions;

        $this->validateNetSalary($netSalary);

        $validated['total_deductions'] = $totalDeductions;
        $validated['net_salary'] = $netSalary;
        $validated['deduction'] = $totalDeductions;
        $validated['status'] = $validated['payment_status'];
        $validated['payment_date'] = $validated['paid_date'] ?? null;

        unset(
            $validated['advance_allocations'],
            $validated['fine_allocations']
        );

        $payroll = DB::transaction(function () use (
            $validated,
            $advanceAllocations,
            $fineAllocations
        ) {
            $payroll = Payroll::create($validated);

            if ($payroll->payment_status === 'Paid') {
                $this->applyAllocations(
                    $payroll,
                    $advanceAllocations,
                    $fineAllocations
                );
            }

            return $payroll;
        });

        $this->activityLogService->created(
            module: 'Payroll',
            subject: $payroll,
            description: "Created payroll for {$payroll->salary_month}.",
            newValues: $payroll->toArray()
        );

        return redirect('/payrolls')
            ->with('success', 'Payroll Added Successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $payroll = Payroll::with([
            'driver',
            'deductionAllocations',
        ])->findOrFail($id);

        return view('payrolls.show', compact('payroll'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $payroll = Payroll::with([
            'deductionAllocations',
        ])->findOrFail($id);

        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        /*
         * Include currently allocated advances even if their
         * remaining balance is zero.
         */
        $allocatedAdvanceIds = $payroll->deductionAllocations
            ->where('deduction_type', 'advance')
            ->pluck('deductible_record_id')
            ->all();

      $advances = DriverAdvance::where(function ($query) use ($allocatedAdvanceIds) {

    $query->where('remaining_amount', '>', 0);

    if (!empty($allocatedAdvanceIds)) {
        $query->orWhereIn('id', $allocatedAdvanceIds);
    }

})
->whereNotIn('status', ['Rejected'])
->orderBy('advance_date')
->get();

        /*
         * Include currently allocated fines even if their
         * payment_status has changed to Deducted.
         */
        $allocatedFineIds = $payroll->deductionAllocations
            ->where('deduction_type', 'fine')
            ->pluck('deductible_record_id')
            ->all();

        $fines = TrafficFine::where('driver_id', $payroll->driver_id)
            ->where('deduct_from_driver', true)
            ->where(function ($query) use ($allocatedFineIds) {
                $query->where('payment_status', 'Paid');

                if (!empty($allocatedFineIds)) {
                    $query->orWhereIn('id', $allocatedFineIds);
                }
            })
            ->orderBy('fine_date')
            ->get();

        return view('payrolls.edit', compact(
            'payroll',
            'drivers',
            'advances',
            'fines'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, string $id)
    {
        $payroll = Payroll::findOrFail($id);

        $oldValues = $payroll->getAttributes();

        $validated = $this->validatePayroll($request);

        $this->validateDuplicatePayroll(
            $validated['driver_id'],
            $validated['salary_month'],
            $payroll->id
        );

        $this->validateOtherDeduction($validated);
        $this->validatePaidDetails($validated);

        $advanceAllocations = $validated['advance_allocations'] ?? [];
        $fineAllocations = $validated['fine_allocations'] ?? [];

        $this->validateAllocationTotals(
            $validated,
            $advanceAllocations,
            $fineAllocations
        );

        $totalDeductions =
            (float) ($validated['visa_deduction'] ?? 0)
            + (float) ($validated['fine_deduction'] ?? 0)
            + (float) ($validated['advance_deduction'] ?? 0)
            + (float) ($validated['other_deduction'] ?? 0);

        $netSalary =
            (float) $validated['basic_salary']
            + (float) ($validated['allowance'] ?? 0)
            + (float) ($validated['overtime'] ?? 0)
            - $totalDeductions;

        $this->validateNetSalary($netSalary);

        $validated['total_deductions'] = $totalDeductions;
        $validated['net_salary'] = $netSalary;
        $validated['deduction'] = $totalDeductions;
        $validated['status'] = $validated['payment_status'];
        $validated['payment_date'] = $validated['paid_date'] ?? null;

        unset(
            $validated['advance_allocations'],
            $validated['fine_allocations']
        );

        DB::transaction(function () use (
            $payroll,
            $validated,
            $advanceAllocations,
            $fineAllocations
        ) {
            /*
             * Restore previous balances before applying
             * the updated payroll allocations.
             */
            $this->reverseAllocations($payroll);

            $payroll->update($validated);

            if ($payroll->payment_status === 'Paid') {
                $this->applyAllocations(
                    $payroll,
                    $advanceAllocations,
                    $fineAllocations
                );
            }
        });

        $this->activityLogService->updated(
            module: 'Payroll',
            subject: $payroll,
            oldValues: $oldValues,
            newValues: $payroll->fresh()->getAttributes(),
            description: "Updated payroll for {$payroll->salary_month}."
        );

        return redirect('/payrolls')
            ->with('success', 'Payroll Updated Successfully!');
    }
/*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->payment_status === 'Paid') {
            return redirect('/payrolls')
                ->withErrors([
                    'delete' =>
                        'Paid payroll cannot be deleted because it is part of payroll history.',
                ]);
        }

        $oldValues = $payroll->getAttributes();
        $salaryMonth = $payroll->salary_month;

        DB::transaction(function () use ($payroll) {
            $this->reverseAllocations($payroll);
            $payroll->delete();
        });

        $this->activityLogService->deleted(
            module: 'Payroll',
            subject: $payroll,
            oldValues: $oldValues,
            description: "Deleted payroll for {$salaryMonth}."
        );

        return redirect('/payrolls')
            ->with('success', 'Payroll Deleted Successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | COMMON PAYROLL VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validatePayroll(Request $request): array
    {
        return $request->validate([
            'driver_id' => [
                'required',
                'exists:drivers,id',
                Rule::exists('drivers', 'id')
                    ->where(fn ($query) => $query->where('status', 'Active')),
            ],

            'salary_month' => [
                'required',
                'date_format:Y-m',
            ],

            'basic_salary' => [
                'required',
                'numeric',
                'gt:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'allowance' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'overtime' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'visa_deduction' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'fine_deduction' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'advance_deduction' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'other_deduction' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'other_deduction_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'payment_status' => [
                'required',
                Rule::in(['Pending', 'Paid', 'Cancelled']),
            ],

            'paid_date' => [
                'nullable',
                'date',
            ],

            'paid_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'advance_allocations' => [
                'nullable',
                'array',
            ],

            'advance_allocations.*.id' => [
                'required',
                'integer',
                'distinct',
                'exists:driver_advances,id',
            ],

            'advance_allocations.*.amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],

            'fine_allocations' => [
                'nullable',
                'array',
            ],

            'fine_allocations.*.id' => [
                'required',
                'integer',
                'distinct',
                'exists:traffic_fines,id',
            ],

            'fine_allocations.*.amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DUPLICATE PAYROLL VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateDuplicatePayroll(
        int|string $driverId,
        string $salaryMonth,
        int|string|null $exceptId = null
    ): void {
        $query = Payroll::where('driver_id', $driverId)
            ->where('salary_month', $salaryMonth);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'salary_month' =>
                    'Payroll for this driver and month already exists.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | OTHER DEDUCTION VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateOtherDeduction(array $validated): void
    {
        $amount = (float) ($validated['other_deduction'] ?? 0);

        if (
            $amount > 0 &&
            empty(trim($validated['other_deduction_reason'] ?? ''))
        ) {
            throw ValidationException::withMessages([
                'other_deduction_reason' =>
                    'Reason is required when Other Deduction is greater than 0.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAID DETAILS VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validatePaidDetails(array $validated): void
    {
        if (($validated['payment_status'] ?? null) !== 'Paid') {
            return;
        }

        if (empty($validated['paid_date'] ?? null)) {
            throw ValidationException::withMessages([
                'paid_date' =>
                    'Paid Date is required when Payment Status is Paid.',
            ]);
        }

        if (empty(trim($validated['paid_reference'] ?? ''))) {
            throw ValidationException::withMessages([
                'paid_reference' =>
                    'Paid Reference is required when Payment Status is Paid.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | NET SALARY VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateNetSalary(float $netSalary): void
    {
        if ($netSalary < 0) {
            throw ValidationException::withMessages([
                'basic_salary' => 'Net salary cannot be negative.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ALLOCATION TOTAL VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateAllocationTotals(
        array $validated,
        array $advanceAllocations,
        array $fineAllocations
    ): void {
        if (($validated['payment_status'] ?? null) !== 'Paid') {
            return;
        }

        $advanceTotal = collect($advanceAllocations)
            ->sum(fn ($item) => (float) ($item['amount'] ?? 0));

        $fineTotal = collect($fineAllocations)
            ->sum(fn ($item) => (float) ($item['amount'] ?? 0));

        $advanceDeduction = (float) ($validated['advance_deduction'] ?? 0);
        $fineDeduction = (float) ($validated['fine_deduction'] ?? 0);

        if (round($advanceTotal, 2) !== round($advanceDeduction, 2)) {
            throw ValidationException::withMessages([
                'advance_deduction' =>
                    'Advance Deduction must equal the total of selected advance allocations.',
            ]);
        }

        if (round($fineTotal, 2) !== round($fineDeduction, 2)) {
            throw ValidationException::withMessages([
                'fine_deduction' =>
                    'Fine Deduction must equal the total of selected fine allocations.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | APPLY ADVANCE AND FINE ALLOCATIONS
    |--------------------------------------------------------------------------
    */

    private function applyAllocations(
        Payroll $payroll,
        array $advanceAllocations,
        array $fineAllocations
    ): void {
        /*
         * ADVANCE ALLOCATIONS
         */

        foreach ($advanceAllocations as $allocation) {
            $advance = DriverAdvance::whereKey($allocation['id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $advance->driver_id !== (int) $payroll->driver_id) {
                throw ValidationException::withMessages([
                    'advance_allocations' =>
                        'Selected advance does not belong to this payroll driver.',
                ]);
            }

            if (
                in_array(
                    $advance->status,
                    ['Rejected', 'Fully Deducted'],
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    'advance_allocations' =>
                        "Advance {$advance->advance_no} is not available for deduction.",
                ]);
            }

            $amount = round((float) $allocation['amount'], 2);
            $remaining = round((float) $advance->remaining_amount, 2);

            if ($amount > $remaining) {
                throw ValidationException::withMessages([
                    'advance_allocations' =>
                        "Deduction for advance {$advance->advance_no} exceeds its remaining balance.",
                ]);
            }

            PayrollDeductionAllocation::create([
                'payroll_id' => $payroll->id,
                'driver_id' => $payroll->driver_id,
                'deduction_type' => 'advance',
                'deductible_record_id' => $advance->id,
                'amount' => $amount,
            ]);

            $newDeducted = round(
                (float) $advance->deducted_amount + $amount,
                2
            );

            $newRemaining = max(
                0,
                round((float) $advance->amount - $newDeducted, 2)
            );

            $advance->update([
                'deducted_amount' => $newDeducted,
                'remaining_amount' => $newRemaining,
                'status' => $newRemaining <= 0
                    ? 'Fully Deducted'
                    : 'Partially Deducted',
            ]);
        }

        /*
         * FINE ALLOCATIONS
         */

        foreach ($fineAllocations as $allocation) {
            $fine = TrafficFine::whereKey($allocation['id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (
                (int) $fine->driver_id !== (int) $payroll->driver_id ||
                !in_array($fine->payment_status, ['Paid'], true) ||
                !$fine->deduct_from_driver
            ) {
                throw ValidationException::withMessages([
                    'fine_allocations' =>
                        "Fine {$fine->fine_number} is not available for salary deduction.",
                ]);
            }

            $alreadyAllocated = (float) PayrollDeductionAllocation::where(
                    'deduction_type',
                    'fine'
                )
                ->where('deductible_record_id', $fine->id)
                ->sum('amount');

            $remaining = max(
                0,
                round((float) $fine->amount - $alreadyAllocated, 2)
            );

            $amount = round((float) $allocation['amount'], 2);

            if ($amount > $remaining) {
                throw ValidationException::withMessages([
                    'fine_allocations' =>
                        "Deduction for fine {$fine->fine_number} exceeds its remaining balance.",
                ]);
            }

            PayrollDeductionAllocation::create([
                'payroll_id' => $payroll->id,
                'driver_id' => $payroll->driver_id,
                'deduction_type' => 'fine',
                'deductible_record_id' => $fine->id,
                'amount' => $amount,
            ]);

            $newAllocated = round($alreadyAllocated + $amount, 2);

            if ($newAllocated >= (float) $fine->amount) {
                $fine->update([
                    'payment_status' => 'Deducted',
                    'deduct_from_driver' => true,
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REVERSE PREVIOUS PAYROLL ALLOCATIONS
    |--------------------------------------------------------------------------
    */

    private function reverseAllocations(Payroll $payroll): void
    {
        $allocations = PayrollDeductionAllocation::where(
            'payroll_id',
            $payroll->id
        )->lockForUpdate()->get();

        $affectedFineIds = [];

        foreach ($allocations as $allocation) {

            /*
             * RESTORE ADVANCE BALANCE
             */

            if ($allocation->deduction_type === 'advance') {
                $advance = DriverAdvance::whereKey(
                    $allocation->deductible_record_id
                )->lockForUpdate()->first();

                if ($advance) {
                    $newDeducted = max(
                        0,
                        round(
                            (float) $advance->deducted_amount
                            - (float) $allocation->amount,
                            2
                        )
                    );

                    $newRemaining = max(
                        0,
                        round(
                            (float) $advance->amount - $newDeducted,
                            2
                        )
                    );

                    $advance->update([
                        'deducted_amount' => $newDeducted,
                        'remaining_amount' => $newRemaining,
                        'status' => $newRemaining <= 0
                            ? 'Fully Deducted'
                            : ($newDeducted > 0
                                ? 'Partially Deducted'
                                : 'Approved'),
                    ]);
                }
            }

            /*
             * REMEMBER AFFECTED FINES
             */

            if ($allocation->deduction_type === 'fine') {
                $affectedFineIds[] = $allocation->deductible_record_id;
            }
        }

        /*
         * Delete old allocation rows before recalculating
         * the remaining balance of each affected fine.
         */

        PayrollDeductionAllocation::where(
            'payroll_id',
            $payroll->id
        )->delete();

        /*
         * RESTORE FINE STATUS ACCORDING TO REMAINING ALLOCATIONS
         */

        foreach (array_unique($affectedFineIds) as $fineId) {
            $fine = TrafficFine::whereKey($fineId)
                ->lockForUpdate()
                ->first();

            if (!$fine) {
                continue;
            }

            $allocatedAmount = (float) PayrollDeductionAllocation::where(
                    'deduction_type',
                    'fine'
                )
                ->where('deductible_record_id', $fine->id)
                ->sum('amount');

            $fineIsFullyDeducted =
                $allocatedAmount >= (float) $fine->amount;

            $fine->update([
                'payment_status' => $fineIsFullyDeducted
                    ? 'Deducted'
                    : 'Paid',
                'deduct_from_driver' => true,
            ]);
        }
    }
}