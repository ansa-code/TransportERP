@extends('layouts.app')

@section('content')

<style>
    .payments-page {
        width: 100%;
        max-width: 100%;
    }

    .payments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .payments-title h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .payments-title p {
        margin: 6px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .add-payment-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .add-payment-btn:hover {
        background: #1d4ed8;
    }

    .payment-kpis {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .payment-kpi {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .payment-kpi-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .payment-kpi-value {
        color: #172033;
        font-size: 23px;
        font-weight: 700;
    }

    .payments-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .payments-search-form {
        width: 300px;
        max-width: 100%;
        display: grid;
        grid-template-columns: 1fr;
        gap: 9px;
        align-items: center;
    }

    .payments-search-form.has-clear {
        grid-template-columns: 1fr 70px;
    }

    .payments-search {
        width: 100%;
        height: 38px;
        padding: 0 12px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        outline: none;
        font-size: 14px;
        background: #ffffff;
        color: #172033;
        box-sizing: border-box;
    }

    .payments-search:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
    }

    .clear-btn {
        width: 70px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #e5e7eb;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        white-space: nowrap;
    }

    .clear-btn:hover {
        background: #d1d5db;
        color: #111827;
    }

    .payments-count {
        color: #64748b;
        font-size: 13px;
    }

    .payments-table-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    /*
    |--------------------------------------------------------------------------
    | Desktop Table
    |--------------------------------------------------------------------------
    */

    .payments-table-wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .payments-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        padding: 12px 10px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .payments-table td {
        padding: 13px 10px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 13px;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .payments-table tbody tr:last-child td {
        border-bottom: none;
    }

    .payments-table tbody tr:hover {
        background: #f8fafc;
    }

    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */

    .payments-table th:nth-child(1),
    .payments-table td:nth-child(1) {
        width: 14%;
    }

    .payments-table th:nth-child(2),
    .payments-table td:nth-child(2) {
        width: 18%;
    }

    .payments-table th:nth-child(3),
    .payments-table td:nth-child(3) {
        width: 12%;
    }

    .payments-table th:nth-child(4),
    .payments-table td:nth-child(4) {
        width: 11%;
    }

    .payments-table th:nth-child(5),
    .payments-table td:nth-child(5) {
        width: 10%;
    }

    .payments-table th:nth-child(6),
    .payments-table td:nth-child(6) {
        width: 11%;
    }

    .payments-table th:nth-child(7),
    .payments-table td:nth-child(7) {
        width: 11%;
    }

    .payments-table th:nth-child(8),
    .payments-table td:nth-child(8) {
        width: 13%;
    }

    /*
    |--------------------------------------------------------------------------
    | Table Content
    |--------------------------------------------------------------------------
    */

    .payment-number {
        color: #2563eb;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .payment-number:hover {
        text-decoration: underline;
    }

    .client-name {
        display: block;
        font-weight: 600;
        color: #1e293b;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-date {
        color: #64748b;
        white-space: nowrap;
    }

    .payment-amount {
        font-weight: 700;
        color: #172033;
        white-space: nowrap;
    }

    .payment-method {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border-radius: 6px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .allocated-amount {
        color: #15803d;
        font-weight: 600;
        white-space: nowrap;
    }

    .unallocated-amount {
        color: #b45309;
        font-weight: 600;
        white-space: nowrap;
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    .actions {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .edit-btn,
    .delete-btn {
        border: none;
        border-radius: 6px;
        padding: 6px 9px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .edit-btn {
        background: #2563eb;
        color: #ffffff;
    }

    .edit-btn:hover {
        background: #1d4ed8;
    }

    .delete-btn {
        background: #dc2626;
        color: #ffffff;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 36px;
        margin-bottom: 10px;
    }

    .empty-state-title {
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 5px;
    }

    .empty-state-text {
        font-size: 13px;
    }

    .pagination-area {
        padding: 16px;
        border-top: 1px solid #e5e7eb;
    }

    /*
    |--------------------------------------------------------------------------
    | Tablet
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1100px) {

        .payments-table-wrapper {
            overflow-x: auto;
        }

        .payments-table {
            min-width: 900px;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    @media (max-width: 768px) {

        .payment-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .payments-table-wrapper {
            overflow-x: auto;
        }

        .payments-table {
            min-width: 900px;
            table-layout: auto;
        }
    }

    @media (max-width: 600px) {

        .payment-kpis {
            grid-template-columns: 1fr;
        }

        .payments-title h1 {
            font-size: 23px;
        }

        .payments-search-form {
            width: 100%;
        }

        .payments-search-form.has-clear {
            grid-template-columns: 1fr 70px;
        }

        .payments-search {
            width: 100%;
        }

        .payments-toolbar {
            align-items: stretch;
        }

        .payments-count {
            text-align: left;
        }
    }
</style>

<div class="payments-page">

    <div class="payments-header">

        <div class="payments-title">
            <h1>Payments</h1>
            <p>Manage client payments and invoice allocations.</p>
        </div>

        <a href="{{ route('payments.create') }}" class="add-payment-btn">
            + Add Payment
        </a>

    </div>

    @php
        $totalPayments = \App\Models\Payment::count();
        $totalReceived = \App\Models\Payment::sum('amount');
        $totalAllocated = \App\Models\PaymentAllocation::sum('allocated_amount');
        $totalUnallocated = \App\Models\Payment::sum('unallocated_amount');

        $hasFilters = request()->filled('search');
    @endphp

    <div class="payment-kpis">

        <div class="payment-kpi">
            <div class="payment-kpi-label">Total Payments</div>
            <div class="payment-kpi-value">
                {{ number_format($totalPayments) }}
            </div>
        </div>

        <div class="payment-kpi">
            <div class="payment-kpi-label">Total Received</div>
            <div class="payment-kpi-value">
                {{ number_format($totalReceived, 2) }}
            </div>
        </div>

        <div class="payment-kpi">
            <div class="payment-kpi-label">Allocated</div>
            <div class="payment-kpi-value">
                {{ number_format($totalAllocated, 2) }}
            </div>
        </div>

        <div class="payment-kpi">
            <div class="payment-kpi-label">Unallocated</div>
            <div class="payment-kpi-value">
                {{ number_format($totalUnallocated, 2) }}
            </div>
        </div>

    </div>

    <div class="payments-toolbar">

        <form
            method="GET"
            action="{{ route('payments.index') }}"
            class="payments-search-form {{ $hasFilters ? 'has-clear' : '' }}"
        >

            <input
                type="text"
                name="search"
                class="payments-search"
                value="{{ request('search') }}"
                placeholder="Search payment no, client or reference..."
            >

            @if($hasFilters)

                <a
                    href="{{ route('payments.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif

        </form>

        <div class="payments-count">
            Showing {{ $payments->firstItem() ?? 0 }}
            - {{ $payments->lastItem() ?? 0 }}
            of {{ $payments->total() }} payments
        </div>

    </div>
    <div class="payments-table-card">

        <div class="payments-table-wrapper">

            <table class="payments-table">

                <thead>
                    <tr>
                        <th>Payment No.</th>
                        <th>Client</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Allocated</th>
                        <th>Unallocated</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($payments as $payment)

                        <tr>

                            <td>
                                <a
                                    href="{{ route('payments.show', $payment) }}"
                                    class="payment-number"
                                >
                                    {{ $payment->payment_no }}
                                </a>
                            </td>

                            <td>
                                <span class="client-name">
                                    {{ $payment->client->client_name ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <span class="payment-date">
                                    {{ $payment->payment_date?->format('d M Y') ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <span class="payment-amount">
                                    {{ number_format((float) $payment->amount, 2) }}
                                </span>
                            </td>

                            <td>
                                <span class="payment-method">
                                    {{ $payment->payment_method }}
                                </span>
                            </td>

                            <td>
                                <span class="allocated-amount">
                                    {{ number_format($payment->allocations->sum('allocated_amount'), 2) }}
                                </span>
                            </td>

                            <td>
                                <span class="unallocated-amount">
                                    {{ number_format((float) $payment->unallocated_amount, 2) }}
                                </span>
                            </td>

                            <td>
                                <div class="actions">

                                    <a
                                        href="{{ route('payments.edit', $payment) }}"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

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
                                            class="delete-btn"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="empty-state">

                                    <div class="empty-state-icon">💳</div>

                                    <div class="empty-state-title">
                                        No Payments Found
                                    </div>

                                    <div class="empty-state-text">
                                        No payment records are available yet.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($payments->hasPages())

            <div class="pagination-area">
                {{ $payments->withQueryString()->links() }}
            </div>

        @endif

    </div>

</div>

@endsection