@extends('layouts.app')

@section('content')

<style>
    .trip-show-page {
        width: 100%;
        box-sizing: border-box;
    }

    .trip-show-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .trip-show-title h1 {
        margin: 0;
        color: #172554;
        font-size: 26px;
        font-weight: 700;
    }

    .trip-show-title p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .trip-show-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .trip-back-btn,
    .trip-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        box-sizing: border-box;
    }

    .trip-back-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .trip-back-btn:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .trip-edit-btn {
        background: #3b82f6;
        color: #ffffff;
        border: 1px solid #3b82f6;
    }

    .trip-edit-btn:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .trip-show-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .trip-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        overflow: hidden;
    }

    .trip-card.full-width {
        grid-column: 1 / -1;
    }

    .trip-card-header {
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .trip-card-header h3 {
        margin: 0;
        color: #172554;
        font-size: 14px;
        font-weight: 700;
    }

    .trip-card-body {
        padding: 18px;
    }


    /* =========================================
       TRIP INFORMATION - DRIVER PROFILE STYLE
    ========================================= */

    .trip-information-card {
        border: 0;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(135deg, #172554 0%, #1e3a8a 65%, #2563eb 100%);
        box-shadow: 0 6px 18px rgba(23, 37, 84, 0.12);
    }

    .trip-information-card .trip-card-header {
        padding: 14px 20px;
        background: transparent;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .trip-information-card .trip-card-header h3 {
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
    }

    .trip-information-card .trip-card-body {
        padding: 18px 20px 20px;
        background: transparent;
    }

    .trip-information-card .trip-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .trip-information-card .trip-info-item {
        min-width: 0;
        padding: 13px 15px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.10);
        box-sizing: border-box;
    }

    .trip-information-card .trip-info-item label {
        display: block;
        margin-bottom: 5px;
        color: #bfdbfe;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .trip-information-card .trip-info-item span {
        display: block;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .trip-information-card .trip-number-value {
        color: #ffffff !important;
        font-size: 14px !important;
        font-weight: 700 !important;
    }

    .trip-information-card .trip-status {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: fit-content;
        padding: 5px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1d4ed8 !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        white-space: nowrap;
    }


    /* =========================================
       GENERAL INFORMATION
    ========================================= */

    .trip-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 22px;
    }

    .trip-info-item label {
        display: block;
        margin-bottom: 5px;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .trip-info-item span {
        display: block;
        color: #334155;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .trip-number-value {
        color: #172554 !important;
        font-weight: 700 !important;
    }


    /* =========================================
       ROUTE
    ========================================= */

    .trip-route-box {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 15px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
    }

    .trip-location {
        flex: 1;
        min-width: 0;
    }

    .trip-location small {
        display: block;
        margin-bottom: 4px;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .trip-location strong {
        display: block;
        color: #334155;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .trip-route-arrow {
        color: #64748b;
        font-size: 18px;
        font-weight: 700;
        flex-shrink: 0;
    }


    /* =========================================
       STATUS
    ========================================= */

    .trip-status {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: fit-content;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px !important;
        font-weight: 700 !important;
        white-space: nowrap;
    }

    .status-planned {
        background: #fef3c7;
        color: #92400e !important;
    }

    .status-assigned {
        background: #dbeafe;
        color: #1d4ed8 !important;
    }

    .status-in-transit {
        background: #e0e7ff;
        color: #4338ca !important;
    }

    .status-delivered {
        background: #dcfce7;
        color: #166534 !important;
    }

    .status-closed {
        background: #d1fae5;
        color: #065f46 !important;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b !important;
    }


    /* =========================================
       REMARKS / POD
    ========================================= */

    .trip-remarks {
        margin: 0;
        color: #475569;
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-line;
    }

    .trip-pod-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
    }

    .trip-pod-link:hover {
        background: #e2e8f0;
        color: #172554;
    }

    .trip-no-pod {
        color: #94a3b8;
        font-size: 12px;
    }
    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 768px) {

        .trip-show-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .trip-show-actions {
            width: 100%;
        }

        .trip-back-btn,
        .trip-edit-btn {
            flex: 1;
        }

        .trip-show-grid {
            grid-template-columns: 1fr;
        }

        .trip-card.full-width {
            grid-column: auto;
        }

        .trip-info-grid {
            grid-template-columns: 1fr;
        }

        .trip-information-card .trip-info-grid {
            grid-template-columns: 1fr;
        }

        .trip-route-box {
            flex-direction: column;
            align-items: stretch;
        }

        .trip-route-arrow {
            text-align: center;
            transform: rotate(90deg);
        }
    }
</style>


<div class="trip-show-page">

    {{-- =========================
         HEADER
    ========================== --}}

    <div class="trip-show-header">

        <div class="trip-show-title">

            <h1>
                Trip Details
            </h1>

            <p>
                View complete information for this trip
            </p>

        </div>

        <div class="trip-show-actions">

            <a
                href="{{ route('trips.index') }}"
                class="trip-back-btn"
            >
                ← Back to Trips
            </a>

            <a
                href="{{ route('trips.edit', $trip->id) }}"
                class="trip-edit-btn"
            >
                Edit Trip
            </a>

        </div>

    </div>


    {{-- =========================
         INFORMATION
    ========================== --}}

    <div class="trip-show-grid">

        {{-- =========================
             TRIP INFORMATION
        ========================== --}}

        <div class="trip-card trip-information-card">

            <div class="trip-card-header">
                <h3>Trip Information</h3>
            </div>

            <div class="trip-card-body">

                <div class="trip-info-grid">

                    <div class="trip-info-item">

                        <label>Trip No.</label>

                        <span class="trip-number-value">
                            {{ $trip->trip_no }}
                        </span>

                    </div>


                    <div class="trip-info-item">

                        <label>Assignment</label>

                        <span>
                            {{ $trip->assignment?->assignment_no ?? '-' }}
                        </span>

                    </div>


                    <div class="trip-info-item">

                        <label>Trip Start</label>

                        <span>
                            {{ $trip->trip_start
                                ? \Carbon\Carbon::parse($trip->trip_start)->format('d M Y, h:i A')
                                : '-' }}
                        </span>

                    </div>


                    <div class="trip-info-item">

                        <label>Trip End</label>

                        <span>
                            {{ $trip->trip_end
                                ? \Carbon\Carbon::parse($trip->trip_end)->format('d M Y, h:i A')
                                : '-' }}
                        </span>

                    </div>


                    <div class="trip-info-item">

                        <label>Status</label>

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

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             CLIENT & FLEET
        ========================== --}}

        <div class="trip-card">

            <div class="trip-card-header">
                <h3>Client & Fleet</h3>
            </div>

            <div class="trip-card-body">

                <div class="trip-info-grid">

                    <div class="trip-info-item">

                        <label>Client</label>

                        <span>
                            {{ $trip->client?->client_name ?? '-' }}
                        </span>

                    </div>


                    <div class="trip-info-item">

                        <label>Vehicle</label>

                        <span>
                            {{ $trip->vehicle?->plate_number
                                ?? $trip->vehicle?->vehicle_number
                                ?? '-' }}
                        </span>

                    </div>


                    <div class="trip-info-item">

                        <label>Driver</label>

                        <span>
                            {{ $trip->driver?->driver_name ?? '-' }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             ROUTE INFORMATION
        ========================== --}}

        <div class="trip-card full-width">

            <div class="trip-card-header">
                <h3>Route Information</h3>
            </div>

            <div class="trip-card-body">

                <div class="trip-route-box">

                    <div class="trip-location">

                        <small>Loading Point</small>

                        <strong>
                            {{ $trip->loading_point ?? '-' }}
                        </strong>

                    </div>


                    <div class="trip-route-arrow">
                        →
                    </div>


                    <div class="trip-location">

                        <small>Unloading Point</small>

                        <strong>
                            {{ $trip->unloading_point ?? '-' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             BILLING
        ========================== --}}

        <div class="trip-card">

            <div class="trip-card-header">
                <h3>Billing Information</h3>
            </div>

            <div class="trip-card-body">

                <div class="trip-info-grid">

                    <div class="trip-info-item">

                        <label>Rate</label>

                        <span>
                            AED {{ number_format($trip->rate ?? 0, 2) }}
                        </span>

                    </div>


                    <div class="trip-info-item">

                        <label>Freight Amount</label>

                        <span>
                            AED {{ number_format($trip->freight_amount ?? 0, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             POD
        ========================== --}}

        <div class="trip-card">

            <div class="trip-card-header">
                <h3>Delivery Document</h3>
            </div>

            <div class="trip-card-body">

                @if($trip->pod_file)

                    <a
                        href="{{ asset('storage/' . $trip->pod_file) }}"
                        target="_blank"
                        class="trip-pod-link"
                    >
                        📄 View POD Document
                    </a>

                @else

                    <span class="trip-no-pod">
                        No POD document uploaded.
                    </span>

                @endif

            </div>

        </div>


        {{-- =========================
             REMARKS
        ========================== --}}

        <div class="trip-card full-width">

            <div class="trip-card-header">
                <h3>Remarks</h3>
            </div>

            <div class="trip-card-body">

                @if($trip->remarks)

                    <p class="trip-remarks">
                        {{ $trip->remarks }}
                    </p>

                @else

                    <span class="trip-no-pod">
                        No remarks added.
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection