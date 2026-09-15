@extends('layouts.app')

@section('content')

<div class="vendor-index-page">

    {{-- PAGE HEADER --}}

    <div class="page-header">

        <div>
            <h1>Vendor Management</h1>

            <p>
                Manage vendors, supplied vehicles, rates and payment terms.
            </p>
        </div>

        <a
            href="{{ route('vendors.create') }}"
            class="add-vendor-btn"
        >
            + Add Vendor
        </a>

    </div>


    {{-- KPI CARDS --}}

    <div class="kpi-grid">

        <div class="kpi-card">

            <div class="kpi-icon">
                🏢
            </div>

            <div class="kpi-content">

                <span>Total Vendors</span>

                <strong>
                    {{ $totalVendors }}
                </strong>

            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-icon">
                ✓
            </div>

            <div class="kpi-content">

                <span>Active Vendors</span>

                <strong>
                    {{ $activeVendors }}
                </strong>

            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-icon">
                ⏸
            </div>

            <div class="kpi-content">

                <span>Inactive Vendors</span>

                <strong>
                    {{ $inactiveVendors }}
                </strong>

            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-icon">
                📦
            </div>

            <div class="kpi-content">

                <span>Archived Vendors</span>

                <strong>
                    {{ $archivedVendors }}
                </strong>

            </div>

        </div>

    </div>


    {{-- SEARCH --}}

    <div class="vendor-search-row">

        <form
            action="{{ route('vendors.index') }}"
            method="GET"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search vendor..."
                class="search-input"
            >
            
        @if($search)
    <a href="{{ route('vendors.index') }}" class="clear-btn">
        Clear
    </a>
@endif 

        </form>

    </div>


    {{-- TABLE HEADER --}}

    <div class="table-topbar">

        <div>

            <h2>
                Vendors
            </h2>

            <p>
                {{ $vendors->total() }} vendor records
            </p>

        </div>

    </div>


    {{-- VENDOR TABLE --}}

    <div class="table-card">

        <table class="vendor-table">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Vendor Name</th>

                    <th>Company</th>

                    <th>Phone</th>

                    <th>Service</th>

                    <th>Status</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>

                @forelse($vendors as $vendor)

                    <tr>

                        <td>
                            #{{ $vendor->id }}
                        </td>


                        <td>

                            <a
                                href="{{ route('vendors.show', $vendor->id) }}"
                                class="vendor-name-link"
                            >
                                {{ $vendor->vendor_name }}
                            </a>

                        </td>


                        <td>
                            {{ $vendor->company_name ?: '—' }}
                        </td>


                        <td>
                            {{ $vendor->phone ?: '—' }}
                        </td>


                        <td>
                            {{ $vendor->service_type ?: '—' }}
                        </td>


                        <td>

                            @php

                                $statusClass = match ($vendor->status) {

                                    'Active' => 'status-active',

                                    'Inactive' => 'status-inactive',

                                    'Archived' => 'status-archived',

                                    default => 'status-inactive',

                                };

                            @endphp


                            <span
                                class="status-badge {{ $statusClass }}"
                            >
                                {{ $vendor->status }}
                            </span>

                        </td>


                        <td>

                            <div class="action-buttons">

                                <a
                                    href="{{ route('vendors.edit', $vendor->id) }}"
                                    class="edit-btn"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route('vendors.destroy', $vendor->id) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="delete-btn"
                                        onclick="return confirm('Delete this vendor?')"
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
                            colspan="7"
                            class="empty-state"
                        >
                            No vendors found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        @if($vendors->hasPages())

            <div class="pagination-area">

                {{ $vendors->links() }}

            </div>

        @endif

    </div>

