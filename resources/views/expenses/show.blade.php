@extends('layouts.app')

@section('content')

@php
    $expenseTypeValues = is_array($expense->expense_type)
        ? $expense->expense_type
        : ($expense->expense_type ? [$expense->expense_type] : []);

    $paymentStatusClass = match($expense->payment_status) {
        'Paid' => 'status-paid',
        'Pending' => 'status-pending',
        'Cancelled' => 'status-cancelled',
        default => 'status-default',
    };
@endphp

<style>
    .expense-show-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .header-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 15px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        box-sizing: border-box;
    }

    .back-btn {
        background: #374151;
        border: 1px solid #374151;
        color: #fff;
    }

    .back-btn:hover {
        background: #1f2937;
    }

    .edit-btn {
        background: #2563eb;
        border: 1px solid #2563eb;
        color: #fff;
    }

    .edit-btn:hover {
        background: #1d4ed8;
    }

    .expense-hero {
        background: linear-gradient(
            135deg,
            #101d42 0%,
            #193b8f 100%
        );
        border-radius: 14px;
        padding: 28px 30px;
        color: #fff;
        margin-bottom: 22px;
        box-shadow: 0 6px 20px rgba(15, 47, 136, 0.18);
    }

    .hero-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 25px;
    }

    .hero-left {
        min-width: 0;
    }

    .hero-label {
        margin: 0 0 7px;
        color: rgba(255, 255, 255, 0.76);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .expense-number {
        margin: 0;
        color: #fff;
        font-size: 30px;
        font-weight: 750;
        line-height: 1.15;
    }

    .hero-date {
        margin: 8px 0 0;
        color: rgba(255, 255, 255, 0.78);
        font-size: 13px;
    }

    .hero-status {
        margin-top: 11px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-paid {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #ffedd5;
        color: #9a3412;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-default {
        background: #f3f4f6;
        color: #4b5563;
    }

    .hero-amount {
        text-align: right;
        flex-shrink: 0;
    }

    .hero-amount-label {
        display: block;
        margin-bottom: 3px;
        color: rgba(255, 255, 255, 0.72);
        font-size: 12px;
        font-weight: 600;
    }

    .hero-amount-value {
        color: #fff;
        font-size: 28px;
        font-weight: 750;
    }

    .hero-cards {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-top: 25px;
    }

    .hero-card {
        min-width: 0;
        padding: 15px 17px;
        border: 1px solid rgba(255, 255, 255, 0.13);
        border-radius: 11px;
        background: rgba(255, 255, 255, 0.09);
        backdrop-filter: blur(4px);
    }

    .hero-card-label {
        display: block;
        margin-bottom: 6px;
        color: rgba(255, 255, 255, 0.70);
        font-size: 11px;
        font-weight: 600;
    }

    .hero-card-value {
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        word-break: break-word;
    }

    .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(0, .85fr);
        gap: 20px;
    }

    .info-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    }

    .card-title {
        margin: 0 0 5px;
        color: #172033;
        font-size: 19px;
        font-weight: 700;
    }

    .card-subtitle {
        margin: 0 0 21px;
        color: #6b7280;
        font-size: 13px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px 24px;
    }

    .info-item {
        min-width: 0;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        display: block;
        margin-bottom: 6px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .info-value {
        color: #172033;
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }

    .info-value a {
        color: #2563eb;
        text-decoration: none;
    }

    .info-value a:hover {
        text-decoration: underline;
    }

    .muted {
        color: #9ca3af;
        font-weight: 500;
    }

    .type-list {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .type-chip {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 7px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
    }

    .cost-section {
        margin-top: 25px;
        padding-top: 21px;
        border-top: 1px solid #e5e7eb;
    }

    .cost-title {
        margin: 0 0 14px;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .cost-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .cost-item {
        padding: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #f9fafb;
    }

    .cost-item.total {
        border-color: #bfdbfe;
        background: #eff6ff;
    }

    .cost-label {
        display: block;
        margin-bottom: 5px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
    }

    .cost-value {
        color: #172033;
        font-size: 15px;
        font-weight: 700;
    }

    .cost-item.total .cost-value {
        color: #2563eb;
        font-size: 17px;
    }

    .reimbursable-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
    }

    .reimbursable-yes {
        background: #dcfce7;
        color: #166534;
    }

    .reimbursable-no {
        background: #f3f4f6;
        color: #4b5563;
    }
    .remarks-box {
        min-height: 85px;
        padding: 13px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #f9fafb;
        color: #374151;
        font-size: 14px;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .receipt-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 13px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #f9fafb;
    }

    .receipt-name {
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }

    .receipt-link {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 11px;
        border-radius: 7px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .receipt-link:hover {
        background: #1d4ed8;
    }

    .audit-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-top: 21px;
        padding-top: 18px;
        border-top: 1px solid #e5e7eb;
    }

    .audit-item {
        padding: 12px;
        border-radius: 8px;
        background: #f9fafb;
    }

    .audit-label {
        display: block;
        margin-bottom: 4px;
        color: #6b7280;
        font-size: 10px;
        font-weight: 700;
    }

    .audit-value {
        color: #374151;
        font-size: 12px;
        font-weight: 600;
    }

    @media (max-width: 1050px) {
        .hero-cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .cost-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .page-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-btn {
            flex: 1;
        }

        .expense-hero {
            padding: 22px 20px;
        }

        .hero-main {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-amount {
            text-align: left;
        }

        .hero-cards {
            grid-template-columns: 1fr;
        }

        .info-card {
            padding: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: auto;
        }

        .cost-grid {
            grid-template-columns: 1fr;
        }

        .audit-grid {
            grid-template-columns: 1fr;
        }

        .receipt-box {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="expense-show-page">

    <div class="page-header">

        <div>
            <h1 class="page-title">
                Expense Details
            </h1>

            <p class="page-subtitle">
                Complete expense, payment, operational and audit information
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('expenses.index') }}"
                class="header-btn back-btn"
            >
                ← Back to Expenses
            </a>

            <a
                href="{{ route('expenses.edit', $expense->id) }}"
                class="header-btn edit-btn"
            >
                Edit Expense
            </a>

        </div>

    </div>

    <div class="expense-hero">

        <div class="hero-main">

            <div class="hero-left">

                <p class="hero-label">
                    Expense Number
                </p>

                <h2 class="expense-number">
                    {{ $expense->expense_no }}
                </h2>

                <p class="hero-date">
                    {{ optional($expense->expense_date)->format('d M Y') ?? 'Date Not Set' }}
                </p>

                <div class="hero-status">
                    <span class="status-badge {{ $paymentStatusClass }}">
                        {{ $expense->payment_status }}
                    </span>
                </div>

            </div>

            <div class="hero-amount">

                <span class="hero-amount-label">
                    Total Amount
                </span>

                <div class="hero-amount-value">
                    {{ number_format((float) $expense->amount, 2) }}
                </div>

            </div>

        </div>

        <div class="hero-cards">

            <div class="hero-card">

                <span class="hero-card-label">
                    Category
                </span>

                <div class="hero-card-value">
                    {{ $expense->category }}
                </div>

            </div>

            <div class="hero-card">

                <span class="hero-card-label">
                    Vehicle
                </span>

                <div class="hero-card-value">

                    @if($expense->vehicle)
                        {{ $expense->vehicle->plate_number }}
                    @else
                        Not Linked
                    @endif

                </div>

            </div>

            <div class="hero-card">

                <span class="hero-card-label">
                    Reimbursable
                </span>

                <div class="hero-card-value">
                    {{ $expense->reimbursable ? 'Yes' : 'No' }}
                </div>

            </div>

        </div>

    </div>

    <div class="content-grid">

        <div class="info-card">

            <h2 class="card-title">
                Expense Information
            </h2>

            <p class="card-subtitle">
                Expense classification and operational references
            </p>

            <div class="info-grid">

                <div class="info-item full-width">

                    <span class="info-label">
                        Expense Type
                    </span>

                    @if(count($expenseTypeValues) > 0)

                        <div class="type-list">

                            @foreach($expenseTypeValues as $type)

                                <span class="type-chip">
                                    {{ $type }}
                                </span>

                            @endforeach

                        </div>

                    @else

                        <div class="info-value muted">
                            Not Set
                        </div>

                    @endif

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Expense Date
                    </span>

                    <div class="info-value">
                        {{ optional($expense->expense_date)->format('d M Y') ?? 'Not Set' }}
                    </div>

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Category
                    </span>

                    <div class="info-value">
                        {{ $expense->category }}
                    </div>

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Vehicle
                    </span>

                    <div class="info-value">

                        @if($expense->vehicle)

                            <a href="{{ route('vehicles.show', $expense->vehicle->id) }}">
                                {{ $expense->vehicle->plate_number }}
                            </a>

                        @else

                            <span class="muted">
                                Not Linked
                            </span>

                        @endif

                    </div>

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Driver
                    </span>

                    <div class="info-value">

                        @if($expense->driver)

                            <a href="{{ route('drivers.show', $expense->driver->id) }}">
                                {{ $expense->driver->driver_name }}
                            </a>

                        @else

                            <span class="muted">
                                Not Linked
                            </span>

                        @endif

                    </div>

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Client
                    </span>

                    <div class="info-value">

                        @if($expense->client)
                            {{ $expense->client->client_name }}
                        @else
                            <span class="muted">Not Linked</span>
                        @endif

                    </div>

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Assignment
                    </span>

                    <div class="info-value">

                        @if($expense->assignment)
                            Assignment #{{ $expense->assignment->id }}
                        @else
                            <span class="muted">Not Linked</span>
                        @endif

                    </div>

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Trip
                    </span>

                    <div class="info-value">

                        @if($expense->trip)
                            Trip #{{ $expense->trip->id }}
                        @else
                            <span class="muted">Not Linked</span>
                        @endif

                    </div>

                </div>

            </div>

            <div class="cost-section">

                <h3 class="cost-title">
                    Cost Breakdown
                </h3>

                <div class="cost-grid">

                    <div class="cost-item">

                        <span class="cost-label">
                            Parts Cost
                        </span>

                        <div class="cost-value">
                            {{ number_format((float) ($expense->parts_cost ?? 0), 2) }}
                        </div>

                    </div>

                    <div class="cost-item">

                        <span class="cost-label">
                            Labour Cost
                        </span>

                        <div class="cost-value">
                            {{ number_format((float) ($expense->labour_cost ?? 0), 2) }}
                        </div>

                    </div>

                    <div class="cost-item">

                        <span class="cost-label">
                            Other Cost
                        </span>

                        <div class="cost-value">
                            {{ number_format((float) ($expense->other_cost ?? 0), 2) }}
                        </div>

                    </div>

                    <div class="cost-item total">

                        <span class="cost-label">
                            Total Amount
                        </span>

                        <div class="cost-value">
                            {{ number_format((float) $expense->amount, 2) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="info-card">

            <h2 class="card-title">
                Payment & Audit
            </h2>

            <p class="card-subtitle">
                Payment, reimbursement and supporting information
            </p>

            <div class="info-grid">

                <div class="info-item">

                    <span class="info-label">
                        Payment Status
                    </span>

                    <div class="info-value">

                        <span class="status-badge {{ $paymentStatusClass }}">
                            {{ $expense->payment_status }}
                        </span>

                    </div>

                </div>

                <div class="info-item">

                    <span class="info-label">
                        Reimbursable
                    </span>

                    <div class="info-value">

                        @if($expense->reimbursable)

                            <span class="reimbursable-badge reimbursable-yes">
                                Yes
                            </span>

                        @else

                            <span class="reimbursable-badge reimbursable-no">
                                No
                            </span>

                        @endif

                    </div>

                </div>

                <div class="info-item full-width">

                    <span class="info-label">
                        Payment Method / Reference
                    </span>

                    <div class="info-value">

                        @if($expense->payment_method_reference)
                            {{ $expense->payment_method_reference }}
                        @else
                            <span class="muted">Not Set</span>
                        @endif

                    </div>

                </div>

                <div class="info-item full-width">

                    <span class="info-label">
                        Receipt
                    </span>

                    @if($expense->receipt)

                        <div class="receipt-box">

                            <div class="receipt-name">
                                {{ basename($expense->receipt) }}
                            </div>

                            <a
    href="{{ route('expenses.receipt', $expense->id) }}"
    target="_blank"
    class="receipt-link"
>
    Open Receipt
</a>
                        </div>

                    @else

                        <div class="info-value muted">
                            No receipt attached
                        </div>

                    @endif

                </div>

                <div class="info-item full-width">

                    <span class="info-label">
                        Remarks
                    </span>

                    @if($expense->remarks)

                        <div class="remarks-box">
                            {{ $expense->remarks }}
                        </div>

                    @else

                        <div class="remarks-box muted">
                            No remarks added.
                        </div>

                    @endif

                </div>

            </div>

            <div class="audit-grid">

                <div class="audit-item">

                    <span class="audit-label">
                        Created At
                    </span>

                    <div class="audit-value">
                        {{ optional($expense->created_at)->format('d M Y, h:i A') }}
                    </div>

                </div>

                <div class="audit-item">

                    <span class="audit-label">
                        Last Updated
                    </span>

                    <div class="audit-value">
                        {{ optional($expense->updated_at)->format('d M Y, h:i A') }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection