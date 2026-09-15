@extends('layouts.app')

@section('content')

<style>
    .fine-show-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header h1 {
        margin: 0;
        color: #111827;
        font-size: 28px;
        font-weight: 800;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: .2s ease;
    }

    .btn-back {
        background: #ffffff;
        color: #374151;
        border-color: #d1d5db;
    }

    .btn-back:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .btn-edit {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    .btn-edit:hover {
        background: #1d4ed8;
        color: #ffffff;
    }


    /* =========================
       HERO
    ========================= */

    .fine-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        padding: 28px;
        border-radius: 18px;
        color: #ffffff;
        background: linear-gradient(135deg, #101d42, #193b8f);
        box-shadow: 0 12px 30px rgba(16, 29, 66, .15);
    }

    .fine-hero::before {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        top: -110px;
        right: -60px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }

    .fine-hero::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: 28%;
        bottom: -120px;
        border-radius: 50%;
        background: rgba(24,182,164,.08);
    }

    .fine-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 22px;
    }

    .fine-main-info {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
    }

    .fine-icon {
        flex: 0 0 72px;
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(135deg, #087cff, #18b6a4);
        font-size: 30px;
        font-weight: 800;
        box-shadow: 0 10px 24px rgba(0,0,0,.18);
    }

    .fine-main-info h2 {
        margin: 0;
        color: #ffffff;
        font-size: 26px;
        font-weight: 800;
    }

    .fine-main-info p {
        margin: 6px 0 0;
        color: rgba(255,255,255,.72);
        font-size: 14px;
    }

    .hero-amount {
        text-align: right;
        flex-shrink: 0;
    }

    .hero-amount span {
        display: block;
        margin-bottom: 4px;
        color: rgba(255,255,255,.65);
        font-size: 12px;
        font-weight: 600;
    }

    .hero-amount strong {
        display: block;
        color: #ffffff;
        font-size: 30px;
        font-weight: 800;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        margin-top: 9px;
        padding: 7px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-unpaid {
        color: #fbbf24;
        background: rgba(245,158,11,.18);
    }

    .status-paid {
        color: #86efac;
        background: rgba(34,197,94,.18);
    }

    .status-deducted {
        color: #93c5fd;
        background: rgba(59,130,246,.18);
    }

    .status-cancelled {
        color: #fca5a5;
        background: rgba(239,68,68,.18);
    }


    /* =========================
       SUMMARY CARDS
       INSIDE HERO
    ========================= */

    .summary-grid {
        position: relative;
        z-index: 2;

        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .summary-card {
        padding: 18px;
        border: 1px solid rgba(255,255,255,.10);
        border-radius: 14px;
        background: rgba(37,99,235,.30);
        box-shadow: 0 4px 14px rgba(0,0,0,.04);
    }

    .summary-label {
        margin-bottom: 8px;
        color: rgba(255,255,255,.68);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .summary-value {
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        word-break: break-word;
    }

    .muted {
        color: rgba(255,255,255,.55) !important;
        font-weight: 600 !important;
    }

    .linked-value {
        color: #ffffff;
        font-weight: 800;
        text-decoration: none;
    }

    .linked-value:hover {
        color: #bfdbfe;
        text-decoration: underline;
    }


    /* =========================
       MAIN DETAILS
    ========================= */

    .details-grid {
        display: grid;
        grid-template-columns: 1.25fr .75fr;
        gap: 20px;
        align-items: start;
    }

    .detail-card {
        margin-bottom: 20px;
        padding: 22px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 14px rgba(15,23,42,.04);
    }

    .detail-card:last-child {
        margin-bottom: 0;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #eef0f4;
    }

    .card-title-icon {
        width: 32px;
        height: 32px;
        flex: 0 0 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: #2563eb;
        background: #eff6ff;
        font-size: 15px;
        font-weight: 800;
    }

    .card-title h3 {
        margin: 0;
        color: #111827;
        font-size: 17px;
        font-weight: 800;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px 24px;
    }

    .info-item label {
        display: block;
        margin-bottom: 5px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
    }

    .info-item .value {
        min-height: 20px;
        color: #111827;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
    }


    /* =========================
       ATTACHMENT
    ========================= */

    .attachment-preview {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        max-width: 100%;
    }

    .attachment-image-link {
        display: block;
        width: 110px;
        height: 80px;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        transition: .2s ease;
    }

    .attachment-image-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(15,23,42,.12);
    }

    .attachment-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }

    .attachment-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 7px;
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: .2s ease;
    }

    .attachment-link:hover {
        color: #1d4ed8;
        background: #dbeafe;
        text-decoration: none;
    }

    .attachment-name {
        max-width: 100%;
        color: #6b7280;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.4;
        word-break: break-all;
    }


    /* =========================
       PAYMENT STATUS
    ========================= */

    .payment-status-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
        padding: 15px 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }

    .payment-status-label {
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
    }

    .payment-status-value {
        color: #111827;
        font-size: 14px;
        font-weight: 800;
    }


    /* =========================
       NOTES
    ========================= */

    .notes-box {
        padding: 15px 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }

    .notes-box label {
        display: block;
        margin-bottom: 7px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
    }

    .notes-box p {
        margin: 0;
        color: #374151;
        font-size: 14px;
        line-height: 1.7;
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1100px) {

        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 760px) {

        .fine-show-page {
            padding: 0 4px;
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

        .fine-hero {
            padding: 22px;
            border-radius: 14px;
        }

        .fine-hero-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .fine-main-info {
            width: 100%;
        }

        .fine-main-info h2 {
            font-size: 21px;
        }

        .hero-amount {
            text-align: left;
        }

        .hero-amount strong {
            font-size: 26px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .detail-card {
            padding: 18px;
            border-radius: 14px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

    }


    @media (max-width: 480px) {

        .page-header h1 {
            font-size: 23px;
        }

        .page-header p {
            font-size: 13px;
        }

        .header-actions {
            flex-direction: column;
        }

        .header-actions .btn {
            width: 100%;
        }

        .fine-icon {
            width: 58px;
            height: 58px;
            flex-basis: 58px;
            border-radius: 14px;
            font-size: 24px;
        }

        .fine-main-info {
            gap: 13px;
        }

        .fine-main-info h2 {
            font-size: 18px;
        }

        .summary-card {
            padding: 16px;
        }

        .detail-card {
            padding: 16px;
        }

        .payment-status-box {
            align-items: flex-start;
            flex-direction: column;
        }

        .attachment-image-link {
            width: 100%;
            max-width: 180px;
            height: 110px;
        }

    }
</style>


<div class="fine-show-page">

    {{-- =========================
         PAGE HEADER
    ========================== --}}

    <div class="page-header">

        <div>

            <h1>
                Traffic Fine Details
            </h1>

            <p>
                View traffic fine, payment and driver deduction information.
            </p>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('traffic-fines.index') }}"
                class="btn btn-back"
            >
                ← Back
            </a>

            <a
                href="{{ route('traffic-fines.edit', $fine->id) }}"
                class="btn btn-edit"
            >
                ✎ Edit Fine
            </a>

        </div>

    </div>


    {{-- =========================
         HERO
    ========================== --}}

    <div class="fine-hero">

        <div class="fine-hero-content">

            <div class="fine-main-info">

                <div class="fine-icon">
                    ⚠
                </div>


                <div>

                    <h2>
                        {{ $fine->fine_number }}
                    </h2>

                    <p>
                        Fine Date:
                        {{ $fine->fine_date ? $fine->fine_date->format('d M Y') : '—' }}
                    </p>


                    @if($fine->payment_status === 'Paid')

                        <span class="status-badge status-paid">
                            Paid
                        </span>

                    @elseif($fine->payment_status === 'Deducted')

                        <span class="status-badge status-deducted">
                            Deducted
                        </span>

                    @elseif($fine->payment_status === 'Cancelled')

                        <span class="status-badge status-cancelled">
                            Cancelled
                        </span>

                    @else

                        <span class="status-badge status-unpaid">
                            Unpaid
                        </span>

                    @endif

                </div>

            </div>


            <div class="hero-amount">

                <span>
                    Fine Amount
                </span>

                <strong>
                    {{ number_format((float) $fine->amount, 2) }}
                </strong>

            </div>

        </div>


        {{-- =========================
             SUMMARY CARDS
             INSIDE HERO
        ========================== --}}

        <div class="summary-grid">

            <div class="summary-card">

                <div class="summary-label">
                    Vehicle
                </div>

                <div class="summary-value">

                    @if($fine->vehicle)

                        <a
                            href="{{ route('vehicles.show', $fine->vehicle->id) }}"
                            class="linked-value"
                        >
                            {{ $fine->vehicle->plate_number }}
                        </a>

                    @else

                        <span class="muted">
                            Not Assigned
                        </span>

                    @endif

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Driver
                </div>

                <div class="summary-value">

                    @if($fine->driver)

                        <a
                            href="{{ route('drivers.show', $fine->driver->id) }}"
                            class="linked-value"
                        >
                            {{ $fine->driver->driver_name }}
                        </a>

                    @else

                        <span class="muted">
                            Not Assigned
                        </span>

                    @endif

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Payment Status
                </div>

                <div class="summary-value">
                    {{ $fine->payment_status }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-label">
                    Driver Deduction
                </div>

                <div class="summary-value">
                    {{ $fine->deduct_from_driver ? 'Yes' : 'No' }}
                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         DETAILS
    ========================== --}}

    <div class="details-grid">


        {{-- LEFT COLUMN --}}
        <div>


            {{-- FINE INFORMATION --}}

            <div class="detail-card">

                <div class="card-title">

                    <div class="card-title-icon">
                        ▣
                    </div>

                    <h3>
                        Fine Information
                    </h3>

                </div>


                <div class="info-grid">

                    <div class="info-item">

                        <label>
                            Fine Number
                        </label>

                        <div class="value">
                            {{ $fine->fine_number }}
                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Fine Date
                        </label>

                        <div class="value">

                            {{ $fine->fine_date
                                ? $fine->fine_date->format('d M Y')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Fine Amount
                        </label>

                        <div class="value">

                            {{ number_format((float) $fine->amount, 2) }}

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Reason / Location
                        </label>

                        <div class="value">

                            {{ $fine->reason_location ?: '—' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- VEHICLE & DRIVER --}}

            <div class="detail-card">

                <div class="card-title">

                    <div class="card-title-icon">
                        🚗
                    </div>

                    <h3>
                        Vehicle & Driver
                    </h3>

                </div>


                <div class="info-grid">

                    <div class="info-item">

                        <label>
                            Vehicle
                        </label>

                        <div class="value">

                            @if($fine->vehicle)

                                <a
                                    href="{{ route('vehicles.show', $fine->vehicle->id) }}"
                                    class="linked-value"
                                >
                                    {{ $fine->vehicle->plate_number }}
                                </a>

                            @else

                                <span class="muted">
                                    Not Assigned
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Driver
                        </label>

                        <div class="value">

                            @if($fine->driver)

                                <a
                                href="{{ route('drivers.show', $fine->driver->id) }}"
                                    class="linked-value"
                                >
                                    {{ $fine->driver->driver_name }}
                                </a>

                            @else

                                <span class="muted">
                                    Not Assigned
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Deduct From Driver
                        </label>

                        <div class="value">

                            {{ $fine->deduct_from_driver ? 'Yes' : 'No' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            <div class="value">

                            {{ $fine->payment_status }}

                        </div>

                    </div>

                </div>

            </div>


        </div>


        {{-- =========================
             RIGHT COLUMN
        ========================== --}}

        <div>


            {{-- PAYMENT INFORMATION --}}

            <div class="detail-card">

                <div class="card-title">

                    <div class="card-title-icon">
                        ₿
                    </div>

                    <h3>
                        Payment Information
                    </h3>
                    </div>


                <div class="payment-status-box">

                    <div>

                        <div class="payment-status-label">
                            Current Status
                        </div>

                        <div class="payment-status-value">
                            {{ $fine->payment_status }}
                        </div>

                    </div>

                </div>


                <div class="info-grid">

                    <div class="info-item">

                        <label>
                            Paid Date
                        </label>

                        <div class="value">

                            {{ $fine->paid_date
                                ? $fine->paid_date->format('d M Y')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div
                    class="info-item">

                        <label>
                            Paid Reference
                        </label>

                        <div class="value">

                            {{ $fine->paid_reference ?: '—' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Attachment
                        </label>

                        <div class="value">

                            @if($fine->attachment)

                                @php
                                    $attachmentPath = ltrim($fine->attachment, '/');
                                    $attachmentUrl = asset('storage/' . $attachmentPath);
                                    $attachmentExtension = strtolower(
                                        pathinfo($attachmentPath, PATHINFO_EXTENSION)
                                    );
                                    $attachmentName = basename($attachmentPath);
                                    @endphp

                                <div class="attachment-preview">

                                    @if(in_array($attachmentExtension, ['jpg', 'jpeg', 'png']))

                                        <a
                                            href="{{ $attachmentUrl }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="attachment-image-link"
                                            title="Open attachment"
                                        >
                                            <img
                                                src="{{ $attachmentUrl }}"
                                                alt="Traffic Fine Attachment"
                                                class="attachment-image"
                                            >
                                        </a>

                                        <a
                                            href="{{ $attachmentUrl }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="attachment-link"
                                        >
                                            ↗ Open Image
                                        </a>

                                    @elseif($attachmentExtension === 'pdf')

                                        <a
                                            href="{{ $attachmentUrl }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="attachment-link"
                                        >
                                            📄 View PDF
                                        </a>

                                    @else

                                        <a
                                            href="{{ $attachmentUrl }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="attachment-link"
                                        >
                                            📎 Open Attachment
                                            </a>

                                    @endif

                                    <div class="attachment-name">
                                        {{ $attachmentName }}
                                    </div>

                                </div>

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Driver Deduction
                        </label>

                        <div class="value">

                            {{ $fine->deduct_from_driver ? 'Yes' : 'No' }}

                        </div>

                    </div>

                </div>

            </div>
            {{-- =========================
                 RECORD INFORMATION
            ========================== --}}

            <div class="detail-card">

                <div class="card-title">

                    <div class="card-title-icon">
                        ✓
                    </div>

                    <h3>
                        Record Information
                    </h3>

                </div>


                <div class="info-grid">

                    <div class="info-item">

                        <label>
                            Created
                        </label>

                        <div class="value">

                            {{ $fine->created_at
                                ? $fine->created_at->format('d M Y, h:i A')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="info-item">

                        <label>
                            Last Updated
                        </label>

                        <div class="value">

                            {{ $fine->updated_at
                                ? $fine->updated_at->format('d M Y, h:i A')
                                : '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection