@extends('layouts.app')

@section('content')

<style>

    .driver-profile-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ================= HEADER ================= */

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .profile-title h1 {
        margin: 0;
        color: #1d3557;
        font-size: 28px;
        font-weight: 800;
    }

    .profile-title p {
        margin: 6px 0 0;
        color: #6c757d;
        font-size: 14px;
    }

    .profile-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .back-btn,
    .edit-profile-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .back-btn {
        background: #6c757d;
        color: white;
    }

    .back-btn:hover {
        background: #5c636a;
        color: white;
    }

    .edit-profile-btn {
        background: #0d6efd;
        color: white;
    }

    .edit-profile-btn:hover {
        background: #0b5ed7;
        color: white;
    }


    /* ================= HERO ================= */

    .driver-hero {
        background: linear-gradient(
            135deg,
            #101d42 0%,
            #193b8f 100%
        );
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
        color: white;
        box-shadow: 0 4px 16px rgba(0,0,0,.10);
        position: relative;
        overflow: hidden;
    }

    .driver-hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -70px;
        bottom: -120px;
        border-radius: 50%;
        background: rgba(42, 111, 255, .25);
        pointer-events: none;
    }

    /*
     * Top identity row:
     * Driver information on left
     * Status on right
     */
    .hero-top {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 25px;
    }

    .driver-identity {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .driver-avatar {
        width: 66px;
        height: 66px;
        border-radius: 15px;
        background: linear-gradient(
            135deg,
            #087cff,
            #18b6a4
        );
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 27px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .driver-identity-text h2 {
        margin: 0;
        color: white;
        font-size: 25px;
        font-weight: 800;
        line-height: 1.1;
    }

    .driver-meta {
        margin-top: 7px;
        color: #b7c5dc;
        font-size: 13px;
        font-weight: 600;
    }

    /*
     * Status is now positioned on the right side
     * of the hero, like the Leave Details page.
     */
    .driver-status-wrapper {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    .driver-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 14px;
        border-radius: 20px;
        background: #d1e7dd;
        color: #0f5132;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .driver-status.leave {
        background: #fff3cd;
        color: #664d03;
    }

    .driver-status.inactive {
        background: #f8d7da;
        color: #842029;
    }

    .driver-status.archived {
        background: #e2e3e5;
        color: #41464b;
    }


    /* ================= HERO MINI CARDS ================= */

    /*
     * Cards are now BELOW the identity/status row,
     * matching the Leave Details hero layout.
     */
    .hero-cards {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        width: 100%;
        margin-top: 22px;
    }

    .hero-card {
        min-height: 76px;
        padding: 13px 14px;
        border-radius: 12px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.12);
        box-sizing: border-box;
    }

    .hero-card-label {
        color: #b7c5dc;
        font-size: 11px;
        margin-bottom: 5px;
    }

    .hero-card-value {
        color: white;
        font-size: 18px;
        font-weight: 800;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    /* ================= MAIN GRID ================= */

    .profile-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
        gap: 20px;
        align-items: start;
    }

    .profile-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .profile-card:last-child {
        margin-bottom: 0;
    }

    .card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .card-header h3 {
        margin: 0;
        color: #1d3557;
        font-size: 17px;
        font-weight: 800;
    }

    .card-body {
        padding: 20px;
    }


    /* ================= DRIVER DETAILS ================= */

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 35px;
        row-gap: 18px;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-label {
        color: #7a8794;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 5px;
        letter-spacing: .3px;
    }

    .detail-value {
        color: #27364a;
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }


    /* ================= SALARY ================= */

    .salary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0f2;
    }

    .salary-row:first-child {
        padding-top: 0;
    }

    .salary-row:last-child {
        border-bottom: none;
    }

    .salary-label {
        color: #6c757d;
        font-size: 13px;
        font-weight: 600;
    }

    .salary-value {
        color: #1d3557;
        font-size: 14px;
        font-weight: 800;
        white-space: nowrap;
    }

    .salary-total {
        margin-top: 4px;
        padding-top: 16px;
        border-top: 2px solid #edf0f2;
    }

    .salary-total .salary-label,
    .salary-total .salary-value {
        color: #1d3557;
        font-size: 15px;
        font-weight: 800;
    }


    /* ================= LEAVE ================= */

    .leave-circle-wrapper {
        display: flex;
        justify-content: center;
        padding: 5px 0 15px;
    }

    .leave-circle {
        width: 145px;
        height: 145px;
        border-radius: 50%;
        background: conic-gradient(
            #10b981 0deg,
            #10b981 96deg,
            #dfe6ee 96deg,
            #dfe6ee 360deg
        );
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .leave-circle::before {
        content: "";
        position: absolute;
        width: 108px;
        height: 108px;
        background: white;
        border-radius: 50%;
    }

    .leave-number {
        position: relative;
        z-index: 1;
        color: #17233d;
        font-size: 25px;
        font-weight: 800;
    }

    .leave-note {
        color: #7a8794;
        font-size: 12px;
        line-height: 1.5;
    }


    /* ================= RESPONSIVE ================= */

    @media (max-width: 1050px) {

        .hero-top {
            align-items: flex-start;
        }

        .profile-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 700px) {

        .profile-header {
            flex-direction: column;
        }

        .profile-header-actions {
            width: 100%;
        }

        .back-btn,
        .edit-profile-btn {
            flex: 1;
        }

        .hero-top {
            align-items: flex-start;
        }

        .driver-identity {
            align-items: flex-start;
        }

        .hero-cards {
            grid-template-columns: 1fr;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

    }

</style>


<div class="driver-profile-page">


    {{-- ================= PAGE HEADER ================= --}}

    <div class="profile-header">

        <div class="profile-title">

            <h1>
                Driver Profile
            </h1>

            <p>
                Visa, passport custody, salary, leave and assigned truck details
            </p>

        </div>


        <div class="profile-header-actions">

            <a
                href="{{ route('drivers.index') }}"
                class="back-btn"
            >
                ← Back to Drivers
            </a>

            <a
                href="{{ route('drivers.edit', $driver->id) }}"
                class="edit-profile-btn"
            >
                Update Driver
            </a>

        </div>

    </div>


    {{-- ================= DRIVER HERO ================= --}}

    <div class="driver-hero">

        {{-- ================= IDENTITY + STATUS ROW ================= --}}

        <div class="hero-top">


            {{-- Driver Identity --}}

            <div class="driver-identity">

                <div class="driver-avatar">
                    {{ strtoupper(substr($driver->driver_name ?? 'D', 0, 1)) }}
                </div>


                <div class="driver-identity-text">

                    <h2>
                        {{ $driver->driver_name ?? 'Driver' }}
                    </h2>

                    <div class="driver-meta">

                        {{ $driver->driver_code ?? '-' }}

                        @if($driver->nationality)
                            · {{ $driver->nationality }}
                        @endif

                        @if($driver->assigned_vehicle_id)
                            · Assigned Vehicle #{{ $driver->assigned_vehicle_id }}
                        @endif

                    </div>

                </div>

            </div>


            {{-- Driver Status --}}

            <div class="driver-status-wrapper">

                @php
                    $driverStatus = $driver->employment_status ?? 'Unknown';
                @endphp


                @if($driverStatus === 'Active')

                    <span class="driver-status">
                        Active
                    </span>

                @elseif($driverStatus === 'On Leave')

                    <span class="driver-status leave">
                        On Leave
                    </span>

                @elseif($driverStatus === 'Inactive')

                    <span class="driver-status inactive">
                        Inactive
                    </span>

                @else

                    <span class="driver-status archived">
                        {{ $driverStatus }}
                    </span>

                @endif

            </div>

        </div>


        {{-- ================= HERO CARDS ================= --}}

        <div class="hero-cards">


            <div class="hero-card">

                <div class="hero-card-label">
                    Visa
                </div>

                <div class="hero-card-value">
                    {{ $driver->visa_type ?? '-' }}
                </div>

            </div>


            <div class="hero-card">

                <div class="hero-card-label">
                    Passport
                </div>

                <div class="hero-card-value">

                    @if($driver->passport_held_by_company)
                        Held
                    @else
                        Driver
                    @endif

                </div>

            </div>


            <div class="hero-card">

                <div class="hero-card-label">
                    Salary
                </div>

                <div class="hero-card-value">
                    AED {{ number_format($driver->basic_salary ?? 0, 0) }}
                </div>

            </div>


        </div>

    </div>


    {{-- ================= PROFILE CONTENT ================= --}}

    <div class="profile-grid">


        {{-- ================= LEFT COLUMN ================= --}}

        <div>


            {{-- Driver Details --}}

            <div class="profile-card">

                <div class="card-header">

                    <h3>
                        Driver Details
                    </h3>

                </div>


                <div class="card-body">

                    <div class="details-grid">


                        <div class="detail-item">
                            <div class="detail-label">Driver ID</div>
                            <div class="detail-value">
                                #{{ $driver->id }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Driver Code</div>
                            <div class="detail-value">
                                {{ $driver->driver_code ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">CNIC</div>
                            <div class="detail-value">
                                {{ $driver->cnic ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value">
                                {{ $driver->phone ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Nationality</div>
                            <div class="detail-value">
                                {{ $driver->nationality ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Date of Birth</div>
                            <div class="detail-value">
                                {{ $driver->date_of_birth ?? '-' }}
                            </div>
                        </div>


        <div class="detail-item">
                            <div class="detail-label">Joining Date</div>
                            <div class="detail-value">
                                {{ $driver->joining_date ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Employment Status</div>
                            <div class="detail-value">
                                {{ $driver->employment_status ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">License Number</div>
                            <div class="detail-value">
                                {{ $driver->license_number ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">License Expiry</div>
                            <div class="detail-value">
                                {{ $driver->license_expiry ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Passport Number</div>
                            <div class="detail-value">
                                {{ $driver->passport_number ?? '-' }}
                            </div>
                        </div>


        <div class="detail-item">
                            <div class="detail-label">Passport Custody</div>
                            <div class="detail-value">

                                @if($driver->passport_held_by_company)
                                    Company
                                @else
                                    Driver
                                @endif

                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Visa Type</div>
                            <div class="detail-value">
                                {{ $driver->visa_type ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Visa Provided By</div>
                            <div class="detail-value">
                                {{ $driver->visa_provided_by ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Visa Expiry</div>
                            <div class="detail-value">
                                {{ $driver->visa_expiry ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Emirates ID</div>
                            <div class="detail-value">
                                {{ $driver->emirates_id ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Address</div>
                            <div class="detail-value">
                                {{ $driver->address ?? '-' }}
                            </div>
                        </div>


                        <div class="detail-item">
                            <div class="detail-label">Remarks</div>
                            <div class="detail-value">
                                {{ $driver->remarks ?? '-' }}
                            </div>
                        </div>


                    </div>

                </div>

            </div>


        </div>
        {{-- ================= RIGHT COLUMN ================= --}}

        <div>


            {{-- Salary Structure --}}

            <div class="profile-card">

                <div class="card-header">

                    <h3>
                        Salary Structure
                    </h3>

                </div>


                <div class="card-body">


                    <div class="salary-row">

                        <span class="salary-label">
                            Basic Salary
                        </span>

                        <span class="salary-value">
                            AED {{ number_format($driver->basic_salary ?? 0, 2) }}
                        </span>

                    </div>


                    <div class="salary-row">

                        <span class="salary-label">
                            Monthly Salary
                        </span>

                        <span class="salary-value">
                            AED {{ number_format($driver->basic_salary ?? 0, 2) }}
                        </span>

                    </div>


                    <div class="salary-row salary-total">

                        <span class="salary-label">
                            Total Basic
                        </span>

                        <span class="salary-value">
                            AED {{ number_format($driver->basic_salary ?? 0, 2) }}
                        </span>

                    </div>


                </div>

            </div>



            {{-- Leave Summary --}}

            <div class="profile-card">

                <div class="card-header">

                    <h3>
                        Leave Summary
                    </h3>

                </div>


                <div class="card-body">


                    @php

                        $leaveUsed = 0;
                        $leaveTotal = 0;
                        $leaveRemaining = 0;

                    @endphp


                    <div class="leave-stats"
                         style="
                            display:grid;
                            grid-template-columns:repeat(3,1fr);
                            gap:10px;
                            margin-top:2px;
                         ">


                        <div
                            style="
                                background:#f8fafc;
                                border-radius:8px;
                                padding:16px 8px;
                                text-align:center;
                            "
                        >

                            <div
                                style="
                                    color:#1d3557;
                                    font-size:20px;
                                    font-weight:800;
                                "
                            >
                                {{ $leaveUsed }}
                            </div>

                            <div
                                style="
                                    color:#6c757d;
                                    font-size:11px;
                                    font-weight:600;
                                    margin-top:4px;
                                "
                            >
                                Used
                            </div>

                        </div>


                        <div
                            style="
                                background:#f8fafc;
                                border-radius:8px;
                                padding:16px 8px;
                                text-align:center;
                            "
                        >

                            <div
                                style="
                                    color:#1d3557;
                                    font-size:20px;
                                    font-weight:800;
                                "
                            >
                                {{ $leaveRemaining }}
                            </div>

                            <div
                                style="
                                    color:#6c757d;
                                    font-size:11px;
                                    font-weight:600;
                                    margin-top:4px;
                                "
                            >
                                Remaining
                            </div>

                        </div>


                        <div
                            style="
                                background:#f8fafc;
                                border-radius:8px;
                                padding:16px 8px;
                                text-align:center;
                            "
                        >

                            <div
                                style="
                                    color:#1d3557;
                                    font-size:20px;
                                    font-weight:800;
                                "
                            >
                                {{ $leaveTotal }}
                            </div>

                            <div
                                style="
                                    color:#6c757d;
                                    font-size:11px;
                                    font-weight:600;
                                    margin-top:4px;
                                "
                            >
                                Total
                            </div>

                        </div>


                    </div>


                </div>

            </div>


        </div>


    </div>


</div>


@endsection