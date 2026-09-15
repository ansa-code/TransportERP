@extends('layouts.app')

@section('content')

<style>

    .advance-show-page {
        max-width: 1200px;
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
        color: #172b4d;
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .page-subtitle {
        color: #718096;
        font-size: 14px;
        line-height: 1.6;
    }

    .header-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .action-btn {
        min-height: 40px;
        padding: 0 15px;
        border-radius: 9px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
    }

    .back-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .back-btn:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .edit-btn {
        background: #2563eb;
        color: white;
        border: 1px solid #2563eb;
    }

    .edit-btn:hover {
        background: #1d4ed8;
        color: white;
    }


    /* =========================
       HERO
    ========================= */

    .advance-hero {
        background: linear-gradient(
            135deg,
            #101d42,
            #193b8f
        );
        border-radius: 18px;
        padding: 25px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 10px 28px rgba(16, 29, 66, .18);
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .hero-left {
        display: flex;
        align-items: center;
        gap: 17px;
    }

    .advance-icon {
        width: 68px;
        height: 68px;
        border-radius: 18px;
        background: linear-gradient(
            135deg,
            #087cff,
            #18b6a4
        );
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        flex-shrink: 0;
    }

    .hero-number {
        font-size: 23px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .hero-driver {
        color: #b9c9e3;
        font-size: 13px;
    }

    .hero-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 13px;
        border-radius: 20px;
        background: rgba(255, 255, 255, .13);
        border: 1px solid rgba(255, 255, 255, .15);
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }


    /* =========================
       SUMMARY
    ========================= */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 22px;
    }

    .summary-card {
        background: rgba(255, 255, 255, .09);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 13px;
        padding: 16px;
    }

    .summary-label {
        color: #aebddd;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .summary-value {
        color: white;
        font-size: 19px;
        font-weight: 800;
    }


    /* =========================
       CONTENT GRID
    ========================= */

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-card {
        background: white;
        border: 1px solid #e6ebf2;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 5px 18px rgba(18, 38, 63, .06);
    }

    .card-title {
        color: #172b4d;
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 18px;
        padding-bottom: 13px;
        border-bottom: 1px solid #edf1f5;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .info-row {
        display: grid;
        grid-template-columns: 145px 1fr;
        gap: 12px;
        align-items: start;
    }

    .info-label {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
    }

    .info-value {
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        word-break: break-word;
    }

    .driver-link {
        color: #087cff;
        text-decoration: none;
        font-weight: 800;
    }

    .driver-link:hover {
        color: #0668d8;
        text-decoration: underline;
    }

    .amount-value {
        color: #172b4d;
        font-size: 14px;
        font-weight: 800;
    }

    .deducted-value {
        color: #16a34a;
        font-size: 14px;
        font-weight: 800;
    }

    .remaining-value {
        color: #d97706;
        font-size: 14px;
        font-weight: 800;
    }

    .fully-paid {
        color: #16a34a;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-approved {
        background: #ecfdf5;
        color: #047857;
    }

    .status-partial {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-full {
        background: #dcfce7;
        color: #15803d;
    }

    .status-rejected {
        background: #fef2f2;
        color: #b91c1c;
    }
    </style>


<div class="advance-show-page">


    <div class="page-header">

        <div>

            <div class="page-title">
                Driver Advance Details
            </div>

            <div class="page-subtitle">
                Complete advance information, approval and recovery details.
            </div>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('driver-advances.index') }}"
                class="action-btn back-btn"
            >
                ← Back
            </a>

            <a
                href="{{ route('driver-advances.edit', $advance->id) }}"
                class="action-btn edit-btn"
            >
                Edit
            </a>

        </div>

    </div>


    <div class="advance-hero">

        <div class="hero-top">

            <div class="hero-left">

                <div class="advance-icon">
                    💰
                </div>

                <div>

                    <div class="hero-number">
                        {{ $advance->advance_no }}
                    </div>

                    <div class="hero-driver">

                        {{ $advance->driver->driver_name ?? 'N/A' }}

                        @if($advance->driver && $advance->driver->driver_code)
                            • {{ $advance->driver->driver_code }}
                        @endif

                    </div>

                </div>

            </div>


            <div class="hero-status">
                {{ $advance->status }}
            </div>

        </div>


        <div class="summary-grid">


            <div class="summary-card">

                <div class="summary-label">
                    Advance Amount
                </div>

                <div class="summary-value">
                    AED {{ number_format((float) $advance->amount, 2) }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Deducted Amount
                </div>

                <div class="summary-value">
                    AED {{ number_format((float) $advance->deducted_amount, 2) }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Remaining Amount
                </div>

                <div class="summary-value">
                    AED {{ number_format((float) $advance->remaining_amount, 2) }}
                </div>

            </div>


        </div>

    </div>


    <div class="content-grid">


        <div class="info-card">

            <div class="card-title">
                Advance Information
            </div>


            <div class="info-list">


                <div class="info-row">

                    <div class="info-label">
                        Advance Number
                    </div>

                    <div class="info-value">
                        {{ $advance->advance_no }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Driver
                    </div>

                    <div class="info-value">

                        @if($advance->driver)

                            <a
                                href="{{ route('drivers.show', $advance->driver->id) }}"
                                class="driver-link"
                            >
                                {{ $advance->driver->driver_name }}
                            </a>

                        @else

                            N/A

                        @endif

                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Driver Code
                    </div>

                    <div class="info-value">
                        {{ $advance->driver->driver_code ?? 'N/A' }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Advance Date
                    </div>

                    <div class="info-value">
                        {{ optional($advance->advance_date)->format('d M Y') }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Amount
                    </div>

                    <div class="info-value amount-value">
                        AED {{ number_format((float) $advance->amount, 2) }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Deducted Amount
                    </div>

                    <div class="info-value deducted-value">
                        AED {{ number_format((float) $advance->deducted_amount, 2) }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Remaining Amount
                    </div>

                    <div class="info-value
                        {{ (float) $advance->remaining_amount <= 0 ? 'fully-paid' : 'remaining-value' }}"
                    >
                        AED {{ number_format((float) $advance->remaining_amount, 2) }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Status
                    </div>

                    <div class="info-value">

                        @if($advance->status === 'Pending')

                            <span class="status-badge status-pending">
                                Pending
                            </span>

                        @elseif($advance->status === 'Approved')

                            <span class="status-badge status-approved">
                                Approved
                            </span>

                        @elseif($advance->status === 'Partially Deducted')

                            <span class="status-badge status-partial">
                                Partially Deducted
                            </span>

                        @elseif($advance->status === 'Fully Deducted')

                            <span class="status-badge status-full">
                                Fully Deducted
                            </span>

                        @elseif($advance->status === 'Rejected')

                            <span class="status-badge status-rejected">
                                Rejected
                            </span>

                        @else

                            <span class="status-badge">
                                {{ $advance->status }}
                            </span>

                        @endif

                    </div>

                </div>


            </div>

        </div>


        <div class="info-card">

            <div class="card-title">
                Approval & Remarks
            </div>


            <div class="info-list">


                <div class="info-row">

                    <div class="info-label">
                        Approved By
                    </div>

                    <div class="info-value">
                        {{ $advance->approvedBy->name ?? 'Not Approved' }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Reason
                    </div>

                    <div class="info-value">
                        {{ $advance->reason ?: 'No reason provided.' }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Remarks
                    </div>

                    <div class="info-value">
                        {{ $advance->remarks ?: 'No remarks provided.' }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Created
                    </div>

                    <div class="info-value">
                        {{ optional($advance->created_at)->format('d M Y, h:i A') }}
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Last Updated
                    </div>

                    <div class="info-value">
                        {{ optional($advance->updated_at)->format('d M Y, h:i A') }}
                    </div>

                </div>


            </div>

        </div>


    </div>


</div>


<style>

    @media (max-width: 800px) {

        .page-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .action-btn {
            flex: 1;
        }

        .hero-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 500px) {

        .advance-hero {
            padding: 19px;
        }

        .advance-icon {
            width: 58px;
            height: 58px;
            font-size: 25px;
        }

        .hero-number {
            font-size: 19px;
        }

        .info-card {
            padding: 17px;
        }

        .info-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }

    }

</style>

@endsection