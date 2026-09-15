<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Assignment;
use App\Models\Fuel;
use App\Models\FuelRecovery;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $invoiceQuery = Invoice::with([
            'client',
            'assignment',
        ])
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'invoice_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'invoice_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas('client', function ($clientQuery) use ($search) {

                    $clientQuery->where(
                        'client_name',
                        'like',
                        "%{$search}%"
                    );

                });

            });

        });

        /*
        |--------------------------------------------------------------------------
        | KPI Totals
        |--------------------------------------------------------------------------
        */

        $totalInvoices = (clone $invoiceQuery)->count();

        $totalAmount = (clone $invoiceQuery)
            ->sum('total_amount');

        $totalPaid = (clone $invoiceQuery)
            ->sum('paid_amount');

        $totalBalance = (clone $invoiceQuery)
            ->sum('balance');

        /*
        |--------------------------------------------------------------------------
        | Paginated Invoices
        |--------------------------------------------------------------------------
        */

        $invoices = (clone $invoiceQuery)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'invoices.index',
            compact(
                'invoices',
                'search',
                'totalInvoices',
                'totalAmount',
                'totalPaid',
                'totalBalance'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Invoice Form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $clients = Client::where(
            'status',
            'Active'
        )
        ->orderBy('client_name')
        ->get();

        $assignments = Assignment::with([
            'client',
        ])
        ->latest()
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Eligible Fuel Recoveries
        |--------------------------------------------------------------------------
        */

        $fuels = Fuel::with([
            'vehicle',
            'driver',
            'client',
        ])
        ->where('reimbursable', true)
        ->whereNotNull('reimbursement_amount')
        ->where('reimbursement_amount', '>', 0)
        ->get()
        ->filter(function ($fuel) {

            $recoveredAmount = (float) $fuel->recoveries()
                ->sum('recovery_amount');

            return $recoveredAmount <
                (float) $fuel->reimbursement_amount;
        })
        ->values();

        return view(
            'invoices.create',
            compact(
                'clients',
                'assignments',
                'fuels'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Invoice
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'client_id' => [
                'required',
                'exists:clients,id',
            ],

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'billing_start' => [
                'required',
                'date',
            ],

            'billing_end' => [
                'required',
                'date',
                'after_or_equal:billing_start',
            ],

            'invoice_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'required',
                'date',
                'after_or_equal:invoice_date',
            ],

            'source' => [
                'nullable',
                'string',
                'max:100',
            ],

            'subtotal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'vat_percent' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'fuel_recoveries' => [
                'nullable',
                'array',
            ],

            'fuel_recoveries.*.fuel_id' => [
                'required',
                'integer',
                'exists:fuels,id',
            ],

            'fuel_recoveries.*.amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'other_reimbursement' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:Draft,Issued,Partial,Paid,Overdue,Cancelled',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Fuel Recoveries
        |--------------------------------------------------------------------------
        */

        $fuelRecoveries = $validated['fuel_recoveries'] ?? [];

        $fuelRecoveryTotal = $this->validateFuelRecoveries(
            $fuelRecoveries,
            $validated['client_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Generate Invoice Number
        |--------------------------------------------------------------------------
        */

        $invoiceNo = $this->generateInvoiceNumber();

        /*
        |--------------------------------------------------------------------------
        | Calculate VAT
        |--------------------------------------------------------------------------
        */

        $subtotal = (float) $validated['subtotal'];

        $vatPercent = (float) $validated['vat_percent'];

        $vatAmount = round(
            ($subtotal * $vatPercent) / 100,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $otherReimbursement =
            (float) $validated['other_reimbursement'];

        $totalAmount = round(
            $subtotal
            + $vatAmount
            + $fuelRecoveryTotal
            + $otherReimbursement,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Initial Payment / Balance
        |--------------------------------------------------------------------------
        */

        $paidAmount = 0;

        $balance = $totalAmount;

        /*
        |--------------------------------------------------------------------------
        | Create Invoice + Fuel Recovery Records
        |--------------------------------------------------------------------------
        */

        $invoice = DB::transaction(function () use (
            $validated,
            $invoiceNo,
            $subtotal,
            $vatPercent,
            $vatAmount,
            $fuelRecoveryTotal,
            $otherReimbursement,
            $totalAmount,
            $paidAmount,
            $balance,
            $fuelRecoveries
        ) {

            $invoice = Invoice::create([

                'invoice_number' => $invoiceNo,

                'invoice_no' => $invoiceNo,

                'client_id' => $validated['client_id'],

                'assignment_id' =>
                    $validated['assignment_id'] ?? null,

                'billing_start' =>
                    $validated['billing_start'],

                'billing_end' =>
                    $validated['billing_end'],

                'invoice_date' =>
                    $validated['invoice_date'],

                'due_date' =>
                    $validated['due_date'],

                'source' =>
                    $validated['source'] ?? null,

                'amount' =>
                    $totalAmount,

                'subtotal' =>
                    $subtotal,

                'vat_percent' =>
                    $vatPercent,

                'vat_amount' =>
                    $vatAmount,

                'fuel_reimbursement' =>
                    $fuelRecoveryTotal,

                'other_reimbursement' =>
                    $otherReimbursement,

                'total_amount' =>
                    $totalAmount,

                'paid_amount' =>
                    $paidAmount,

                'balance' =>
                    $balance,

                'status' =>
                    $validated['status'],

                'notes' =>
                    $validated['notes'] ?? null,

            ]);

            foreach ($fuelRecoveries as $recovery) {

                FuelRecovery::create([

                    'fuel_id' =>
                        $recovery['fuel_id'],

                    'invoice_id' =>
                        $invoice->id,

                    'recovery_amount' =>
                        $recovery['amount'],

                ]);
            }

            return $invoice;
        });

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->created(
            module: 'Invoice',
            subject: $invoice,
            description: "Created invoice {$invoice->invoice_no}.",
            newValues: $invoice->toArray()
        );

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice Added Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Invoice
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $invoice = Invoice::with([
            'client',
            'assignment',
            'fuelRecoveries.fuel.vehicle',
            'fuelRecoveries.fuel.driver',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Calculate Overdue Status
        |--------------------------------------------------------------------------
        */

        if (
            $invoice->status !== 'Cancelled'
            && (float) $invoice->balance > 0
            && $invoice->due_date
            && $invoice->due_date->isPast()
            && $invoice->status !== 'Paid'
        ) {
            $invoice->status = 'Overdue';
        }

        return view(
            'invoices.show',
            compact('invoice')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Invoice PDF
    |--------------------------------------------------------------------------
    */

    public function pdf(string $id)
    {
        $invoice = Invoice::with([
            'client',
            'assignment',
            'fuelRecoveries.fuel.vehicle',
            'fuelRecoveries.fuel.driver',
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'invoices.pdf',
            compact('invoice')
        );

        $fileName =
            ($invoice->invoice_no ?? $invoice->invoice_number)
            . '.pdf';

        return $pdf->stream($fileName);
    }

    /*
    |--------------------------------------------------------------------------
    | Email Invoice
    |--------------------------------------------------------------------------
    */

    public function email(Request $request, string $id)
    {
        $validated = $request->validate([

            'recipient_email' => [
                'required',
                'email',
                'max:255',
            ],

            'message' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ]);

        $invoice = Invoice::with([
            'client',
            'assignment',
            'fuelRecoveries.fuel.vehicle',
            'fuelRecoveries.fuel.driver',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Generate Fresh Invoice PDF
        |--------------------------------------------------------------------------
        |
        | The PDF is generated here specifically for the email.
        | This guarantees that the email always receives the
        | current invoice PDF as an actual attachment.
        |
        */

        $pdf = Pdf::loadView(
            'invoices.pdf',
            compact('invoice')
        );

        $pdfContent = $pdf->output();

        $fileName =
            ($invoice->invoice_no ?? $invoice->invoice_number)
            . '.pdf';

        /*
        |--------------------------------------------------------------------------
        | Send Invoice Email With PDF Attachment
        |--------------------------------------------------------------------------
        */

        Mail::to(
            $validated['recipient_email']
        )->send(
            new InvoiceMail(
                invoice: $invoice,
                messageText: $validated['message'] ?? '',
                pdfContent: $pdfContent,
                pdfFileName: $fileName
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->custom(
            action: 'emailed',
            module: 'Invoice',
            description: "Emailed invoice {$invoice->invoice_no} to {$validated['recipient_email']}.",
            subject: $invoice
        );

        return redirect()
            ->route(
                'invoices.show',
                $invoice->id
            )
            ->with(
                'success',
                'Invoice emailed successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Invoice Form
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $invoice = Invoice::with([
            'fuelRecoveries',
        ])->findOrFail($id);

        $clients = Client::where(
            'status',
            'Active'
        )
        ->orderBy('client_name')
        ->get();

        $assignments = Assignment::with([
            'client',
        ])
        ->latest()
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Eligible Fuel Recoveries
        |--------------------------------------------------------------------------
        */

        $existingFuelIds = $invoice->fuelRecoveries
            ->pluck('fuel_id')
            ->toArray();

        $fuels = Fuel::with([
            'vehicle',
            'driver',
            'client',
        ])
        ->where('reimbursable', true)
        ->whereNotNull('reimbursement_amount')
        ->where('reimbursement_amount', '>', 0)
        ->get()
        ->filter(function ($fuel) use ($existingFuelIds) {

            if (in_array($fuel->id, $existingFuelIds)) {
                return true;
            }

            $recoveredAmount = (float) $fuel->recoveries()
                ->sum('recovery_amount');

            return $recoveredAmount <
                (float) $fuel->reimbursement_amount;
        })
        ->values();

        return view(
            'invoices.edit',
            compact(
                'invoice',
                'clients',
                'assignments',
                'fuels'
            )
        );
    }
    /*
    |--------------------------------------------------------------------------
    | Update Invoice
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {
        $invoice = Invoice::with([
            'fuelRecoveries',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Old Values For Audit Trail
        |--------------------------------------------------------------------------
        */

        $oldValues = $invoice->getAttributes();

        $validated = $request->validate([

            'client_id' => [
                'required',
                'exists:clients,id',
            ],

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'billing_start' => [
                'required',
                'date',
            ],

            'billing_end' => [
                'required',
                'date',
                'after_or_equal:billing_start',
            ],

            'invoice_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'required',
                'date',
                'after_or_equal:invoice_date',
            ],

            'source' => [
                'nullable',
                'string',
                'max:100',
            ],

            'subtotal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'vat_percent' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'fuel_recoveries' => [
                'nullable',
                'array',
            ],

            'fuel_recoveries.*.fuel_id' => [
                'required',
                'integer',
                'exists:fuels,id',
            ],

            'fuel_recoveries.*.amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'other_reimbursement' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:Draft,Issued,Partial,Paid,Overdue,Cancelled',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Fuel Recoveries
        |--------------------------------------------------------------------------
        */

        $fuelRecoveries = $validated['fuel_recoveries'] ?? [];

        $fuelRecoveryTotal = $this->validateFuelRecoveries(
            $fuelRecoveries,
            $validated['client_id'],
            $invoice->id
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate VAT
        |--------------------------------------------------------------------------
        */

        $subtotal = (float) $validated['subtotal'];

        $vatPercent = (float) $validated['vat_percent'];

        $vatAmount = round(
            ($subtotal * $vatPercent) / 100,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $otherReimbursement =
            (float) $validated['other_reimbursement'];

        $totalAmount = round(
            $subtotal
            + $vatAmount
            + $fuelRecoveryTotal
            + $otherReimbursement,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Preserve Existing Payments
        |--------------------------------------------------------------------------
        */

        $paidAmount =
            (float) $invoice->paid_amount;

        $balance = max(
            $totalAmount - $paidAmount,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | Automatically Determine Payment Status
        |--------------------------------------------------------------------------
        */

        $status = $validated['status'];

        if ($status !== 'Cancelled') {

            if ($paidAmount <= 0) {

                if (
                    $status === 'Paid'
                    || $status === 'Partial'
                ) {
                    $status = 'Issued';
                }

            } elseif ($paidAmount >= $totalAmount) {

                $status = 'Paid';

            } else {

                $status = 'Partial';
            }

            /*
            |--------------------------------------------------------------------------
            | Overdue
            |--------------------------------------------------------------------------
            */

            if (
                $balance > 0
                && $validated['due_date']
                && now()->startOfDay()->gt(
                    \Carbon\Carbon::parse(
                        $validated['due_date']
                    )->startOfDay()
                )
            ) {
                $status = 'Overdue';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Invoice + Fuel Recoveries
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $invoice,
            $validated,
            $subtotal,
            $vatPercent,
            $vatAmount,
            $fuelRecoveryTotal,
            $otherReimbursement,
            $totalAmount,
            $paidAmount,
            $balance,
            $status,
            $fuelRecoveries
        ) {

            $invoice->update([

                'client_id' =>
                    $validated['client_id'],

                'assignment_id' =>
                    $validated['assignment_id'] ?? null,

                'billing_start' =>
                    $validated['billing_start'],

                'billing_end' =>
                    $validated['billing_end'],

                'invoice_date' =>
                    $validated['invoice_date'],

                'due_date' =>
                    $validated['due_date'],

                'source' =>
                    $validated['source'] ?? null,

                'amount' =>
                    $totalAmount,

                'subtotal' =>
                    $subtotal,

                'vat_percent' =>
                    $vatPercent,

                'vat_amount' =>
                    $vatAmount,

                'fuel_reimbursement' =>
                    $fuelRecoveryTotal,

                'other_reimbursement' =>
                    $otherReimbursement,

                'total_amount' =>
                    $totalAmount,

                'paid_amount' =>
                    $paidAmount,

                'balance' =>
                    $balance,

                'status' =>
                    $status,

                'notes' =>
                    $validated['notes'] ?? null,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Replace Fuel Recovery Records
            |--------------------------------------------------------------------------
            */

            FuelRecovery::where(
                'invoice_id',
                $invoice->id
            )->delete();

            foreach ($fuelRecoveries as $recovery) {

                FuelRecovery::create([

                    'fuel_id' =>
                        $recovery['fuel_id'],

                    'invoice_id' =>
                        $invoice->id,

                    'recovery_amount' =>
                        $recovery['amount'],

                ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->updated(
            module: 'Invoice',
            subject: $invoice,
            oldValues: $oldValues,
            newValues: $invoice->getAttributes(),
            description: "Updated invoice {$invoice->invoice_no}."
        );

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice Updated Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Invoice
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $invoice = Invoice::with([
            'fuelRecoveries',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Protect Finance History
        |--------------------------------------------------------------------------
        */

        if ($invoice->fuelRecoveries()->exists()) {

            return redirect()
                ->route('invoices.index')
                ->with(
                    'error',
                    'Invoice cannot be deleted because fuel recovery records are linked to it.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Protect Payment Allocations
        |--------------------------------------------------------------------------
        */

        if ($invoice->payments()->exists()) {

            return redirect()
                ->route('invoices.index')
                ->with(
                    'error',
                    'Invoice cannot be deleted because payment records are linked to it.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Capture Values Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues = $invoice->getAttributes();

        $invoiceNo = $invoice->invoice_no;

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->deleted(
            module: 'Invoice',
            subject: $invoice,
            oldValues: $oldValues,
            description: "Deleted invoice {$invoiceNo}."
        );

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice Deleted Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Fuel Recoveries
    |--------------------------------------------------------------------------
    */

    private function validateFuelRecoveries(
        array $recoveries,
        int $clientId,
        ?int $invoiceId = null
    ): float {

        $totalRecovery = 0;

        $fuelIds = [];

        foreach ($recoveries as $recovery) {

            $fuelId = (int) $recovery['fuel_id'];

            $amount = (float) $recovery['amount'];

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Fuel IDs in Same Invoice Request
            |--------------------------------------------------------------------------
            */

            if (in_array($fuelId, $fuelIds, true)) {

                abort(
                    422,
                    'The same fuel entry cannot be added more than once to an invoice.'
                );
            }

            $fuelIds[] = $fuelId;

            /*
            |--------------------------------------------------------------------------
            | Load Fuel
            |--------------------------------------------------------------------------
            */

            $fuel = Fuel::with([
                'recoveries',
            ])->find($fuelId);

            if (!$fuel) {

                abort(
                    422,
                    'Selected fuel entry was not found.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Fuel Must Be Reimbursable
            |--------------------------------------------------------------------------
            */

            if (!$fuel->reimbursable) {

                abort(
                    422,
                    'Selected fuel entry is not marked as reimbursable.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Fuel Must Belong To Selected Client
            |--------------------------------------------------------------------------
            */

            if (
                $fuel->client_id !== null
                && (int) $fuel->client_id !== $clientId
            ) {

                abort(
                    422,
                    'Selected fuel entry does not belong to the selected client.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Already Recovered Amount
            |--------------------------------------------------------------------------
            */

            $alreadyRecovered = (float) $fuel->recoveries
                ->when(
                    $invoiceId !== null,
                    function ($collection) use ($invoiceId) {

                        return $collection->where(
                            'invoice_id',
                            '!=',
                            $invoiceId
                        );

                    }
                )
                ->sum('recovery_amount');

            /*
            |--------------------------------------------------------------------------
            | Calculate Remaining Recovery
            |--------------------------------------------------------------------------
            */

            $reimbursementAmount =
                (float) $fuel->reimbursement_amount;

            $remainingAmount = round(
                $reimbursementAmount
                - $alreadyRecovered,
                2
            );

            /*
            |--------------------------------------------------------------------------
            | Recovery Amount Cannot Exceed Outstanding Amount
            |--------------------------------------------------------------------------
            */

            if ($amount > $remainingAmount) {

                abort(
                    422,
                    "Fuel entry {$fuel->fuel_entry_no} has only AED "
                    . number_format($remainingAmount, 2)
                    . " available for recovery."
                );
            }

            $totalRecovery = round(
                $totalRecovery + $amount,
                2
            );
        }

        return $totalRecovery;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Invoice Number
    |--------------------------------------------------------------------------
    */

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');

        $prefix = "AST-INV-{$year}.";

        $lastInvoice = Invoice::where(
            'invoice_no',
            'like',
            $prefix . '%'
        )
        ->orderByDesc('id')
        ->first();

        if (!$lastInvoice) {

            $sequence = 1;

        } else {

            $lastNumber = (int) str_replace(
                $prefix,
                '',
                $lastInvoice->invoice_no
            );

            $sequence = $lastNumber + 1;
        }

        return $prefix . str_pad(
            $sequence,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}