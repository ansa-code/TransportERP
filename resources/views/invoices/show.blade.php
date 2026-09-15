@extends('layouts.app')

@section('content')

<div class="invoice-show-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h1>Invoice Details</h1>

            <p>
                View complete invoice, billing and payment information.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('invoices.index') }}"
                class="back-btn"
            >
                ← Back
            </a>

            <a
                href="{{ route('invoices.edit', $invoice->id) }}"
                class="edit-header-btn"
            >
                Edit Invoice
            </a>

        </div>

    </div>


    {{-- INVOICE HERO --}}
    <div class="invoice-hero">

        <div class="hero-top">

            <div class="hero-left">

                <div class="invoice-icon">
                    🧾
                </div>

                <div>

                    <span class="hero-label">
                        INVOICE
                    </span>

                    <h2>
                        {{ $invoice->invoice_no }}
                    </h2>

                    <p>
                        {{ $invoice->client->client_name ?? 'N/A' }}
                    </p>

                </div>

            </div>


            @php

                $displayStatus = $invoice->status;

                $statusClass = match ($invoice->status) {

                    'Draft' => 'hero-status-draft',

                    'Issued' => 'hero-status-issued',

                    'Partial' => 'hero-status-partial',

                    'Paid' => 'hero-status-paid',

                    'Overdue' => 'hero-status-overdue',

                    'Cancelled' => 'hero-status-cancelled',

                    default => 'hero-status-draft',

                };


                $isOverdue =
                    $invoice->status !== 'Cancelled'
                    && $invoice->status !== 'Paid'
                    && (float) $invoice->balance > 0
                    && $invoice->due_date
                    && $invoice->due_date->isPast();


                if ($isOverdue) {

                    $displayStatus = 'Overdue';

                    $statusClass = 'hero-status-overdue';

                }

            @endphp


            <span class="hero-status {{ $statusClass }}">
                {{ $displayStatus }}
            </span>

        </div>


        {{-- HERO SUMMARY --}}
        <div class="hero-summary-grid">

            <div class="hero-summary-card">

                <span>
                    Client
                </span>

                <strong>
                    {{ $invoice->client->client_name ?? 'N/A' }}
                </strong>

            </div>


            <div class="hero-summary-card">

                <span>
                    Invoice Date
                </span>

                <strong>
                    {{ $invoice->invoice_date?->format('d M Y') ?? '—' }}
                </strong>

            </div>


            <div class="hero-summary-card">

                <span>
                    Due Date
                </span>

                <strong class="{{ $isOverdue ? 'hero-overdue-text' : '' }}">
                    {{ $invoice->due_date?->format('d M Y') ?? '—' }}
                </strong>

            </div>


            <div class="hero-summary-card">

                <span>
                    Total Amount
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->total_amount, 2) }}
                </strong>

            </div>

        </div>

    </div>


    {{-- ACTION BAR --}}
    <div class="action-bar">

        <div>

            <strong>
                Invoice Actions
            </strong>

            <span>
                Print, download or email this invoice.
            </span>

        </div>


        <div class="invoice-actions">

            <a
                href="{{ route('invoices.pdf', $invoice->id) }}"
                target="_blank"
                class="print-btn"
            >
                📄 PDF
            </a>

            <button
                type="button"
                class="print-btn"
                onclick="window.print()"
            >
                🖨️ Print Invoice
            </button>

            <button
                type="button"
                class="email-btn"
                onclick="openEmailModal()"
            >
                📧 Email Invoice
            </button>

        </div>

    </div>


    {{-- EMAIL MODAL --}}
    <div
        id="emailInvoiceModal"
        class="email-modal-overlay"
        onclick="closeEmailModal(event)"
    >

        <div
            class="email-modal"
            onclick="event.stopPropagation()"
        >

            <div class="email-modal-header">

                <div>

                    <span class="email-modal-label">
                        INVOICE EMAIL
                    </span>

                    <h2>
                        Email Invoice
                    </h2>

                    <p>
                        Send {{ $invoice->invoice_no }} as a PDF attachment.
                    </p>

                </div>


                <button
                    type="button"
                    class="email-modal-close"
                    onclick="closeEmailModal()"
                >
                    ×
                </button>

            </div>


            <form
                action="{{ route('invoices.email', $invoice->id) }}"
                method="POST"
                class="email-form"
            >

                @csrf

                <div class="email-form-group">

                    <label for="recipient_email">
                        Recipient Email
                    </label>

                    <input
                        type="email"
                        id="recipient_email"
                        name="recipient_email"
                        placeholder="client@example.com"
                        required
                        autocomplete="email"
                    >

                </div>


                <div class="email-form-group">

                    <label for="message">
                        Message
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        placeholder="Enter a message for the client..."
                    >Dear Client,

