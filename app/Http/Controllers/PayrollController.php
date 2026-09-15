<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Driver;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

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

    public function create()
    {
        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        return view('payrolls.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
                'min:0',
            ],

            'allowance' => [
                'required',
                'numeric',
                'min:0',
            ],

            'overtime' => [
                'required',
                'numeric',
                'min:0',
            ],

            /*
             |--------------------------------------------------------------------------
             | Deductions are OPTIONAL
             |--------------------------------------------------------------------------
             */

            'visa_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fine_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'advance_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deduction_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'payment_status' => [
                'required',
                Rule::in([
                    'Pending',
                    'Paid',
                    'Cancelled',
                ]),
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
        ]);

        /*
         |--------------------------------------------------------------------------
         | Convert blank optional deductions to zero
         |--------------------------------------------------------------------------
         */

        $validated['visa_deduction'] =
            $validated['visa_deduction'] ?? 0;

        $validated['fine_deduction'] =
            $validated['fine_deduction'] ?? 0;

        $validated['advance_deduction'] =
            $validated['advance_deduction'] ?? 0;

        $validated['other_deduction'] =
            $validated['other_deduction'] ?? 0;

        /*
         |--------------------------------------------------------------------------
         | Prevent Duplicate Payroll
         |--------------------------------------------------------------------------
         */

        if (
            Payroll::where('driver_id', $validated['driver_id'])
                ->where('salary_month', $validated['salary_month'])
                ->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'salary_month' =>
                        'Payroll for this driver and month already exists.',
                ]);
        }

        /*
         |--------------------------------------------------------------------------
         | Other Deduction Reason
         |--------------------------------------------------------------------------
         */

        $otherDeduction = (float) $validated['other_deduction'];

        if (
            $otherDeduction > 0 &&
            empty(trim($validated['other_deduction_reason'] ?? ''))
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'other_deduction_reason' =>
                        'Reason is required when Other Deduction is greater than 0.',
                ]);
        }

        /*
         |--------------------------------------------------------------------------
         | Paid Status Validation
         |--------------------------------------------------------------------------
         */

        if ($validated['payment_status'] === 'Paid') {

            if (empty($validated['paid_date'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'paid_date' =>
                            'Paid Date is required when Payment Status is Paid.',
                    ]);
            }

            if (empty(trim($validated['paid_reference'] ?? ''))) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'paid_reference' =>
                            'Paid Reference is required when Payment Status is Paid.',
                    ]);
            }
        }

        /*
         |--------------------------------------------------------------------------
         | Calculate Total Deductions
         |--------------------------------------------------------------------------
         */

        $totalDeductions =
            (float) $validated['visa_deduction']
            + (float) $validated['fine_deduction']
            + (float) $validated['advance_deduction']
            + (float) $validated['other_deduction'];

        /*
         |--------------------------------------------------------------------------
         | Calculate Net Salary
         |--------------------------------------------------------------------------
         */

        $netSalary =
            (float) $validated['basic_salary']
            + (float) $validated['allowance']
            + (float) $validated['overtime']
            - $totalDeductions;

        $validated['total_deductions'] = $totalDeductions;
        $validated['net_salary'] = $netSalary;

        /*
         |--------------------------------------------------------------------------
         | Keep Existing Fields Synchronized
         |--------------------------------------------------------------------------
         */

        $validated['deduction'] = $totalDeductions;

        $validated['status'] =
            $validated['payment_status'];

        $validated['payment_date'] =
            $validated['paid_date'] ?? null;

        /*
         |--------------------------------------------------------------------------
         | Create Payroll
         |--------------------------------------------------------------------------
         */

        $payroll = Payroll::create($validated);

        /*
         |--------------------------------------------------------------------------
         | Audit Trail
         |--------------------------------------------------------------------------
         */

        $this->activityLogService->created(
            module: 'Payroll',
            subject: $payroll,
            description: "Created payroll for {$payroll->salary_month}.",
            newValues: $payroll->toArray()
        );

        return redirect('/payrolls')
            ->with(
                'success',
                'Payroll Added Successfully!'
            );
    }

    public function show(string $id)
    {
        $payroll = Payroll::with('driver')
            ->findOrFail($id);

        return view(
            'payrolls.show',
            compact('payroll')
        );
    }

    public function edit(string $id)
    {
        $payroll = Payroll::findOrFail($id);

        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        return view(
            'payrolls.edit',
            compact(
                'payroll',
                'drivers'
            )
        );
    }

    public function update(Request $request, string $id)
    {
        $payroll = Payroll::findOrFail($id);

        /*
         |--------------------------------------------------------------------------
         | Capture Old Values For Audit Trail
         |--------------------------------------------------------------------------
         */

        $oldValues = $payroll->getAttributes();

        $validated = $request->validate([
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
                'min:0',
            ],

            'allowance' => [
                'required',
                'numeric',
                'min:0',
            ],

            'overtime' => [
                'required',
                'numeric',
                'min:0',
            ],

            /*
             |--------------------------------------------------------------------------
             | Deductions are OPTIONAL
             |--------------------------------------------------------------------------
             */

            'visa_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fine_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'advance_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deduction' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deduction_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'payment_status' => [
                'required',
                Rule::in([
                    'Pending',
                    'Paid',
                    'Cancelled',
                ]),
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
        ]);

        /*
         |--------------------------------------------------------------------------
         | Convert blank optional deductions to zero
         |--------------------------------------------------------------------------
         */

        $validated['visa_deduction'] =
            $validated['visa_deduction'] ?? 0;

        $validated['fine_deduction'] =
            $validated['fine_deduction'] ?? 0;

        $validated['advance_deduction'] =
            $validated['advance_deduction'] ?? 0;

        $validated['other_deduction'] =
            $validated['other_deduction'] ?? 0;

        /*
         |--------------------------------------------------------------------------
         | Prevent Duplicate Payroll
         |--------------------------------------------------------------------------
         */

        $duplicatePayroll = Payroll::where(
                'driver_id',
                $validated['driver_id']
            )
            ->where(
                'salary_month',
                $validated['salary_month']
            )
            ->where(
                'id',
                '!=',
                $payroll->id
            )
            ->exists();

        if ($duplicatePayroll) {
            return back()
                ->withInput()
                ->withErrors([
                    'salary_month' =>
                        'Payroll for this driver and month already exists.',
                ]);
        }

        /*
         |--------------------------------------------------------------------------
         | Other Deduction Reason
         |--------------------------------------------------------------------------
         */

        $otherDeduction = (float) $validated['other_deduction'];

        if (
            $otherDeduction > 0 &&
            empty(trim($validated['other_deduction_reason'] ?? ''))
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'other_deduction_reason' =>
                        'Reason is required when Other Deduction is greater than 0.',
                ]);
        }

        /*
         |--------------------------------------------------------------------------
         | Paid Status Validation
         |--------------------------------------------------------------------------
         */

        if ($validated['payment_status'] === 'Paid') {

            if (empty($validated['paid_date'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'paid_date' =>
                            'Paid Date is required when Payment Status is Paid.',
                    ]);
            }

            if (empty(trim($validated['paid_reference'] ?? ''))) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'paid_reference' =>
                            'Paid Reference is required when Payment Status is Paid.',
                    ]);
            }
        }

        /*
         |--------------------------------------------------------------------------
         | Calculate Total Deductions
         |--------------------------------------------------------------------------
         */

        $totalDeductions =
            (float) $validated['visa_deduction']
            + (float) $validated['fine_deduction']
            + (float) $validated['advance_deduction']
            + (float) $validated['other_deduction'];

        /*
         |--------------------------------------------------------------------------
         | Calculate Net Salary
         |--------------------------------------------------------------------------
         */

        $netSalary =
            (float) $validated['basic_salary']
            + (float) $validated['allowance']
            + (float) $validated['overtime']
            - $totalDeductions;

        $validated['total_deductions'] = $totalDeductions;
        $validated['net_salary'] = $netSalary;

        /*
         |--------------------------------------------------------------------------
         | Keep Existing Fields Synchronized
         |--------------------------------------------------------------------------
         */

        $validated['deduction'] = $totalDeductions;

        $validated['status'] =
            $validated['payment_status'];

        $validated['payment_date'] =
            $validated['paid_date'] ?? null;

        /*
         |--------------------------------------------------------------------------
         | Update Payroll
         |--------------------------------------------------------------------------
         */

        $payroll->update($validated);

        /*
         |--------------------------------------------------------------------------
         | Audit Trail
         |--------------------------------------------------------------------------
         */

        $this->activityLogService->updated(
            module: 'Payroll',
            subject: $payroll,
            oldValues: $oldValues,
            newValues: $payroll->getAttributes(),
            description: "Updated payroll for {$payroll->salary_month}."
        );

        return redirect('/payrolls')
            ->with(
                'success',
                'Payroll Updated Successfully!'
            );
    }

    public function destroy(string $id)
    {
        $payroll = Payroll::findOrFail($id);

        /*
         |--------------------------------------------------------------------------
         | Paid Payroll Protection
         |--------------------------------------------------------------------------
         */

        if ($payroll->payment_status === 'Paid') {
            return redirect('/payrolls')
                ->withErrors([
                    'delete' =>
                        'Paid payroll cannot be deleted because it is part of payroll history.',
                ]);
        }

        /*
         |--------------------------------------------------------------------------
         | Capture Values Before Delete
         |--------------------------------------------------------------------------
         */

        $oldValues = $payroll->getAttributes();

        $salaryMonth = $payroll->salary_month;

        /*
         |--------------------------------------------------------------------------
         | Audit Trail
         |--------------------------------------------------------------------------
         */

        $this->activityLogService->deleted(
            module: 'Payroll',
            subject: $payroll,
            oldValues: $oldValues,
            description: "Deleted payroll for {$salaryMonth}."
        );

        /*
         |--------------------------------------------------------------------------
         | Delete Payroll
         |--------------------------------------------------------------------------
         */

        $payroll->delete();

        return redirect('/payrolls')
            ->with(
                'success',
                'Payroll Deleted Successfully!'
            );
    }
}