</div>
<style>

    /* =========================
       PAGE HEADER
    ========================= */

    .vendor-index-page {
        width: 100%;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        font-size: 27px;
        font-weight: 700;
        color: #172554;
    }

    .page-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .add-vendor-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 18px;
        border-radius: 7px;
        background: #123a63;
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .add-vendor-btn:hover {
        background: #0d2f50;
        color: #ffffff;
    }


    /* =========================
       KPI CARDS
    ========================= */

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .kpi-card {
        min-height: 94px;
        padding: 17px 18px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.04);
    }

    .kpi-icon {
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        border-radius: 8px;
        background: #e8f0f8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .kpi-content {
        min-width: 0;
    }

    .kpi-content span {
        display: block;
        margin-bottom: 4px;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
    }

    .kpi-content strong {
        display: block;
        color: #172554;
        font-size: 24px;
        line-height: 1.1;
        font-weight: 700;
    }


    /* =========================
       SEARCH
    ========================= */

    .vendor-search-row {
        width: 100%;
        margin-bottom: 10px;
    }

    .search-form {
        margin: 0;
    }

    .search-input {
        width: 300px;
        height: 38px;
        padding: 0 13px;

        border: 1px solid #cbd5e1;
        border-radius: 6px;
        outline: none;

        background: #ffffff;
        color: #1e293b;
        font-size: 13px;

        transition: 0.2s ease;
    }

    .search-input::placeholder {
        color: #94a3b8;
    }

    .search-input:focus {
        border-color: #315f8f;
        box-shadow: 0 0 0 2px rgba(49, 95, 143, 0.10);
    }

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
    background: #f3f4f6;
    color: #111827;
}

    /* =========================
       TABLE HEADER
    ========================= */

    .table-topbar {
        min-height: 55px;
        padding: 10px 16px;

        background: #ffffff;

        border: 1px solid #e2e8f0;
        border-bottom: 0;

        border-radius: 9px 9px 0 0;

        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .table-topbar h2 {
        margin: 0 0 3px;

        color: #172554;
        font-size: 18px;
        font-weight: 700;
    }

    .table-topbar p {
        margin: 0;

        color: #64748b;
        font-size: 12px;
    }


    /* =========================
       TABLE
    ========================= */

    .table-card {
        width: 100%;

        background: #ffffff;

        border: 1px solid #e2e8f0;
        border-radius: 0 0 9px 9px;

        overflow: hidden;
    }

    .vendor-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .vendor-table th {
        padding: 12px 14px;

        background: #f8fafc;

        border-bottom: 1px solid #e2e8f0;

        color: #475569;

        font-size: 12px;
        font-weight: 700;

        text-align: left;
        white-space: nowrap;
    }

    .vendor-table td {
        padding: 12px 14px;

        border-bottom: 1px solid #eef2f7;

        color: #334155;

        font-size: 13px;
        vertical-align: middle;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .vendor-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .vendor-table tbody tr:hover {
        background: #f8fafc;
    }


    /* COLUMN SIZING */

    .vendor-table th:nth-child(1),
    .vendor-table td:nth-child(1) {
        width: 7%;
    }

    .vendor-table th:nth-child(2),
    .vendor-table td:nth-child(2) {
        width: 20%;
    }

    .vendor-table th:nth-child(3),
    .vendor-table td:nth-child(3) {
        width: 17%;
    }

    .vendor-table th:nth-child(4),
    .vendor-table td:nth-child(4) {
        width: 14%;
    }

    .vendor-table th:nth-child(5),
    .vendor-table td:nth-child(5) {
        width: 16%;
    }

    .vendor-table th:nth-child(6),
    .vendor-table td:nth-child(6) {
        width: 11%;
    }

    .vendor-table th:nth-child(7),
    .vendor-table td:nth-child(7) {
        width: 15%;
    }


    /* =========================
       VENDOR NAME
    ========================= */

    .vendor-name-link {
        color: #123a63;
        text-decoration: none;
        font-weight: 700;
    }

    .vendor-name-link:hover {
        color: #0d2f50;
        text-decoration: underline;
    }


    /* =========================
       STATUS
    ========================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 72px;
        height: 27px;
        padding: 0 9px;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 700;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fef3c7;
        color: #92400e;
    }

    .status-archived {
        background: #e2e8f0;
        color: #475569;
    }


    /* =========================
       ACTION BUTTONS
    ========================= */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .action-buttons form {
        margin: 0;
    }

    .edit-btn,
    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        height: 32px;
        padding: 0 11px;

        border-radius: 5px;

        font-size: 12px;
        font-weight: 600;
        text-decoration: none;

        cursor: pointer;

        transition: 0.2s ease;
    }

   .edit-btn {
    background: #2563eb;
    color: #ffffff;
    border: 1px solid #2563eb;
}

.edit-btn:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
}

.delete-btn {
    background: #dc2626;
    color: #ffffff;
    border: 1px solid #dc2626;
}

.delete-btn:hover {
    background: #b91c1c;
    border-color: #b91c1c;
}

    /* =========================
       EMPTY STATE
    ========================= */

    .empty-state {
        padding: 45px 20px !important;

        text-align: center !important;

        color: #64748b !important;

        font-size: 14px !important;
    }


    /* =========================
       PAGINATION
    ========================= */

    .pagination-area {
        padding: 14px 16px;

        border-top: 1px solid #e2e8f0;
    }

    .pagination-area nav {
        display: flex;
        justify-content: center;
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1100px) {

        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .vendor-table {
            min-width: 850px;
        }

        .table-card {
            overflow-x: auto;
        }

    }


    @media (max-width: 700px) {

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .add-vendor-btn {
            width: 100%;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .search-input {
            width: 100%;
        }

        .table-card {
            overflow-x: auto;
        }

    }

</style>

@endsection