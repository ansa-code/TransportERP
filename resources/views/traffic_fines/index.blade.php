@extends('layouts.app')

@section('title', 'Traffic Fines')

@section('content')

<style>
    .traffic-fines-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header-left h1 {
        margin: 0;
        color: #172554;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .page-header-left p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .add-fine-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        background: #0b2a6f;
        color: #ffffff;
        text-decoration: none;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 700;
        border: 1px solid #0b2a6f;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .add-fine-btn:hover {
        background: #123d96;
        color: #ffffff;
    }

    .filter-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .filter-form {
        display: flex;
        align-items: flex-end;
        gap: 14px;
    }

    .filter-group {
        width: 320px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .search-input {
        width: 100%;
        height: 42px;
        padding: 0 13px;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        outline: none;
        color: #1e293b;
        background: #ffffff;
        font-size: 14px;
        box-sizing: border-box;
    }

    .search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .clear-btn {
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 16px;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
    }

    .clear-btn:hover {
        background: #f8fafc;
        color: #1e293b;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        min-width: 0;
    }

    .kpi-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        margin-bottom: 8px;
    }

    .kpi-value {
        color: #172554;
        font-size: 25px;
        font-weight: 800;
        line-height: 1.2;
    }

    .kpi-subtitle {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 12px;
    }

    .traffic-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .traffic-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .traffic-card-header h2 {
        margin: 0;
        color: #172554;
        font-size: 17px;
        font-weight: 800;
    }

    .traffic-card-header span {
        color: #64748b;
        font-size: 13px;
    }

    .table-wrapper {
        width: 100%;
        overflow: hidden;
    }

    .traffic-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .traffic-table th {
        padding: 12px 10px;
        text-align: left;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        white-space: nowrap;
    }

    .traffic-table td {
        padding: 13px 10px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 12px;
        vertical-align: middle;
        overflow: hidden;
    }

    /*
     * Adjusted table widths:
     * Fine Number gets more room.
     * Vehicle / Driver is more compact.
     * Fine Date is brought closer to Vehicle / Driver.
     */
    .traffic-table th:nth-child(1),
    .traffic-table td:nth-child(1) {
        width: 17%;
    }

    .traffic-table th:nth-child(2),
    .traffic-table td:nth-child(2) {
        width: 15%;
    }

    .traffic-table th:nth-child(3),
    .traffic-table td:nth-child(3) {
        width: 12%;
    }

    .traffic-table th:nth-child(4),
    .traffic-table td:nth-child(4) {
        width: 13%;
    }

    .traffic-table th:nth-child(5),
    .traffic-table td:nth-child(5) {
        width: 13%;
    }

    .traffic-table th:nth-child(6),
    .traffic-table td:nth-child(6) {
        width: 12%;
    }

    .traffic-table th:nth-child(7),
    .traffic-table td:nth-child(7) {
        width: 18%;
    }

    .traffic-table tbody tr:last-child td {
        border-bottom: none;
    }

    .traffic-table tbody tr:hover {
        background: #fafcff;
    }

    .fine-number {
        color: #1456c0;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .fine-number:hover {
        color: #0b2a6f;
        text-decoration: underline;
    }

    .vehicle-text {
        color: #172554;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .driver-text {
        margin-top: 3px;
        color: #64748b;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .muted-text {
        color: #94a3b8;
    }

    .amount-text {
        color: #172554;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge,
    .deduction-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        padding: 5px 7px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-unpaid {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-paid {
        background: #ecfdf5;
        color: #047857;
    }

    .status-deducted {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-cancelled {
        background: #f1f5f9;
        color: #64748b;
    }

    .deduction-yes {
        background: #eef2ff;
        color: #4338ca;
    }

    .deduction-no {
        background: #f8fafc;
        color: #64748b;
    }

    .actions {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        height: 31px;
        padding: 0 9px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
        box-sizing: border-box;
    }

    .edit-btn {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    .edit-btn:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .delete-btn {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    .delete-btn:hover {
        background: #b91c1c;
        color: #ffffff;
    }

    .delete-form {
        margin: 0;
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
    }

    .empty-state-title {
        margin: 0 0 6px;
        color: #334155;
        font-size: 16px;
        font-weight: 800;
    }

    .empty-state-text {
        margin: 0;
        color: #94a3b8;
        font-size: 13px;
    }

    .pagination-area {
        padding: 16px 20px;
        border-top: 1px solid #eef2f7;
    }

    .pagination-area nav {
        display: flex;
        justify-content: center;
    }

    .pagination-area svg {
        width: 18px;
        height: 18px;
    }

    .pagination-area .hidden {
        display: none;
    }

    @media (max-width: 1100px) {
        .traffic-table th,
        .traffic-table td {
            padding-left: 8px;
            padding-right: 8px;
        }

        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .add-fine-btn {
            width: 100%;
        }

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            width: 100%;
        }

        .clear-btn {
            width: 100%;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .traffic-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .traffic-table {
            min-width: 900px;
            table-layout: auto;
        }
    }
</style>

<div class="traffic-fines-page">

    <div class="page-header">

        <div class="page-header-left">
            <h1>Traffic Fines & Penalties</h1>
            <p>Manage traffic fines, payment status and driver deductions.</p>
        </div>

        <a
            href="{{ route('traffic-fines.create') }}"
            class="add-fine-btn"
        >
            + Add Traffic Fine
        </a>

    </div>

    @php
        $allFines = $fines->getCollection();

        $totalFines = $allFines->count();

        $totalAmount = $allFines->sum(function ($fine) {
            return (float) $fine->amount;
        });

        $unpaidAmount = $allFines
            ->where('payment_status', 'Unpaid')
            ->sum(function ($fine) {
                return (float) $fine->amount;
            });

        $deductedAmount = $allFines
            ->where('payment_status', 'Deducted')
            ->sum(function ($fine) {
                return (float) $fine->amount;
            });
    @endphp

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">
                Fines
            </div>

            <div class="kpi-value">
                {{ $totalFines }}
            </div>

            <div class="kpi-subtitle">
                Current page records
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">
                Fine Amount
            </div>

            <div class="kpi-value">
                AED {{ number_format($totalAmount, 2) }}
            </div>

            <div class="kpi-subtitle">
                Current page total
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">
                Unpaid
            </div>

            <div class="kpi-value">
                AED {{ number_format($unpaidAmount, 2) }}
            </div>

            <div class="kpi-subtitle">
                Outstanding fine amount
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">
                Deducted
            </div>

            <div class="kpi-value">
                AED {{ number_format($deductedAmount, 2) }}
            </div>

            <div class="kpi-subtitle">
                Driver deduction records
            </div>
        </div>

    </div>

    <div class="filter-card">

        <form
            method="GET"
            action="{{ route('traffic-fines.index') }}"
            class="filter-form"
        >

            <div class="filter-group">

                <label for="search">
                    Search
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    class="search-input"
                    value="{{ $search ?? '' }}"
                    placeholder="Fine number, vehicle, driver or reason..."
                    autocomplete="off"
                >

            </div>

            @if(!empty($search))

                <a
                    href="{{ route('traffic-fines.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>
    <div class="traffic-card">

        <div class="traffic-card-header">

            <h2>
                Traffic Fine Records
            </h2>

            <span>
                {{ $fines->total() }}
                total record{{ $fines->total() == 1 ? '' : 's' }}
            </span>

        </div>

        <div class="table-wrapper">

            <table class="traffic-table">

                <thead>

                    <tr>
                        <th>Fine Number</th>
                        <th>Vehicle / Driver</th>
                        <th>Fine Date</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Driver Deduction</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($fines as $fine)

                        <tr>

                            <td>
                                <a
                                    href="{{ route('traffic-fines.show', $fine->id) }}"
                                    class="fine-number"
                                >
                                    {{ $fine->fine_number }}
                                </a>
                            </td>

                            <td>

                                @if($fine->vehicle)

                                    <div class="vehicle-text">
                                        {{ $fine->vehicle->plate_number }}
                                    </div>

                                @else

                                    <div class="muted-text">
                                        No Vehicle
                                    </div>

                                @endif

                                @if($fine->driver)

                                    <div class="driver-text">
                                        {{ $fine->driver->driver_name }}
                                    </div>

                                @else

                                    <div class="driver-text muted-text">
                                        No Driver
                                    </div>

                                @endif

                            </td>

                            <td>
                                {{ $fine->fine_date?->format('d M Y') ?? '—' }}
                            </td>

                            <td>

                                <span class="amount-text">
                                    AED {{ number_format((float) $fine->amount, 2) }}
                                </span>

                            </td>

                            <td>

                                @if($fine->payment_status === 'Unpaid')

                                    <span class="status-badge status-unpaid">
                                        Unpaid
                                    </span>

                                @elseif($fine->payment_status === 'Paid')

                                    <span class="status-badge status-paid">
                                        Paid
                                    </span>

                                @elseif($fine->payment_status === 'Deducted')

                                    <span class="status-badge status-deducted">
                                        Deducted
                                    </span>

                                @else

                                    <span class="status-badge status-cancelled">
                                        Cancelled
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($fine->deduct_from_driver)

                                    <span class="deduction-badge deduction-yes">
                                        Yes
                                    </span>

                                @else

                                    <span class="deduction-badge deduction-no">
                                        No
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        href="{{ route('traffic-fines.edit', $fine->id) }}"
                                        class="action-btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('traffic-fines.destroy', $fine->id) }}"
                                        method="POST"
                                        class="delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this traffic fine?');"
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

                            <td colspan="7">

                                <div class="empty-state">

                                    <p class="empty-state-title">
                                        No Traffic Fines Found
                                    </p>

                                    <p class="empty-state-text">
                                        No traffic fine records match your current search.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($fines->hasPages())

            <div class="pagination-area">
                {{ $fines->links() }}
            </div>

        @endif

    </div>

</div>

@endsection