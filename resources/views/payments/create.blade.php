@extends('layouts.app')

@section('content')

<style>
    .payment-create-page {
        width: 100%;
    }

    .payment-create-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .payment-create-title h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .payment-create-title p {
        margin: 6px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #334155;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #f8fafc;
    }

    .payment-form-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .form-section {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-title {
        margin: 0 0 18px;
        font-size: 17px;
        font-weight: 700;
        color: #172033;
    }

    .section-description {
        margin: -10px 0 18px;
        color: #718096;
        font-size: 13px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        margin-bottom: 7px;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #dc2626;
    }

    .form-control,
    .form-select,
    .form-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #ffffff;
        color: #172033;
        font-size: 14px;
        outline: none;
        transition: 0.2s ease;
    }

    .form-control,
    .form-select {
        height: 40px;
        padding: 0 11px;
    }

    .form-textarea {
        min-height: 95px;
        padding: 10px 11px;
        resize: vertical;
        font-family: inherit;
    }

    .form-control:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #64748b;
    }

    .help-text {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 12px;
    }

    .error-text {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
    }

    .invoice-allocation-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .invoice-allocation-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-allocation-table th {
        padding: 11px 12px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .invoice-allocation-table td {
        padding: 12px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 13px;
        vertical-align: middle;
    }

    .invoice-allocation-table tbody tr:last-child td {
        border-bottom: none;
    }

    .invoice-number-link {
        color: #2563eb;
        font-weight: 700;
        text-decoration: none;
    }

    .invoice-number-link:hover {
        text-decoration: underline;
    }

    .invoice-client {
        font-weight: 600;
        color: #1e293b;
    }

    .invoice-balance {
        font-weight: 700;
        color: #172033;
        white-space: nowrap;
    }

    .allocation-input {
        width: 140px;
        height: 36px;
        padding: 0 9px;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        outline: none;
        font-size: 13px;
    }

    .allocation-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
    }

    .no-invoices {
        padding: 30px 15px;
        text-align: center;
        color: #64748b;
        font-size: 13px;
    }

    .payment-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-top: 20px;
    }

    .summary-card {
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #f8fafc;
    }

    .summary-label {
        margin-bottom: 6px;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    .summary-value {
        color: #172033;
        font-size: 20px;
        font-weight: 700;
    }

    .summary-value.unallocated {
        color: #b45309;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 20px 24px;
        background: #f8fafc;
    }

    .cancel-btn,
    .save-btn {
        border-radius: 7px;
        padding: 10px 17px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .cancel-btn {
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #475569;
    }

    .cancel-btn:hover {
        background: #f1f5f9;
    }

    .save-btn {
        border: none;
        background: #2563eb;
        color: #ffffff;
    }

    .save-btn:hover {
        background: #1d4ed8;
    }

    .validation-box {
        margin-bottom: 20px;
        padding: 14px 16px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 13px;
    }

    .validation-box strong {
        display: block;
        margin-bottom: 6px;
    }

    .validation-box ul {
        margin: 0;
        padding-left: 18px;
    }
    @media (max-width: 800px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .payment-summary {
            grid-template-columns: 1fr;
        }

        .invoice-allocation-table {
            min-width: 760px;
        }
    }

    @media (max-width: 600px) {
        .payment-create-title h1 {
            font-size: 23px;
        }

        .form-section {
            padding: 18px;
        }

        .form-actions {
            padding: 16px 18px;
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-btn,
        .save-btn {
            text-align: center;
        }
    }
</style>

<div class="payment-create-page">

    <div class="payment-create-header">

        <div class="payment-create-title">
            <h1>Add Payment</h1>
            <p>Record a client payment and allocate it to invoices.</p>
        </div>

        <a
            href="{{ route('payments.index') }}"
            class="back-btn"
        >
            ← Back to Payments
        </a>

    </div>

    @if($errors->any())
        <div class="validation-box">

            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    <form
        method="POST"
        action="{{ route('payments.store') }}"
        enctype="multipart/form-data"
        id="paymentForm"
    >

        @csrf

        <div class="payment-form-card">

            <div class="form-section">

                <h2 class="section-title">
                    Payment Information
                </h2>

                <div class="form-grid">

                    <div class="form-group">

                        <label class="form-label">
                            Payment No.
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Auto Generated"
                            readonly
                        >

                        <div class="help-text">
                            Payment number will be generated automatically.
                        </div>

                    </div>

                    <div class="form-group">

                        <label
                            for="client_id"
                            class="form-label"
                        >
                            Client <span class="required">*</span>
                        </label>

                        <select
                            name="client_id"
                            id="client_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Client
                            </option>

                            @foreach($clients as $client)

                                <option
                                    value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->client_name }}
                                </option>

                            @endforeach

                        </select>

                        @error('client_id')
                            <div class="error-text">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label
                            for="payment_date"
                            class="form-label"
                        >
                            Payment Date <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            id="payment_date"
                            class="form-control"
                            value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                            required
                        >

                        @error('payment_date')
                            <div class="error-text">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label
                            for="amount"
                            class="form-label"
                        >
                            Payment Amount <span class="required">*</span>
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            class="form-control"
                            value="{{ old('amount') }}"
                            min="0.01"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                        @error('amount')
                            <div class="error-text">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label
                            for="payment_method"
                            class="form-label"
                        >
                            Payment Method <span class="required">*</span>
                        </label>

                        <select
                            name="payment_method"
                            id="payment_method"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Method
                            </option>

                            <option
                                value="Bank"
                                {{ old('payment_method') === 'Bank' ? 'selected' : '' }}
                            >
                                Bank
                            </option>

                            <option
                                value="Cash"
                                {{ old('payment_method') === 'Cash' ? 'selected' : '' }}
                            >
                                Cash
                            </option>

                            <option
                                value="Cheque"
                                {{ old('payment_method') === 'Cheque' ? 'selected' : '' }}
                            >
                                Cheque
                            </option>

                            <option
                                value="Other"
                                {{ old('payment_method') === 'Other' ? 'selected' : '' }}
                            >
                                Other
                            </option>

                        </select>

                        @error('payment_method')
                            <div class="error-text">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label
                            for="reference"
                            class="form-label"
                        >
                            Reference
                        </label>

                        <input
                            type="text"
                            name="reference"
                            id="reference"
                            class="form-control"
                            value="{{ old('reference') }}"
                            maxlength="255"
                            placeholder="Cheque no., bank reference, etc."
                        >

                        <div class="help-text">
                            Optional payment reference.
                        </div>

                        @error('reference')
                            <div class="error-text">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="form-section">

                <h2 class="section-title">
                    Invoice Allocation
                </h2>

                <p class="section-description">
                    Allocate this payment against one or more outstanding invoices.
                    Leave all allocations empty if the payment is unallocated.
                </p>

                @if($invoices->count())

                    <div class="invoice-allocation-table-wrapper">

                        <table class="invoice-allocation-table">

                            <thead>
                                <tr>
                                    <th style="width: 20%;">Invoice No.</th>
                                    <th style="width: 28%;">Client</th>
                                    <th style="width: 20%;">Due Date</th>
                                    <th style="width: 17%;">Outstanding</th>
                                    <th style="width: 15%;">Allocate</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($invoices as $invoice)

                                    <tr
                                        class="invoice-row"
                                        data-client-id="{{ $invoice->client_id }}"
                                    >

                                        <td>

                                            <a
                                                href="{{ route('invoices.show', $invoice) }}"
                                                target="_blank"
                                                class="invoice-number-link"
                                            >
                                                {{ $invoice->invoice_no ?? $invoice->invoice_number }}
                                            </a>

                                            <input
                                                type="hidden"
                                                name="allocations[{{ $invoice->id }}][invoice_id]"
                                                value="{{ $invoice->id }}"
                                            >

                                        </td>

                                        <td>
                                            <span class="invoice-client">
                                                {{ $invoice->client->client_name ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $invoice->due_date
                                                ? $invoice->due_date->format('d M Y')
                                                : 'N/A'
                                            }}
                                        </td>

                                        <td>
                                            <span class="invoice-balance">
                                                {{ number_format((float) $invoice->balance, 2) }}
                                            </span>
                                        </td>

                                        <td>

                                            <input
                                                type="number"
                                                name="allocations[{{ $invoice->id }}][allocated_amount]"
                                                class="allocation-input"
                                                min="0"
                                                max="{{ (float) $invoice->balance }}"
                                                step="0.01"
                                                value="{{ old('allocations.' . $invoice->id . '.allocated_amount') }}"
                                                data-balance="{{ (float) $invoice->balance }}"
                                                placeholder="0.00"
                                            >

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="no-invoices">
                        No outstanding invoices are available for allocation.
                    </div>

                @endif

                <div class="payment-summary">

                    <div class="summary-card">

                        <div class="summary-label">
                            Payment Amount
                        </div>

                        <div
                            class="summary-value"
                            id="summaryPaymentAmount"
                        >
                            0.00
                        </div>

                    </div>

                    <div class="summary-card">

                        <div class="summary-label">
                            Total Allocated
                        </div>

                        <div
                            class="summary-value"
                            id="summaryAllocated"
                        >
                            0.00
                        </div>

                    </div>

                    <div class="summary-card">

                        <div class="summary-label">
                            Unallocated Amount
                        </div>

                        <div
                            class="summary-value unallocated"
                            id="summaryUnallocated"
                        >
                            0.00
                        </div>

                    </div>

                </div>

            </div>
            <div class="form-section">

                <h2 class="section-title">
                    Additional Information
                </h2>

                <div class="form-grid">

                    <div class="form-group full-width">

                        <label
                            for="notes"
                            class="form-label"
                        >
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            class="form-textarea"
                            placeholder="Add any payment notes..."
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <div class="error-text">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label
                            for="attachment"
                            class="form-label"
                        >
                            Attachment
                        </label>

                        <input
                            type="file"
                            name="attachment"
                            id="attachment"
                            class="form-control"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                        >

                        <div class="help-text">
                            Optional. Maximum file size: 5 MB.
                        </div>

                        @error('attachment')
                            <div class="error-text">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('payments.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Save Payment
                </button>

            </div>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const clientSelect =
            document.getElementById('client_id');

        const amountInput =
            document.getElementById('amount');

        const summaryPaymentAmount =
            document.getElementById('summaryPaymentAmount');

        const summaryAllocated =
            document.getElementById('summaryAllocated');

        const summaryUnallocated =
            document.getElementById('summaryUnallocated');

        const invoiceRows =
            document.querySelectorAll('.invoice-row');

        const allocationInputs =
            document.querySelectorAll('.allocation-input');

        function calculateTotals() {

            const paymentAmount =
                parseFloat(amountInput?.value || 0);

            let allocated = 0;

            allocationInputs.forEach(function (input) {

                const row =
                    input.closest('.invoice-row');

                if (row && row.style.display === 'none') {
                    return;
                }

                const value =
                    parseFloat(input.value || 0);

                const balance =
                    parseFloat(input.dataset.balance || 0);

                if (value > balance) {
                    input.value = balance.toFixed(2);
                }

                if (value > 0) {
                    allocated += Math.min(value, balance);
                }

            });

            let unallocated =
                paymentAmount - allocated;

            if (unallocated < 0) {
                unallocated = 0;
            }

            summaryPaymentAmount.textContent =
                paymentAmount.toFixed(2);

            summaryAllocated.textContent =
                allocated.toFixed(2);

            summaryUnallocated.textContent =
                unallocated.toFixed(2);
        }

        function updateInvoiceVisibility() {

            if (!clientSelect) {
                return;
            }

            const selectedClient =
                clientSelect.value;

            invoiceRows.forEach(function (row) {

                const rowClientId =
                    row.getAttribute('data-client-id');

                if (
                    !selectedClient ||
                    rowClientId === selectedClient
                ) {

                    row.style.display = '';

                } else {

                    row.style.display = 'none';

                    const input =
                        row.querySelector('.allocation-input');

                    if (input) {
                        input.value = '';
                    }

                }

            });

            calculateTotals();
        }

        if (clientSelect) {

            clientSelect.addEventListener(
                'change',
                updateInvoiceVisibility
            );

        }

        if (amountInput) {

            amountInput.addEventListener(
                'input',
                calculateTotals
            );

        }

        allocationInputs.forEach(function (input) {

            input.addEventListener(
                'input',
                function () {

                    const balance =
                        parseFloat(
                            this.dataset.balance || 0
                        );

                    const value =
                        parseFloat(this.value || 0);

                    if (value > balance) {
                        this.value =
                            balance.toFixed(2);
                    }

                    calculateTotals();

                }
            );

        });

        updateInvoiceVisibility();
        calculateTotals();

    });
</script>

@endsection