@extends('layouts.app')

@section('content')

@php
    $hasFilters = request()->filled('search');
@endphp

<div class="invoice-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h1>Invoice Management</h1>

            <p>
                Manage client invoices, billing amounts and payment balances.
            </p>
        </div>

        <a
            href="{{ route('invoices.create') }}"
            class="add-invoice-btn"
        >
            + Add Invoice
        </a>

    </div>


    {{-- KPI CARDS --}}
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-icon blue">
                🧾
            </div>

            <div>
                <span>Total Invoices</span>

                <strong>
                    {{ $totalInvoices }}
                </strong>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon green">
                💰
            </div>

            <div>
                <span>Total Amount</span>

                <strong>
                    AED {{ number_format($totalAmount, 2) }}
                </strong>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon purple">
                ✓
            </div>

            <div>
                <span>Paid Amount</span>

                <strong>
                    AED {{ number_format($totalPaid, 2) }}
                </strong>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon orange">
                ⏳
            </div>

            <div>
                <span>Outstanding</span>

                <strong>
                    AED {{ number_format($totalBalance, 2) }}
                </strong>
            </div>

        </div>

    </div>


    {{-- SEARCH --}}
    <div class="toolbar">

        <form
            action="{{ route('invoices.index') }}"
            method="GET"
            class="search-form {{ $hasFilters ? 'has-clear' : '' }}"
        >

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search invoice or client..."
                autocomplete="off"
            >

            @if($hasFilters)

                <a
                    href="{{ route('invoices.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>


    {{-- TABLE CARD --}}
    <div class="table-card">

        <div class="table-header">

            <div>

                <h2>
                    Invoices
                </h2>

                <p>
                    Client billing and payment status overview.
                </p>

            </div>

        </div>


        <div class="table-wrapper">

            <table class="invoice-table">

                <thead>

                    <tr>

                        <th>Invoice No.</th>

                        <th>Client</th>

                        <th>Source</th>

                        <th>Total Amount</th>

                        <th>Paid</th>

                        <th>Due Date</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($invoices as $invoice)

                        @php

                            $statusClass = match ($invoice->status) {

                                'Draft' => 'status-draft',

                                'Issued' => 'status-issued',

                                'Partial' => 'status-partial',

                                'Paid' => 'status-paid',

                                'Overdue' => 'status-overdue',

                                'Cancelled' => 'status-cancelled',

                                default => 'status-draft',

                            };


                            $displayStatus = $invoice->status;


                            $isOverdue =
                                $invoice->status !== 'Cancelled'
                                && $invoice->status !== 'Paid'
                                && (float) $invoice->balance > 0
                                && $invoice->due_date
                                && $invoice->due_date->isPast();


                            if ($isOverdue) {

                                $displayStatus = 'Overdue';

                                $statusClass = 'status-overdue';

                            }

                        @endphp


                        <tr>

                            {{-- INVOICE NUMBER --}}
                            <td>

                                <a
                                    href="{{ route('invoices.show', $invoice->id) }}"
                                    class="invoice-number"
                                >
                                    {{ $invoice->invoice_no }}
                                </a>

                            </td>


                            {{-- CLIENT --}}
                            <td>

                                <div class="client-name">

                                    {{ $invoice->client->client_name ?? 'N/A' }}

                                </div>

                            </td>


                            {{-- SOURCE --}}
                            <td>

                                @if($invoice->source)

                                    <span class="source-badge">
                                        {{ $invoice->source }}
                                    </span>

                                @else

                                    <span class="muted">
                                        Manual
                                    </span>

                                @endif

                            </td>


                            {{-- TOTAL AMOUNT --}}
                            <td>

                                <strong class="amount total-amount">
                                    AED {{ number_format((float) $invoice->total_amount, 2) }}
                                </strong>

                            </td>


                            {{-- PAID --}}
                            <td>

                                <strong class="amount paid-amount">
                                    AED {{ number_format((float) $invoice->paid_amount, 2) }}
                                </strong>

                            </td>


                            {{-- DUE DATE --}}
                            <td>

                                <span class="due-date
                                    @if($isOverdue)
                                        overdue-date
                                    @endif
                                ">

                                    {{ $invoice->due_date?->format('d M Y') ?? '—' }}

                                </span>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                <span class="status-badge {{ $statusClass }}">
                                    {{ $displayStatus }}
                                </span>

                            </td>


                            {{-- ACTIONS --}}
                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('invoices.edit', $invoice->id) }}"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="{{ route('invoices.destroy', $invoice->id) }}"
                                        method="POST"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-btn"
                                            onclick="return confirm('Delete this invoice record?')"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="empty-row"
                            >

                                <div class="empty-icon">
                                    🧾
                                </div>

                                <strong>
                                    No invoices found
                                </strong>

                                <span>
                                    Create your first invoice to get started.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($invoices->hasPages())

            <div class="pagination-area">

                {{ $invoices->links() }}

            </div>

        @endif

    </div>

