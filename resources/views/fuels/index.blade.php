@extends('layouts.app')

@section('title', 'Fuels')

@section('content')

<style>
    .fuel-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 28px;
    }

    .page-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        color: #172554;
    }

    .page-subtitle {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 15px;
    }

    .add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        background: #2563eb;
        color: #fff;
        border: 1px solid #2563eb;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: .2s ease;
        white-space: nowrap;
    }

    .add-btn:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .kpi-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 22px 24px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .kpi-label {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .kpi-value {
        color: #172554;
        font-size: 29px;
        line-height: 1.1;
        font-weight: 800;
    }

    /* SEARCH */
    .fuel-search-wrap {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        margin: 4px 0 24px;
    }

    .fuel-search {
        width: 420px;
        max-width: 100%;
        height: 42px;
        padding: 0 15px;
        border: 1px solid #dbe1ea;
        border-radius: 8px;
        background: #fff;
        color: #172554;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
        transition: .2s ease;
    }

    .fuel-search::placeholder {
        color: #94a3b8;
    }

    .fuel-search:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    /* CLEAR BUTTON - ONLY VISIBLE AFTER SEARCH */
    .clear-btn {
        height: 38px;
        min-width: 70px;
        padding: 0 14px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #e5e7eb;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .clear-btn:hover {
        background: #d1d5db;
        color: #111827;
    }

    .records-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .records-header {
        padding: 22px 24px 18px;
        border-bottom: 1px solid #eef2f7;
    }

    .records-title {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #172554;
    }

    .records-subtitle {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .table-wrap {
        width: 100%;
        overflow-x: hidden;
    }

    .fuel-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .fuel-table th {
        padding: 14px 18px;
        text-align: left;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        text-transform: none;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .fuel-table td {
        padding: 16px 18px;
        color: #475569;
        font-size: 13px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fuel-table tbody tr:last-child td {
        border-bottom: none;
    }

    .fuel-table tbody tr:hover {
        background: #fafcff;
    }

    .fuel-entry-link {
        color: #315f8f;
        font-weight: 800;
        text-decoration: none;
    }

    .fuel-entry-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    .vehicle-plate {
        color: #172554;
        font-weight: 800;
    }

    .paid-by {
        color: #315f8f;
        font-weight: 700;
        font-size: 12px;
    }

    .reimbursement-no {
        color: #64748b;
        font-weight: 600;
    }

    .reimbursement-yes {
        color: #15803d;
        font-weight: 700;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }

    .edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 11px;
        background: #2563eb !important;
        border: 1px solid #2563eb !important;
        color: #fff !important;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .edit-btn:hover {
        background: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
        color: #fff !important;
    }

    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 11px;
        background: #dc2626 !important;
        border: 1px solid #dc2626 !important;
        color: #fff !important;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .delete-btn:hover {
        background: #b91c1c !important;
        border-color: #b91c1c !important;
    }

    .empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #64748b;
        font-size: 14px;
    }

    .pagination-wrap {
        padding: 18px 24px;
        border-top: 1px solid #eef2f7;
    }

    @media (max-width: 1100px) {
        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .fuel-table {
            table-layout: auto;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .fuel-table {
            min-width: 1050px;
        }
    }

    @media (max-width: 700px) {
        .page-header {
            flex-direction: column;
        }

        .add-btn {
            width: 100%;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .fuel-search-wrap {
            justify-content: stretch;
        }

        .fuel-search {
            width: 100%;
        }

        .records-header {
            padding: 18px;
        }
    }
</style>

<div class="fuel-page">

    <div class="page-header">
        <div>
            <h1 class="page-title">Fuel Management</h1>
            <p class="page-subtitle">
                Track fuel usage, operating costs and client reimbursement.
            </p>
        </div>

        <a href="{{ route('fuels.create') }}" class="add-btn">
            + Add Fuel
        </a>
    </div>

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">Total Fuel Cost</div>
            <div class="kpi-value">
                {{ number_format($fuels->total() > 0 ? \App\Models\Fuel::sum('total_amount') : 0, 2) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Reimbursable Fuel</div>
            <div class="kpi-value">
                {{ number_format(\App\Models\Fuel::where('reimbursable', true)->sum('reimbursement_amount'), 2) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Recovered</div>
            <div class="kpi-value">
                0.00
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Outstanding</div>
            <div class="kpi-value">
                {{ number_format(\App\Models\Fuel::where('reimbursable', true)->sum('reimbursement_amount'), 2) }}
            </div>
        </div>

    </div>

    {{-- SEARCH --}}
    <div class="fuel-search-wrap">

        <form action="{{ route('fuels.index') }}" method="GET">
            <input
                type="text"
                name="search"
                class="fuel-search"
                value="{{ request('search') }}"
                placeholder="Search fuel..."
                autocomplete="off"
            >
        </form>

        @if(request('search'))
            <a href="{{ route('fuels.index') }}" class="clear-btn">
                Clear
            </a>
        @endif

    </div>

    <div class="records-card">

        <div class="records-header">
            <h2 class="records-title">Fuel Records</h2>
            <p class="records-subtitle">
                View and manage recorded fuel transactions.
            </p>
        </div>

        <div class="table-wrap">
            <table class="fuel-table">
                <thead>
                    <tr>
                        <th style="width: 13%;">Fuel Entry No.</th>
                        <th style="width: 11%;">Vehicle</th>
                        <th style="width: 10%;">Driver</th>
                        <th style="width: 9%;">Quantity</th>
                        <th style="width: 11%;">Total Cost</th>
                        <th style="width: 11%;">Paid By</th>
                        <th style="width: 11%;">Reimbursement</th>
                        <th style="width: 10%;">Date</th>
                        <th style="width: 14%;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($fuels as $fuel)
                        <tr>

                            <td>
                                <a
                                    href="{{ route('fuels.show', $fuel->id) }}"
                                    class="fuel-entry-link"
                                >
                                    {{ $fuel->fuel_entry_no }}
                                </a>
                            </td>

                            <td>
                                <span class="vehicle-plate">
                                    {{ $fuel->vehicle?->plate_number ?? '—' }}
                                </span>
                            </td>

                            <td>
                                {{ $fuel->driver?->driver_name ?? '—' }}
                            </td>

                            <td>
                                {{ number_format($fuel->liters, 2) }} L
                            </td>

                            <td>
                                {{ number_format($fuel->total_amount, 2) }}
                            </td>

                            <td>
                                <span class="paid-by">
                                    {{ $fuel->paid_by }}
                                </span>
                            </td>

                            <td>
                                @if($fuel->reimbursable)
                                    <span class="reimbursement-yes">
                                        Yes
                                    </span>
                                @else
                                    <span class="reimbursement-no">
                                        No
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $fuel->fuel_date?->format('d M Y') ?? '—' }}
                            </td>

                            <td>
                                <div class="action-buttons">

                                    <a
                                        href="{{ route('fuels.edit', $fuel->id) }}"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('fuels.destroy', $fuel->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this fuel record?');"
                                        style="margin:0;"
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
                            <td colspan="9">
                                <div class="empty-state">
                                    No fuel records found.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fuels->hasPages())
            <div class="pagination-wrap">
                {{ $fuels->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>

@endsection