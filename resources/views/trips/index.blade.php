@extends('layouts.app')

@section('content')

<style>
    .trip-page {
        width: 100%;
        box-sizing: border-box;
    }

    /* =========================
       HEADER
    ========================== */

    .trip-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .trip-title h1 {
        margin: 0;
        color: #172554;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.3;
    }

    .trip-title p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .create-trip-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 16px;
        background: #172554;
        color: #ffffff;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .create-trip-btn:hover {
        background: #1e3a8a;
        color: #ffffff;
    }


    /* =========================
       FILTERS
    ========================== */

    .trip-filters {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        margin-bottom: 20px;
        box-sizing: border-box;
    }

    .trip-search {
        width: 420px;
        max-width: 420px;
        height: 42px;
        padding: 0 14px;
        border: 1px solid #dbe3ef;
        border-radius: 7px;
        background: #ffffff;
        color: #334155;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .trip-search::placeholder {
        color: #94a3b8;
    }

    .trip-search:focus {
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.12);
    }

    .trip-status-select {
        width: 180px;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #dbe3ef;
        border-radius: 7px;
        background: #ffffff;
        color: #334155;
        font-size: 13px;
        outline: none;
        cursor: pointer;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .trip-status-select:focus {
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.12);
    }


    /* =========================
       TABLE
    ========================== */

    .trip-table-wrapper {
        width: 100%;
        box-sizing: border-box;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        overflow: hidden;
    }

    .trip-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .trip-table th {
        padding: 13px 10px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
    }

    .trip-table td {
        padding: 14px 10px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 11.5px;
        line-height: 1.45;
        vertical-align: middle;
        overflow: hidden;
        box-sizing: border-box;
    }

    .trip-table tbody tr:last-child td {
        border-bottom: none;
    }

    .trip-table tbody tr:hover {
        background: #fafcff;
    }


    /* =========================
       COLUMN WIDTHS
    ========================== */

    .trip-table th:nth-child(1),
    .trip-table td:nth-child(1) {
        width: 11%;
    }

    .trip-table th:nth-child(2),
    .trip-table td:nth-child(2) {
        width: 9%;
    }

    .trip-table th:nth-child(3),
    .trip-table td:nth-child(3) {
        width: 11%;
    }

    .trip-table th:nth-child(4),
    .trip-table td:nth-child(4) {
        width: 8%;
    }

    .trip-table th:nth-child(5),
    .trip-table td:nth-child(5) {
        width: 10%;
    }

    .trip-table th:nth-child(6),
    .trip-table td:nth-child(6) {
        width: 15%;
    }

    .trip-table th:nth-child(7),
    .trip-table td:nth-child(7) {
        width: 9%;
    }

    .trip-table th:nth-child(8),
    .trip-table td:nth-child(8) {
        width: 8%;
    }

    .trip-table th:nth-child(9),
    .trip-table td:nth-child(9) {
        width: 8%;
    }

    .trip-table th:nth-child(10),
    .trip-table td:nth-child(10) {
        width: 11%;
    }


    /* =========================
       TEXT
    ========================== */

    .trip-number-link {
        display: inline-block;
        color: #172554;
        font-weight: 700;
        text-decoration: none;
        white-space: normal;
        overflow-wrap: anywhere;
        transition: color 0.2s ease;
    }

    .trip-number-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    .trip-assignment {
        display: inline-block;
        color: #334155;
        font-weight: 600;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .trip-route {
        display: flex;
        align-items: center;
        gap: 6px;
        line-height: 1.4;
        min-width: 0;
    }

    .trip-route-start,
    .trip-route-end {
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .trip-route-arrow {
        color: #64748b;
        font-weight: 700;
        flex-shrink: 0;
    }

    .trip-date {
        white-space: nowrap;
    }

    .trip-freight {
        white-space: nowrap;
        font-weight: 600;
    }


    /* =========================
       STATUS
    ========================== */

    .trip-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .status-planned {
        background: #fef3c7;
        color: #92400e;
    }

    .status-assigned {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-in-transit {
        background: #e0e7ff;
        color: #4338ca;
    }

    .status-delivered {
        background: #dcfce7;
        color: #166534;
    }

    .status-closed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

        .trip-clear-btn {
    height: 42px;
    padding: 0 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #6c757d;
    color: #ffffff;
    border-radius: 7px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.trip-clear-btn:hover {
    background: #5c636a;
    color: #ffffff;
}
    /* =========================
       ACTIONS
    ========================== */

    .trip-actions {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        white-space: nowrap;
        width: 100%;
    }

    .trip-actions form {
        margin: 0;
        padding: 0;
    }

    .trip-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 30px;
        padding: 0;
        border: none;
        border-radius: 6px;
        background: #3b82f6;
        color: #ffffff;
        font-size: 10px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        white-space: nowrap;
    }

    .trip-edit-btn:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .trip-delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 30px;
        padding: 0;
        border: none;
        border-radius: 6px;
        background: #ef4444;
        color: #ffffff;
        font-size: 10px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        white-space: nowrap;
    }

    .trip-delete-btn:hover {
        background: #dc2626;
        color: #ffffff;
    }


    /* =========================
       EMPTY
    ========================== */

    .trip-empty {
        padding: 40px 20px !important;
        text-align: center !important;
        color: #64748b !important;
        font-size: 13px !important;
    }


    /* =========================
       PAGINATION
    ========================== */

    .trip-pagination {
        margin-top: 20px;
    }


    /* =========================
       LAPTOP
    ========================== */

    @media (max-width: 1200px) {

        .trip-search {
            width: 350px;
            max-width: 350px;
        }

        .trip-table th {
            padding: 11px 7px;
            font-size: 10px;
        }

        .trip-table td {
            padding: 12px 7px;
            font-size: 10.5px;
        }

        .trip-edit-btn {
            width: 47px;
            height: 30px;
            font-size: 10px;
        }

        .trip-delete-btn {
            width: 45px;
            height: 30px;
            font-size: 10px;
        }

        .trip-status {
            font-size: 8px;
            padding: 4px 6px;
        }
    }


    /* =========================
       MOBILE
    ========================== */

    @media (max-width: 768px) {

        .trip-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .create-trip-btn {
            width: 100%;
        }

        .trip-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .trip-search,
        .trip-status-select {
            width: 100%;
            max-width: 100%;
        }

        .trip-table-wrapper {
            border: none;
            background: transparent;
            overflow: visible;
        }

        .trip-table,
        .trip-table thead,
        .trip-table tbody,
        .trip-table tr,
        .trip-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .trip-table thead {
            display: none;
        }

        .trip-table tbody tr {
            margin-bottom: 12px;
            padding: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .trip-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 9px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
            overflow: visible;
        }

        .trip-table td:last-child {
            border-bottom: none;
        }

        .trip-table td::before {
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .trip-table td:nth-child(1)::before {
            content: "Trip No.";
        }

        .trip-table td:nth-child(2)::before {
            content: "Assignment";
        }

        .trip-table td:nth-child(3)::before {
            content: "Client";
        }

        .trip-table td:nth-child(4)::before {
            content: "Vehicle";
        }

        .trip-table td:nth-child(5)::before {
            content: "Driver";
        }

        .trip-table td:nth-child(6)::before {
            content: "Route";
        }

        .trip-table td:nth-child(7)::before {
            content: "Trip Date";
        }

        .trip-table td:nth-child(8)::before {
            content: "Freight";
        }

        .trip-table td:nth-child(9)::before {
            content: "Status";
        }

        .trip-table td:nth-child(10)::before {
            content: "Action";
        }

        .trip-route {
            justify-content: flex-end;
            max-width: 60%;
            flex-wrap: wrap;
            text-align: right;
        }

        .trip-actions {
            justify-content: flex-end;
        }
    }
</style>


<div class="trip-page">

    {{-- =========================
         HEADER
    ========================== --}}

    <div class="trip-header">

        <div class="trip-title">

            <h1>
                Trip Management
            </h1>

            <p>
                Manage movement-level jobs, routes, drivers and trip billing
            </p>

        </div>

        <a
            href="{{ route('trips.create') }}"
            class="create-trip-btn"
        >
            + Create Trip
        </a>

    </div>


    {{-- =========================
         FILTERS
    ========================== --}}

    <form
        action="{{ route('trips.index') }}"
        method="GET"
        class="trip-filters"
    >

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            class="trip-search"
            placeholder="🔍 Search trip, client, truck or driver..."
        >

        <select
            name="status"
            class="trip-status-select"
        >

            <option value="">
                All Statuses
            </option>

            <option
                value="Planned"
                {{ $status === 'Planned' ? 'selected' : '' }}
            >
                Planned
            </option>

            <option
                value="Assigned"
                {{ $status === 'Assigned' ? 'selected' : '' }}
            >
                Assigned
            </option>

            <option
                value="In Transit"
                {{ $status === 'In Transit' ? 'selected' : '' }}
            >
                In Transit
            </option>

            <option
                value="Delivered"
                {{ $status === 'Delivered' ? 'selected' : '' }}
            >
                Delivered
            </option>

            <option
                value="Closed"
                {{ $status === 'Closed' ? 'selected' : '' }}
            >
                Closed
            </option>

            <option
                value="Cancelled"
                {{ $status === 'Cancelled' ? 'selected' : '' }}
            >
                Cancelled
            </option>

        </select>
     
        @if($search || $status)
    <a
        href="{{ route('trips.index') }}"
        class="trip-clear-btn"
    >
        Clear
    </a>
@endif
    </form>


    {{-- =========================
         TABLE
    ========================== --}}

    <div class="trip-table-wrapper">

        <table class="trip-table">

            <thead>

                <tr>

                    <th>Trip No.</th>
                    <th>Assignment</th>
                    <th>Client</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                    <th>Route</th>
                    <th>Trip Date</th>
                    <th>Freight</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                @forelse($trips as $trip)

                    <tr>

                        <td>
                            <a
                                href="{{ route('trips.show', $trip->id) }}"
                                class="trip-number-link"
                            >
                                {{ $trip->trip_no }}
                            </a>
                        </td>

                        <td>
                            <span class="trip-assignment">
                                {{ $trip->assignment?->assignment_no ?? '-' }}
                            </span>
                        </td>

                        <td>
                            {{ $trip->client?->client_name ?? '-' }}
                        </td>

                        <td>
                            {{ $trip->vehicle?->plate_number
                                ?? $trip->vehicle?->vehicle_number
                                ?? '-' }}
                        </td>

                        <td>
                            {{ $trip->driver?->driver_name ?? '-' }}
                        </td>

                        <td>
                            <div class="trip-route">

                                <span class="trip-route-start">
                                    {{ $trip->loading_point }}
                                </span>

                                <span class="trip-route-arrow">
                                    →
                                </span>

                                <span class="trip-route-end">
                                    {{ $trip->unloading_point }}
                                </span>

                            </div>
                        </td>

                        <td>
                            <span class="trip-date">
                                {{ $trip->trip_start
                                    ? \Carbon\Carbon::parse($trip->trip_start)->format('d M Y')
                                    : '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="trip-freight">
                                AED {{ number_format($trip->freight_amount, 2) }}
                            </span>
                        </td>

                        <td>

                            @php

                                $statusClass = match ($trip->status) {

                                    'Planned' => 'status-planned',

                                    'Assigned' => 'status-assigned',

                                    'In Transit' => 'status-in-transit',

                                    'Delivered' => 'status-delivered',

                                    'Closed' => 'status-closed',

                                    'Cancelled' => 'status-cancelled',

                                    default => 'status-planned',

                                };

                            @endphp

                            <span class="trip-status {{ $statusClass }}">
                                {{ $trip->status }}
                            </span>

                        </td>

                        <td>

                            <div class="trip-actions">

                                <a
                                    href="{{ route('trips.edit', $trip->id) }}"
                                    class="trip-edit-btn"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('trips.destroy', $trip->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="trip-delete-btn"
                                        onclick="return confirm('Delete this trip record?')"
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
                            colspan="10"
                            class="trip-empty"
                        >
                            No trips found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
    {{-- =========================
         PAGINATION
    ========================== --}}

    @if(method_exists($trips, 'links'))
        <div class="trip-pagination">
            {{ $trips->links() }}
        </div>
    @endif

</div>
<script>
    document.querySelectorAll('.trip-status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            this.form.submit();
        });
    });
</script>

@endsection