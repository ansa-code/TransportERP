<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    private function expenseTypes(): array
    {
        return [
            'Fuel' => [
                'Fuel Purchase',
                'Diesel',
                'Petrol',
                'Other',
            ],

            'Maintenance' => [
                'Tyre',
                'Battery',
                'Washing',
                'Oil Change',
                'AC Repair',
                'Brake',
                'Electrical',
                'Spare Parts',
                'Other',
            ],

            'Visa' => [
                'Visa Fee',
                'Medical',
                'Emirates ID',
                'Other',
            ],

            'Fine' => [
                'Traffic Fine',
                'Other',
            ],

            'Registration' => [
                'Registration Fee',
                'Renewal',
                'Inspection',
                'Other',
            ],

            'Insurance' => [
                'Insurance Premium',
                'Renewal',
                'Other',
            ],

            'Admin' => [
                'Office',
                'Stationery',
                'Other',
            ],

            'Other' => [
                'Other',
            ],
        ];
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $category = $request->category;
        $expenseType = $request->expense_type;
        $paymentStatus = $request->payment_status;
        $reimbursable = $request->reimbursable;

        $expenses = Expense::with([
            'vehicle',
            'driver',
            'client',
            'assignment',
            'trip',
        ])
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('expense_no', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");

                    $q->orWhereJsonContains(
                        'expense_type',
                        $search
                    );

                    $q->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where(
                            'plate_number',
                            'like',
                            "%{$search}%"
                        );
                    });

                    $q->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery->where(
                            'driver_name',
                            'like',
                            "%{$search}%"
                        );
                    });

                    $q->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where(
                            'client_name',
                            'like',
                            "%{$search}%"
                        );
                    });
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($expenseType, function ($query) use ($expenseType) {
                $query->whereJsonContains(
                    'expense_type',
                    $expenseType
                );
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where(
                    'payment_status',
                    $paymentStatus
                );
            })
            ->when(
                $reimbursable !== null && $reimbursable !== '',
                function ($query) use ($reimbursable) {
                    $query->where(
                        'reimbursable',
                        (bool) $reimbursable
                    );
                }
            )
            ->latest('expense_date')
            ->paginate(10)
            ->withQueryString();

        return view('expenses.index', compact(
            'expenses',
            'search',
            'category',
            'expenseType',
            'paymentStatus',
            'reimbursable'
        ));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();

        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        $clients = Client::orderBy('id')->get();

        $assignments = Assignment::latest('id')->get();

        $trips = Trip::latest('id')->get();

        return view('expenses.create', compact(
            'vehicles',
            'drivers',
            'clients',
            'assignments',
            'trips'
        ));
    }

    public function store(Request $request)
    {
        $expenseTypes = $this->expenseTypes();

        $validated = $request->validate([

            'category' => [
                'required',
                Rule::in(array_keys($expenseTypes)),
            ],

            'expense_type' => [
                'required',
                'array',
                'min:1',
            ],

            'expense_type.*' => [
                'required',
                'string',
                'max:255',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'vehicle_id' => [
                'nullable',
                'exists:vehicles,id',
            ],

            'driver_id' => [
                'nullable',
                'exists:drivers,id',
            ],

            'client_id' => [
                'nullable',
                'exists:clients,id',
            ],

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'trip_id' => [
                'nullable',
                'exists:trips,id',
            ],

            'parts_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'labour_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_status' => [
                'required',
                Rule::in([
                    'Pending',
                    'Paid',
                    'Cancelled',
                ]),
            ],

            'payment_method_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reimbursable' => [
                'required',
                'boolean',
            ],

            'receipt' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        foreach ($validated['expense_type'] as $type) {

            if (
                !in_array(
                    $type,
                    $expenseTypes[$validated['category']],
                    true
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'expense_type' =>
                            'One or more selected Expense Types do not belong to the selected Category.',
                    ]);
            }
        }

        $partsCost = (float) ($validated['parts_cost'] ?? 0);
        $labourCost = (float) ($validated['labour_cost'] ?? 0);
        $otherCost = (float) ($validated['other_cost'] ?? 0);

        $totalAmount =
            $partsCost +
            $labourCost +
            $otherCost;

        if ($totalAmount <= 0) {

            return back()
                ->withInput()
                ->withErrors([
                    'parts_cost' =>
                        'Total Amount must be greater than 0.',
                ]);
        }

        if (
            $validated['payment_status'] === 'Paid'
            && empty(
                trim(
                    $validated['payment_method_reference'] ?? ''
                )
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment_method_reference' =>
                        'Payment Method / Reference is required when Payment Status is Paid.',
                ]);
        }

        $validated['parts_cost'] = $partsCost;
        $validated['labour_cost'] = $labourCost;
        $validated['other_cost'] = $otherCost;
        $validated['amount'] = $totalAmount;

        $validated['expense_no'] =
            $this->generateExpenseNumber();

        if ($request->hasFile('receipt')) {

            $validated['receipt'] = $request
                ->file('receipt')
                ->store(
                    'expenses/receipts',
                    'public'
                );
        }

        $expense = Expense::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->created(
            module: 'Expense',
            subject: $expense,
            description: "Created expense record {$expense->expense_no}.",
            newValues: $expense->toArray()
        );

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense Added Successfully!'
            );
    }

    public function show($id)
{
    $expense = Expense::with([
        'vehicle',
        'driver',
        'client',
        'assignment',
        'trip',
    ])->findOrFail($id);

    return view(
        'expenses.show',
        compact('expense')
    );
}

