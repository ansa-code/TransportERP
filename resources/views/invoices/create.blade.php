@extends('layouts.app')

@section('content')

<div class="invoice-create-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h1>Create Invoice</h1>

            <p>
                Create a new client invoice and calculate billing totals.
            </p>
        </div>

        <a
            href="{{ route('invoices.index') }}"
            class="back-btn"
        >
            ← Back to Invoices
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="error-box">

            <strong>
                Please fix the following errors:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('invoices.store') }}"
        method="POST"
        class="invoice-form"
    >

        @csrf


        {{-- INVOICE INFORMATION --}}
        <div class="form-card">

            <div class="card-heading">

                <div>
                    <h2>Invoice Information</h2>

                    <p>
                        Basic invoice and client billing details.
                    </p>
                </div>

            </div>


            <div class="form-grid">

                {{-- INVOICE NUMBER --}}
                <div class="form-group">

                    <label>
                        Invoice No.
                    </label>

                    <input
                        type="text"
                        value="Auto Generated"
                        class="readonly-input"
                        readonly
                    >

                    <small>
                        Invoice number will be generated automatically.
                    </small>

                </div>


                {{-- CLIENT --}}
                <div class="form-group">

                    <label for="client_id">
                        Client <span>*</span>
                    </label>

                    <select
                        name="client_id"
                        id="client_id"
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

                </div>


                {{-- ASSIGNMENT --}}
                <div class="form-group">

                    <label for="assignment_id">
                        Assignment
                    </label>

                    <select
                        name="assignment_id"
                        id="assignment_id"
                    >

                        <option value="">
                            Select Assignment
                        </option>

                        @foreach($assignments as $assignment)

                            <option
                                value="{{ $assignment->id }}"
                                {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}
                            >
                                {{ $assignment->id }}

                                @if($assignment->client)
                                    — {{ $assignment->client->client_name }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    <small>
                        Optional source reference.
                    </small>

                </div>


                {{-- SOURCE --}}
                <div class="form-group">

                    <label for="source">
                        Source
                    </label>

                    <select
                        name="source"
                        id="source"
                    >

                        <option value="">
                            Select Source
                        </option>

                        <option
                            value="Assignment"
                            {{ old('source') === 'Assignment' ? 'selected' : '' }}
                        >
                            Assignment
                        </option>

                        <option
                            value="Trips"
                            {{ old('source') === 'Trips' ? 'selected' : '' }}
                        >
                            Trips
                        </option>

                        <option
                            value="Manual"
                            {{ old('source') === 'Manual' ? 'selected' : '' }}
                        >
                            Manual
                        </option>

                    </select>

                </div>


                {{-- INVOICE DATE --}}
                <div class="form-group">

                    <label for="invoice_date">
                        Invoice Date <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="invoice_date"
                        id="invoice_date"
                        value="{{ old('invoice_date', now()->format('Y-m-d')) }}"
                        required
                    >

                </div>


                {{-- DUE DATE --}}
                <div class="form-group">

                    <label for="due_date">
                        Due Date <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        id="due_date"
                        value="{{ old('due_date') }}"
                        required
                    >

                    <small>
                        Calculated from invoice date and credit terms.
                    </small>

                </div>

            </div>

        </div>


        {{-- BILLING PERIOD --}}
        <div class="form-card">

            <div class="card-heading">

                <div>
                    <h2>Billing Period</h2>

                    <p>
                        Define the period covered by this invoice.
                    </p>
                </div>

            </div>


            <div class="form-grid">

                {{-- BILLING START --}}
                <div class="form-group">

                    <label for="billing_start">
                        Billing Start <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="billing_start"
                        id="billing_start"
                        value="{{ old('billing_start') }}"
                        required
                    >

                </div>


                {{-- BILLING END --}}
                <div class="form-group">

                    <label for="billing_end">
                        Billing End <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="billing_end"
                        id="billing_end"
                        value="{{ old('billing_end') }}"
                        required
                    >

                </div>

            </div>

        </div>


        {{-- BILLING AMOUNTS --}}
        <div class="form-card">

            <div class="card-heading">

                <div>
                    <h2>Billing Amounts</h2>

                    <p>
                        Enter subtotal and applicable recoveries.
                    </p>
                </div>

            </div>


            <div class="form-grid">

                {{-- SUBTOTAL --}}
                <div class="form-group">

                    <label for="subtotal">
                        Subtotal (AED) <span>*</span>
                    </label>

                    <input
                        type="number"
                        name="subtotal"
                        id="subtotal"
                        value="{{ old('subtotal', '0.00') }}"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>


                {{-- VAT --}}
                <div class="form-group">

                    <label for="vat_percent">
                        VAT % <span>*</span>
                    </label>

                    <input
                        type="number"
                        name="vat_percent"
                        id="vat_percent"
                        value="{{ old('vat_percent', '0.00') }}"
                        min="0"
                        max="100"
                        step="0.01"
                        required
                    >

                    <small>
                        VAT amount will be calculated automatically.
                    </small>

                </div>

            </div>


            {{-- FUEL RECOVERY --}}
            <div class="fuel-recovery-section">

                <div class="section-heading">

                    <div>
                        <h3>Fuel Recovery</h3>

                        <p>
                            Select reimbursable fuel entries to recover on this invoice.
                        </p>
                    </div>

                </div>


                <div class="fuel-recovery-table-wrap">

                    <table class="fuel-recovery-table">

                        <thead>

                            <tr>
                                <th>Fuel Entry</th>
                                <th>Vehicle</th>
                                <th>Client</th>
                                <th>Reimbursement</th>
                                <th>Recover</th>
                            </tr>

                        </thead>

                        <tbody id="fuel-recovery-body">

                            @forelse($fuels as $fuel)

                                @php
                                    $recoveredAmount = (float) $fuel->recoveries()
                                        ->sum('recovery_amount');

                                    $remainingAmount = max(
                                        (float) $fuel->reimbursement_amount - $recoveredAmount,
                                        0
                                    );
                                @endphp

                                @if($remainingAmount > 0)

                                    <tr
                                        class="fuel-row"
                                        data-fuel-id="{{ $fuel->id }}"
                                        data-remaining="{{ number_format($remainingAmount, 2, '.', '') }}"
                                    >

                                        <td>
                                            <strong>
                                                {{ $fuel->fuel_entry_no }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $fuel->vehicle->plate_number ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $fuel->client->client_name ?? '—' }}
                                        </td>

                                        <td>
                                            AED {{ number_format($remainingAmount, 2) }}
                                        </td>

                                        <td>

                                            <div class="recovery-input-wrap">

                                                <input
                                                    type="checkbox"
                                                    class="fuel-checkbox"
                                                    data-fuel-id="{{ $fuel->id }}"
                                                >

                                                <input
                                                    type="number"
                                                    class="fuel-amount-input"
                                                    data-fuel-id="{{ $fuel->id }}"
                                                    value="0.00"
                                                    min="0"
                                                    max="{{ number_format($remainingAmount, 2, '.', '') }}"
                                                    step="0.01"
                                                    disabled
                                                >

                                            </div>

                                        </td>

                                    </tr>

                                @endif

                            @empty

                                <tr>
                                    <td
                                        colspan="5"
                                        class="empty-fuel-message"
                                    >
                                        No outstanding reimbursable fuel entries available.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="fuel-recovery-summary">

                    <span>
                        Selected Fuel Recovery
                    </span>

                    <strong id="fuel-recovery-total">
                        AED 0.00
                    </strong>

                </div>

            </div>


            {{-- HIDDEN FUEL RECOVERY INPUTS --}}
            <div id="fuel-recovery-hidden-inputs"></div>


            {{-- OTHER REIMBURSEMENT --}}
            <div class="form-grid">

                <div class="form-group">

                    <label for="other_reimbursement">
                        Other Reimbursement (AED) <span>*</span>
                    </label>

                    <input
                        type="number"
                        name="other_reimbursement"
                        id="other_reimbursement"
                        value="{{ old('other_reimbursement', '0.00') }}"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>

            </div>
            {{-- CALCULATION PREVIEW --}}
            <div class="calculation-box">

                <div class="calculation-row">
                    <span>Subtotal</span>
                    <strong id="preview-subtotal">AED 0.00</strong>
                </div>

                <div class="calculation-row">
                    <span>VAT Amount</span>
                    <strong id="preview-vat">AED 0.00</strong>
                </div>

                <div class="calculation-row">
                    <span>Fuel Recovery</span>
                    <strong id="preview-fuel">AED 0.00</strong>
                </div>

                <div class="calculation-row">
                    <span>Other Reimbursement</span>
                    <strong id="preview-other">AED 0.00</strong>
                </div>

                <div class="calculation-total">
                    <span>Total Amount</span>
                    <strong id="preview-total">AED 0.00</strong>
                </div>

            </div>

        </div>


        {{-- STATUS & NOTES --}}
        <div class="form-card">

            <div class="card-heading">

                <div>
                    <h2>Status & Notes</h2>

                    <p>
                        Set the initial invoice status and add internal notes.
                    </p>
                </div>

            </div>

            <div class="form-grid">

                {{-- STATUS --}}
                <div class="form-group">

                    <label for="status">
                        Status <span>*</span>
                    </label>

                    <select
                        name="status"
                        id="status"
                        required
                    >

                        <option
                            value="Draft"
                            {{ old('status', 'Draft') === 'Draft' ? 'selected' : '' }}
                        >
                            Draft
                        </option>

                        <option
                            value="Issued"
                            {{ old('status') === 'Issued' ? 'selected' : '' }}
                        >
                            Issued
                        </option>

                    </select>

                </div>


                {{-- NOTES --}}
                <div class="form-group full-width">

                    <label for="notes">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        placeholder="Enter invoice notes..."
                    >{{ old('notes') }}</textarea>

                </div>

            </div>

        </div>


        {{-- FORM ACTIONS --}}
        <div class="form-actions">

            <a
                href="{{ route('invoices.index') }}"
                class="cancel-btn"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="save-btn"
            >
                Create Invoice
            </button>

        </div>

    </form>

