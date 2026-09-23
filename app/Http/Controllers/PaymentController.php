<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | Payment List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $payments = Payment::with('client')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('payment_no', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($clientQuery) use ($search) {
                            $clientQuery->where(
                                'client_name',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Payment Form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

        $invoices = Invoice::with('client')
            ->where('status', '!=', 'Cancelled')
            ->where('balance', '>', 0)
            ->orderBy('due_date')
            ->get();

        return view(
            'payments.create',
            compact('clients', 'invoices')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Payment
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => [
                'required',
                'integer',
                'exists:clients,id',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'payment_method' => [
                'required',
                'in:Bank,Cash,Cheque,Other',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'max:5120',
            ],

            'allocations' => [
                'nullable',
                'array',
            ],

            'allocations.*.invoice_id' => [
                'required',
                'integer',
                'exists:invoices,id',
            ],

            'allocations.*.allocated_amount' => [
                'nullable',
                'numeric',
                'gt:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Clean and combine duplicate allocation rows
        |--------------------------------------------------------------------------
        */

        $allocations = $this->cleanAllocations(
            $request->input('allocations', [])
        );

        $paymentAmount = (float) $validated['amount'];

        $totalAllocated = $this->getTotalAllocated(
            $allocations
        );

        /*
        |--------------------------------------------------------------------------
        | Payment amount cannot be less than allocations
        |--------------------------------------------------------------------------
        */

        if ($totalAllocated > $paymentAmount) {
            throw ValidationException::withMessages([
                'amount' =>
                    'Total allocated amount cannot be greater than the payment amount.',
            ]);
        }

        $attachmentPath = null;

        try {
            $payment = DB::transaction(function () use (
                $validated,
                $allocations,
                $totalAllocated,
                $request,
                &$attachmentPath
            ) {
                /*
                |--------------------------------------------------------------------------
                | Get selected invoice IDs
                |--------------------------------------------------------------------------
                */

                $invoiceIds = collect($allocations)
                    ->pluck('invoice_id')
                    ->unique()
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | Lock selected invoices
                |--------------------------------------------------------------------------
                |
                | This protects invoice balances when multiple payments
                | are being processed at the same time.
                |
                */

                $invoices = Invoice::whereIn('id', $invoiceIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                /*
                |--------------------------------------------------------------------------
                | Validate allocations
                |--------------------------------------------------------------------------
                */

                $this->validateAllocations(
                    $allocations,
                    $invoices,
                    (int) $validated['client_id']
                );

                /*
                |--------------------------------------------------------------------------
                | Store payment attachment
                |--------------------------------------------------------------------------
                */

                if ($request->hasFile('attachment')) {
                    $attachmentPath = $request
                        ->file('attachment')
                        ->store('payments', 'public');
                }

                /*
                |--------------------------------------------------------------------------
                | Create payment
                |--------------------------------------------------------------------------
                */

                $paymentAmount = (float) $validated['amount'];

                $payment = Payment::create([
                    'payment_no' => $this->generatePaymentNumber(),

                    'client_id' => $validated['client_id'],

                    'payment_date' => $validated['payment_date'],

                    'amount' => $paymentAmount,

                    'payment_method' => $validated['payment_method'],

                    'reference' => $validated['reference'] ?? null,

                    'unallocated_amount' => max(
                        $paymentAmount - $totalAllocated,
                        0
                    ),

                    'notes' => $validated['notes'] ?? null,

                    'attachment' => $attachmentPath,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Create payment allocations
                |--------------------------------------------------------------------------
                */

                foreach ($allocations as $allocation) {
                    PaymentAllocation::create([
                        'payment_id' => $payment->id,

                        'invoice_id' => $allocation['invoice_id'],

                        'allocated_amount' => $allocation['allocated_amount'],
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Recalculate affected invoices
                |--------------------------------------------------------------------------
                */

                foreach ($invoiceIds as $invoiceId) {
                    $this->recalculateInvoice(
                        (int) $invoiceId,
                        true
                    );
                }

                return $payment;
            });
        } catch (\Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | Remove attachment if transaction fails
            |--------------------------------------------------------------------------
            */

            if ($attachmentPath) {
                Storage::disk('public')->delete(
                    $attachmentPath
                );
            }

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->created(
            module: 'Payment',
            subject: $payment,
            description: "Created payment {$payment->payment_no}.",
            newValues: $payment->toArray()
        );

        return redirect()
            ->route('payments.index')
            ->with(
                'success',
                'Payment created successfully.'
            );
    }

          /*
|--------------------------------------------------------------------------
| Show Payment
|--------------------------------------------------------------------------
*/

public function show(Payment $payment)
{
    $payment->load([
        'client',
        'allocations.invoice',
        'invoices',
    ]);

    return view(
        'payments.show',
        compact('payment')
    );
}

/*
|--------------------------------------------------------------------------
| Display Payment Attachment
|--------------------------------------------------------------------------
*/

public function attachment(Payment $payment)
{
    abort_unless($payment->attachment, 404);

    $disk = Storage::disk('public');

    abort_unless(
        $disk->exists($payment->attachment),
        404
    );

    return $disk->response(
        $payment->attachment
    );
}

/*
|--------------------------------------------------------------------------
| Edit Payment Form
|--------------------------------------------------------------------------
*/

    public function edit(Payment $payment)
    {
        $payment->load([
            'client',
            'allocations.invoice',
        ]);

        $clients = Client::where('status', 'Active')
            ->orderBy('client_name')
            ->get();

        $invoices = Invoice::with('client')
            ->where('status', '!=', 'Cancelled')
            ->orderBy('due_date')
            ->get();

        return view(
            'payments.edit',
            compact(
                'payment',
                'clients',
                'invoices'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Payment
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Payment $payment
    ) {
        $oldValues = $payment->getAttributes();

        $validated = $request->validate([
            'client_id' => [
                'required',
                'integer',
                'exists:clients,id',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'payment_method' => [
                'required',
                'in:Bank,Cash,Cheque,Other',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'max:5120',
            ],

            'allocations' => [
                'nullable',
                'array',
            ],

            'allocations.*.invoice_id' => [
                'required',
                'integer',
                'exists:invoices,id',
            ],

            'allocations.*.allocated_amount' => [
                'nullable',
                'numeric',
                'gt:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Clean allocations
        |--------------------------------------------------------------------------
        */

        $allocations = $this->cleanAllocations(
            $request->input('allocations', [])
        );

        $paymentAmount = (float) $validated['amount'];

        $totalAllocated = $this->getTotalAllocated(
            $allocations
        );

        if ($totalAllocated > $paymentAmount) {
            throw ValidationException::withMessages([
                'amount' =>
                    'Total allocated amount cannot be greater than the payment amount.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Existing invoice IDs
        |--------------------------------------------------------------------------
        */

        $oldInvoiceIds = $payment->allocations()
            ->pluck('invoice_id')
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Attachment tracking
        |--------------------------------------------------------------------------
        */

        $oldAttachment = $payment->attachment;

        $newAttachmentPath = $oldAttachment;

        $storedNewAttachment = null;

        try {
            DB::transaction(function () use (
                $validated,
                $allocations,
                $totalAllocated,
                $request,
                $payment,
                $oldInvoiceIds,
                &$newAttachmentPath,
                &$storedNewAttachment
            ) {
                /*
                |--------------------------------------------------------------------------
                | New invoice IDs
                |--------------------------------------------------------------------------
                */

                $newInvoiceIds = collect($allocations)
                    ->pluck('invoice_id')
                    ->unique()
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | Lock all affected invoices
                |--------------------------------------------------------------------------
                */

                $allInvoiceIds = $oldInvoiceIds
                    ->merge($newInvoiceIds)
                    ->unique()
                    ->values();

                $invoices = Invoice::whereIn(
                        'id',
                        $allInvoiceIds
                    )
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                /*
                |--------------------------------------------------------------------------
                | Validate updated allocations
                |--------------------------------------------------------------------------
                */

                $this->validateAllocationsForUpdate(
                    $allocations,
                    $invoices,
                    (int) $validated['client_id'],
                    $payment->id
                );

                /*
                |--------------------------------------------------------------------------
                | Store replacement attachment
                |--------------------------------------------------------------------------
                */

                if ($request->hasFile('attachment')) {
                    $storedNewAttachment = $request
                        ->file('attachment')
                        ->store('payments', 'public');

                    $newAttachmentPath = $storedNewAttachment;
                }

                /*
                |--------------------------------------------------------------------------
                | Update payment
                |--------------------------------------------------------------------------
                */

                $paymentAmount = (float) $validated['amount'];

                $payment->update([
                    'client_id' => $validated['client_id'],

                    'payment_date' => $validated['payment_date'],

                    'amount' => $paymentAmount,

                    'payment_method' => $validated['payment_method'],

                    'reference' => $validated['reference'] ?? null,

                    'unallocated_amount' => max(
                        $paymentAmount - $totalAllocated,
                        0
                    ),

                    'notes' => $validated['notes'] ?? null,

                    'attachment' => $newAttachmentPath,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Replace allocations
                |--------------------------------------------------------------------------
                */

                $payment->allocations()->delete();

                foreach ($allocations as $allocation) {
                    PaymentAllocation::create([
                        'payment_id' => $payment->id,

                        'invoice_id' => $allocation['invoice_id'],

                        'allocated_amount' => $allocation['allocated_amount'],
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Recalculate old invoices
                |--------------------------------------------------------------------------
                */

                foreach ($oldInvoiceIds as $invoiceId) {
                    $this->recalculateInvoice(
                        (int) $invoiceId,
                        true
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Recalculate newly selected invoices
                |--------------------------------------------------------------------------
                */

                foreach ($newInvoiceIds as $invoiceId) {
                    if (!$oldInvoiceIds->contains($invoiceId)) {
                        $this->recalculateInvoice(
                            (int) $invoiceId,
                            true
                        );
                    }
                }
            });
        } catch (\Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | Remove newly stored attachment if update fails
            |--------------------------------------------------------------------------
            */

            if ($storedNewAttachment) {
                Storage::disk('public')->delete(
                    $storedNewAttachment
                );
            }

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | Delete old attachment after successful transaction
        |--------------------------------------------------------------------------
        */

        if (
            $oldAttachment
            && $storedNewAttachment
            && $oldAttachment !== $storedNewAttachment
        ) {
            Storage::disk('public')->delete(
                $oldAttachment
            );
        }
        $this->activityLogService->updated(
            module: 'Payment',
            subject: $payment,
            oldValues: $oldValues,
            newValues: $payment->getAttributes(),
            description: "Updated payment {$payment->payment_no}."
        );

        return redirect()
            ->route('payments.index')
            ->with(
                'success',
                'Payment updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Payment
    |--------------------------------------------------------------------------
    */

    public function destroy(Payment $payment)
    {
        $invoiceIds = $payment->allocations()
            ->pluck('invoice_id')
            ->unique()
            ->values();

        $attachmentPath = $payment->attachment;

        $oldValues = $payment->getAttributes();
        $paymentNo = $payment->payment_no;

        DB::transaction(function () use (
            $payment,
            $invoiceIds
        ) {
            /*
            |--------------------------------------------------------------------------
            | Lock affected invoices
            |--------------------------------------------------------------------------
            */

            Invoice::whereIn('id', $invoiceIds)
                ->lockForUpdate()
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Delete payment
            |--------------------------------------------------------------------------
            */

            $payment->delete();

            /*
            |--------------------------------------------------------------------------
            | Recalculate invoice balances
            |--------------------------------------------------------------------------
            */

            foreach ($invoiceIds as $invoiceId) {
                $this->recalculateInvoice(
                    (int) $invoiceId,
                    true
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->deleted(
            module: 'Payment',
            subject: $payment,
            oldValues: $oldValues,
            description: "Deleted payment {$paymentNo}."
        );

        /*
        |--------------------------------------------------------------------------
        | Delete attachment after successful transaction
        |--------------------------------------------------------------------------
        */

        if ($attachmentPath) {
            Storage::disk('public')->delete(
                $attachmentPath
            );
        }

        return redirect()
            ->route('payments.index')
            ->with(
                'success',
                'Payment deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Clean Allocation Rows
    |--------------------------------------------------------------------------
    */

    private function cleanAllocations(
        array $allocations
    ): array {
        return collect($allocations)
            ->filter(function ($allocation) {
                return isset($allocation['invoice_id'])
                    && isset($allocation['allocated_amount'])
                    && $allocation['allocated_amount'] !== ''
                    && (float) $allocation['allocated_amount'] > 0;
            })
            ->groupBy(function ($allocation) {
                return (int) $allocation['invoice_id'];
            })
            ->map(function ($rows, $invoiceId) {
                return [
                    'invoice_id' => (int) $invoiceId,

                    'allocated_amount' => $rows->sum(
                        function ($row) {
                            return (float) $row['allocated_amount'];
                        }
                    ),
                ];
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Get Total Allocated Amount
    |--------------------------------------------------------------------------
    */

    private function getTotalAllocated(
        array $allocations
    ): float {
        return collect($allocations)
            ->sum(function ($allocation) {
                return (float) $allocation['allocated_amount'];
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Allocations For New Payment
    |--------------------------------------------------------------------------
    */

    private function validateAllocations(
        array $allocations,
        $invoices,
        int $clientId
    ): void {
        foreach ($allocations as $allocation) {
            $invoiceId = (int) $allocation['invoice_id'];

            $allocatedAmount = (float) $allocation[
                'allocated_amount'
            ];

            /*
            |--------------------------------------------------------------------------
            | Invoice must exist in locked collection
            |--------------------------------------------------------------------------
            */

            if (!$invoices->has($invoiceId)) {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'One of the selected invoices could not be found.',
                ]);
            }

            $invoice = $invoices->get($invoiceId);

            /*
            |--------------------------------------------------------------------------
            | Payment client and invoice client must match
            |--------------------------------------------------------------------------
            */

            if ((int) $invoice->client_id !== $clientId) {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'You can only allocate a payment to invoices belonging to the selected client.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Cancelled invoices cannot receive payments
            |--------------------------------------------------------------------------
            */

            if ($invoice->status === 'Cancelled') {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'Cancelled invoices cannot receive payments.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Allocation cannot exceed outstanding balance
            |--------------------------------------------------------------------------
            */

            if (
                $allocatedAmount
                > (float) $invoice->balance
            ) {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'Allocation for invoice '
                        . (
                            $invoice->invoice_no
                            ?? $invoice->invoice_number
                        )
                        . ' cannot be greater than its outstanding balance.',
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Allocations For Payment Update
    |--------------------------------------------------------------------------
    */

    private function validateAllocationsForUpdate(
        array $allocations,
        $invoices,
        int $clientId,
        int $paymentId
    ): void {
        foreach ($allocations as $allocation) {
            $invoiceId = (int) $allocation['invoice_id'];

            $allocatedAmount = (float) $allocation[
                'allocated_amount'
            ];

            /*
            |--------------------------------------------------------------------------
            | Invoice must exist in locked collection
            |--------------------------------------------------------------------------
            */

            if (!$invoices->has($invoiceId)) {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'One of the selected invoices could not be found.',
                ]);
            }

            $invoice = $invoices->get($invoiceId);

            /*
            |--------------------------------------------------------------------------
            | Client validation
            |--------------------------------------------------------------------------
            */

            if ((int) $invoice->client_id !== $clientId) {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'You can only allocate a payment to invoices belonging to the selected client.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Cancelled invoice protection
            |--------------------------------------------------------------------------
            */

            if ($invoice->status === 'Cancelled') {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'Cancelled invoices cannot receive payments.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Restore this payment's old allocation temporarily
            |--------------------------------------------------------------------------
            */

            $oldAllocation = PaymentAllocation::where(
                    'payment_id',
                    $paymentId
                )
                ->where(
                    'invoice_id',
                    $invoiceId
                )
                ->sum('allocated_amount');

            $availableBalance =
                (float) $invoice->balance
                + (float) $oldAllocation;

            /*
            |--------------------------------------------------------------------------
            | New allocation cannot exceed available balance
            |--------------------------------------------------------------------------
            */

            if (
                $allocatedAmount
                > $availableBalance
            ) {
                throw ValidationException::withMessages([
                    'allocations' =>
                        'Allocation for invoice '
                        . (
                            $invoice->invoice_no
                            ?? $invoice->invoice_number
                        )
                        . ' cannot be greater than its available outstanding balance.',
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Payment Number
    |--------------------------------------------------------------------------
    */

    private function generatePaymentNumber(): string
    {
        $year = now()->format('Y');

        $prefix = "AST-RCP-{$year}.";

        $lastPayment = Payment::where(
                'payment_no',
                'like',
                $prefix . '%'
            )
            ->orderByDesc('id')
            ->first();

        if (!$lastPayment) {
            $number = 1;
        } else {
            $lastNumber = (int) substr(
                $lastPayment->payment_no,
                -4
            );

            $number = $lastNumber + 1;
        }

        return $prefix . str_pad(
            $number,
            4,
            '0',
            STR_PAD_LEFT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Recalculate Invoice Financial State
    |--------------------------------------------------------------------------
    |
    | FRD BR-006:
    | Invoice balance = Total Amount - Valid Allocated Payments
    |
    | FRD BR-007:
    | Paid     = balance zero
    | Partial  = payment exists but balance remains
    | Overdue  = due date has passed and balance remains
    |--------------------------------------------------------------------------
    */

    private function recalculateInvoice(
        int $invoiceId,
        bool $lockInvoice = false
    ): void {
        $query = Invoice::where(
            'id',
            $invoiceId
        );

        /*
        |--------------------------------------------------------------------------
        | Lock invoice when called inside a transaction
        |--------------------------------------------------------------------------
        */

        if ($lockInvoice) {
            $query->lockForUpdate();
        }

        $invoice = $query->first();

        if (!$invoice) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate valid allocated payments
        |--------------------------------------------------------------------------
        */

        $paidAmount = PaymentAllocation::where(
                'invoice_id',
                $invoiceId
            )
            ->sum('allocated_amount');

        $paidAmount = (float) $paidAmount;

        $totalAmount = (float) $invoice->total_amount;

        /*
        |--------------------------------------------------------------------------
        | Calculate balance
        |--------------------------------------------------------------------------
        */

        $balance = max(
            $totalAmount - $paidAmount,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | Cancelled invoices remain Cancelled
        |--------------------------------------------------------------------------
        */

        if ($invoice->status === 'Cancelled') {
            $invoice->update([
                'paid_amount' => $paidAmount,

                'balance' => $balance,

                'status' => 'Cancelled',
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Determine financial status
        |--------------------------------------------------------------------------
        */

        if (
            $balance <= 0
            && $totalAmount > 0
        ) {
            /*
            | Fully paid
            */

            $status = 'Paid';
        } elseif ($paidAmount > 0) {
            /*
            | Partially paid
            */

            $status = 'Partial';
        } elseif (
            $invoice->due_date
            && today()->gt($invoice->due_date)
        ) {
            /*
            | No payment + due date has already passed
            */

            $status = 'Overdue';
        } else {
            /*
            | No payment and not overdue
            */

            $status = 'Issued';
        }

        /*
        |--------------------------------------------------------------------------
        | Save calculated financial state
        |--------------------------------------------------------------------------
        */

        $invoice->update([
            'paid_amount' => $paidAmount,

            'balance' => $balance,

            'status' => $status,
        ]);
    }
}