public function receipt($id)
{
    $expense = Expense::findOrFail($id);

    abort_unless($expense->receipt, 404);

    $disk = Storage::disk('public');

    abort_unless($disk->exists($expense->receipt), 404);

    return $disk->response($expense->receipt);
}



    public function edit($id)
    {
        $expense = Expense::findOrFail($id);

        $vehicles = Vehicle::orderBy('plate_number')->get();

        $drivers = Driver::where('status', 'Active')
            ->orderBy('driver_name')
            ->get();

        $clients = Client::orderBy('id')->get();

        $assignments = Assignment::latest('id')->get();

        $trips = Trip::latest('id')->get();

        return view('expenses.edit', compact(
            'expense',
            'vehicles',
            'drivers',
            'clients',
            'assignments',
            'trips'
        ));
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Old Values For Audit Trail
        |--------------------------------------------------------------------------
        */

        $oldValues = $expense->getAttributes();

        $expenseTypes = $this->expenseTypes();

        $validated = $request->validate([

            'category' => [
                'required',
                Rule::in(array_keys($expenseTypes)),
            ],

            'expense_type' => [
                'required',
                'array',
                'min:1',
            ],

            'expense_type.*' => [
                'required',
                'string',
                'max:255',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'vehicle_id' => [
                'nullable',
                'exists:vehicles,id',
            ],

            'driver_id' => [
                'nullable',
                'exists:drivers,id',
            ],

            'client_id' => [
                'nullable',
                'exists:clients,id',
            ],

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'trip_id' => [
                'nullable',
                'exists:trips,id',
            ],

            'parts_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'labour_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_status' => [
                'required',
                Rule::in([
                    'Pending',
                    'Paid',
                    'Cancelled',
                ]),
            ],

            'payment_method_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reimbursable' => [
                'required',
                'boolean',
            ],

            'receipt' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        foreach ($validated['expense_type'] as $type) {

            if (
                !in_array(
                    $type,
                    $expenseTypes[$validated['category']],
                    true
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'expense_type' =>
                            'One or more selected Expense Types do not belong to the selected Category.',
                    ]);
            }
        }

        $partsCost = (float) ($validated['parts_cost'] ?? 0);
        $labourCost = (float) ($validated['labour_cost'] ?? 0);
        $otherCost = (float) ($validated['other_cost'] ?? 0);

        $totalAmount =
            $partsCost +
            $labourCost +
            $otherCost;

        if ($totalAmount <= 0) {

            return back()
                ->withInput()
                ->withErrors([
                    'parts_cost' =>
                        'Total Amount must be greater than 0.',
                ]);
        }

        if (
            $validated['payment_status'] === 'Paid'
            && empty(
                trim(
                    $validated['payment_method_reference'] ?? ''
                )
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment_method_reference' =>
                        'Payment Method / Reference is required when Payment Status is Paid.',
                ]);
        }

        $validated['parts_cost'] = $partsCost;
        $validated['labour_cost'] = $labourCost;
        $validated['other_cost'] = $otherCost;
        $validated['amount'] = $totalAmount;

        if ($request->hasFile('receipt')) {

            if (
                $expense->receipt
                && Storage::disk('public')
                    ->exists($expense->receipt)
            ) {
                Storage::disk('public')->delete(
                    $expense->receipt
                );
            }

            $validated['receipt'] = $request
                ->file('receipt')
                ->store(
                    'expenses/receipts',
                    'public'
                );
        }

        $expense->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->updated(
            module: 'Expense',
            subject: $expense,
            oldValues: $oldValues,
            newValues: $expense->getAttributes(),
            description: "Updated expense record {$expense->expense_no}."
        );

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense Updated Successfully!'
            );
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Values Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues = $expense->getAttributes();
        $expenseNo = $expense->expense_no;

        if (
            $expense->receipt
            && Storage::disk('public')
                ->exists($expense->receipt)
        ) {
            Storage::disk('public')->delete(
                $expense->receipt
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->deleted(
            module: 'Expense',
            subject: $expense,
            oldValues: $oldValues,
            description: "Deleted expense record {$expenseNo}."
        );

        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Expense Deleted Successfully!'
            );
    }

    private function generateExpenseNumber()
    {
        $year = date('Y');

        $lastExpense = Expense::whereYear(
            'created_at',
            $year
        )
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastExpense
            ? ((int) substr(
                $lastExpense->expense_no,
                -5
            )) + 1
            : 1;

        return 'AST-EXP-' . $year . '-' .
            str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
}