</div>


<style>

    .invoice-page {
        width: 100%;
        max-width: 1400px;
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


    /* ADD BUTTON */

    .add-invoice-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-height: 40px;

        padding: 0 17px;

        border-radius: 9px;

        background: #101d42;
        color: #ffffff;

        text-decoration: none;

        font-size: 13px;
        font-weight: 700;

        white-space: nowrap;
    }

    .add-invoice-btn:hover {
        background: #193b8f;
    }


    /* KPI CARDS */

    .stats-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 15px;

        margin-bottom: 20px;
    }

    .stat-card {
        min-width: 0;
        width: 100%;
        box-sizing: border-box;

        display: flex;
        align-items: center;

        gap: 13px;

        padding: 17px 18px;

        background: #ffffff;

        border: 1px solid #e7ebf2;
        border-radius: 13px;

        box-shadow: 0 4px 14px rgba(16, 29, 66, .05);

        overflow: hidden;
    }

    .stat-card > div:last-child {
        min-width: 0;
        flex: 1;
        overflow: hidden;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        font-size: 19px;
    }

    .stat-icon.blue {
        background: #eaf2ff;
    }

    .stat-icon.green {
        background: #eafaf5;
    }

    .stat-icon.purple {
        background: #f3edff;
    }

    .stat-icon.orange {
        background: #fff5e8;
    }

    .stat-card span {
        display: block;

        margin-bottom: 4px;

        color: #7a8497;

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;
    }

    .stat-card strong {
        display: block;

        width: 100%;

        color: #101d42;

        font-size: 15px;
        font-weight: 800;

        white-space: nowrap;

        overflow: hidden;
        text-overflow: ellipsis;

        line-height: 1.2;
    }


    /* SEARCH */

    .toolbar {
        display: flex;
        align-items: center;

        margin-bottom: 15px;
    }

    .search-form {
        width: 300px;
        max-width: 100%;

        display: grid;
        grid-template-columns: 1fr;
        gap: 9px;
        align-items: center;
    }

    .search-form.has-clear {
        grid-template-columns: 1fr 70px;
    }

    .search-form input {
        width: 100%;
        height: 38px;

        padding: 0 12px;

        border: 1px solid #d8dee8;
        border-radius: 8px;

        background: #ffffff;

        color: #25324d;

        font-size: 13px;

        outline: none;

        box-sizing: border-box;
    }

    .search-form input:focus {
        border-color: #2563eb;

        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }


    /* CLEAR BUTTON */

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


    /* TABLE CARD */

    .table-card {
        width: 100%;
        min-width: 0;

        background: #ffffff;

        border: 1px solid #e7ebf2;
        border-radius: 14px;

        box-shadow: 0 5px 18px rgba(16, 29, 66, .05);

        overflow: hidden;
    }

    .table-header {
        display: flex;
        align-items: center;

        padding: 19px 21px;

        border-bottom: 1px solid #edf0f5;
    }

    .table-header h2 {
        margin: 0 0 4px;

        color: #101d42;

        font-size: 16px;
        font-weight: 800;
    }

    .table-header p {
        margin: 0;

        color: #7a8497;

        font-size: 12px;
    }


    /* TABLE */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .invoice-table {
        width: 100%;

        border-collapse: collapse;

        table-layout: auto;
    }

    .invoice-table th {
        padding: 12px 13px;

        background: #f8fafc;

        color: #64748b;

        border-bottom: 1px solid #e5e7eb;

        font-size: 10px;
        font-weight: 800;

        text-align: left;

        text-transform: uppercase;
        letter-spacing: .45px;

        white-space: nowrap;
    }

    .invoice-table td {
        padding: 13px 13px;

        border-bottom: 1px solid #eef2f7;

        color: #334155;

        font-size: 12px;

        vertical-align: middle;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: none;
    }

    .invoice-table tbody tr:hover {
        background: #fafcff;
    }
    /* INVOICE */

    .invoice-number {
        color: #2563eb;

        font-size: 12px;
        font-weight: 800;

        text-decoration: none;

        white-space: nowrap;
    }

    .invoice-number:hover {
        text-decoration: underline;
    }


    /* CLIENT */

    .client-name {
        color: #1f2937;

        font-size: 12px;
        font-weight: 700;

        white-space: nowrap;
    }


    /* SOURCE */

    .source-badge {
        display: inline-flex;
        align-items: center;

        padding: 5px 8px;

        border-radius: 7px;

        background: #f1f5f9;

        color: #475569;

        font-size: 10px;
        font-weight: 700;

        white-space: nowrap;
    }

    .muted {
        color: #94a3b8;

        font-size: 11px;
    }


    /* AMOUNTS */

    .amount {
        font-size: 11px;
        font-weight: 800;

        white-space: nowrap;
    }

    .total-amount {
        color: #101d42;
    }

    .paid-amount {
        color: #047857;
    }


    /* DUE DATE */

    .due-date {
        color: #475569;

        font-size: 11px;
        font-weight: 650;

        white-space: nowrap;
    }

    .overdue-date {
        color: #b91c1c;

        font-weight: 800;
    }


    /* STATUS */

    .status-badge {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 5px 9px;

        border-radius: 999px;

        font-size: 10px;
        font-weight: 800;

        white-space: nowrap;
    }

    .status-draft {
        background: #f1f5f9;
        color: #475569;
    }

    .status-issued {
        background: #eaf2ff;
        color: #1d4ed8;
    }

    .status-partial {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-paid {
        background: #ecfdf5;
        color: #047857;
    }

    .status-overdue {
        background: #fef2f2;
        color: #b91c1c;
    }

    .status-cancelled {
        background: #f1f5f9;
        color: #64748b;
    }


    /* ACTIONS */

    .action-buttons {
        display: flex;

        align-items: center;

        gap: 5px;

        white-space: nowrap;
    }

    .action-buttons form {
        margin: 0;
    }

    .edit-btn,
    .delete-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-height: 29px;

        padding: 0 8px;

        border-radius: 6px;

        font-size: 10px;
        font-weight: 800;

        text-decoration: none;

        cursor: pointer;
    }

    .edit-btn {
        background: #2563eb;
        color: #ffffff;
    }

    .edit-btn:hover {
        background: #1d4ed8;
    }

    .delete-btn {
        border: none;

        background: #dc2626;
        color: #ffffff;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }


    /* EMPTY */

    .empty-row {
        padding: 45px 20px !important;

        text-align: center;

        color: #94a3b8;
    }

    .empty-icon {
        margin-bottom: 8px;

        font-size: 28px;
    }

    .empty-row strong {
        display: block;

        margin-bottom: 4px;

        color: #475569;

        font-size: 14px;
    }

    .empty-row span {
        font-size: 12px;
    }


    /* PAGINATION */

    .pagination-area {
        padding: 15px 20px;

        border-top: 1px solid #edf0f5;
    }


    /* TABLET */

    @media (max-width: 1100px) {

        .stats-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    /* MOBILE */

    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .add-invoice-btn {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .toolbar {
            width: 100%;
        }

        .search-form {
            width: 100%;
        }

        .search-form.has-clear {
            grid-template-columns: 1fr 70px;
        }

        .table-card {
            border-radius: 12px;
        }

        .invoice-table {
            min-width: 850px;
        }

    }

</style>

@endsection