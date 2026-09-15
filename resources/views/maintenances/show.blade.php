@extends('layouts.app')

@section('content')

<div class="maintenance-profile-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h1>Maintenance Details</h1>
            <p>View maintenance job, cost breakdown and service information.</p>
        </div>

        <div class="header-actions">

            <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">
                ← Back to Maintenance
            </a>

            <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-primary">
                ✎ Update Maintenance
            </a>

        </div>

    </div>


    {{-- BLUE HERO --}}
    <div class="maintenance-hero">

        {{-- HERO TOP --}}
        <div class="hero-top">

            <div class="hero-main">

                <div class="maintenance-icon">
                    🔧
                </div>

                <div class="hero-info">

                    <span class="hero-label">
                        MAINTENANCE JOB
                    </span>

                    <h2>
                        {{ $maintenance->maintenance_no }}
                    </h2>

                    <p>
                        {{ $maintenance->vehicle->plate_number ?? 'Vehicle Not Available' }}
                    </p>

                </div>

            </div>


            <div class="hero-status">

                <span class="status-badge
                    @if($maintenance->status === 'Completed')
                        completed
                    @elseif($maintenance->status === 'In Progress')
                        progress
                    @elseif($maintenance->status === 'Cancelled')
                        cancelled
                    @else
                        open
                    @endif
                ">
                    {{ $maintenance->status }}
                </span>

            </div>

        </div>


        {{-- SUMMARY CARDS INSIDE BLUE HERO --}}
        <div class="summary-grid">

            {{-- REPAIR TYPE --}}
            <div class="summary-card">

                <div class="summary-icon">
                    🔧
                </div>

                <div class="summary-content">

                    <span>
                        Repair Type
                    </span>

                    <strong>
                        {{ $maintenance->repair_type }}
                    </strong>

                </div>

            </div>


            {{-- MAINTENANCE DATE --}}
            <div class="summary-card">

                <div class="summary-icon">
                    📅
                </div>

                <div class="summary-content">

                    <span>
                        Maintenance Date
                    </span>

                    <strong>
                        {{ $maintenance->maintenance_date?->format('d M Y') ?? '—' }}
                    </strong>

                </div>

            </div>


            {{-- ODOMETER --}}
            <div class="summary-card">

                <div class="summary-icon">
                    🚗
                </div>

                <div class="summary-content">

                    <span>
                        Odometer
                    </span>

                    <strong>
                        {{ $maintenance->odometer !== null
                            ? number_format($maintenance->odometer) . ' km'
                            : '—' }}
                    </strong>

                </div>

            </div>


            {{-- TOTAL COST --}}
            <div class="summary-card cost-highlight">

                <div class="summary-icon">
                    💰
                </div>

                <div class="summary-content">

                    <span>
                        Total Cost
                    </span>

                    <strong>
                        AED {{ number_format((float) $maintenance->total_cost, 2) }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- MAIN DETAILS AREA --}}
    <div class="details-area">


        {{-- MAINTENANCE INFORMATION + SERVICE SCHEDULE --}}
        <div class="content-grid">

            {{-- MAINTENANCE INFORMATION --}}
            <div class="profile-card">

                <div class="card-header">

                    <div>
                        <h3>
                            Maintenance Information
                        </h3>

                        <p>
                            Basic details of this maintenance job.
                        </p>
                    </div>

                </div>


                <div class="detail-grid">

                    <div class="detail-item">

                        <label>
                            Maintenance Number
                        </label>

                        <strong>
                            {{ $maintenance->maintenance_no }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Vehicle
                        </label>

                        <strong>
                            {{ $maintenance->vehicle->plate_number ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Maintenance Date
                        </label>

                        <strong>
                            {{ $maintenance->maintenance_date?->format('d M Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Repair Type
                        </label>

                        <strong>
                            {{ $maintenance->repair_type }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Workshop / Vendor
                        </label>

                        <strong>
                            {{ $maintenance->workshop ?: '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Odometer
                        </label>

                        <strong>
                            {{ $maintenance->odometer !== null
                                ? number_format($maintenance->odometer) . ' km'
                                : '—' }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- SERVICE SCHEDULE --}}
            <div class="profile-card">

                <div class="card-header">

                    <div>
                        <h3>
                            Service Schedule
                        </h3>

                        <p>
                            Downtime and next service information.
                        </p>
                    </div>

                </div>


                <div class="detail-grid">

                    <div class="detail-item">

                        <label>
                            Out of Service From
                        </label>

                        <strong>
                            {{ $maintenance->out_of_service_start?->format('d M Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Out of Service To
                        </label>

                        <strong>
                            {{ $maintenance->out_of_service_end?->format('d M Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Next Service Date
                        </label>

                        <strong>
                            {{ $maintenance->next_service_date?->format('d M Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <label>
                            Status
                        </label>

                        <strong>
                            {{ $maintenance->status }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- COST BREAKDOWN --}}
        <div class="profile-card cost-card">

            <div class="card-header">

                <div>
                    <h3>
                        Cost Breakdown
                    </h3>

                    <p>
                        Separate parts, labour and other maintenance costs.
                    </p>
                </div>

            </div>


            <div class="cost-grid">

                <div class="cost-item">

                    <span>
                        Parts Cost
                    </span>

                    <strong>
                        AED {{ number_format((float) $maintenance->parts_cost, 2) }}
                    </strong>

                </div>


                <div class="cost-item">

                    <span>
                        Labour Cost
                    </span>

                    <strong>
                        AED {{ number_format((float) $maintenance->labour_cost, 2) }}
                    </strong>

                </div>


                <div class="cost-item">

                    <span>
                        Other Cost
                    </span>

                    <strong>
                        AED {{ number_format((float) $maintenance->other_cost, 2) }}
                    </strong>

                </div>


                <div class="cost-item total">

                    <span>
                        Total Maintenance Cost
                    </span>

                    <strong>
                        AED {{ number_format((float) $maintenance->total_cost, 2) }}
                    </strong>

                </div>

            </div>

        </div>
        {{-- DOCUMENT + REMARKS --}}
        <div class="content-grid">

            {{-- INVOICE / RECEIPT --}}
            <div class="profile-card">

                <div class="card-header">

                    <div>
                        <h3>
                            Invoice / Receipt
                        </h3>

                        <p>
                            Supporting finance document for this maintenance job.
                        </p>
                    </div>

                </div>


                <div class="document-box">

                    @if($maintenance->invoice_receipt)

                        <div class="document-value">

                            <span class="document-icon">
                                📄
                            </span>

                            <span>
                                {{ $maintenance->invoice_receipt }}
                            </span>

                        </div>

                    @else

                        <div class="empty-state">
                            No invoice or receipt recorded.
                        </div>

                    @endif

                </div>

            </div>


            {{-- REMARKS --}}
            <div class="profile-card">

                <div class="card-header">

                    <div>
                        <h3>
                            Remarks
                        </h3>

                        <p>
                            Additional notes related to this maintenance job.
                        </p>
                    </div>

                </div>


                <div class="remarks-box">

                    @if($maintenance->remarks)

                        {{ $maintenance->remarks }}

                    @else

                        <span class="empty-text">
                            No remarks recorded.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- FOOTER ACTIONS --}}
        <div class="bottom-actions">

            <a
                href="{{ route('maintenances.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Maintenance
            </a>

            <a
                href="{{ route('maintenances.edit', $maintenance->id) }}"
                class="btn btn-primary"
            >
                ✎ Edit Maintenance
            </a>

        </div>


    </div>

</div>


<style>
    * {
        box-sizing: border-box;
    }

    .maintenance-profile-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 10px 0 40px;
        min-width: 0;
    }


    /* PAGE HEADER */

    .page-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #101d42;
        font-size: 28px;
        font-weight: 800;
    }

    .page-header p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .header-actions,
    .bottom-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }


    /* BUTTONS */

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 40px;
        padding: 0 17px;

        border-radius: 9px;
        border: none;

        text-decoration: none;

        font-size: 13px;
        font-weight: 700;

        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background: #101d42;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #193b8f;
    }

    .btn-secondary {
        background: #eef2f7;
        color: #25324d;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }


    /* BLUE HERO */

    .maintenance-hero {
        width: 100%;

        background: linear-gradient(
            135deg,
            #101d42,
            #193b8f
        );

        border-radius: 18px;

        padding: 26px 30px 24px;

        color: #ffffff;

        box-shadow: 0 14px 35px rgba(16, 29, 66, .18);

        overflow: hidden;
    }

    .hero-top {
        width: 100%;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 25px;
    }

    .hero-main {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
    }

    .maintenance-icon {
        width: 68px;
        height: 68px;
        min-width: 68px;

        border-radius: 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(
            135deg,
            #087cff,
            #18b6a4
        );

        font-size: 30px;

        box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
    }

    .hero-info {
        min-width: 0;
    }

    .hero-label {
        display: block;

        font-size: 11px;
        letter-spacing: 1.5px;

        opacity: .72;

        font-weight: 700;

        margin-bottom: 5px;
    }

    .maintenance-hero h2 {
        margin: 0;

        font-size: 25px;
        font-weight: 800;
    }

    .maintenance-hero p {
        margin: 5px 0 0;

        font-size: 14px;
        opacity: .82;
    }

    .hero-status {
        flex-shrink: 0;
    }


    /* STATUS */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 8px 15px;

        border-radius: 999px;

        font-size: 12px;
        font-weight: 800;

        background: rgba(255, 255, 255, .15);
        color: #ffffff;

        border: 1px solid rgba(255, 255, 255, .20);
    }

    .status-badge.completed {
        background: rgba(24, 182, 164, .22);
    }

    .status-badge.progress {
        background: rgba(8, 124, 255, .24);
    }

    .status-badge.cancelled {
        background: rgba(239, 68, 68, .22);
    }

    .status-badge.open {
        background: rgba(245, 158, 11, .22);
    }


    /* FOUR SUMMARY CARDS INSIDE BLUE HERO */

    .summary-grid {
        width: 100%;

        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 14px;

        margin-top: 24px;
    }

    .summary-card {
        width: 100%;
        min-width: 0;

        padding: 15px;

        border-radius: 12px;

        background: rgba(255, 255, 255, .10);

        border: 1px solid rgba(255, 255, 255, .10);

        display: flex;
        align-items: center;

        gap: 11px;

        overflow: hidden;
    }

    .summary-icon {
        width: 41px;
        height: 41px;
        min-width: 41px;

        border-radius: 10px;

        background: rgba(255, 255, 255, .13);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 18px;
    }

    .summary-content {
        min-width: 0;
        overflow: hidden;
    }

    .summary-content span {
        display: block;

        color: rgba(255, 255, 255, .72);

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .5px;

        margin-bottom: 5px;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .summary-content strong {
        display: block;

        color: #ffffff;

        font-size: 15px;
        font-weight: 800;

        line-height: 1.2;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .summary-card.cost-highlight {
        background: rgba(255, 255, 255, .15);
        border-color: rgba(255, 255, 255, .16);
    }


    /* WHITE DETAILS AREA */

    .details-area {
        width: 100%;

        margin-top: 18px;
        padding: 22px;

        background: #ffffff;

        border: 1px solid #e5e9f2;
        border-radius: 15px;

        box-shadow: 0 5px 18px rgba(16, 29, 66, .05);

        min-width: 0;
        overflow: hidden;
    }


    /* CONTENT GRID */

    .content-grid {
        width: 100%;
        min-width: 0;

        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 20px;

        margin-bottom: 20px;
    }


    /* PROFILE CARDS */

    .profile-card {
        width: 100%;
        min-width: 0;
        max-width: 100%;

        background: #ffffff;

        border: 1px solid #e8edf4;
        border-radius: 15px;

        box-shadow: 0 5px 16px rgba(16, 29, 66, .05);

        overflow: hidden;
    }

    .card-header {
        padding: 20px 22px;

        border-bottom: 1px solid #edf0f5;
    }

    .card-header h3 {
        margin: 0 0 5px;

        color: #101d42;

        font-size: 16px;
        font-weight: 800;
    }

    .card-header p {
        margin: 0;

        color: #7a8497;

        font-size: 12px;
    }


    /* DETAILS */

    .detail-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 22px;

        padding: 22px;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-item label {
        display: block;

        margin-bottom: 7px;

        color: #8a94a6;

        font-size: 10px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .detail-item strong {
        display: block;

        color: #1f2937;

        font-size: 14px;
        font-weight: 700;

        overflow-wrap: anywhere;
    }


    /* COST BREAKDOWN */

    .cost-card {
        margin-bottom: 20px;
    }

    .cost-grid {
        width: 100%;

        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 12px;

        padding: 20px 22px;
    }

    .cost-item {
        min-width: 0;

        background: #f8fafc;

        border: 1px solid #edf0f5;
        border-radius: 11px;

        padding: 16px;

        overflow: hidden;
    }

    .cost-item span {
        display: block;

        color: #7a8497;

        font-size: 11px;
        font-weight: 700;

        margin-bottom: 7px;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cost-item strong {
        color: #101d42;

        font-size: 16px;
        font-weight: 800;

        white-space: nowrap;
    }

    .cost-item.total {
        background: #eef5ff;
        border-color: #d7e6ff;
    }

    .cost-item.total strong {
        color: #0b5ed7;
    }


    /* DOCUMENT */

    .document-box {
        padding: 22px;
    }

    .document-value {
        display: flex;
        align-items: center;

        gap: 10px;

        padding: 13px;

        background: #f8fafc;

        border: 1px solid #edf0f5;
        border-radius: 10px;

        color: #25324d;

        font-size: 13px;
        font-weight: 600;

        overflow-wrap: anywhere;
    }

    .document-icon {
        flex-shrink: 0;
    }

    .empty-state,
    .empty-text {
        color: #9aa3b2;
        font-size: 13px;
    }


    /* REMARKS */

    .remarks-box {
        padding: 22px;

        min-height: 74px;

        color: #374151;

        font-size: 13px;
        line-height: 1.7;

        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }


    /* BOTTOM ACTIONS */

    .bottom-actions {
        justify-content: flex-end;
        margin-top: 5px;
    }


    /* TABLET */

    @media (max-width: 1000px) {

        .summary-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .cost-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }
    }


    /* MOBILE */

    @media (max-width: 650px) {

        .maintenance-profile-page {
            padding-left: 0;
            padding-right: 0;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .maintenance-hero {
            padding: 21px 16px;
        }

        .hero-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-status {
            align-self: flex-start;
        }

        .summary-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .details-area {
            padding: 14px;
        }

        .detail-grid,
        .cost-grid {
            grid-template-columns: 1fr;
        }

        .bottom-actions {
            width: 100%;
            flex-direction: column;
        }

        .bottom-actions .btn {
            width: 100%;
        }
    }


    /* PRINT */

    @media print {

        .header-actions,
        .bottom-actions {
            display: none !important;
        }

        .maintenance-profile-page {
            max-width: 100%;
        }

        .maintenance-hero {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .details-area,
        .profile-card {
            box-shadow: none;
        }

        body {
            background: #ffffff !important;
        }
    }

</style>

@endsection