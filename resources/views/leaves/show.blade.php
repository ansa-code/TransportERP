@extends('layouts.app')

@section('title', 'Leave Details')

@section('content')

<style>
    .leave-show-page {
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
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #101d42;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 9px;
        align-items: center;
    }

    .back-btn,
    .edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 750;
        text-decoration: none;
        white-space: nowrap;
    }

    .back-btn {
        background: #fff;
        color: #475569;
        border: 1px solid #d8dee8;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #101d42;
    }

    .edit-btn {
        background: #2563eb;
        color: #fff;
        border: 1px solid #2563eb;
    }

    .edit-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }


    /* BLUE HERO */

    .leave-hero {
        position: relative;
        overflow: hidden;

        background: linear-gradient(
            135deg,
            #101d42 0%,
            #193b8f 100%
        );

        border-radius: 18px;

        padding: 28px;

        color: #fff;

        margin-bottom: 22px;

        box-shadow: 0 12px 30px rgba(16, 29, 66, .18);
    }

    .leave-hero::after {
        content: '';

        position: absolute;

        width: 260px;
        height: 260px;

        right: -90px;
        top: -120px;

        border-radius: 50%;

        background: rgba(255, 255, 255, .06);
    }

    .hero-top {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 22px;
    }

    .hero-content {
        display: flex;
        align-items: center;
        gap: 22px;

        min-width: 0;
    }

    .leave-avatar {
        width: 76px;
        height: 76px;
        flex: 0 0 76px;

        border-radius: 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(
            135deg,
            #087cff,
            #18b6a4
        );

        color: #fff;

        font-size: 32px;
        font-weight: 800;

        box-shadow: 0 8px 20px rgba(0, 0, 0, .18);
    }

    .hero-info {
        min-width: 0;
    }

    .hero-label {
        margin-bottom: 5px;

        color: rgba(255, 255, 255, .68);

        font-size: 12px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .hero-driver {
        margin: 0;

        color: #fff;

        font-size: 24px;
        font-weight: 800;
    }

    .hero-meta {
        display: flex;
        flex-wrap: wrap;

        gap: 9px;

        margin-top: 9px;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;

        padding: 6px 10px;

        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 999px;

        background: rgba(255, 255, 255, .09);

        color: rgba(255, 255, 255, .88);

        font-size: 11px;
        font-weight: 650;
    }


    /* HERO STATUS */

    .hero-status {
        position: relative;
        z-index: 3;

        flex-shrink: 0;
    }

    .hero-status .status-badge {
        border: 1px solid rgba(255, 255, 255, .18);
    }


    /* SUMMARY CARDS INSIDE HERO */

    .summary-grid {
        position: relative;
        z-index: 2;

        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 14px;

        margin-top: 24px;
    }

    .summary-card {
        min-width: 0;

        display: flex;
        align-items: center;

        gap: 11px;

        padding: 15px;

        border-radius: 12px;

        background: rgba(255, 255, 255, .10);

        border: 1px solid rgba(255, 255, 255, .10);

        overflow: hidden;
    }

    .summary-icon {
        width: 41px;
        height: 41px;
        min-width: 41px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: rgba(255, 255, 255, .13);

        font-size: 18px;
    }

    .summary-content {
        min-width: 0;
        overflow: hidden;
    }

    .summary-label {
        display: block;

        margin-bottom: 5px;

        color: rgba(255, 255, 255, .70);

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .5px;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .summary-value {
        display: block;

        color: #fff;

        font-size: 15px;
        font-weight: 800;

        line-height: 1.2;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    /* STATUS */

    .status-badge {
        display: inline-flex;
        align-items: center;

        padding: 6px 11px;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 750;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-approved {
        background: #ecfdf5;
        color: #047857;
    }

    .status-rejected {
        background: #fef2f2;
        color: #b91c1c;
    }

    .status-cancelled {
        background: #f1f5f9;
        color: #475569;
    }


    /* DETAILS */

    .details-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 20px;
    }

    .detail-card {
        min-width: 0;

        background: #fff;

        border: 1px solid #e5e7eb;
        border-radius: 14px;

        padding: 22px;

        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);

        overflow: hidden;
    }

    .detail-title {
        margin: 0 0 18px;

        color: #101d42;

        font-size: 17px;
        font-weight: 800;
    }

    .detail-list {
        display: grid;
        gap: 0;
    }

    .detail-row {
        display: grid;

        grid-template-columns: 160px minmax(0, 1fr);

        gap: 18px;

        padding: 13px 0;

        border-bottom: 1px solid #eef2f7;
    }

    .detail-row:first-child {
        padding-top: 0;
    }

    .detail-row:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .detail-label {
        color: #64748b;

        font-size: 12px;
        font-weight: 650;
    }

    .detail-value {
        min-width: 0;

        color: #334155;

        font-size: 13px;
        font-weight: 650;

        word-break: break-word;
    }

    .driver-link {
        color: #2563eb;

        text-decoration: none;

        font-weight: 750;
    }

    .driver-link:hover {
        text-decoration: underline;
    }


    /* REPLACEMENT */

    .replacement-box {
        margin-top: 18px;

        padding: 15px;

        border-radius: 10px;

        background: #f8fafc;

        border: 1px solid #e2e8f0;
    }

    .replacement-title {
        margin: 0 0 6px;

        color: #101d42;

        font-size: 12px;
        font-weight: 800;
    }

    .replacement-text {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    /* NOTES */

    .notes-box {
        margin-top: 18px;

        padding: 16px;

        border-radius: 10px;

        background: #f8fafc;

        border: 1px solid #e2e8f0;
    }

    .notes-title {
        margin: 0 0 7px;

        color: #101d42;

        font-size: 12px;
        font-weight: 800;
    }

    .notes-text {
        margin: 0;

        color: #475569;

        font-size: 13px;

        line-height: 1.7;

        white-space: pre-wrap;

        overflow-wrap: anywhere;
    }

    .empty-value {
        color: #94a3b8;
        font-weight: 500;
    }


    /* TABLET */

    @media (max-width: 1000px) {

        .summary-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .details-grid {
            grid-template-columns: 1fr;
        }
    }


    /* MOBILE */

    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .back-btn,
        .edit-btn {
            flex: 1;
        }

        .leave-hero {
            padding: 22px;
        }

        .hero-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-content {
            align-items: flex-start;
        }

        .leave-avatar {
            width: 62px;
            height: 62px;

            flex-basis: 62px;

            font-size: 26px;
        }

        .hero-driver {
            font-size: 20px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .detail-card {
            padding: 18px;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 5px;
        }
    }
</style>


<div class="leave-show-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>

            <h1 class="page-title">
                Leave Details
            </h1>

            <p class="page-subtitle">
                View complete driver leave and approval information
            </p>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('leaves.index') }}"
                class="back-btn"
            >
                ← Back
            </a>

            <a
                href="{{ route('leaves.edit', $leave->id) }}"
                class="edit-btn"
            >
                Edit Leave
            </a>

        </div>

    </div>


    @php

        $statusClass = match ($leave->approval_status) {

            'Pending' => 'status-pending',

            'Approved' => 'status-approved',

            'Rejected' => 'status-rejected',

            'Cancelled' => 'status-cancelled',

            default => 'status-cancelled',

        };


        $driverName = $leave->driver->driver_name ?? 'N/A';


        $initials = collect(
            preg_split('/\s+/', trim($driverName))
        )
        ->filter()
        ->map(
            fn ($word) =>
                strtoupper(substr($word, 0, 1))
        )
        ->take(2)
        ->implode('');

    @endphp


    {{-- BLUE HERO --}}
    <div class="leave-hero">

        {{-- HERO TOP --}}
        <div class="hero-top">

            <div class="hero-content">

                <div class="leave-avatar">
                    {{ $initials ?: 'L' }}
                </div>


                <div class="hero-info">

                    <div class="hero-label">
                        Driver Leave
                    </div>

                    <h2 class="hero-driver">
                        {{ $driverName }}
                    </h2>


                    <div class="hero-meta">

                        @if($leave->driver?->driver_code)

                            <span class="hero-pill">
                                {{ $leave->driver->driver_code }}
                            </span>

                        @endif


                        <span class="hero-pill">
                            {{ $leave->leave_type }}
                        </span>


                        <span class="hero-pill">

                            {{ $leave->start_date?->format('d M Y') }}

                            —

                            {{ $leave->end_date?->format('d M Y') }}

                        </span>

                    </div>

                </div>

            </div>


            {{-- HERO STATUS --}}
            <div class="hero-status">

                <span class="status-badge {{ $statusClass }}">
                    {{ $leave->approval_status }}
                </span>

            </div>

        </div>


        {{-- FOUR SUMMARY CARDS INSIDE BLUE HERO --}}
        <div class="summary-grid">

            {{-- LEAVE TYPE --}}
            <div class="summary-card">

                <div class="summary-icon">
                    📋
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Leave Type
                    </span>

                    <strong class="summary-value">
                        {{ $leave->leave_type }}
                    </strong>

                </div>

            </div>


            {{-- START DATE --}}
            <div class="summary-card">

                <div class="summary-icon">
                    📅
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Start Date
                    </span>

                    <strong class="summary-value">
                        {{ $leave->start_date?->format('d M Y') ?? '-' }}
                    </strong>

                </div>

            </div>


            {{-- END DATE --}}
            <div class="summary-card">

                <div class="summary-icon">
                    🗓️
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        End Date
                    </span>

                    <strong class="summary-value">
                        {{ $leave->end_date?->format('d M Y') ?? '-' }}
                    </strong>

                </div>

            </div>


            {{-- APPROVAL STATUS --}}
            <div class="summary-card">

                <div class="summary-icon">
                    ✓
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Approval Status
                    </span>

                    <strong class="summary-value">
                        {{ $leave->approval_status }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- DETAILS GRID --}}
    <div class="details-grid">

        {{-- LEAVE INFORMATION --}}
        <div class="detail-card">

            <h2 class="detail-title">
                Leave Information
            </h2>


            <div class="detail-list">

                <div class="detail-row">

                    <div class="detail-label">
                        Driver
                    </div>

                    <div class="detail-value">

                        @if($leave->driver)

                            <a
                                href="{{ route('drivers.show', $leave->driver->id) }}"
                                class="driver-link"
                            >
                                {{ $leave->driver->driver_name }}
                            </a>

                        @else

                            <span class="empty-value">
                                Driver Not Available
                            </span>

                        @endif

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Driver Code
                    </div>

                    <div class="detail-value">
                        {{ $leave->driver->driver_code ?? '-' }}
                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Leave Type
                    </div>

                    <div class="detail-value">
                        {{ $leave->leave_type }}
                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Start Date
                    </div>

                    <div class="detail-value">
                        {{ $leave->start_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        End Date
                    </div>

                    <div class="detail-value">
                        {{ $leave->end_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Approval Status
                    </div>

                    <div class="detail-value">

                        <span class="status-badge {{ $statusClass }}">
                            {{ $leave->approval_status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>
        {{-- REPLACEMENT & APPROVAL --}}
        <div class="detail-card">

            <h2 class="detail-title">
                Replacement & Approval
            </h2>


            <div class="detail-list">

                <div class="detail-row">

                    <div class="detail-label">
                        Replacement Driver
                    </div>

                    <div class="detail-value">

                        @if($leave->replacementDriver)

                            <a
                                href="{{ route('drivers.show', $leave->replacementDriver->id) }}"
                                class="driver-link"
                            >
                                {{ $leave->replacementDriver->driver_name }}
                            </a>

                        @else

                            <span class="empty-value">
                                Not Assigned
                            </span>

                        @endif

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Replacement Code
                    </div>

                    <div class="detail-value">
                        {{ $leave->replacementDriver->driver_code ?? '-' }}
                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Created
                    </div>

                    <div class="detail-value">
                        {{ $leave->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Last Updated
                    </div>

                    <div class="detail-value">
                        {{ $leave->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </div>

                </div>

            </div>


            {{-- APPROVAL NOTES --}}
            <div class="notes-box">

                <h3 class="notes-title">
                    Approval Notes
                </h3>


                @if($leave->approval_notes)

                    <p class="notes-text">
                        {{ $leave->approval_notes }}
                    </p>

                @else

                    <p class="notes-text empty-value">
                        No approval notes added.
                    </p>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection