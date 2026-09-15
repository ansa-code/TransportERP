@extends('layouts.app')

@section('content')

@php
    $expenseTypes = [
        'Fuel' => [
            'Fuel Purchase',
            'Diesel',
            'Petrol',
            'Other',
        ],
        'Maintenance' => [
            'Tyre',
            'Battery',
            'Washing',
            'Oil Change',
            'AC Repair',
            'Brake',
            'Electrical',
            'Spare Parts',
            'Other',
        ],
        'Visa' => [
            'Visa Fee',
            'Medical',
            'Emirates ID',
            'Other',
        ],
        'Fine' => [
            'Traffic Fine',
            'Other',
        ],
        'Registration' => [
            'Registration Fee',
            'Renewal',
            'Inspection',
            'Other',
        ],
        'Insurance' => [
            'Insurance Premium',
            'Renewal',
            'Other',
        ],
        'Admin' => [
            'Office',
            'Stationery',
            'Other',
        ],
        'Other' => [
            'Other',
        ],
    ];

    $hasFilters =
        request()->filled('search') ||
        request()->filled('category') ||
        request()->filled('expense_type') ||
        request()->filled('payment_status');
@endphp

<style>
    .expense-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 20px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .page-subtitle {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border: 1px solid #2563eb;
        border-radius: 8px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .add-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .kpi-card {
        min-width: 0;
        padding: 16px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 11px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .kpi-label {
        display: block;
        margin-bottom: 6px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 600;
    }

    .kpi-value {
        color: #172033;
        font-size: 20px;
        font-weight: 750;
    }

    .kpi-value.blue {
        color: #2563eb;
    }

    .filter-card {
        padding: 15px;
        margin-bottom: 18px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 11px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .filter-form {
        display: flex;
        gap: 9px;
        align-items: center;
        width: 100%;
    }

    .filter-input {
        width: 300px;
        flex: 0 0 300px;
        height: 38px;
        padding: 0 10px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #374151;
        font-size: 12px;
        outline: none;
        box-sizing: border-box;
    }

    .filter-select {
        flex: 1 1 0;
        min-width: 0;
        height: 38px;
        padding: 0 10px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #374151;
        font-size: 12px;
        outline: none;
        box-sizing: border-box;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
    }

    .clear-btn {
        flex: 0 0 70px;
        width: 70px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #e5e7eb;
        color: #374151;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        box-sizing: border-box;
    }

    .clear-btn:hover {
        background: #d1d5db;
        color: #374151;
    }

    .records-card {
        width: 100%;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    }

    .records-header {
        padding: 19px 22px 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .records-title {
        margin: 0;
        color: #172033;
        font-size: 18px;
        font-weight: 700;
    }

    .records-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 12px;
    }

    .table-wrap {
        width: 100%;
        overflow: hidden;
    }

    .expense-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .expense-table th {
        padding: 12px 10px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 10px;
        font-weight: 750;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .025em;
        line-height: 1.25;
    }

    .expense-table td {
        padding: 13px 10px;
        border-bottom: 1px solid #eef2f7;
        color: #374151;
        font-size: 12px;
        vertical-align: middle;
        overflow: hidden;
    }

    .expense-table tbody tr:hover {
        background: #fafcff;
    }

    .expense-table tbody tr:last-child td {
        border-bottom: none;
    }

    .col-expense-no {
        width: 18%;
    }

    .col-category {
        width: 12%;
    }

    .col-type {
        width: 20%;
    }

    .col-date {
        width: 11%;
    }

    .col-amount {
        width: 12%;
    }

    .col-payment {
        width: 12%;
    }

    .col-actions {
        width: 15%;
    }

    .expense-number-link {
        display: block;
        color: #172033;
        text-decoration: none;
        font-weight: 700;
        line-height: 1.35;
        word-break: break-word;
    }

    .expense-number-link:hover {
        color: #2563eb;
    }

    .category-text {
        color: #2563eb;
        font-weight: 650;
        word-break: break-word;
    }

    .expense-type-text {
        color: #374151;
        line-height: 1.35;
        word-break: break-word;
    }

    .date-text {
        color: #4b5563;
        line-height: 1.35;
        white-space: normal;
    }

    .amount-text {
        color: #172033;
        font-weight: 700;
        white-space: nowrap;
    }

    .payment-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        min-height: 25px;
        padding: 3px 7px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .payment-paid {
        background: #dcfce7;
        color: #166534;
    }

    .payment-pending {
        background: #ffedd5;
        color: #9a3412;
    }

    .payment-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .payment-default {
        background: #f3f4f6;
        color: #4b5563;
    }

    .actions {
        display: flex;
        align-items: center;
        gap: 6px;
        width: 100%;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 31px;
        padding: 0;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
        box-sizing: border-box;
        cursor: pointer;
        white-space: nowrap;
    }

    .edit-btn {
        background: #2563eb;
        border: 1px solid #2563eb;
        color: #fff;
    }

    .edit-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .delete-btn {
        background: #dc2626;
        border: 1px solid #dc2626;
        color: #fff;
    }

    .delete-btn:hover {
        background: #b91c1c;
        color: #fff;
    }

    .delete-form {
        margin: 0;
        padding: 0;
    }

    .muted {
        color: #9ca3af;
        font-weight: 500;
    }

    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-title {
        margin: 0 0 5px;
        color: #374151;
        font-size: 16px;
        font-weight: 700;
    }

    .empty-text {
        margin: 0;
        color: #9ca3af;
        font-size: 12px;
    }

    .pagination-wrap {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 1050px) {

        .filter-form {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
        }

        .filter-input {
            width: 100%;
            flex: none;
        }

        .filter-select {
            width: 100%;
        }

        .clear-btn {
            width: 70px;
            flex: none;
        }

        .expense-table th {
            padding: 11px 7px;
            font-size: 9px;
        }

        .expense-table td {
            padding: 12px 7px;
            font-size: 11px;
        }

        .action-btn {
            width: 48px;
            height: 30px;
            font-size: 9px;
        }

        .actions {
            gap: 4px;
        }
    }

    @media (max-width: 750px) {

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .add-btn {
            width: 100%;
        }

        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .filter-input {
            grid-column: 1 / -1;
        }

        .clear-btn {
            width: 100%;
            grid-column: auto;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .expense-table {
            min-width: 760px;
        }
    }

    @media (max-width: 500px) {

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .filter-input {
            grid-column: auto;
        }

        .clear-btn {
            width: 100%;
        }
    }
</style>

<div class="expense-page">

    <div class="page-header">

        <div>
            <h1 class="page-title">
                Expenses
            </h1>

            <p class="page-subtitle">
                Expense records, payment status and recovery information
            </p>
        </div>

        <a
            href="{{ route('expenses.create') }}"
            class="add-btn"
        >
            + Add Expense
        </a>

    </div>

    <div class="kpi-grid">

        <div class="kpi-card">

            <span class="kpi-label">
                Total Expenses
            </span>

            <div class="kpi-value">
                {{ $expenses->total() }}
            </div>

        </div>

        <div class="kpi-card">

            <span class="kpi-label">
                Total Amount
            </span>

            <div class="kpi-value blue">
                {{ number_format((float) $expenses->sum('amount'), 2) }}
            </div>

        </div>

        <div class="kpi-card">

            <span class="kpi-label">
                Paid Expenses
            </span>

            <div class="kpi-value">
                {{ $expenses->where('payment_status', 'Paid')->count() }}
            </div>

        </div>

        <div class="kpi-card">

            <span class="kpi-label">
                Pending Expenses
            </span>

            <div class="kpi-value">
                {{ $expenses->where('payment_status', 'Pending')->count() }}
            </div>

        </div>

    </div>

    <div class="filter-card">

        <form
            action="{{ route('expenses.index') }}"
            method="GET"
            class="filter-form"
        >

            <input
                type="text"
                name="search"
                class="filter-input"
                placeholder="Search expenses..."
                value="{{ request('search') }}"
                autocomplete="off"
            >

            <select
                name="category"
                class="filter-select"
                onchange="this.form.submit()"
            >

                <option value="">
                    All Categories
                </option>

                @foreach(array_keys($expenseTypes) as $category)

                    <option
                        value="{{ $category }}"
                        {{ request('category') === $category ? 'selected' : '' }}
                    >
                        {{ $category }}
                    </option>

                @endforeach

            </select>

            <select
                name="expense_type"
                class="filter-select"
                onchange="this.form.submit()"
            >

                <option value="">
                    All Expense Types
                </option>

                @foreach($expenseTypes as $types)

                    @foreach($types as $type)

                        <option
                            value="{{ $type }}"
                            {{ request('expense_type') === $type ? 'selected' : '' }}
                        >
                            {{ $type }}
                        </option>

                    @endforeach

                @endforeach

            </select>

            <select
                name="payment_status"
                class="filter-select"
                onchange="this.form.submit()"
            >

                <option value="">
                    All Payment Status
                </option>

                <option
                    value="Pending"
                    {{ request('payment_status') === 'Pending' ? 'selected' : '' }}
                >
                    Pending
                </option>

                <option
                    value="Paid"
                    {{ request('payment_status') === 'Paid' ? 'selected' : '' }}
                >
                    Paid
                </option>

                <option
                    value="Cancelled"
                    {{ request('payment_status') === 'Cancelled' ? 'selected' : '' }}
                >
                    Cancelled
                </option>

            </select>

            @if($hasFilters)

                <a
                    href="{{ route('expenses.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>
    <div class="records-card">

        <div class="records-header">

            <h2 class="records-title">
                Expense Records
            </h2>

            <p class="records-subtitle">
                Expense history, payment status and recovery information
            </p>

        </div>

        @if($expenses->count())

            <div class="table-wrap">

                <table class="expense-table">

                    <thead>

                        <tr>

                            <th class="col-expense-no">
                                Expense No.
                            </th>

                            <th class="col-category">
                                Category
                            </th>

                            <th class="col-type">
                                Expense Type
                            </th>

                            <th class="col-date">
                                Date
                            </th>

                            <th class="col-amount">
                                Amount
                            </th>

                            <th class="col-payment">
                                Payment
                            </th>

                            <th class="col-actions">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($expenses as $expense)

                            @php
                                $expenseTypeValues = is_array($expense->expense_type)
                                    ? $expense->expense_type
                                    : ($expense->expense_type ? [$expense->expense_type] : []);

                                $paymentClass = match($expense->payment_status) {
                                    'Paid' => 'payment-paid',
                                    'Pending' => 'payment-pending',
                                    'Cancelled' => 'payment-cancelled',
                                    default => 'payment-default',
                                };
                            @endphp

                            <tr>

                                <td>

                                    <a
                                        href="{{ route('expenses.show', $expense->id) }}"
                                        class="expense-number-link"
                                    >
                                        {{ $expense->expense_no }}
                                    </a>

                                </td>

                                <td>

                                    <div class="category-text">
                                        {{ $expense->category }}
                                    </div>

                                </td>

                                <td>

                                    @if(count($expenseTypeValues) > 0)

                                        <div class="expense-type-text">
                                            {{ implode(', ', $expenseTypeValues) }}
                                        </div>

                                    @else

                                        <span class="muted">
                                            Not Set
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="date-text">
                                        {{ optional($expense->expense_date)->format('d M Y') ?? 'Not Set' }}
                                    </div>

                                </td>

                                <td>

                                    <div class="amount-text">
                                        {{ number_format((float) $expense->amount, 2) }}
                                    </div>

                                </td>

                                <td>

                                    <span class="payment-badge {{ $paymentClass }}">
                                        {{ $expense->payment_status }}
                                    </span>

                                </td>

                                <td>

                                    <div class="actions">

                                        <a
                                            href="{{ route('expenses.edit', $expense->id) }}"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('expenses.destroy', $expense->id) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Are you sure you want to delete this expense?');"
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

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="pagination-wrap">
                {{ $expenses->links() }}
            </div>

        @else

            <div class="empty-state">

                <h3 class="empty-title">
                    No Expense Records Found
                </h3>

                <p class="empty-text">
                    No expenses match the current search or filters.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection