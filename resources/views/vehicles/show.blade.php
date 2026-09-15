@extends('layouts.app')

@section('content')

<style>

    /* =========================================================
       VEHICLE PROFILE PAGE
    ========================================================= */

    .truck-profile-page {
        max-width: 1400px;
        margin: 0 auto;
    }


    /* =========================================================
       TOP HEADER
    ========================================================= */

    .truck-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 26px;
    }

    .truck-page-title h1 {
        margin: 0;
        color: #14213d;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.2;
    }

    .truck-page-title p {
        margin: 6px 0 0;
        color: #6c757d;
        font-size: 13px;
    }

    .truck-page-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    /* BACK BUTTON */

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        background: #6c757d;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: #5c636a;
        color: #ffffff;
    }


    /* EDIT BUTTON */

    .edit-truck-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 17px;
        background: #0d6efd;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(13,110,253,.18);
    }

    .edit-truck-btn:hover {
        background: #0b5ed7;
        color: #ffffff;
    }


    /* =========================================================
       MAIN PROFILE LAYOUT
    ========================================================= */

    .truck-main-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 445px;
        gap: 18px;
        align-items: start;
    }


    /* =========================================================
       HERO CARD
    ========================================================= */

    .truck-hero {
        position: relative;
        min-height: 220px;
        padding: 28px 24px 24px;
        border-radius: 22px;
        overflow: hidden;
        background:
            linear-gradient(
                135deg,
                #111d42 0%,
                #172a62 48%,
                #2148a5 100%
            );
        box-shadow: 0 8px 24px rgba(16,32,72,.16);
    }

    .truck-hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -85px;
        bottom: -145px;
        border-radius: 50%;
        background: rgba(34,91,210,.35);
        pointer-events: none;
    }

    .truck-hero-content {
        position: relative;
        z-index: 2;
    }

    .truck-hero-title {
        margin: 0;
        color: #ffffff;
        font-size: 30px;
        font-weight: 800;
        line-height: 1.2;
    }

    .truck-hero-subtitle {
        margin: 7px 0 24px;
        color: #d7def2;
        font-size: 14px;
        font-weight: 600;
    }


    /* =========================================================
       HERO SUMMARY BOXES
    ========================================================= */

    .truck-hero-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 13px;
    }

    .truck-hero-stat {
        min-height: 74px;
        padding: 13px 14px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 14px;
        background: rgba(255,255,255,.10);
        box-sizing: border-box;
    }

    .truck-hero-stat-label {
        margin-bottom: 5px;
        color: #c5cee5;
        font-size: 12px;
        font-weight: 600;
    }

    .truck-hero-stat-value {
        color: #ffffff;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.2;
    }


    /* =========================================================
       TRUCK VISUAL CARD
    ========================================================= */

    .truck-visual-card {
        height: 220px;
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        border: 1px solid #dfe8f2;
        background:
            linear-gradient(
                135deg,
                #eaf4ff 0%,
                #f5fbff 100%
            );
        box-shadow: 0 8px 24px rgba(31,62,94,.08);
    }

    .truck-visual {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 320px;
        height: 125px;
        transform: translate(-42%, -42%);
    }

    .truck-cabin {
        position: absolute;
        left: 0;
        bottom: 30px;
        width: 120px;
        height: 76px;
        background: #2864dc;
        border-radius: 15px 24px 10px 10px;
    }

    .truck-window {
        position: absolute;
        left: 16px;
        top: 12px;
        width: 67px;
        height: 34px;
        border-radius: 7px;
        background: #e9f5ff;
        border: 2px solid rgba(255,255,255,.8);
    }

    .truck-front {
        position: absolute;
        right: -13px;
        bottom: 30px;
        width: 28px;
        height: 48px;
        background: #2864dc;
        border-radius: 0 12px 8px 0;
    }

    .truck-body {
        position: absolute;
        left: 112px;
        bottom: 29px;
        width: 205px;
        height: 84px;
        background: #ffffff;
        border: 3px solid #2864dc;
        border-radius: 9px 14px 8px 8px;
        box-sizing: border-box;
    }

    .truck-body-line {
        position: absolute;
        left: 0;
        top: 13px;
        width: 100%;
        height: 2px;
        background: #edf3fa;
    }

    .truck-wheel {
        position: absolute;
        bottom: 5px;
        width: 39px;
        height: 39px;
        border-radius: 50%;
        background: #263650;
        border: 6px solid #dbe4ef;
        box-sizing: border-box;
    }

    .truck-wheel-one {
        left: 55px;
    }

    .truck-wheel-two {
        right: 15px;
    }

    .truck-ground {
        position: absolute;
        left: 35px;
        right: 15px;
        bottom: 6px;
        height: 5px;
        border-radius: 10px;
        background: #dce7f2;
    }


    /* =========================================================
       COMMON WHITE CARD
    ========================================================= */

    .truck-white-card {
        margin-top: 18px;
        background: #ffffff;
        border: 1px solid #e8edf2;
        border-radius: 18px;
        box-shadow: 0 5px 18px rgba(31,62,94,.07);
        overflow: hidden;
    }

    .truck-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f3;
    }

    .truck-card-header h3 {
        margin: 0;
        color: #1d3557;
        font-size: 17px;
        font-weight: 800;
    }

    .truck-card-body {
        padding: 20px;
    }


    /* =========================================================
       COMPLIANCE DOCUMENTS
    ========================================================= */

    .compliance-list {
        display: flex;
        flex-direction: column;
    }

    .compliance-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0f3;
    }

    .compliance-row:first-child {
        padding-top: 0;
    }

    .compliance-row:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .compliance-label {
        color: #495057;
        font-size: 13px;
        font-weight: 700;
    }

    .compliance-value {
        color: #343a40;
        font-size: 13px;
        font-weight: 800;
        text-align: right;
    }


    /* =========================================================
       ASSIGNMENT HISTORY
    ========================================================= */

    .assignment-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .assignment-table {
        width: 100%;
        border-collapse: collapse;
    }

    .assignment-table th {
        padding: 0 10px 12px;
        color: #7a8490;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
        border-bottom: 1px solid #e9edf1;
    }

    .assignment-table td {
        padding: 13px 10px;
        color: #343a40;
        font-size: 13px;
        white-space: nowrap;
        border-bottom: 1px solid #edf0f3;
    }

    .assignment-table tbody tr:last-child td {
        border-bottom: none;
    }

    .assignment-client {
        font-weight: 700;
        color: #1d3557;
    }

    .assignment-amount {
        font-weight: 800;
    }

    .assignment-empty {
        padding: 25px 10px;
        text-align: center;
        color: #6c757d;
        font-size: 13px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .truck-main-layout {
            grid-template-columns: 1fr;
        }

        .truck-visual-card {
            height: 210px;
        }

    }


    @media (max-width: 750px) {

        .truck-page-header {
            flex-direction: column;
        }

        .truck-page-actions {
            width: 100%;
        }

        .back-btn,
        .edit-truck-btn {
            flex: 1;
        }

        .truck-hero {
            padding: 23px 18px 20px;
        }

        .truck-hero-title {
            font-size: 25px;
        }

        .truck-hero-stats {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 500px) {

        .truck-page-title h1 {
            font-size: 24px;
        }

        .truck-visual {
            transform: translate(-48%, -42%) scale(.82);
        }

        .truck-card-header,
        .truck-card-body {
            padding-left: 15px;
            padding-right: 15px;
        }

    }

</style>


<div class="truck-profile-page">


    {{-- =========================================================
         TOP HEADER
    ========================================================== --}}

    <div class="truck-page-header">

        <div class="truck-page-title">

            <h1>
                Truck Profile
            </h1>

            <p>
                Vehicle details, documents, assignment history and profitability
            </p>

        </div>


        <div class="truck-page-actions">

            <a
                href="{{ route('vehicles.index') }}"
                class="back-btn"
            >
                ← Back
            </a>

            <a
                href="{{ route('vehicles.edit', $vehicle->id) }}"
                class="edit-truck-btn"
            >
                Edit Truck
            </a>

        </div>

    </div>


    {{-- =========================================================
         MAIN LAYOUT
    ========================================================== --}}

    <div class="truck-main-layout">


        {{-- =====================================================
             LEFT SIDE
        ====================================================== --}}

        <div>


            {{-- ================= HERO ================= --}}

            <div class="truck-hero">

                <div class="truck-hero-content">

                    <h2 class="truck-hero-title">

                        {{ $vehicle->plate_number ?? 'Vehicle' }}

                        @if($vehicle->brand || $vehicle->model)

                            ·
                            {{ $vehicle->brand ?? '' }}
                            {{ $vehicle->model ?? '' }}

                        @endif

                    </h2>


                    @php
                        $latestAssignment = $vehicle->assignments
                            ->sortByDesc('loading_date')
                            ->first();
                    @endphp


                    <div class="truck-hero-subtitle">

                        {{ $vehicle->vehicle_type ?? 'Truck' }}

                        @if($vehicle->load_capacity !== null)

                            · {{ $vehicle->load_capacity }}
                            {{ $vehicle->load_capacity_unit ?? '' }}
                            Capacity

                        @elseif($vehicle->capacity !== null)

                            · {{ $vehicle->capacity }} Capacity

                        @endif

                        @if($latestAssignment?->driver?->driver_name)

                            · Driver:
                            {{ $latestAssignment->driver->driver_name }}

                        @endif

                    </div>


                    <div class="truck-hero-stats">


                        {{-- STATUS --}}

                        <div class="truck-hero-stat">

                            <div class="truck-hero-stat-label">
                                Status
                            </div>

                            <div class="truck-hero-stat-value">
                                {{ $vehicle->status ?? '-' }}
                            </div>

                        </div>


                        {{-- MONTHLY RATE --}}

                        <div class="truck-hero-stat">

                            <div class="truck-hero-stat-label">
                                Monthly Rate
                            </div>

                            <div class="truck-hero-stat-value">

                                @if(
                                    isset($vehicle->monthly_rate) &&
                                    $vehicle->monthly_rate !== null
                                )

                                    AED {{ number_format($vehicle->monthly_rate, 0) }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        {{-- PROFIT --}}

                        <div class="truck-hero-stat">

                            <div class="truck-hero-stat-label">
                                Profit
                            </div>

                            <div class="truck-hero-stat-value">

                                @if(
                                    isset($vehicle->profit) &&
                                    $vehicle->profit !== null
                                )

                                    AED {{ number_format($vehicle->profit, 0) }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                    </div>

                </div>

            </div>


            {{-- =================================================
                 ASSIGNMENT HISTORY
            ================================================== --}}

            <div class="truck-white-card">

                <div class="truck-card-header">

                    <h3>
                        Assignment History
                    </h3>

                </div>


                <div class="truck-card-body">

                    <div class="assignment-table-wrapper">

                        <table class="assignment-table">

                            <thead>

                                <tr>

                                    <th>CLIENT</th>
                                    <th>DRIVER</th>
                                    <th>PERIOD</th>
                                    <th>BILLING</th>
                                    <th>AMOUNT</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $vehicle->assignments
                                    ->sortByDesc('loading_date')
                                    as $assignment
                                )

                                    <tr>

                                        <td class="assignment-client">
                                            {{ $assignment->client?->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $assignment->driver?->driver_name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $assignment->loading_date ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $assignment->billing_type ?? '-' }}
                                        </td>

                                        <td class="assignment-amount">

                                            @if(
                                                isset($assignment->amount) &&
                                                $assignment->amount !== null
                                            )

                                                AED {{ number_format($assignment->amount, 0) }}

                                            @else

                                                -

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="assignment-empty"
                                        >
                                            No driver assignment records found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </div>
        {{-- =====================================================
             RIGHT SIDE
        ====================================================== --}}

        <div>


            {{-- ================= TRUCK VISUAL ================= --}}

            <div class="truck-visual-card">

                <div class="truck-visual">

                    <div class="truck-ground"></div>

                    <div class="truck-cabin">

                        <div class="truck-window"></div>

                    </div>

                    <div class="truck-front"></div>

                    <div class="truck-body">

                        <div class="truck-body-line"></div>

                    </div>

                    <div class="truck-wheel truck-wheel-one"></div>

                    <div class="truck-wheel truck-wheel-two"></div>

                </div>

            </div>


            {{-- =================================================
                 COMPLIANCE DOCUMENTS
            ================================================== --}}

            <div class="truck-white-card">

                <div class="truck-card-header">

                    <h3>
                        Compliance Documents
                    </h3>

                </div>


                <div class="truck-card-body">

                    <div class="compliance-list">


                        {{-- REGISTRATION --}}

                        <div class="compliance-row">

                            <span class="compliance-label">
                                Registration Expiry
                            </span>

                            <span class="compliance-value">
                                {{ $vehicle->registration_expiry ?? '-' }}
                            </span>

                        </div>


                        {{-- INSURANCE --}}

                        <div class="compliance-row">

                            <span class="compliance-label">
                                Insurance Expiry
                            </span>

                            <span class="compliance-value">
                                {{ $vehicle->insurance_expiry ?? '-' }}
                            </span>

                        </div>


                        {{-- PERMIT --}}

                        <div class="compliance-row">

                            <span class="compliance-label">
                                Permit Expiry
                            </span>

                            <span class="compliance-value">
                                {{ $vehicle->permit_expiry ?? '-' }}
                            </span>

                        </div>


                        {{-- SALIK TAG --}}

                        <div class="compliance-row">

                            <span class="compliance-label">
                                Salik Tag
                            </span>

                            <span class="compliance-value">
                                {{ $vehicle->salik_tag ?? '-' }}
                            </span>

                        </div>


                    </div>

                </div>

            </div>


        </div>


    </div>

</div>

@endsection