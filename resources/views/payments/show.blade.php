@extends('layouts.app')

@section('content')

<style>
    .payment-show-page {
        width: 100%;
    }

    .payment-hero {
        background: linear-gradient(135deg, #172554, #2563eb);
        border-radius: 14px;
        padding: 24px;
        color: #ffffff;
        margin-bottom: 20px;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.16);
    }

    .payment-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
    }

    .payment-hero-main {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .payment-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .payment-hero h1 {
        margin: 0;
        font-size: 27px;
        font-weight: 700;
    }

    .payment-hero-subtitle {
        margin-top: 5px;
        color: rgba(255, 255, 255, 0.78);
        font-size: 13px;
    }

    .payment-method-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border-radius: 7px;
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
    }

    .payment-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-top: 22px;
    }

    .payment-summary-card {
        padding: 15px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.11);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .payment-summary-label {
        margin-bottom: 6px;
        color: rgba(255, 255, 255, 0.72);
        font-size: 11px;
        font-weight: 600;
    }

    .payment-summary-value {
        color: #ffffff;
        font-size: 19px;
        font-weight: 700;
    }

    .payment-action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .payment-actions-left,
    .payment-actions-right {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 14px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .back-btn {
        background: #ffffff;
        color: #475569;
        border: 1px solid #d1d5db;
    }

    .back-btn:hover {
        background: #f8fafc;
    }

    .edit-btn {
        background: #2563eb;
        color: #ffffff;
    }

    .edit-btn:hover {
        background: #1d4ed8;
    }

    .print-btn {
        background: #0f766e;
        color: #ffffff;
    }

    .print-btn:hover {
        background: #115e59;
    }

    .delete-btn {
        background: #dc2626;
        color: #ffffff;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }

    .payment-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .payment-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .payment-card-title {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .payment-card-body {
        padding: 20px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 25px;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-label {
        margin-bottom: 5px;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .detail-value {
        color: #1e293b;
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }

    .detail-value a {
        color: #2563eb;
        text-decoration: none;
    }

    .detail-value a:hover {
        text-decoration: underline;
    }

    .allocation-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .allocation-table {
        width: 100%;
        border-collapse: collapse;
    }

    .allocation-table th {
        padding: 12px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .allocation-table td {
        padding: 13px 14px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 13px;
        vertical-align: middle;
    }

    .allocation-table tbody tr:last-child td {
        border-bottom: none;
    }

    .allocation-table tbody tr:hover {
        background: #f8fafc;
    }

    .invoice-link {
        color: #2563eb;
        font-weight: 700;
        text-decoration: none;
    }

    .invoice-link:hover {
        text-decoration: underline;
    }

    .allocated-amount {
        color: #15803d;
        font-weight: 700;
        white-space: nowrap;
    }

    .allocation-total-row td {
        background: #f8fafc;
        font-weight: 700;
        border-top: 1px solid #e5e7eb;
    }

    .allocation-total-label {
        text-align: right;
        color: #475569;
    }

    .allocation-total-value {
        color: #172033;
        white-space: nowrap;
    }

    .empty-allocation {
        text-align: center;
        padding: 35px 20px;
        color: #64748b;
    }

    .empty-allocation-icon {
        font-size: 30px;
        margin-bottom: 8px;
    }

    .empty-allocation-title {
        color: #334155;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .empty-allocation-text {
        font-size: 12px;
    }
    .notes-box {
        padding: 15px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .attachment-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f8fafc;
    }

    .attachment-name {
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        word-break: break-all;
    }

    .attachment-btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 6px;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .attachment-btn:hover {
        background: #1d4ed8;
    }

    .no-attachment {
        color: #94a3b8;
        font-size: 13px;
    }

    .bottom-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 0 5px;
    }

    @media (max-width: 900px) {
        .payment-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .payment-hero {
            padding: 20px;
        }

        .payment-hero h1 {
            font-size: 23px;
        }

        .payment-summary-grid {
            grid-template-columns: 1fr;
        }

        .payment-action-bar {
            align-items: stretch;
        }

        .payment-actions-left,
        .payment-actions-right {
            width: 100%;
        }

        .action-btn {
            justify-content: center;
        }

        .attachment-box {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media print {
        body {
            background: #ffffff !important;
        }

        .sidebar,
        .navbar,
        .payment-action-bar,
        .bottom-actions {
            display: none !important;
        }

        .payment-show-page {
            width: 100%;
        }

        .payment-hero {
            box-shadow: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .payment-card {
            box-shadow: none;
            break-inside: avoid;
        }
    }
</style>

<div class="payment-show-page">

    <div class="payment-hero">

        <div class="payment-hero-top">

            <div class="payment-hero-main">

                <div class="payment-icon">
                    💳
                </div>

                <div>

                    <h1>
                        {{ $payment->payment_no }}
                    </h1>

                    <div class="payment-hero-subtitle">
                        Client Payment
                        @if($payment->client)
                            • {{ $payment->client->client_name }}
                        @endif
                    </div>

                </div>

            </div>

            <div class="payment-method-badge">
                {{ $payment->payment_method }}
            </div>

        </div>

        <div class="payment-summary-grid">

            <div class="payment-summary-card">

                <div class="payment-summary-label">
                    Payment Amount
                </div>

                <div class="payment-summary-value">
                    {{ number_format((float) $payment->amount, 2) }}
                </div>

            </div>

            <div class="payment-summary-card">

                <div class="payment-summary-label">
                    Allocated
                </div>

                <div class="payment-summary-value">
                    {{ number_format((float) $payment->allocations->sum('allocated_amount'), 2) }}
                </div>

            </div>

            <div class="payment-summary-card">

                <div class="payment-summary-label">
                    Unallocated
                </div>

                <div class="payment-summary-value">
                    {{ number_format((float) $payment->unallocated_amount, 2) }}
                </div>

            </div>

            <div class="payment-summary-card">

                <div class="payment-summary-label">
                    Invoices
                </div>

                <div class="payment-summary-value">
                    {{ $payment->allocations->count() }}
                </div>

            </div>

        </div>

    </div>

    <div class="payment-action-bar">

        <div class="payment-actions-left">

            <a
                href="{{ route('payments.index') }}"
                class="action-btn back-btn"
            >
                ← Back
            </a>

            <a
                href="{{ route('payments.edit', $payment) }}"
                class="action-btn edit-btn"
            >
                Edit
            </a>

        </div>

        <div class="payment-actions-right">

            <button
                type="button"
                class="action-btn print-btn"
                onclick="window.print()"
            >
                🖨 Print
            </button>

            <form
                method="POST"
                action="{{ route('payments.destroy', $payment) }}"
                onsubmit="return confirm('Are you sure you want to delete this payment?');"
                style="display: inline;"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="action-btn delete-btn"
                >
                    Delete
                </button>

            </form>

        </div>

    </div>

    <div class="payment-card">

        <div class="payment-card-header">

            <h2 class="payment-card-title">
                Payment Details
            </h2>

        </div>

        <div class="payment-card-body">

            <div class="detail-grid">

                <div class="detail-item">

                    <div class="detail-label">
                        Payment No.
                    </div>

                    <div class="detail-value">
                        {{ $payment->payment_no }}
                    </div>

                </div>

                <div class="detail-item">

                    <div class="detail-label">
                        Client
                    </div>

                    <div class="detail-value">

                        @if($payment->client)

                            {{ $payment->client->client_name }}

                        @else

                            N/A

                        @endif

                    </div>

                </div>

                <div class="detail-item">

                    <div class="detail-label">
                        Payment Date
                    </div>

                    <div class="detail-value">
                        {{ $payment->payment_date?->format('d M Y') ?? 'N/A' }}
                    </div>

                </div>

                <div class="detail-item">

                    <div class="detail-label">
                        Payment Method
                    </div>

                    <div class="detail-value">
                        {{ $payment->payment_method }}
                    </div>

                </div>

                <div class="detail-item">

                    <div class="detail-label">
                        Reference
                    </div>

                    <div class="detail-value">
                        {{ $payment->reference ?: 'N/A' }}
                    </div>

                </div>

                <div class="detail-item">

                    <div class="detail-label">
                        Created
                    </div>

                    <div class="detail-value">
                        {{ $payment->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="payment-card">

        <div class="payment-card-header">

            <h2 class="payment-card-title">
                Invoice Allocations
            </h2>

        </div>

        <div class="payment-card-body" style="padding: 0;">

            @if($payment->allocations->count())

                <div class="allocation-table-wrapper">

                    <table class="allocation-table">

                        <thead>

                            <tr>
                                <th>Invoice No.</th>
                                <th>Client</th>
                                <th>Invoice Date</th>
                                <th>Invoice Total</th>
                                <th>Allocated Amount</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($payment->allocations as $allocation)

                                @php
                                    $invoice = $allocation->invoice;
                                @endphp

                                <tr>

                                    <td>

                                        @if($invoice)

                                            <a
                                                href="{{ route('invoices.show', $invoice) }}"
                                                class="invoice-link"
                                            >
                                                {{ $invoice->invoice_no ?? $invoice->invoice_number }}
                                            </a>

                                        @else

                                            N/A

                                        @endif

                                    </td>

                                    <td>
                                        {{ $invoice?->client?->client_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $invoice?->invoice_date?->format('d M Y') ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $invoice
                                            ? number_format((float) ($invoice->total_amount ?? $invoice->amount ?? 0), 2)
                                            : 'N/A'
                                        }}
                                    </td>

                                    <td>

                                        <span class="allocated-amount">
                                            {{ number_format((float) $allocation->allocated_amount, 2) }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                            <tr class="allocation-total-row">

                                <td
                                    colspan="4"
                                    class="allocation-total-label"
                                >
                                    Total Allocated
                                </td>

                                <td class="allocation-total-value">
                                    {{ number_format((float) $payment->allocations->sum('allocated_amount'), 2) }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-allocation">

                    <div class="empty-allocation-icon">
                        💳
                    </div>

                    <div class="empty-allocation-title">
                        No Invoice Allocations
                    </div>

                    <div class="empty-allocation-text">
                        This payment is currently unallocated.
                    </div>

                </div>

            @endif

        </div>

    </div>

    <div class="payment-card">

        <div class="payment-card-header">

            <h2 class="payment-card-title">
                Notes
            </h2>

        </div>

        <div class="payment-card-body">

            @if($payment->notes)

                <div class="notes-box">
                    {{ $payment->notes }}
                </div>

            @else

                <div class="no-attachment">
                    No notes added for this payment.
                </div>

            @endif

        </div>

    </div>

    <div class="payment-card">

        <div class="payment-card-header">

            <h2 class="payment-card-title">
                Attachment
            </h2>

        </div>

        <div class="payment-card-body">

            @if($payment->attachment)

                <div class="attachment-box">

                    <div class="attachment-name">
                        {{ basename($payment->attachment) }}
                    </div>

                    <a
    href="{{ route('payments.attachment', $payment) }}"
    target="_blank"
    class="attachment-btn"
>
    Open Attachment
</a>

                </div>

            @else

                <div class="no-attachment">
                    No attachment uploaded.
                </div>

            @endif

        </div>

    </div>

    <div class="bottom-actions">

        <a
            href="{{ route('payments.edit', $payment) }}"
            class="action-btn edit-btn"
        >
            Edit Payment
        </a>

        <a
            href="{{ route('payments.index') }}"
            class="action-btn back-btn"
        >
            Back to Payments
        </a>

    </div>

</div>

@endsection