</div>


<style>

    .invoice-create-page {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header h1 {
        margin: 0;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 7px;
        background: #eef4ff;
        color: #193b8f;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #d7e3ff;
    }

    .back-btn:hover {
        background: #e0ebff;
    }

    .error-box {
        margin-bottom: 20px;
        padding: 14px 18px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 13px;
    }

    .error-box ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .card-heading {
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #eef0f4;
    }

    .card-heading h2 {
        margin: 0;
        color: #101d42;
        font-size: 18px;
        font-weight: 700;
    }

    .card-heading p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
    }

    .form-group label span {
        color: #dc2626;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
        color: #1e293b;
        font-size: 13px;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .form-group input,
    .form-group select {
        height: 38px;
        padding: 0 11px;
    }

    .form-group textarea {
        padding: 10px 11px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #94a3b8;
        font-size: 11px;
    }

    .readonly-input {
        background: #f8fafc !important;
        color: #64748b !important;
    }

    .fuel-recovery-section {
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid #eef0f4;
    }

    .section-heading {
        margin-bottom: 14px;
    }

    .section-heading h3 {
        margin: 0;
        color: #101d42;
        font-size: 15px;
        font-weight: 700;
    }

    .section-heading p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .fuel-recovery-table-wrap {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .fuel-recovery-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .fuel-recovery-table th {
        padding: 11px 12px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #475569;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
    }

    .fuel-recovery-table td {
        padding: 11px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 12px;
        vertical-align: middle;
    }

    .fuel-recovery-table tbody tr:last-child td {
        border-bottom: none;
    }

    .fuel-recovery-table strong {
        color: #1e293b;
    }

    .recovery-input-wrap {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .fuel-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .fuel-amount-input {
        width: 110px;
        height: 34px;
        padding: 0 8px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 12px;
    }

    .fuel-amount-input:disabled {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .empty-fuel-message {
        padding: 22px !important;
        text-align: center;
        color: #94a3b8 !important;
    }

    .fuel-recovery-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
        padding: 11px 14px;
        border-radius: 7px;
        background: #f0f6ff;
        color: #475569;
        font-size: 13px;
    }

    .fuel-recovery-summary strong {
        color: #193b8f;
        font-size: 14px;
    }

    .calculation-box {
        margin-top: 22px;
        padding: 16px 18px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .calculation-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 7px 0;
        color: #64748b;
        font-size: 13px;
    }

    .calculation-row strong {
        color: #334155;
        font-weight: 600;
    }

    .calculation-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-top: 8px;
        padding-top: 13px;
        border-top: 1px solid #cbd5e1;
        color: #101d42;
        font-size: 15px;
        font-weight: 700;
    }

    .calculation-total strong {
        color: #193b8f;
        font-size: 17px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 8px;
    }

    .cancel-btn,
    .save-btn {
        min-height: 38px;
        padding: 0 18px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .save-btn {
        border: none;
        background: #2563eb;
        color: #ffffff;
    }

    .save-btn:hover {
        background: #1d4ed8;
    }

    @media (max-width: 768px) {

        .invoice-create-page {
            padding: 0 12px 30px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .back-btn {
            width: 100%;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-card {
            padding: 16px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }

    }

</style>


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const subtotalInput =
            document.getElementById('subtotal');

        const vatPercentInput =
            document.getElementById('vat_percent');

        const otherReimbursementInput =
            document.getElementById('other_reimbursement');

        const fuelTotalDisplay =
            document.getElementById('fuel-recovery-total');

        const hiddenInputs =
            document.getElementById('fuel-recovery-hidden-inputs');

        const previewSubtotal =
            document.getElementById('preview-subtotal');

        const previewVat =
            document.getElementById('preview-vat');

        const previewFuel =
            document.getElementById('preview-fuel');

        const previewOther =
            document.getElementById('preview-other');

        const previewTotal =
            document.getElementById('preview-total');


        function money(value) {

            return 'AED ' + Number(value || 0).toLocaleString(
                'en-AE',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

        }


        function getFuelRecoveryTotal() {

            let total = 0;

            document
                .querySelectorAll('.fuel-amount-input')
                .forEach(function (input) {

                    if (!input.disabled) {

                        let value =
                            parseFloat(input.value) || 0;

                        let max =
                            parseFloat(input.max) || 0;

                        if (value > max) {
                            value = max;
                            input.value = max.toFixed(2);
                        }

                        if (value < 0) {
                            value = 0;
                            input.value = '0.00';
                        }

                        total += value;

                    }

                });

            return total;

        }


       function rebuildFuelRecoveryInputs() {

    hiddenInputs.innerHTML = '';

    let index = 0;

    document
        .querySelectorAll('.fuel-amount-input')
        .forEach(function (input) {

            if (input.disabled) {
                return;
            }

            const amount =
                parseFloat(input.value) || 0;

            if (amount <= 0) {
                return;
            }

            const fuelId =
                input.dataset.fuelId;


            const fuelIdInput =
                document.createElement('input');

            fuelIdInput.type = 'hidden';

            fuelIdInput.name =
                'fuel_recoveries[' +
                index +
                '][fuel_id]';

            fuelIdInput.value =
                fuelId;


            const amountInput =
                document.createElement('input');

            amountInput.type = 'hidden';

            amountInput.name =
                'fuel_recoveries[' +
                index +
                '][amount]';

            amountInput.value =
                amount.toFixed(2);


            hiddenInputs.appendChild(
                fuelIdInput
            );

            hiddenInputs.appendChild(
                amountInput
            );

            index++;

        });

}


        function calculateTotal() {

            const subtotal =
                parseFloat(subtotalInput.value) || 0;

            const vatPercent =
                parseFloat(vatPercentInput.value) || 0;

            const other =
                parseFloat(otherReimbursementInput.value) || 0;

            const vat =
                subtotal * vatPercent / 100;

            const fuel =
                getFuelRecoveryTotal();

            const total =
                subtotal + vat + fuel + other;


            previewSubtotal.textContent =
                money(subtotal);

            previewVat.textContent =
                money(vat);

            previewFuel.textContent =
                money(fuel);

            previewOther.textContent =
                money(other);

            previewTotal.textContent =
                money(total);

            fuelTotalDisplay.textContent =
                money(fuel);

            rebuildFuelRecoveryInputs();

        }


        document
            .querySelectorAll('.fuel-checkbox')
            .forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {

                    const fuelId =
                        this.dataset.fuelId;

                    const amountInput =
                        document.querySelector(
                            '.fuel-amount-input[data-fuel-id="' +
                            fuelId +
                            '"]'
                        );

                    if (!amountInput) {
                        return;
                    }

                    amountInput.disabled =
                        !this.checked;

                    if (this.checked && !amountInput.value) {
                        amountInput.value = '0.00';
                    }

                    if (!this.checked) {
                        amountInput.value = '0.00';
                    }

                    calculateTotal();

                });

            });


        document
            .querySelectorAll('.fuel-amount-input')
            .forEach(function (input) {

                input.addEventListener('input', calculateTotal);

            });


        [
            subtotalInput,
            vatPercentInput,
            otherReimbursementInput
        ].forEach(function (input) {

            input.addEventListener('input', calculateTotal);

        });


        const invoiceDate =
            document.getElementById('invoice_date');

        const dueDate =
            document.getElementById('due_date');

        const billingStart =
            document.getElementById('billing_start');

        const billingEnd =
            document.getElementById('billing_end');


        function updateDateLimits() {

            if (invoiceDate.value) {

                dueDate.min =
                    invoiceDate.value;

                billingStart.max =
                    invoiceDate.value;

            }

            if (billingStart.value) {

                billingEnd.min =
                    billingStart.value;

            }

        }


        invoiceDate.addEventListener(
            'change',
            updateDateLimits
        );

        billingStart.addEventListener(
            'change',
            updateDateLimits
        );


        updateDateLimits();

        calculateTotal();

    });

</script>

@endsection