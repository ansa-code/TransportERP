@extends('layouts.app')

@section('content')

@php
    $hasFilters =
        request()->filled('search') ||
        request()->filled('payment_status');
@endphp

<style>
    .payroll-page {
        width: 100%;
        max-width: 1450px;
        min-width: 0;
        margin: 0 auto;
        overflow: hidden;
    }

    .payroll-header {
        width: 100%;
        min-width: 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 22px;
    }

    .payroll-title {
        min-width: 0;
    }

    .payroll-title h2 {
        margin: 0;
        color: #0f172a;
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .payroll-title p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .add-payroll-btn {
        height: 40px;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 17px;
        background: #2563eb;
        color: #ffffff;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 9px 18px rgba(37, 99, 235, 0.20);
        white-space: nowrap;
    }

    .add-payroll-btn:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .payroll-kpis {
        width: 100%;
        min-width: 0;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 18px;
    }

    .kpi-card {
        min-width: 0;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e4eaf3;
        border-radius: 19px;
        padding: 18px;
        box-shadow: 0 15px 36px rgba(15, 23, 42, 0.06);
    }

    .kpi-label {
        min-width: 0;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .kpi-value {
        width: 100%;
        min-width: 0;
        margin-top: 9px;
        color: #0f172a;
        font-size: clamp(17px, 1.7vw, 27px);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.035em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .kpi-note {
        width: 100%;
        min-width: 0;
        margin-top: 9px;
        color: #2563eb;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .filter-card {
        width: 100%;
        min-width: 0;
        background: #ffffff;
        border: 1px solid #e4eaf3;
        border-radius: 19px;
        padding: 16px;
        margin-bottom: 18px;
        box-shadow: 0 13px 32px rgba(15, 23, 42, 0.05);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 300px 220px;
        gap: 12px;
        align-items: center;
    }

    .filter-form.has-clear {
        grid-template-columns: 300px 220px 70px;
    }

    .filter-input,
    .filter-select {
        width: 100%;
        height: 38px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #f8fafc;
        color: #334155;
        padding: 0 12px;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .filter-input:focus,
    .filter-select:focus {
        background: #ffffff;
        border-color: #93c5fd;
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

    .records-card {
        width: 100%;
        min-width: 0;
        background: #ffffff;
        border: 1px solid #e4eaf3;
        border-radius: 20px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .records-head {
        padding: 19px 20px 15px;
        border-bottom: 1px solid #eef2f7;
    }

    .records-head h3 {
        margin: 0;
        color: #0f172a;
        font-size: 17px;
        font-weight: 800;
    }

    .records-head p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .table-wrap {
        width: 100%;
        min-width: 0;
        overflow: hidden;
    }

    .payroll-table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .payroll-table th {
        height: 48px;
        padding: 0 6px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .payroll-table td {
        height: 57px;
        padding: 0 6px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 11px;
        font-weight: 600;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .payroll-table tbody tr:hover {
        background: #f8fbff;
    }

    .payroll-table th:nth-child(1),
    .payroll-table td:nth-child(1) {
        width: 11%;
    }

    .payroll-table th:nth-child(2),
    .payroll-table td:nth-child(2) {
        width: 11%;
    }

    .payroll-table th:nth-child(3),
    .payroll-table td:nth-child(3) {
        width: 15%;
    }

    .payroll-table th:nth-child(4),
    .payroll-table td:nth-child(4) {
        width: 10%;
    }

    .payroll-table th:nth-child(5),
    .payroll-table td:nth-child(5) {
        width: 17%;
    }

    .payroll-table th:nth-child(6),
    .payroll-table td:nth-child(6) {
        width: 14%;
    }

    .payroll-table th:nth-child(7),
    .payroll-table td:nth-child(7) {
        width: 8%;
    }

    .payroll-table th:nth-child(8),
    .payroll-table td:nth-child(8) {
        width: 14%;
    }

    .month-link {
        color: #2563eb;
        font-weight: 800;
        text-decoration: none;
    }

    .month-link:hover {
        text-decoration: underline;
    }

    .driver-name {
        width: 100%;
        min-width: 0;
        color: #0f172a;
        font-weight: 800;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .money {
        color: #334155;
        font-weight: 600;
        white-space: nowrap;
    }

    .net-money {
        color: #0f172a;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 60px;
        height: 27px;
        padding: 0 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .status-paid {
        background: #dcfce7;
        color: #15803d;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #b91c1c;
    }

    .action-buttons {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 5px;
        white-space: nowrap;
        overflow: visible;
    }

    .action-btn {
        width: 46px !important;
        min-width: 46px !important;
        max-width: 46px !important;

        height: 29px !important;
        min-height: 29px !important;
        max-height: 29px !important;

        flex: 0 0 46px;

        box-sizing: border-box;
        appearance: none;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        margin: 0;
        padding: 0;

        border-radius: 7px;

        font-family: inherit;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;

        text-decoration: none;
        cursor: pointer;
    }

    .edit-btn {
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #2563eb;
    }

    .edit-btn:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .delete-btn {
        background: #dc2626;
        color: #ffffff;
        border: 1px solid #dc2626;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-icon {
        margin-bottom: 10px;
        font-size: 32px;
    }

    .empty-state h4 {
        margin: 0;
        color: #334155;
        font-size: 15px;
    }

    .empty-state p {
        margin: 6px 0 0;
        font-size: 12px;
    }

    .pagination-wrap {
        padding: 17px 20px;
        border-top: 1px solid #eef2f7;
    }

    .pagination-wrap nav {
        display: flex;
        justify-content: flex-end;
    }

    @media (max-width: 1200px) {

        .payroll-table th,
        .payroll-table td {
            padding-left: 5px;
            padding-right: 5px;
        }

        .payroll-table th {
            font-size: 8px;
            letter-spacing: 0.015em;
        }

        .payroll-table td {
            font-size: 10px;
        }

        .action-btn {
            width: 43px !important;
            min-width: 43px !important;
            max-width: 43px !important;

            height: 28px !important;
            min-height: 28px !important;
            max-height: 28px !important;

            flex-basis: 43px;

            font-size: 9px;
        }
    }

    @media (max-width: 1000px) {

        .payroll-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .table-wrap {
            overflow-x: auto;
        }

        .payroll-table {
            min-width: 1050px;
        }

        .filter-form {
            grid-template-columns: minmax(0, 300px) minmax(180px, 220px);
        }

        .filter-form.has-clear {
            grid-template-columns: minmax(0, 300px) minmax(180px, 220px) 70px;
        }
    }

    @media (max-width: 700px) {

        .payroll-header {
            flex-direction: column;
            align-items: stretch;
        }

        .add-payroll-btn {
            width: fit-content;
        }

        .filter-form,
        .filter-form.has-clear {
            grid-template-columns: 1fr;
        }

        .filter-input,
        .filter-select {
            width: 100%;
        }

        .clear-btn {
            width: 70px;
        }

        .payroll-kpis {
            grid-template-columns: 1fr;
        }

        .payroll-title h2 {
            font-size: 24px;
        }

        .kpi-value {
            font-size: 24px;
        }

        .records-head {
            padding: 17px;
        }

        .pagination-wrap {
            padding: 15px;
        }
    }
</style>

<div class="payroll-page">

    <div class="payroll-header">

        <div class="payroll-title">

            <h2>
                Payroll Management
            </h2>

            <p>
                Driver salary processing, earnings, deductions and payment tracking
            </p>

        </div>

        <a
            href="{{ route('payrolls.create') }}"
            class="add-payroll-btn"
        >
            + Add Payroll
        </a>

    </div>

    @php

        $pageTotalBasic = $payrolls->sum('basic_salary');

        $pageTotalAllowance = $payrolls->sum('allowance');

        $pageTotalOvertime = $payrolls->sum('overtime');

        $pageTotalDeductions = $payrolls->sum('total_deductions');

        $pageTotalNet = $payrolls->sum('net_salary');

        $pagePaidCount = $payrolls
            ->where('payment_status', 'Paid')
            ->count();

    @endphp

    <div class="payroll-kpis">

        <div class="kpi-card">

            <div class="kpi-label">
                Payroll Records
            </div>

            <div class="kpi-value">
                {{ $payrolls->total() }}
            </div>

            <div class="kpi-note">
                Driver payroll records
            </div>

        </div>

        <div class="kpi-card">

            <div class="kpi-label">
                Gross Salary
            </div>

            <div class="kpi-value">
                AED {{ number_format(
                    $pageTotalBasic +
                    $pageTotalAllowance +
                    $pageTotalOvertime,
                    2
                ) }}
            </div>

            <div class="kpi-note">
                Current page total
            </div>

        </div>

        <div class="kpi-card">

            <div class="kpi-label">
                Total Deductions
            </div>

            <div class="kpi-value">
                AED {{ number_format(
                    $pageTotalDeductions,
                    2
                ) }}
            </div>

            <div class="kpi-note">
                Visa, fines, advances & other
            </div>

        </div>

        <div class="kpi-card">

            <div class="kpi-label">
                Net Payroll
            </div>

            <div class="kpi-value">
                AED {{ number_format(
                    $pageTotalNet,
                    2
                ) }}
            </div>

            <div class="kpi-note">
                {{ $pagePaidCount }} paid on current page
            </div>

        </div>

    </div>

    <div class="filter-card">

        <form
            action="{{ route('payrolls.index') }}"
            method="GET"
            class="filter-form {{ $hasFilters ? 'has-clear' : '' }}"
        >

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                class="filter-input"
                placeholder="Search driver or payroll month..."
            >

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
                    {{ $paymentStatus === 'Pending' ? 'selected' : '' }}
                >
                    Pending
                </option>

                <option
                    value="Paid"
                    {{ $paymentStatus === 'Paid' ? 'selected' : '' }}
                >
                    Paid
                </option>

                <option
                    value="Cancelled"
                    {{ $paymentStatus === 'Cancelled' ? 'selected' : '' }}
                >
                    Cancelled
                </option>

            </select>

            @if($hasFilters)

                <a
                    href="{{ route('payrolls.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>
    <div class="records-card">

        <div class="records-head">

            <h3>
                Payroll Records
            </h3>

            <p>
                Driver salary, earnings, deductions and payment status
            </p>

        </div>

        <div class="table-wrap">

            <table class="payroll-table">

                <thead>

                    <tr>

                        <th>
                            Payroll Month
                        </th>

                        <th>
                            Driver
                        </th>

                        <th>
                            Basic Salary
                        </th>

                        <th>
                            Overtime
                        </th>

                        <th>
                            Total Deductions
                        </th>

                        <th>
                            Net Salary
                        </th>

                        <th>
                            Payment
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($payrolls as $payroll)

                        <tr>

                            <td>

                                <a
                                    href="{{ route('payrolls.show', $payroll->id) }}"
                                    class="month-link"
                                >
                                    {{ $payroll->salary_month }}
                                </a>

                            </td>

                            <td>

                                <div class="driver-name">

                                    {{ $payroll->driver->driver_name ?? 'N/A' }}

                                </div>

                            </td>

                            <td class="money">

                                AED
                                {{ number_format(
                                    $payroll->basic_salary,
                                    2
                                ) }}

                            </td>

                            <td class="money">

                                AED
                                {{ number_format(
                                    $payroll->overtime,
                                    2
                                ) }}

                            </td>

                            <td class="money">

                                AED
                                {{ number_format(
                                    $payroll->total_deductions,
                                    2
                                ) }}

                            </td>

                            <td class="net-money">

                                AED
                                {{ number_format(
                                    $payroll->net_salary,
                                    2
                                ) }}

                            </td>

                            <td>

                                @if($payroll->payment_status === 'Paid')

                                    <span class="status-badge status-paid">
                                        Paid
                                    </span>

                                @elseif($payroll->payment_status === 'Cancelled')

                                    <span class="status-badge status-cancelled">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="status-badge status-pending">
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('payrolls.edit', $payroll->id) }}"
                                        class="action-btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('payrolls.destroy', $payroll->id) }}"
                                        method="POST"
                                        style="margin:0; padding:0;"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn delete-btn"
                                            onclick="return confirm('Delete this payroll record?')"
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

                                    <div class="empty-icon">
                                        💳
                                    </div>

                                    <h4>
                                        No Payroll Records Found
                                    </h4>

                                    <p>
                                        Add a payroll record to start managing driver salaries.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($payrolls->hasPages())

            <div class="pagination-wrap">

                {{ $payrolls->links() }}

            </div>

        @endif

    </div>

</div>

@endsection