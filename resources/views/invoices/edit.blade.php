@extends('layouts.app')

@section('content')

<div class="invoice-edit-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h1>Edit Invoice</h1>

            <p>
                Update invoice, billing and payment-related details.
            </p>
        </div>

        <a href="{{ route('invoices.index') }}" class="back-btn">
            ← Back to Invoices
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="error-box">

            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <form
        action="{{ route('invoices.update', $invoice->id) }}"
        method="POST"
        class="invoice-form"
    >

        @csrf
        @method('PUT')


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

                    <label>Invoice No.</label>

                    <input
                        type="text"
                        value="{{ $invoice->invoice_no }}"
                        class="readonly-input"
                        readonly
                    >

                    <small>
                        Invoice number cannot be changed.
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
                                {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}
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
                                {{ old('assignment_id', $invoice->assignment_id) == $assignment->id ? 'selected' : '' }}
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
                            {{ old('source', $invoice->source) === 'Assignment' ? 'selected' : '' }}
                        >
                            Assignment
                        </option>

                        <option
                            value="Trips"
                            {{ old('source', $invoice->source) === 'Trips' ? 'selected' : '' }}
                        >
                            Trips
                        </option>

                        <option
                            value="Manual"
                            {{ old('source', $invoice->source) === 'Manual' ? 'selected' : '' }}
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
                        value="{{ old('invoice_date', $invoice->invoice_date?->format('Y-m-d')) }}"
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
                        value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}"
                        required
                    >

                    <small>
                        Due date must be on or after invoice date.
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
                        value="{{ old('billing_start', $invoice->billing_start?->format('Y-m-d')) }}"
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
                        value="{{ old('billing_end', $invoice->billing_end?->format('Y-m-d')) }}"
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
                        Update subtotal, VAT and reimbursable fuel recovery.
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
                        value="{{ old('subtotal', number_format((float) $invoice->subtotal, 2, '.', '')) }}"
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
                        value="{{ old('vat_percent', number_format((float) $invoice->vat_percent, 2, '.', '')) }}"
                        min="0"
                        max="100"
                        step="0.01"
                        required
                    >

                    <small>
                        VAT amount is calculated from subtotal.
                    </small>

                </div>

            </div>


            {{-- FUEL RECOVERY --}}
            <div class="fuel-recovery-section">

                <div class="section-heading">

                    <div>
                        <h3>Fuel Recovery</h3>

                        <p>
                            Select reimbursable fuel entries that should be recovered through this invoice.
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
                                <th>Available</th>
                                <th>Recover</th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse($fuels as $fuel)

                                @php

                                    $currentRecovery = $invoice->fuelRecoveries
                                        ->where('fuel_id', $fuel->id)
                                        ->sum('recovery_amount');

                                    $otherRecovered = $fuel->recoveries
                                        ->where('invoice_id', '!=', $invoice->id)
                                        ->sum('recovery_amount');

                                    $remainingAmount = max(
                                        (float) $fuel->reimbursement_amount
                                        - (float) $otherRecovered,
                                        0
                                    );

                                    $isSelected = (float) $currentRecovery > 0;

                                @endphp


                                @if($remainingAmount > 0)

                                    <tr
                                        class="fuel-row"
                                        data-fuel-id="{{ $fuel->id }}"
                                        data-remaining="{{ number_format($remainingAmount, 2, '.', '') }}"
                                    >

                                        {{-- FUEL ENTRY --}}
                                        <td>

                                            <strong>
                                                {{ $fuel->fuel_entry_no }}
                                            </strong>

                                        </td>


                                        {{-- VEHICLE --}}
                                        <td>
                                            {{ $fuel->vehicle->plate_number ?? '—' }}
                                        </td>


                                        {{-- CLIENT --}}
                                        <td>
                                            {{ $fuel->client->client_name ?? '—' }}
                                        </td>


                                        {{-- AVAILABLE --}}
                                        <td>

                                            AED
                                            {{ number_format($remainingAmount, 2) }}

                                        </td>


                                        {{-- RECOVER --}}
                                        <td>

                                            <div class="recovery-input-wrap">

                                                <input
                                                    type="checkbox"
                                                    class="fuel-checkbox"
                                                    data-fuel-id="{{ $fuel->id }}"
                                                    {{ $isSelected ? 'checked' : '' }}
                                                >


                                                <input
                                                    type="number"
                                                    class="fuel-amount-input"
                                                    data-fuel-id="{{ $fuel->id }}"
                                                    value="{{ number_format((float) $currentRecovery, 2, '.', '') }}"
                                                    min="0"
                                                    max="{{ number_format($remainingAmount, 2, '.', '') }}"
                                                    step="0.01"
                                                    {{ $isSelected ? '' : 'disabled' }}
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
                        value="{{ old('other_reimbursement', number_format((float) $invoice->other_reimbursement, 2, '.', '')) }}"
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

                    <strong id="preview-subtotal">
                        AED 0.00
                    </strong>

                </div>


                <div class="calculation-row">

                    <span>VAT Amount</span>

                    <strong id="preview-vat">
                        AED 0.00
                    </strong>

                </div>


                <div class="calculation-row">

                    <span>Fuel Recovery</span>

                    <strong id="preview-fuel">
                        AED 0.00
                    </strong>

                </div>


                <div class="calculation-row">

                    <span>Other Reimbursement</span>

                    <strong id="preview-other">
                        AED 0.00
                    </strong>

                </div>


                <div class="calculation-total">

                    <span>Total Amount</span>

                    <strong id="preview-total">
                        AED 0.00
                    </strong>

                </div>

            </div>

        </div>
        {{-- PAYMENT SUMMARY --}}
        <div class="form-card">

            <div class="card-heading">

                <div>
                    <h2>Payment Summary</h2>

                    <p>
                        Current payment and outstanding balance.
                    </p>
                </div>

            </div>


            <div class="summary-grid">

                <div class="summary-item">

                    <span>
                        Current Paid Amount
                    </span>

                    <strong>
                        AED {{ number_format((float) $invoice->paid_amount, 2) }}
                    </strong>

                </div>


                <div class="summary-item">

                    <span>
                        Current Balance
                    </span>

                    <strong>
                        AED {{ number_format((float) $invoice->balance, 2) }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- STATUS & NOTES --}}
        <div class="form-card">

            <div class="card-heading">

                <div>
                    <h2>Status & Notes</h2>

                    <p>
                        Update invoice status and relevant notes.
                    </p>
                </div>

            </div>


            <div class="form-grid">

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
                            {{ old('status', $invoice->status) === 'Draft' ? 'selected' : '' }}
                        >
                            Draft
                        </option>

                        <option
                            value="Issued"
                            {{ old('status', $invoice->status) === 'Issued' ? 'selected' : '' }}
                        >
                            Issued
                        </option>

                        <option
                            value="Partial"
                            {{ old('status', $invoice->status) === 'Partial' ? 'selected' : '' }}
                        >
                            Partial
                        </option>

                        <option
                            value="Paid"
                            {{ old('status', $invoice->status) === 'Paid' ? 'selected' : '' }}
                        >
                            Paid
                        </option>

                        <option
                            value="Overdue"
                            {{ old('status', $invoice->status) === 'Overdue' ? 'selected' : '' }}
                        >
                            Overdue
                        </option>

                        <option
                            value="Cancelled"
                            {{ old('status', $invoice->status) === 'Cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                </div>


                <div class="form-group full-width">

                    <label for="notes">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        placeholder="Enter any invoice notes..."
                    >{{ old('notes', $invoice->notes) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
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
                Update Invoice
            </button>

        </div>

    </form>

</div>


<style>

    .invoice-edit-page {
        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
        padding: 10px 0 40px;
        min-width: 0;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #101d42;
        font-size: 28px;
        font-weight: 800;
    }

    .page-header p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 16px;
        border-radius: 9px;
        background: #f1f5f9;
        color: #334155;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: #e2e8f0;
    }

    .error-box {
        margin-bottom: 18px;
        padding: 13px 16px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 12px;
    }

    .error-box strong {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
    }

    .error-box ul {
        margin: 0;
        padding-left: 18px;
    }

    .error-box li {
        margin-bottom: 3px;
    }

    .form-card {
        margin-bottom: 18px;
        background: #ffffff;
        border: 1px solid #e7ebf2;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(16, 29, 66, .05);
        overflow: hidden;
    }

    .card-heading {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .card-heading h2 {
        margin: 0 0 4px;
        color: #101d42;
        font-size: 16px;
        font-weight: 800;
    }

    .card-heading p {
        margin: 0;
        color: #7a8497;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
        padding: 20px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
    }

    .form-group label span {
        color: #dc2626;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d8dee8;
        border-radius: 8px;
        background: #ffffff;
        color: #25324d;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }

    .form-group input,
    .form-group select {
        height: 40px;
        padding: 0 11px;
    }

    .form-group textarea {
        min-height: 100px;
        padding: 11px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #94a3b8;
        font-size: 10px;
        line-height: 1.4;
    }

    .readonly-input {
        background: #f8fafc !important;
        color: #64748b !important;
        cursor: not-allowed;
    }

    /* FUEL RECOVERY */

    .fuel-recovery-section {
        margin: 0 20px 20px;
        padding-top: 20px;
        border-top: 1px solid #edf0f5;
    }

    .section-heading {
        margin-bottom: 14px;
    }

    .section-heading h3 {
        margin: 0 0 4px;
        color: #101d42;
        font-size: 15px;
        font-weight: 800;
    }

    .section-heading p {
        margin: 0;
        color: #7a8497;
        font-size: 12px;
    }

    .fuel-recovery-table-wrap {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
    }

    .fuel-recovery-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
    }

    .fuel-recovery-table th {
        padding: 11px 12px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #475569;
        text-align: left;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
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
        width: 16px !important;
        height: 16px !important;
        cursor: pointer;
    }

    .fuel-amount-input {
        width: 115px !important;
        height: 34px !important;
        padding: 0 8px !important;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 12px !important;
    }

    .fuel-amount-input:disabled {
        background: #f8fafc !important;
        color: #94a3b8 !important;
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
        gap: 15px;
        margin-top: 12px;
        padding: 11px 14px;
        border-radius: 8px;
        background: #f0f6ff;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .fuel-recovery-summary strong {
        color: #193b8f;
        font-size: 14px;
    }

    /* CALCULATION */

    .calculation-box {
        margin: 0 20px 20px;
        padding: 14px 16px;
        border: 1px solid #e5eaf1;
        border-radius: 10px;
        background: #f8fafc;
    }

    .calculation-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 7px 0;
        color: #64748b;
        font-size: 12px;
    }

    .calculation-row strong {
        color: #334155;
        font-size: 12px;
        white-space: nowrap;
    }

    .calculation-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 7px;
        padding-top: 12px;
        border-top: 1px solid #dce3ec;
        color: #101d42;
        font-size: 13px;
        font-weight: 800;
    }

    .calculation-total strong {
        color: #101d42;
        font-size: 16px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* PAYMENT SUMMARY */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
        padding: 20px;
    }

    .summary-item {
        padding: 15px;
        border: 1px solid #e5eaf1;
        border-radius: 10px;
        background: #f8fafc;
    }

    .summary-item span {
        display: block;
        margin-bottom: 6px;
        color: #7a8497;
        font-size: 11px;
        font-weight: 700;
    }

    .summary-item strong {
        display: block;
        color: #101d42;
        font-size: 17px;
        font-weight: 800;
    }

    /* ACTIONS */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 5px;
    }

    .cancel-btn,
    .save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
    }

    .cancel-btn {
        border: 1px solid #d8dee8;
        background: #ffffff;
        color: #475569;
    }

    .cancel-btn:hover {
        background: #f8fafc;
    }

    .save-btn {
        border: none;
        background: #101d42;
        color: #ffffff;
    }

    .save-btn:hover {
        background: #193b8f;
    }

    @media (max-width: 700px) {

        .invoice-edit-page {
            padding-left: 0;
            padding-right: 0;
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
            gap: 15px;
            padding: 16px;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .fuel-recovery-section {
            margin-left: 16px;
            margin-right: 16px;
        }

        .calculation-box {
            margin: 0 16px 16px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
            padding: 16px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
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


        function money(value) {

            return 'AED ' + Number(value || 0).toLocaleString(
                'en-AE',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

        }


        function getFuelTotal() {

            let total = 0;

            document
                .querySelectorAll('.fuel-amount-input')
                .forEach(function (input) {

                    if (input.disabled) {
                        return;
                    }

                    let value =
                        parseFloat(input.value) || 0;

                    const max =
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

                });

            return total;

        }


        function rebuildFuelInputs() {

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


        function calculateInvoice() {

            const subtotal =
                parseFloat(subtotalInput.value) || 0;

            const vatPercent =
                parseFloat(vatPercentInput.value) || 0;

            const other =
                parseFloat(otherReimbursementInput.value) || 0;

            const vat =
                subtotal * vatPercent / 100;

            const fuel =
                getFuelTotal();

            const total =
                subtotal + vat + fuel + other;


            document.getElementById(
                'preview-subtotal'
            ).textContent = money(subtotal);


            document.getElementById(
                'preview-vat'
            ).textContent = money(vat);


            document.getElementById(
                'preview-fuel'
            ).textContent = money(fuel);


            document.getElementById(
                'preview-other'
            ).textContent = money(other);


            document.getElementById(
                'preview-total'
            ).textContent = money(total);


            fuelTotalDisplay.textContent =
                money(fuel);


            rebuildFuelInputs();

        }


        document
            .querySelectorAll('.fuel-checkbox')
            .forEach(function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    function () {

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

                        if (!this.checked) {
                            amountInput.value =
                                '0.00';
                        }

                        calculateInvoice();

                    }
                );

            });


        document
            .querySelectorAll('.fuel-amount-input')
            .forEach(function (input) {

                input.addEventListener(
                    'input',
                    calculateInvoice
                );

            });


        subtotalInput.addEventListener(
            'input',
            calculateInvoice
        );

        vatPercentInput.addEventListener(
            'input',
            calculateInvoice
        );

        otherReimbursementInput.addEventListener(
            'input',
            calculateInvoice
        );


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

        calculateInvoice();

    });

</script>

@endsection