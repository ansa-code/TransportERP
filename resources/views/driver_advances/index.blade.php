@extends('layouts.app')

@section('content')

<style>

    .advance-page {
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
        color: #172b4d;
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .page-subtitle {
        color: #718096;
        font-size: 14px;
        line-height: 1.6;
    }

    .add-btn {
        background: #087cff;
        color: white;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
        transition: .2s;
    }

    .add-btn:hover {
        background: #0668d8;
        color: white;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: white;
        border: 1px solid #e6ebf2;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 18px rgba(18, 38, 63, .06);
    }

    .kpi-label {
        color: #718096;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .kpi-value {
        color: #172b4d;
        font-size: 25px;
        font-weight: 800;
    }

    .kpi-note {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 6px;
    }

    .filter-card {
        background: white;
        border: 1px solid #e6ebf2;
        border-radius: 15px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: 0 5px 18px rgba(18, 38, 63, .05);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 350px 220px;
        gap: 12px;
        align-items: end;
    }

    .filter-form.has-clear {
        grid-template-columns: 350px 220px 70px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }

    .form-label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        height: 38px;
        border: 1px solid #d9e1eb;
        border-radius: 9px;
        padding: 0 12px;
        background: white;
        color: #1e293b;
        outline: none;
        font-size: 13px;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #087cff;
        box-shadow: 0 0 0 3px rgba(8, 124, 255, .10);
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

    .table-card {
        background: white;
        border: 1px solid #e6ebf2;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(18, 38, 63, .05);
    }

    .table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .table-title {
        color: #172b4d;
        font-size: 16px;
        font-weight: 800;
    }

    .table-subtitle {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 4px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .advance-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .advance-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 13px 10px;
        text-align: left;
        border-bottom: 1px solid #e7edf3;
        white-space: nowrap;
    }

    .advance-table td {
        padding: 14px 10px;
        border-bottom: 1px solid #edf1f5;
        color: #334155;
        font-size: 12px;
        vertical-align: middle;
        overflow: hidden;
    }

    .advance-table tbody tr:hover {
        background: #fafcff;
    }

    .advance-table tbody tr:last-child td {
        border-bottom: none;
    }

    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */

    .col-no {
    width: 12%;
}

.col-driver {
    width: 12%;
}

.col-date {
    width: 8%;
}

.col-amount {
    width: 14%;
}

.col-deducted {
    width: 14%;
}

.col-remaining {
    width: 14%;
}

.col-status {
    width: 12%;
}

.col-actions {
    width: 14%;
}

    /*
    |--------------------------------------------------------------------------
    | Table Content
    |--------------------------------------------------------------------------
    */

    .advance-number {
        display: block;
        color: #087cff;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .advance-number:hover {
        color: #0668d8;
        text-decoration: underline;
    }

    .driver-name {
        display: block;
        width: 100%;
        color: #172b4d;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .driver-label {
        color: #94a3b8;
        font-size: 10px;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .money {
        font-weight: 800;
        color: #172b4d;
        white-space: nowrap;
    }

    .remaining-money {
        color: #d97706;
        font-weight: 800;
        white-space: nowrap;
    }

    .zero-money {
        color: #16a34a;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        max-width: 100%;
        padding: 6px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        box-sizing: border-box;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-approved {
        background: #ecfdf5;
        color: #047857;
    }

    .status-partial {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-full {
        background: #dcfce7;
        color: #15803d;
    }

    .status-rejected {
        background: #fef2f2;
        color: #b91c1c;
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 5px;
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 52px;
        height: 31px;
        padding: 0 8px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 10px;
        font-weight: 800;
        border: 1px solid transparent;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .edit-btn {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .edit-btn:hover {
        background: #1d4ed8;
        color: white;
    }

    .delete-form {
        display: inline;
        margin: 0;
        flex-shrink: 0;
    }

    .delete-btn {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 38px;
        margin-bottom: 10px;
    }

    .empty-title {
        color: #475569;
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .empty-text {
        font-size: 12px;
    }

    .pagination-area {
        padding: 18px 20px;
        border-top: 1px solid #edf1f5;
    }

    .pagination-area nav {
        display: flex;
        justify-content: center;
    }

    /*
    |--------------------------------------------------------------------------
    | Tablet
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1100px) {

        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .filter-form.has-clear {
            grid-template-columns: 1fr 1fr 70px;
        }

        .advance-table th,
        .advance-table td {
            padding-left: 8px;
            padding-right: 8px;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Table Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 900px) {

        .table-wrapper {
            overflow-x: auto;
        }

        .advance-table {
            min-width: 950px;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
        }

        .add-btn {
            width: 100%;
            justify-content: center;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .filter-form.has-clear {
            grid-template-columns: 1fr 70px;
        }

        .table-card {
            overflow: hidden;
        }

    }

</style>


<div class="advance-page">

    <div class="page-header">

        <div>

            <div class="page-title">
                Driver Advances
            </div>

            <div class="page-subtitle">
                Manage driver advances, approvals, recoveries and remaining balances.
            </div>

        </div>


        <a
            href="{{ route('driver-advances.create') }}"
            class="add-btn"
        >
            + Add Driver Advance
        </a>

    </div>


    @php
        $hasFilters = request()->filled('search') || request()->filled('status');
    @endphp


    <div class="kpi-grid">

        <div class="kpi-card">

            <div class="kpi-label">
                Total Advances
            </div>

            <div class="kpi-value">
                {{ number_format($advances->total()) }}
            </div>

            <div class="kpi-note">
                Current filtered records
            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-label">
                Total Amount
            </div>

            <div class="kpi-value">
                AED {{ number_format($advances->sum('amount'), 2) }}
            </div>

            <div class="kpi-note">
                Current page records
            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-label">
                Deducted Amount
            </div>

            <div class="kpi-value">
                AED {{ number_format($advances->sum('deducted_amount'), 2) }}
            </div>

            <div class="kpi-note">
                Payroll recovery recorded
            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-label">
                Remaining Amount
            </div>

            <div class="kpi-value">
                AED {{ number_format($advances->sum('remaining_amount'), 2) }}
            </div>

            <div class="kpi-note">
                Current page balance
            </div>

        </div>

    </div>


    <div class="filter-card">

        <form
            method="GET"
            action="{{ route('driver-advances.index') }}"
            class="filter-form {{ $hasFilters ? 'has-clear' : '' }}"
        >

            <div class="form-group">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control"
                    placeholder="Advance No, Driver or Reason"
                    autocomplete="off"
                >

            </div>


            <div class="form-group">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-control"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        All Statuses
                    </option>

                    <option
                        value="Pending"
                        {{ $status === 'Pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="Approved"
                        {{ $status === 'Approved' ? 'selected' : '' }}
                    >
                        Approved
                    </option>

                    <option
                        value="Partially Deducted"
                        {{ $status === 'Partially Deducted' ? 'selected' : '' }}
                    >
                        Partially Deducted
                    </option>

                    <option
                        value="Fully Deducted"
                        {{ $status === 'Fully Deducted' ? 'selected' : '' }}
                    >
                        Fully Deducted
                    </option>

                    <option
                        value="Rejected"
                        {{ $status === 'Rejected' ? 'selected' : '' }}
                    >
                        Rejected
                    </option>

                </select>

            </div>


            @if($hasFilters)

                <a
                    href="{{ route('driver-advances.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>
    <div class="table-card">

        <div class="table-header">

            <div>

                <div class="table-title">
                    Driver Advance Records
                </div>

                <div class="table-subtitle">
                    Advance history and recovery status
                </div>

            </div>

        </div>


        <div class="table-wrapper">

            <table class="advance-table">

                <thead>

                    <tr>

                        <th class="col-no">
                            Advance No
                        </th>

                        <th class="col-driver">
                            Driver
                        </th>

                        <th class="col-date">
                            Date
                        </th>

                        <th class="col-amount">
                            Amount
                        </th>

                        <th class="col-deducted">
                            Deducted
                        </th>

                        <th class="col-remaining">
                            Remaining
                        </th>

                        <th class="col-status">
                            Status
                        </th>

                        <th class="col-actions">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($advances as $advance)

                        <tr>

                            <td>

                                <a
                                    href="{{ route('driver-advances.show', $advance->id) }}"
                                    class="advance-number"
                                >
                                    {{ $advance->advance_no }}
                                </a>

                            </td>


                            <td>

                                <div class="driver-name">
                                    {{ $advance->driver->driver_name ?? 'N/A' }}
                                </div>

                                @if($advance->driver && $advance->driver->driver_code)

                                    <div class="driver-label">
                                        {{ $advance->driver->driver_code }}
                                    </div>

                                @endif

                            </td>


                            <td>
                                {{ optional($advance->advance_date)->format('d M Y') }}
                            </td>


                            <td>

                                <span class="money">
                                    AED {{ number_format((float) $advance->amount, 2) }}
                                </span>

                            </td>


                            <td>

                                <span class="money">
                                    AED {{ number_format((float) $advance->deducted_amount, 2) }}
                                </span>

                            </td>


                            <td>

                                @if((float) $advance->remaining_amount > 0)

                                    <span class="remaining-money">
                                        AED {{ number_format((float) $advance->remaining_amount, 2) }}
                                    </span>

                                @else

                                    <span class="zero-money">
                                        AED 0.00
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($advance->status === 'Pending')

                                    <span class="status-badge status-pending">
                                        Pending
                                    </span>

                                @elseif($advance->status === 'Approved')

                                    <span class="status-badge status-approved">
                                        Approved
                                    </span>

                                @elseif($advance->status === 'Partially Deducted')

                                    <span class="status-badge status-partial">
                                        Partially Deducted
                                    </span>

                                @elseif($advance->status === 'Fully Deducted')

                                    <span class="status-badge status-full">
                                        Fully Deducted
                                    </span>

                                @elseif($advance->status === 'Rejected')

                                    <span class="status-badge status-rejected">
                                        Rejected
                                    </span>

                                @else

                                    <span class="status-badge">
                                        {{ $advance->status }}
                                    </span>

                                @endif

                            </td>


                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('driver-advances.edit', $advance->id) }}"
                                        class="action-btn edit-btn"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('driver-advances.destroy', $advance->id) }}"
                                        class="delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this driver advance?');"
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

                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        💰
                                    </div>

                                    <div class="empty-title">
                                        No Driver Advances Found
                                    </div>

                                    <div class="empty-text">
                                        No advance records match your current filters.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($advances->hasPages())

            <div class="pagination-area">
                {{ $advances->links() }}
            </div>

        @endif

    </div>

</div>

@endsection