Please find attached invoice {{ $invoice->invoice_no }}.

Thank you,
Al Shaqra Transport</textarea>

                </div>


                <div class="email-form-info">

                    <span>
                        📎
                    </span>

                    <div>

                        <strong>
                            PDF Attachment
                        </strong>

                        <p>
                            The invoice PDF will be attached automatically.
                        </p>

                    </div>

                </div>


                <div class="email-modal-actions">

                    <button
                        type="button"
                        class="email-cancel-btn"
                        onclick="closeEmailModal()"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="email-send-btn"
                    >
                        📧 Send Invoice
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- BILLING PERIOD --}}
    <div class="detail-card">

        <div class="detail-card-header">

            <div>

                <h2>
                    Billing Period
                </h2>

                <p>
                    Period covered by this invoice.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            <div class="detail-item">

                <span>
                    Billing Start
                </span>

                <strong>
                    {{ $invoice->billing_start?->format('d M Y') ?? '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Billing End
                </span>

                <strong>
                    {{ $invoice->billing_end?->format('d M Y') ?? '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Source
                </span>

                <strong>
                    {{ $invoice->source ?: 'Manual' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Assignment
                </span>

                <strong>

                    @if($invoice->assignment)

                        Assignment #{{ $invoice->assignment->id }}

                    @else

                        —

                    @endif

                </strong>

            </div>

        </div>

    </div>


    {{-- AMOUNT BREAKDOWN --}}
    <div class="detail-card">

        <div class="detail-card-header">

            <div>

                <h2>
                    Amount Breakdown
                </h2>

                <p>
                    Complete calculation of invoice amount.
                </p>

            </div>

        </div>


        <div class="amount-box">

            <div class="amount-row">

                <span>
                    Subtotal
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->subtotal, 2) }}
                </strong>

            </div>


            <div class="amount-row">

                <span>
                    VAT
                    ({{ number_format((float) $invoice->vat_percent, 2) }}%)
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->vat_amount, 2) }}
                </strong>

            </div>


            <div class="amount-row">

                <span>
                    Fuel Reimbursement
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->fuel_reimbursement, 2) }}
                </strong>

            </div>


            <div class="amount-row">

                <span>
                    Other Reimbursement
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->other_reimbursement, 2) }}
                </strong>

            </div>


            <div class="amount-total">

                <span>
                    Total Amount
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->total_amount, 2) }}
                </strong>

            </div>

        </div>

    </div>
    {{-- PAYMENT & BALANCE --}}
    <div class="detail-card">

        <div class="detail-card-header">

            <div>

                <h2>
                    Payment Summary
                </h2>

                <p>
                    Current payment position of this invoice.
                </p>

            </div>

        </div>


        <div class="payment-summary-grid">

            {{-- TOTAL --}}
            <div class="payment-box total-box">

                <span>
                    Total Amount
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->total_amount, 2) }}
                </strong>

            </div>


            {{-- PAID --}}
            <div class="payment-box paid-box">

                <span>
                    Paid Amount
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->paid_amount, 2) }}
                </strong>

            </div>


            {{-- BALANCE --}}
            <div class="payment-box balance-box">

                <span>
                    Outstanding Balance
                </span>

                <strong>
                    AED {{ number_format((float) $invoice->balance, 2) }}
                </strong>

            </div>

        </div>

    </div>


    {{-- INVOICE DETAILS --}}
    <div class="detail-card">

        <div class="detail-card-header">

            <div>

                <h2>
                    Invoice Details
                </h2>

                <p>
                    General information and invoice status.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            <div class="detail-item">

                <span>
                    Invoice No.
                </span>

                <strong>
                    {{ $invoice->invoice_no }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Invoice Date
                </span>

                <strong>
                    {{ $invoice->invoice_date?->format('d M Y') ?? '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Due Date
                </span>

                <strong class="{{ $isOverdue ? 'overdue-detail' : '' }}">
                    {{ $invoice->due_date?->format('d M Y') ?? '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Status
                </span>

                <strong>
                    <span class="detail-status {{ $statusClass }}">
                        {{ $displayStatus }}
                    </span>
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Source
                </span>

                <strong>
                    {{ $invoice->source ?: 'Manual' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Client
                </span>

                <strong>
                    {{ $invoice->client->client_name ?? 'N/A' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- NOTES --}}
    <div class="detail-card">

        <div class="detail-card-header">

            <div>

                <h2>
                    Notes
                </h2>

                <p>
                    Additional information attached to this invoice.
                </p>

            </div>

        </div>


        <div class="notes-area">

            @if($invoice->notes)

                <p>
                    {{ $invoice->notes }}
                </p>

            @else

                <span class="empty-notes">
                    No notes added for this invoice.
                </span>

            @endif

        </div>

    </div>


    {{-- FOOTER ACTIONS --}}
    <div class="footer-actions">

        <a
            href="{{ route('invoices.index') }}"
            class="cancel-btn"
        >
            ← Back to Invoices
        </a>


        <a
            href="{{ route('invoices.edit', $invoice->id) }}"
            class="edit-btn"
        >
            Edit Invoice
        </a>

    </div>

</div>


<style>

    /* PAGE */

    .invoice-show-page {
        width: 100%;
        max-width: 1150px;

        margin: 0 auto;

        padding: 10px 0 40px;

        min-width: 0;
    }


    /* PAGE HEADER */

    .page-header {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 20px;

        margin-bottom: 20px;
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


    .header-actions {
        display: flex;

        align-items: center;

        gap: 8px;
    }


    /* HEADER BUTTONS */

    .back-btn,
    .edit-header-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-height: 40px;

        padding: 0 15px;

        border-radius: 8px;

        text-decoration: none;

        font-size: 12px;
        font-weight: 800;

        white-space: nowrap;
    }


    .back-btn {
        border: 1px solid #d8dee8;

        background: #ffffff;

        color: #475569;
    }


    .back-btn:hover {
        background: #f8fafc;
    }


    .edit-header-btn {
        background: #2563eb;

        color: #ffffff;
    }


    .edit-header-btn:hover {
        background: #1d4ed8;
    }


    /* HERO */

    .invoice-hero {
        padding: 22px;

        border-radius: 16px;

        background: #101d42;

        box-shadow:
            0 10px 25px rgba(16, 29, 66, .14);

        color: #ffffff;
    }


    .hero-top {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .hero-left {
        display: flex;

        align-items: center;

        gap: 14px;

        min-width: 0;
    }


    .invoice-icon {
        width: 52px;
        height: 52px;

        min-width: 52px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: rgba(255, 255, 255, .12);

        font-size: 24px;
    }


    .hero-label {
        display: block;

        margin-bottom: 4px;

        color: rgba(255, 255, 255, .62);

        font-size: 10px;
        font-weight: 800;

        letter-spacing: 1px;
    }


    .hero-left h2 {
        margin: 0 0 4px;

        color: #ffffff;

        font-size: 23px;
        font-weight: 800;
    }


    .hero-left p {
        margin: 0;

        color: rgba(255, 255, 255, .72);

        font-size: 12px;
    }


    /* HERO STATUS */

    .hero-status {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 7px 12px;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 800;

        white-space: nowrap;
    }


    .hero-status-draft {
        background: rgba(255, 255, 255, .13);

        color: #e2e8f0;
    }


    .hero-status-issued {
        background: #dbeafe;

        color: #1d4ed8;
    }


    .hero-status-partial {
        background: #ffedd5;

        color: #c2410c;
    }


    .hero-status-paid {
        background: #d1fae5;

        color: #047857;
    }


    .hero-status-overdue {
        background: #fee2e2;

        color: #b91c1c;
    }


    .hero-status-cancelled {
        background: #e2e8f0;

        color: #475569;
    }


    /* HERO SUMMARY */

    .hero-summary-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 11px;

        margin-top: 20px;
    }


    .hero-summary-card {
        min-width: 0;

        padding: 13px 14px;

        border: 1px solid rgba(255, 255, 255, .12);

        border-radius: 10px;

        background: rgba(255, 255, 255, .07);
    }


    .hero-summary-card span {
        display: block;

        margin-bottom: 5px;

        color: rgba(255, 255, 255, .58);

        font-size: 10px;
        font-weight: 700;
    }


    .hero-summary-card strong {
        display: block;

        color: #ffffff;

        font-size: 13px;
        font-weight: 800;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }


    .hero-overdue-text {
        color: #fecaca !important;
    }


    /* ACTION BAR */

    .action-bar {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 20px;

        margin: 17px 0;

        padding: 14px 17px;

        border: 1px solid #e7ebf2;

        border-radius: 12px;

        background: #ffffff;

        box-shadow:
            0 4px 14px rgba(16, 29, 66, .04);
    }


    .action-bar strong {
        display: block;

        margin-bottom: 3px;

        color: #101d42;

        font-size: 13px;
        font-weight: 800;
    }


    .action-bar > div:first-child span {
        color: #7a8497;

        font-size: 11px;
    }


    .invoice-actions {
        display: flex;

        align-items: center;

        gap: 7px;
    }


    .print-btn,
    .email-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-height: 34px;

        padding: 0 11px;

        border: none;

        border-radius: 7px;

        text-decoration: none;

        font-size: 10px;
        font-weight: 800;

        cursor: pointer;
    }


    .print-btn {
        background: #101d42;

        color: #ffffff;
    }


    .print-btn:hover {
        background: #193b8f;
    }


    .email-btn {
        background: #eaf2ff;

        color: #1d4ed8;
    }


    .email-btn:hover {
        background: #dbeafe;
    }


    /* EMAIL MODAL */

    .email-modal-overlay {
        position: fixed;

        inset: 0;

        z-index: 9999;

        display: none;

        align-items: center;
        justify-content: center;

        padding: 20px;

        background: rgba(15, 23, 42, .58);

        backdrop-filter: blur(3px);
    }


    .email-modal-overlay.active {
        display: flex;
    }


    .email-modal {
        width: 100%;

        max-width: 520px;

        max-height: 90vh;

        overflow-y: auto;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 25px 60px rgba(15, 23, 42, .22);
    }


    .email-modal-header {
        display: flex;

        align-items: flex-start;
        justify-content: space-between;

        gap: 20px;

        padding: 20px;

        border-bottom: 1px solid #edf0f5;
    }


    .email-modal-label {
        display: block;

        margin-bottom: 5px;

        color: #2563eb;

        font-size: 9px;
        font-weight: 800;

        letter-spacing: 1px;
    }


    .email-modal-header h2 {
        margin: 0 0 5px;

        color: #101d42;

        font-size: 20px;
        font-weight: 800;
    }


    .email-modal-header p {
        margin: 0;

        color: #7a8497;

        font-size: 11px;
    }


    .email-modal-close {
        width: 32px;
        height: 32px;

        flex-shrink: 0;

        border: none;

        border-radius: 8px;

        background: #f1f5f9;

        color: #475569;

        font-size: 22px;

        line-height: 1;

        cursor: pointer;
    }


    .email-modal-close:hover {
        background: #e2e8f0;
    }


    .email-form {
        padding: 20px;
    }


    .email-form-group {
        margin-bottom: 16px;
    }


    .email-form-group label {
        display: block;

        margin-bottom: 7px;

        color: #334155;

        font-size: 11px;
        font-weight: 800;
    }


    .email-form-group input,
    .email-form-group textarea {
        width: 100%;

        box-sizing: border-box;

        border: 1px solid #d8dee8;

        border-radius: 8px;

        background: #ffffff;

        color: #334155;

        font-family: inherit;

        font-size: 12px;

        outline: none;
    }


    .email-form-group input {
        height: 40px;

        padding: 0 12px;
    }


    .email-form-group textarea {
        min-height: 130px;

        padding: 11px 12px;

        resize: vertical;

        line-height: 1.6;
    }


    .email-form-group input:focus,
    .email-form-group textarea:focus {
        border-color: #2563eb;

        box-shadow:
            0 0 0 3px rgba(37, 99, 235, .10);
    }


    .email-form-info {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-bottom: 18px;

        padding: 11px 12px;

        border: 1px solid #dbeafe;

        border-radius: 9px;

        background: #eff6ff;
    }


    .email-form-info > span {
        font-size: 20px;
    }


    .email-form-info strong {
        display: block;

        margin-bottom: 2px;

        color: #1e3a8a;

        font-size: 11px;
        font-weight: 800;
    }


    .email-form-info p {
        margin: 0;

        color: #64748b;

        font-size: 10px;
    }


    .email-modal-actions {
        display: flex;

        align-items: center;
        justify-content: flex-end;

        gap: 8px;
    }


    .email-cancel-btn,
    .email-send-btn {
        min-height: 38px;

        padding: 0 15px;

        border-radius: 8px;

        font-size: 11px;
        font-weight: 800;

        cursor: pointer;
    }


    .email-cancel-btn {
        border: 1px solid #d8dee8;

        background: #ffffff;

        color: #475569;
    }


    .email-cancel-btn:hover {
        background: #f8fafc;
    }


    .email-send-btn {
        border: none;

        background: #2563eb;

        color: #ffffff;
    }


    .email-send-btn:hover {
        background: #1d4ed8;
    }
    /* DETAIL CARD */

    .detail-card {
        margin-bottom: 17px;

        background: #ffffff;

        border: 1px solid #e7ebf2;

        border-radius: 14px;

        box-shadow:
            0 5px 18px rgba(16, 29, 66, .05);

        overflow: hidden;
    }


    .detail-card-header {
        display: flex;

        align-items: center;

        padding: 17px 20px;

        border-bottom: 1px solid #edf0f5;
    }


    .detail-card-header h2 {
        margin: 0 0 4px;

        color: #101d42;

        font-size: 16px;

        font-weight: 800;
    }


    .detail-card-header p {
        margin: 0;

        color: #7a8497;

        font-size: 11px;
    }


    /* DETAIL GRID */

    .detail-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 0;
    }


    .detail-item {
        min-width: 0;

        padding: 16px 20px;

        border-bottom: 1px solid #eef2f7;
    }


    .detail-item:nth-last-child(-n + 2) {
        border-bottom: none;
    }


    .detail-item:nth-child(odd) {
        border-right: 1px solid #eef2f7;
    }


    .detail-item span:first-child {
        display: block;

        margin-bottom: 5px;

        color: #94a3b8;

        font-size: 10px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .35px;
    }


    .detail-item strong {
        display: block;

        color: #334155;

        font-size: 13px;

        font-weight: 800;

        word-break: break-word;
    }


    .overdue-detail {
        color: #b91c1c !important;
    }


    /* AMOUNT BOX */

    .amount-box {
        margin: 18px 20px;

        padding: 14px 16px;

        border: 1px solid #e5eaf1;

        border-radius: 11px;

        background: #f8fafc;
    }


    .amount-row {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        padding: 9px 0;

        color: #64748b;

        font-size: 12px;
    }


    .amount-row strong {
        color: #334155;

        font-size: 12px;

        font-weight: 800;

        white-space: nowrap;
    }


    .amount-total {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        margin-top: 7px;

        padding-top: 13px;

        border-top: 1px solid #dce3ec;

        color: #101d42;

        font-size: 13px;

        font-weight: 800;
    }


    .amount-total strong {
        color: #101d42;

        font-size: 17px;

        font-weight: 800;

        white-space: nowrap;
    }


    /* PAYMENT SUMMARY */

    .payment-summary-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 13px;

        padding: 18px 20px;
    }


    .payment-box {
        min-width: 0;

        padding: 16px;

        border: 1px solid #e5eaf1;

        border-radius: 11px;

        background: #f8fafc;
    }


    .payment-box span {
        display: block;

        margin-bottom: 6px;

        color: #7a8497;

        font-size: 10px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .3px;
    }


    .payment-box strong {
        display: block;

        font-size: 17px;

        font-weight: 800;

        white-space: nowrap;
    }


    .total-box strong {
        color: #101d42;
    }


    .paid-box {
        background: #f0fdf4;

        border-color: #dcfce7;
    }


    .paid-box strong {
        color: #047857;
    }


    .balance-box {
        background: #fff7ed;

        border-color: #fed7aa;
    }


    .balance-box strong {
        color: #c2410c;
    }


    /* DETAIL STATUS */

    .detail-status {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        padding: 5px 9px;

        border-radius: 999px;

        font-size: 10px;

        font-weight: 800;
    }


    .detail-status.hero-status-draft {
        background: #f1f5f9;

        color: #475569;
    }


    .detail-status.hero-status-issued {
        background: #eaf2ff;

        color: #1d4ed8;
    }


    .detail-status.hero-status-partial {
        background: #fff7ed;

        color: #c2410c;
    }


    .detail-status.hero-status-paid {
        background: #ecfdf5;

        color: #047857;
    }


    .detail-status.hero-status-overdue {
        background: #fef2f2;

        color: #b91c1c;
    }


    .detail-status.hero-status-cancelled {
        background: #f1f5f9;

        color: #64748b;
    }


    /* NOTES */

    .notes-area {
        padding: 18px 20px;
    }


    .notes-area p {
        margin: 0;

        color: #475569;

        font-size: 13px;

        line-height: 1.7;

        white-space: pre-wrap;
    }


    .empty-notes {
        color: #94a3b8;

        font-size: 12px;
    }


    /* FOOTER ACTIONS */

    .footer-actions {
        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 8px;

        margin-top: 5px;
    }


    .cancel-btn,
    .edit-btn {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-height: 39px;

        padding: 0 15px;

        border-radius: 8px;

        text-decoration: none;

        font-size: 12px;

        font-weight: 800;
    }


    .cancel-btn {
        border: 1px solid #d8dee8;

        background: #ffffff;

        color: #475569;
    }


    .cancel-btn:hover {
        background: #f8fafc;
    }


    .edit-btn {
        background: #2563eb;

        color: #ffffff;
    }


    .edit-btn:hover {
        background: #1d4ed8;
    }


    /* MOBILE */

    @media (max-width: 850px) {

        .hero-summary-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }


        .payment-summary-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 700px) {

        .invoice-show-page {
            padding-left: 10px;

            padding-right: 10px;
        }


        .page-header {
            flex-direction: column;

            align-items: flex-start;
        }


        .header-actions {
            width: 100%;
        }


        .back-btn,
        .edit-header-btn {
            flex: 1;
        }


        .hero-top {
            flex-direction: column;

            align-items: flex-start;
        }


        .hero-status {
            align-self: flex-start;
        }


        .hero-summary-grid {
            grid-template-columns: 1fr;
        }


        .action-bar {
            flex-direction: column;

            align-items: flex-start;
        }


        .invoice-actions {
            width: 100%;

            flex-wrap: wrap;
        }


        .print-btn,
        .email-btn {
            flex: 1;
        }


        .detail-grid {
            grid-template-columns: 1fr;
        }


        .detail-item:nth-child(odd) {
            border-right: none;
        }


        .detail-item {
            border-bottom: 1px solid #eef2f7 !important;
        }


        .detail-item:last-child {
            border-bottom: none !important;
        }


        .amount-box {
            margin: 16px;
        }


        .payment-summary-grid {
            padding: 16px;
        }


        .footer-actions {
            flex-direction: column-reverse;

            align-items: stretch;
        }


        .cancel-btn,
        .edit-btn {
            width: 100%;
        }


        .email-modal-overlay {
            padding: 12px;
        }


        .email-modal {
            max-height: 94vh;

            border-radius: 13px;
        }


        .email-modal-header {
            padding: 17px;
        }


        .email-form {
            padding: 17px;
        }


        .email-modal-actions {
            flex-direction: column-reverse;

            align-items: stretch;
        }


        .email-cancel-btn,
        .email-send-btn {
            width: 100%;
        }

    }


    /* PRINT */

    @media print {

        body {
            background: #ffffff !important;
        }


        .page-header,
        .action-bar,
        .footer-actions,
        .email-modal-overlay {
            display: none !important;
        }


        .invoice-show-page {
            max-width: none;

            padding: 0;
        }


        .invoice-hero {
            box-shadow: none;

            break-inside: avoid;
        }


        .detail-card {
            box-shadow: none;

            break-inside: avoid;
        }

    }

</style>


<script>

    function openEmailModal() {

        const modal =
            document.getElementById('emailInvoiceModal');

        if (!modal) {
            return;
        }


        modal.classList.add('active');


        setTimeout(function () {

            const emailInput =
                document.getElementById('recipient_email');

            if (emailInput) {
                emailInput.focus();
            }

        }, 100);

    }


    function closeEmailModal(event) {

        if (
            event
            && event.target
            && event.target.id !== 'emailInvoiceModal'
        ) {
            return;
        }


        const modal =
            document.getElementById('emailInvoiceModal');

        if (!modal) {
            return;
        }


        modal.classList.remove('active');

    }


    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Escape') {
                return;
            }


            const modal =
                document.getElementById('emailInvoiceModal');


            if (
                modal
                && modal.classList.contains('active')
            ) {

                modal.classList.remove('active');

            }

        }
    );

</script>

@endsection