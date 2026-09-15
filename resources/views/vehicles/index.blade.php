@extends('layouts.app')

@section('content')

<style>

    .vehicle-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ================= HEADER ================= */

    .fleet-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 22px;
        gap: 20px;
    }

    .fleet-title h1 {
        margin: 0;
        color: #1d3557;
        font-size: 28px;
    }

    .fleet-title p {
        margin: 6px 0 0;
        color: #6c757d;
        font-size: 14px;
    }

    .fleet-header-right {
        display: flex;
        align-items: center;
    }

    .add-vehicle-btn {
        background: #0d6efd;
        color: white;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 7px;
        font-weight: bold;
        white-space: nowrap;
    }

    .add-vehicle-btn:hover {
        background: #0b5ed7;
        color: white;
    }


    /* ================= KPI CARDS ================= */

    .vehicle-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
    }

    .stat-label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .stat-value {
        color: #1d3557;
        font-size: 27px;
        font-weight: bold;
    }

    .stat-subtext {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .stat-subtext-green {
        color: #059669;
    }

    .stat-subtext-red {
        color: #EF4444;
    }


    /* ================= SEARCH + FILTERS ================= */

    .vehicle-tools {
        background: white;
        padding: 18px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        margin-bottom: 22px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: minmax(250px, 2fr) repeat(4, 1fr);
        gap: 10px;
        align-items: center;
    }

    .search-input,
    .filter-select {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #343a40;
        background: white;
        outline: none;
    }

    .search-input {
        font-weight: 400;
    }

    .filter-select option {
        font-weight: 700;
    }

    .search-input:focus,
    .filter-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.10);
    }


    .clear-btn {
        padding: 10px 14px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 7px;
        font-size: 13px;
        white-space: nowrap;
    }

    .clear-btn:hover {
        background: #5c636a;
        color: white;
    }


    /* ================= TABLE ================= */

    .vehicle-table-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        overflow: hidden;
    }

    .table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .table-header h3 {
        margin: 0;
        color: #1d3557;
        font-size: 18px;
    }

    .table-header span {
        color: #6c757d;
        font-size: 13px;
    }

    .vehicle-table-wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .vehicle-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .vehicle-table th {
        background: #f8f9fa;
        color: #495057;
        font-size: 12px;
        text-align: left;
        padding: 13px 9px;
        border-bottom: 1px solid #dee2e6;
        white-space: nowrap;
    }

    .vehicle-table td {
        padding: 13px 9px;
        border-bottom: 1px solid #edf0f2;
        color: #343a40;
        font-size: 13px;
        white-space: nowrap;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .vehicle-table tbody tr:hover {
        background: #f8fbff;
    }


    /* ================= COLUMN WIDTHS ================= */

    .vehicle-table th:nth-child(1),
    .vehicle-table td:nth-child(1) {
        width: 12%;
    }

    .vehicle-table th:nth-child(2),
    .vehicle-table td:nth-child(2) {
        width: 17%;
    }

    .vehicle-table th:nth-child(3),
    .vehicle-table td:nth-child(3) {
        width: 11%;
    }

    .vehicle-table th:nth-child(4),
    .vehicle-table td:nth-child(4) {
        width: 10%;
    }

    .vehicle-table th:nth-child(5),
    .vehicle-table td:nth-child(5) {
        width: 12%;
    }

    .vehicle-table th:nth-child(6),
    .vehicle-table td:nth-child(6) {
        width: 10%;
    }

    .vehicle-table th:nth-child(7),
    .vehicle-table td:nth-child(7) {
        width: 11%;
    }

    .vehicle-table th:nth-child(8),
    .vehicle-table td:nth-child(8) {
        width: 17%;
    }

    .vehicle-name {
        font-weight: bold;
        color: #1d3557;
    }


    /* ================= STATUS ================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: bold;
        white-space: nowrap;
    }

    .status-active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-assigned {
        background: #cfe2ff;
        color: #084298;
    }

    .status-idle {
        background: #fff3cd;
        color: #664d03;
    }

    .status-maintenance {
        background: #f8d7da;
        color: #842029;
    }

    .status-inactive,
    .status-archived {
        background: #e2e3e5;
        color: #41464b;
    }


    /* ================= ACTIONS ================= */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .edit-btn,
    .delete-btn {
        width: 58px;
        height: 32px;
        padding: 0;
        margin: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        border-radius: 6px;
        font-size: 11px;
        font-weight: bold;
        line-height: 1;
        flex-shrink: 0;
    }

    .edit-btn {
        background: #0d6efd;
        color: white;
        text-decoration: none;
    }

    .edit-btn:hover {
        background: #0b5ed7;
        color: white;
    }

    .delete-btn {
        background: #dc3545;
        color: white;
        border: none;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #bb2d3b;
    }


    /* ================= RESPONSIVE ================= */

    @media (max-width: 1150px) {

        .filter-form {
            grid-template-columns: repeat(3, 1fr);
        }

        .search-input {
            grid-column: span 3;
        }

    }


    @media (max-width: 900px) {

        .vehicle-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-form {
            grid-template-columns: repeat(2, 1fr);
        }

        .search-input {
            grid-column: span 2;
        }

        .fleet-header {
            flex-direction: column;
        }

    }


    @media (max-width: 650px) {

        .vehicle-stats {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .search-input {
            grid-column: auto;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .vehicle-table th,
        .vehicle-table td {
            font-size: 10px;
            padding: 8px 5px;
        }

        .status-badge {
            font-size: 9px;
            padding: 4px 6px;
        }

        .edit-btn,
        .delete-btn {
            width: 48px;
            height: 29px;
            font-size: 10px;
        }

    }

</style>


<div class="vehicle-page">


    {{-- ================= HEADER ================= --}}

    <div class="fleet-header">

        <div class="fleet-title">

            <h1>
                Fleet Management
            </h1>

            <p>
                Manage vehicles, ownership, availability and fleet status
            </p>

        </div>


        <div class="fleet-header-right">

            <a
                href="{{ route('vehicles.create') }}"
                class="add-vehicle-btn"
            >
                + Add Vehicle
            </a>

        </div>

    </div>


    {{-- ================= KPI CARDS ================= --}}

    <div class="vehicle-stats">


        {{-- Company Trucks --}}

        <div class="stat-card">

            <div class="stat-label">
                Company Trucks
            </div>

            <div class="stat-value">
                {{ \App\Models\Vehicle::where('ownership_type', 'Company Owned')->count() }}
            </div>

            <div class="stat-subtext stat-subtext-green">
                Own fleet
            </div>

        </div>


        {{-- Vendor Trucks --}}

        <div class="stat-card">

            <div class="stat-label">
                Vendor Trucks
            </div>

            <div class="stat-value">
                {{ \App\Models\Vehicle::where('ownership_type', 'Hired')->count() }}
            </div>

            <div class="stat-subtext stat-subtext-green">
                Partner fleet
            </div>

        </div>


        {{-- Available --}}

        <div class="stat-card">

            <div class="stat-label">
                Available
            </div>

            <div class="stat-value">
                {{ \App\Models\Vehicle::where('status', 'Idle')->count() }}
            </div>

            <div class="stat-subtext stat-subtext-green">
                Ready to assign
            </div>

        </div>


        {{-- Under Repair --}}

        <div class="stat-card">

            <div class="stat-label">
                Under Repair
            </div>

            <div class="stat-value">
                {{ \App\Models\Vehicle::where('status', 'Maintenance')->count() }}
            </div>

            <div class="stat-subtext stat-subtext-red">
                Needs action
            </div>

        </div>


    </div>


    {{-- ================= SEARCH + FILTERS ================= --}}

    <div class="vehicle-tools">

        <form
            action="{{ route('vehicles.index') }}"
            method="GET"
            class="filter-form"
        >

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search plate, brand, model, category..."
                class="search-input"
            >


            <select
                name="vehicle_type"
                class="filter-select"
            >

                <option value="">
                    Truck Type
                </option>

                <option value="Heavy Truck"
                    {{ request('vehicle_type') == 'Heavy Truck' ? 'selected' : '' }}>
                    Heavy Truck
                </option>

                <option value="Small Truck"
                    {{ request('vehicle_type') == 'Small Truck' ? 'selected' : '' }}>
                    Small Truck
                </option>

                <option value="Van"
                    {{ request('vehicle_type') == 'Van' ? 'selected' : '' }}>
                    Van
                </option>

                <option value="Pickup"
                    {{ request('vehicle_type') == 'Pickup' ? 'selected' : '' }}>
                    Pickup
                </option>

                <option value="Trailer"
                    {{ request('vehicle_type') == 'Trailer' ? 'selected' : '' }}>
                    Trailer
                </option>

                <option value="Other"
                    {{ request('vehicle_type') == 'Other' ? 'selected' : '' }}>
                    Other
                </option>

            </select>


            <select
                name="capacity"
                class="filter-select"
            >

                <option value="">
                    Capacity
                </option>

                <option value="small"
                    {{ request('capacity') == 'small' ? 'selected' : '' }}>
                    Small
                </option>

                <option value="medium"
                    {{ request('capacity') == 'medium' ? 'selected' : '' }}>
                    Medium
                </option>

                <option value="large"
                    {{ request('capacity') == 'large' ? 'selected' : '' }}>
                    Large
                </option>

            </select>


            <select
                name="ownership_type"
                class="filter-select"
            >

                <option value="">
                    Ownership
                </option>

                <option value="Company Owned"
                    {{ request('ownership_type') == 'Company Owned' ? 'selected' : '' }}>
                    Company Owned
                </option>

                <option value="Hired"
                    {{ request('ownership_type') == 'Hired' ? 'selected' : '' }}>
                    Hired
                </option>

                <option value="Financed"
                    {{ request('ownership_type') == 'Financed' ? 'selected' : '' }}>
                    Financed
                </option>

                <option value="Other"
                    {{ request('ownership_type') == 'Other' ? 'selected' : '' }}>
                    Other
                </option>

            </select>


            <select
                name="status"
                class="filter-select"
            >

                <option value="">
                    Status
                </option>

                <option value="Active"
                    {{ request('status') == 'Active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="Assigned"
                    {{ request('status') == 'Assigned' ? 'selected' : '' }}>
                    Assigned
                </option>

                <option value="Idle"
                    {{ request('status') == 'Idle' ? 'selected' : '' }}>
                    Idle
                </option>

                <option value="Maintenance"
                    {{ request('status') == 'Maintenance' ? 'selected' : '' }}>
                    Maintenance
                </option>

                <option value="Inactive"
                    {{ request('status') == 'Inactive' ? 'selected' : '' }}>
                    Inactive
                </option>

                <option value="Archived"
                    {{ request('status') == 'Archived' ? 'selected' : '' }}>
                    Archived
                </option>

            </select>



            @if(
                $search ||
                request('vehicle_type') ||
                request('capacity') ||
                request('ownership_type') ||
                request('status')
            )

                <a
                    href="{{ route('vehicles.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif
            

        </form>

    </div>
       {{-- ================= VEHICLE TABLE ================= --}}

    <div class="vehicle-table-card">

        <div class="table-header">

            <h3>
                Fleet Inventory
            </h3>

            <span>
                Showing {{ $vehicles->count() }} records
            </span>

        </div>


        <div class="vehicle-table-wrapper">

            <table class="vehicle-table">

                <thead>

                    <tr>
                        <th>Plate</th>
                        <th>Brand / Model</th>
                        <th>Category</th>
                        <th>Capacity</th>
                        <th>Ownership</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($vehicles as $vehicle)

                        <tr>

                            {{-- Plate --}}

                            <td>
                                {{ $vehicle->plate_number ?? '-' }}
                            </td>


                            {{-- Brand / Model --}}

                            <td class="vehicle-name">

    <a
        href="{{ route('vehicles.show', $vehicle->id) }}"
        style="
            color: #1d3557;
            text-decoration: none;
            font-weight: 800;
        "
    >

        {{ $vehicle->brand ?? '-' }}

        @if($vehicle->model)
            / {{ $vehicle->model }}
        @endif

    </a>

</td>


                            {{-- Category --}}

                            <td>
                                {{ $vehicle->category ?? '-' }}
                            </td>


                            {{-- Capacity --}}

                            <td>

                                @if($vehicle->load_capacity !== null)

                                    {{ $vehicle->load_capacity }}
                                    {{ $vehicle->load_capacity_unit ?? '' }}

                                @elseif($vehicle->capacity !== null)

                                    {{ $vehicle->capacity }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Ownership --}}

                            <td>
                                {{ $vehicle->ownership_type ?? '-' }}
                            </td>


                            {{-- Driver --}}

                            <td>

                                @php
                                    $assignment = $vehicle->assignments
                                        ->sortByDesc('loading_date')
                                        ->first();
                                @endphp

                                {{ $assignment?->driver?->driver_name ?? '-' }}

                            </td>


                            {{-- Status --}}

                            <td>

                                @if($vehicle->status === 'Active')

                                    <span class="status-badge status-active">
                                        Active
                                    </span>

                                @elseif($vehicle->status === 'Assigned')

                                    <span class="status-badge status-assigned">
                                        Assigned
                                    </span>

                                @elseif($vehicle->status === 'Idle')

                                    <span class="status-badge status-idle">
                                        Idle
                                    </span>

                                @elseif($vehicle->status === 'Maintenance')

                                    <span class="status-badge status-maintenance">
                                        Maintenance
                                    </span>

                                @elseif($vehicle->status === 'Inactive')

                                    <span class="status-badge status-inactive">
                                        Inactive
                                    </span>

                                @elseif($vehicle->status === 'Archived')

                                    <span class="status-badge status-archived">
                                        Archived
                                    </span>

                                @else

                                    <span class="status-badge status-idle">
                                        {{ $vehicle->status ?? '-' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}

                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('vehicles.edit', $vehicle->id) }}"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="{{ route('vehicles.destroy', $vehicle->id) }}"
                                        method="POST"
                                        style="display:inline; margin:0;"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-btn"
                                            onclick="return confirm('Delete this vehicle record?')"
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
                                style="
                                    text-align:center;
                                    padding:30px;
                                    color:#6c757d;
                                "
                            >
                                No vehicles found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


</div>

<script>
    document.querySelectorAll('.filter-select').forEach(function (select) {
        select.addEventListener('change', function () {
            this.form.submit();
        });
    });
</script>

@endsection