@extends('layouts.app')

@section('content')

<style>

    /* =========================
       DRIVER PAGE
    ========================= */

    .driver-page {
        max-width: 1400px;
        margin: 0 auto;
    }


    /* =========================
       HEADER
    ========================= */

    .driver-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 22px;
    }

    .driver-title h1 {
        margin: 0;
        color: #1d3557;
        font-size: 28px;
        font-weight: 800;
    }

    .add-driver-btn {
        background: #0d6efd;
        color: white;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 7px;
        font-weight: bold;
        white-space: nowrap;
        transition: .2s;
    }

    .add-driver-btn:hover {
        background: #0b5ed7;
        color: white;
    }


    /* =========================
       SUMMARY CARDS
    ========================= */

    .driver-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .stat-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .07);
    }

    .stat-label {
        color: #6c757d;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .stat-value {
        color: #1d3557;
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
    }

    .stat-subtext {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .stat-positive .stat-subtext {
        color: #198754;
    }

    .stat-warning .stat-subtext {
        color: #f08c00;
    }


    /* =========================
       SEARCH + FILTERS
    ========================= */

    .driver-tools {
        background: white;
        padding: 18px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .07);
        margin-bottom: 22px;
    }

    .filter-form {
        display: grid;
        grid-template-columns:
            minmax(250px, 2fr)
            repeat(3, 1fr)
            auto
            auto;
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
        color: #343a40;
        background: white;
        outline: none;
        box-sizing: border-box;
    }

    .search-input {
        font-weight: 400;
    }

    .filter-select {
        font-weight: 700;
    }

    .filter-select option {
        font-weight: 700;
    }

    .search-input:focus,
    .filter-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .10);
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


    /* =========================
       TABLE CARD
    ========================= */

    .driver-table-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .07);
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
        font-weight: 800;
    }

    .table-header span {
        color: #6c757d;
        font-size: 13px;
    }


    /* =========================
       TABLE
    ========================= */

    .driver-table-wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .driver-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .driver-table th {
        background: #f8f9fa;
        color: #495057;
        font-size: 12px;
        font-weight: 800;
        text-align: left;
        padding: 13px 10px;
        border-bottom: 1px solid #dee2e6;
        white-space: nowrap;
    }

    .driver-table td {
        padding: 13px 10px;
        border-bottom: 1px solid #edf0f2;
        color: #343a40;
        font-size: 13px;
        vertical-align: middle;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .driver-table tbody tr:hover {
        background: #f8fbff;
    }


    /* =========================
       COLUMN WIDTHS
    ========================= */

    .driver-table th:nth-child(1),
    .driver-table td:nth-child(1) {
        width: 20%;
    }

    .driver-table th:nth-child(2),
    .driver-table td:nth-child(2) {
        width: 13%;
    }

    .driver-table th:nth-child(3),
    .driver-table td:nth-child(3) {
        width: 19%;
    }

    .driver-table th:nth-child(4),
    .driver-table td:nth-child(4) {
        width: 13%;
    }

    .driver-table th:nth-child(5),
    .driver-table td:nth-child(5) {
        width: 13%;
    }

    .driver-table th:nth-child(6),
    .driver-table td:nth-child(6) {
        width: 11%;
    }

    .driver-table th:nth-child(7),
    .driver-table td:nth-child(7) {
        width: 11%;
    }


    /* =========================
       DRIVER
    ========================= */

    .driver-name {
        font-weight: 700;
    }

    .driver-profile-link {
        color: #1d3557;
        text-decoration: none;
        font-weight: 800;
    }

    .driver-profile-link:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    .driver-code {
        margin-top: 4px;
        color: #6f42c1;
        font-size: 11px;
        font-weight: 700;
    }


    /* =========================
       PASSPORT HELD
    ========================= */

    .passport-number {
        font-weight: 700;
        color: #343a40;
    }

    .passport-held-company {
        margin-top: 4px;
        color: #f08c00;
        font-size: 11px;
        font-weight: 800;
    }

    .passport-with-driver {
        margin-top: 4px;
        color: #198754;
        font-size: 11px;
        font-weight: 800;
    }


    /* =========================
       STATUS
    ========================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-inactive {
        background: #f8d7da;
        color: #842029;
    }

    .status-leave {
        background: #fff3cd;
        color: #664d03;
    }

    .status-archived {
        background: #e2e3e5;
        color: #41464b;
    }


    /* =========================
       ACTIONS
    ========================= */

    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .edit-btn,
    .delete-btn {
        width: 52px;
        min-width: 52px;
        height: 32px;
        padding: 0;
        margin: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
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

    .actions-column {
        width: 120px !important;
        min-width: 120px;
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1100px) {

        .filter-form {
            grid-template-columns: repeat(3, 1fr);
        }

        .search-input {
            grid-column: span 3;
        }

    }


    @media (max-width: 900px) {

        .driver-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .driver-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-form {
            grid-template-columns: repeat(2, 1fr);
        }

        .search-input {
            grid-column: span 2;
        }

    }


    @media (max-width: 650px) {

        .driver-stats {
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

        .driver-table th,
        .driver-table td {
            font-size: 11px;
            padding: 9px 6px;
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


<div class="driver-page">


    {{-- =========================
         HEADER
    ========================== --}}

    <div class="driver-header">

        <div class="driver-title">

            <h1>
                Driver Management
            </h1>

        </div>


        <a
            href="{{ route('drivers.create') }}"
            class="add-driver-btn"
        >
            + Add Driver
        </a>

    </div>


    {{-- =========================
         SUMMARY CARDS
    ========================== --}}

    <div class="driver-stats">


        {{-- Total Drivers --}}

        <div class="stat-card">

            <div class="stat-label">
                Total Drivers
            </div>

            <div class="stat-value">
                {{ $totalDrivers }}
            </div>

            <div class="stat-subtext">
                All registered drivers
            </div>

        </div>


        {{-- Company Visa --}}

        <div class="stat-card stat-positive">

            <div class="stat-label">
                Company Visa
            </div>

            <div class="stat-value">
                {{ $companyVisaDrivers }}
            </div>

            <div class="stat-subtext">
                Drivers on company visa
            </div>

        </div>


        {{-- Own Visa --}}

        <div class="stat-card stat-positive">

            <div class="stat-label">
                Own Visa
            </div>

            <div class="stat-value">
                {{ $ownVisaDrivers }}
            </div>

            <div class="stat-subtext">
                Drivers with own visa
            </div>

        </div>


        {{-- Passport Held --}}

        <div class="stat-card stat-warning">

            <div class="stat-label">
                Passport Held
            </div>

            <div class="stat-value">
                {{ $passportHeldDrivers }}
            </div>

            <div class="stat-subtext">
                Passport held by company
            </div>

        </div>

    </div>


    {{-- =========================
         SEARCH + FILTERS
    ========================== --}}

    <div class="driver-tools">

        <form
            action="{{ route('drivers.index') }}"
            method="GET"
            class="filter-form"
        >

            {{-- Search --}}

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search name, code, CNIC, phone, passport, Emirates ID..."
                class="search-input"
            >


            {{-- Visa Type --}}

            <select
                name="visa_type"
                class="filter-select"
            >

                <option value="">
                    Visa Type
                </option>

                <option
                    value="Company Visa"
                    {{ request('visa_type') == 'Company Visa' ? 'selected' : '' }}
                >
                    Company Visa
                </option>

                <option
                    value="Own Visa"
                    {{ request('visa_type') == 'Own Visa' ? 'selected' : '' }}
                >
                    Own Visa
                </option>

            </select>


    {{-- Continue with PART 2 immediately after this line --}}
           {{-- Passport Custody --}}

            <select
                name="passport_custody"
                class="filter-select"
            >

                <option value="">
                    Passport Custody
                </option>

                <option
                    value="company"
                    {{ request('passport_custody') == 'company' ? 'selected' : '' }}
                >
                    Company
                </option>

                <option
                    value="driver"
                    {{ request('passport_custody') == 'driver' ? 'selected' : '' }}
                >
                    Driver
                </option>

            </select>


            {{-- Status --}}

            <select
                name="status"
                class="filter-select"
            >

                <option value="">
                    Status
                </option>

                <option
                    value="Active"
                    {{ request('status') == 'Active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="On Leave"
                    {{ request('status') == 'On Leave' ? 'selected' : '' }}
                >
                    On Leave
                </option>

                <option
                    value="Inactive"
                    {{ request('status') == 'Inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

                <option
                    value="Archived"
                    {{ request('status') == 'Archived' ? 'selected' : '' }}
                >
                    Archived
                </option>

            </select>


            {{-- Clear Button --}}

            @if(
                $search ||
                request('visa_type') ||
                request('passport_custody') ||
                request('status')
            )

                <a
                    href="{{ route('drivers.index') }}"
                    class="clear-btn"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>


    {{-- =========================
         DRIVER RECORDS
    ========================== --}}

    <div class="driver-table-card">


        <div class="table-header">

            <h3>
                Driver Records
            </h3>

            <span>
                Showing {{ $drivers->count() }} records
            </span>

        </div>


        <div class="driver-table-wrapper">

            <table class="driver-table">


                {{-- TABLE HEADER --}}

                <thead>

                    <tr>

                        <th>
                            Driver
                        </th>

                        <th>
                            Visa
                        </th>

                        <th>
                            Passport Held
                        </th>

                        <th>
                            Truck
                        </th>

                        <th>
                            Salary
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="actions-column">
                            Action
                        </th>

                    </tr>

                </thead>


                {{-- TABLE BODY --}}

                <tbody>

                    @forelse($drivers as $driver)

                        <tr>


                            {{-- Driver --}}

                            <td class="driver-name">

                                <a
                                    href="{{ route('drivers.show', $driver->id) }}"
                                    class="driver-profile-link"
                                >
                                    {{ $driver->driver_name ?? '-' }}
                                </a>

                                <div class="driver-code">
                                    {{ $driver->driver_code ?? '-' }}
                                </div>

                            </td>


                            {{-- Visa --}}

                            <td>
                                {{ $driver->visa_type ?? '-' }}
                            </td>


                            {{-- Passport Held --}}

                            <td>

                                <div class="passport-number">
                                    {{ $driver->passport_number ?? '-' }}
                                </div>


                                @if($driver->passport_held_by_company)

                                    <div class="passport-held-company">
                                        Held by Company
                                    </div>

                                @else

                                    <div class="passport-with-driver">
                                        With Driver
                                    </div>

                                @endif

                            </td>


                            {{-- Truck --}}

                            <td>

                                @php
                                    $activeAssignment = $driver->assignments->first();
                                @endphp

                                @if($activeAssignment && $activeAssignment->vehicle)

                                    {{ $activeAssignment->vehicle->plate_number ?? '#' . $activeAssignment->vehicle->id }}

                                @elseif($driver->assigned_vehicle_id)

                                    #{{ $driver->assigned_vehicle_id }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Salary --}}

                            <td>
                                AED {{ number_format($driver->basic_salary ?? 0, 2) }}
                            </td>


                            {{-- Status --}}

                            <td>

                                @if($driver->employment_status === 'Active')

                                    <span class="status-badge status-active">
                                        Active
                                    </span>

                                @elseif($driver->employment_status === 'On Leave')

                                    <span class="status-badge status-leave">
                                        On Leave
                                    </span>

                                @elseif($driver->employment_status === 'Inactive')

                                    <span class="status-badge status-inactive">
                                        Inactive
                                    </span>

                                @elseif($driver->employment_status === 'Archived')

                                    <span class="status-badge status-archived">
                                        Archived
                                    </span>

                                @else

                                    <span class="status-badge status-archived">
                                        {{ $driver->employment_status ?? '-' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Action --}}

                            <td>

                                <div class="action-buttons">


                                    <a
                                        href="{{ route('drivers.edit', $driver->id) }}"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="{{ route('drivers.destroy', $driver->id) }}"
                                        method="POST"
                                        style="display:inline; margin:0;"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="delete-btn"
                                            onclick="return confirm('Delete this driver record?')"
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
                                style="
                                    text-align:center;
                                    padding:30px;
                                    color:#6c757d;
                                "
                            >
                                No drivers found.
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