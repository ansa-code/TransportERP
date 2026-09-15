@extends('layouts.app')

@section('content')

<style>

    /* =========================
       PAGE
    ========================= */

    .vendor-show-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }


    /* =========================
       PAGE HEADER
    ========================= */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-title-area h1 {
        margin: 0 0 6px;
        color: #172554;
        font-size: 30px;
        font-weight: 700;
    }

    .page-title-area p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .page-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .back-btn,
    .update-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 15px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .back-btn {
        background: #475569;
        border: 1px solid #475569;
        color: #ffffff;
    }

    .back-btn:hover {
        background: #334155;
        border-color: #334155;
        color: #ffffff;
    }

    .update-btn {
        background: #2563eb;
        border: 1px solid #2563eb;
        color: #ffffff;
    }

    .update-btn:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff;
    }


    /* =========================
       DARK BLUE HERO
    ========================= */

    .vendor-hero {
        width: 100%;
        padding: 24px;
        box-sizing: border-box;
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(18, 58, 99, 0.20);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }


    /* =========================
       HERO TOP
       IDENTITY LEFT / STATUS RIGHT
    ========================= */

    .hero-main {
        width: 100%;
        min-width: 0;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 25px;
    }

    .hero-identity {
        min-width: 0;

        display: flex;
        align-items: center;

        gap: 17px;

        flex: 1;
    }

    .vendor-avatar {
        width: 68px;
        height: 68px;

        flex: 0 0 68px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.13);

        border: 1px solid rgba(255, 255, 255, 0.18);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 28px;
    }

    .hero-info {
        min-width: 0;
    }

    .hero-info h2 {
        margin: 0 0 5px;

        color: #ffffff;

        font-size: 25px;
        font-weight: 700;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .hero-subtitle {
        margin-bottom: 10px;

        color: rgba(255, 255, 255, 0.80);

        font-size: 13px;
        font-weight: 600;
    }

    .hero-contact {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 7px 15px;

        margin-bottom: 0;
    }

    .hero-contact span {
        color: rgba(255, 255, 255, 0.80);

        font-size: 11px;
    }


    /* =========================
       STATUS RIGHT SIDE
    ========================= */

    .hero-status-wrapper {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        flex-shrink: 0;
    }

    .hero-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 67px;
        height: 27px;

        padding: 0 10px;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fef3c7;
        color: #92400e;
    }

    .status-archived {
        background: #e2e8f0;
        color: #475569;
    }


    /* =========================
       HERO CARDS
       BELOW IDENTITY / STATUS
    ========================= */

    .hero-cards {
        width: 100%;

        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 10px;

        margin-top: 22px;
    }

    .hero-card {
        min-height: 70px;

        padding: 11px 13px;

        box-sizing: border-box;

        background: rgba(37,99,235,.30);

        border: 1px solid rgba(255, 255, 255, 0.10);

        border-radius: 8px;

        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .hero-card-label {
        margin-bottom: 5px;

        color: rgba(255, 255, 255, 0.68);

        font-size: 10px;
        font-weight: 500;
    }

    .hero-card strong {
        color: #ffffff;

        font-size: 14px;
        font-weight: 700;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }


    /* =========================
       DETAIL CARD
    ========================= */

    .detail-card {
        margin-bottom: 18px;

        padding: 20px;

        background: #ffffff;

        border: 1px solid #e2e8f0;

        border-radius: 9px;

        box-shadow:
            0 2px 7px rgba(15, 23, 42, 0.04);
    }

    .detail-header {
        margin-bottom: 18px;

        padding-bottom: 13px;

        border-bottom: 1px solid #eef2f7;
    }

    .detail-header h2 {
        margin: 0 0 4px;

        color: #172554;

        font-size: 17px;
        font-weight: 700;
    }

    .detail-header p {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    /* =========================
       DETAIL GRID
    ========================= */

    .detail-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 18px 28px;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-item span {
        display: block;

        margin-bottom: 5px;

        color: #64748b;

        font-size: 11px;
        font-weight: 600;

        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .detail-item strong {
        display: block;

        color: #1e293b;

        font-size: 13px;
        font-weight: 600;

        line-height: 1.5;

        word-break: break-word;
    }

    .full-width {
        grid-column: 1 / -1;
    }


    /* =========================
       VEHICLES
    ========================= */

    .vehicle-box {
        min-height: 52px;

        padding: 12px;

        background: #f8fafc;

        border: 1px solid #e2e8f0;

        border-radius: 7px;
    }

    .plate-list {
        display: flex;

        align-items: center;

        flex-wrap: wrap;

        gap: 8px;
    }

    .plate-badge {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-height: 30px;

        padding: 0 11px;

        background: #e8f0f8;

        border: 1px solid #cbd5e1;

        border-radius: 6px;

        color: #315f8f;

        font-size: 12px;
        font-weight: 700;
    }

    .no-data {
        color: #94a3b8;

        font-size: 13px;
    }


    /* =========================
       RATES
    ========================= */

    .rate-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 13px;
    }

    .rate-box {
        min-height: 78px;

        padding: 13px;

        box-sizing: border-box;

        background: #f8fafc;

        border: 1px solid #e2e8f0;

        border-radius: 7px;

        display: flex;

        flex-direction: column;

        justify-content: center;
    }

    .rate-box span {
        margin-bottom: 6px;

        color: #64748b;

        font-size: 11px;
        font-weight: 500;
    }

    .rate-box strong {
        color: #172554;

        font-size: 15px;
        font-weight: 700;

        word-break: break-word;
    }


    /* =========================
       NOTES
    ========================= */

    .notes-box {
        min-height: 55px;

        padding: 13px;

        box-sizing: border-box;

        background: #f8fafc;

        border: 1px solid #e2e8f0;

        border-radius: 7px;

        color: #334155;

        font-size: 13px;

        line-height: 1.6;

        white-space: pre-line;

        word-break: break-word;
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1050px) {

        .hero-main {
            align-items: flex-start;
        }

        .hero-cards {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .rate-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 700px) {

        .page-header {
            align-items: flex-start;

            flex-direction: column;
        }

        .page-actions {
            width: 100%;
        }

        .back-btn,
        .update-btn {
            flex: 1;
        }

        .vendor-hero {
            padding: 18px;
        }

        .hero-main {
            align-items: flex-start;
        }

        .hero-identity {
            align-items: flex-start;
        }

        .hero-info h2 {
            font-size: 21px;
        }

        .hero-cards {
            grid-template-columns: 1fr;
        }

        .detail-card {
            padding: 16px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: auto;
        }

        .rate-grid {
            grid-template-columns: 1fr;
        }

    }

</style>


<div class="vendor-show-page">

    {{-- =========================
         PAGE HEADER
    ========================== --}}

    <div class="page-header">

        <div class="page-title-area">

            <h1>
                Vendor Profile
            </h1>

            <p>
                Vendor contact, supplied vehicles, rates and payment details.
            </p>

        </div>


        <div class="page-actions">

            <a
                href="{{ route('vendors.index') }}"
                class="back-btn"
            >
                ← Back to Vendors
            </a>

            <a
                href="{{ route('vendors.edit', $vendor->id) }}"
                class="update-btn"
            >
                Update Vendor
            </a>

        </div>

    </div>


    {{-- =========================
         BLUE PROFILE HERO
    ========================== --}}

    <div class="vendor-hero">

        {{-- =========================
             IDENTITY + STATUS ROW
        ========================== --}}

        <div class="hero-main">


            {{-- Vendor Identity --}}

            <div class="hero-identity">

                <div class="vendor-avatar">
                    🏢
                </div>


                <div class="hero-info">

                    <h2>
                        {{ $vendor->vendor_name }}
                    </h2>

                    <div class="hero-subtitle">

                        @if($vendor->company_name)
                            {{ $vendor->company_name }}
                        @else
                            Vendor
                        @endif

                        @if($vendor->service_type)
                            · {{ $vendor->service_type }}
                        @endif

                    </div>


                    <div class="hero-contact">

                        @if($vendor->phone)

                            <span>
                                ☎ {{ $vendor->phone }}
                            </span>

                        @endif


                        @if($vendor->email)

                            <span>
                                ✉ {{ $vendor->email }}
                            </span>

                        @endif


                        @if($vendor->contact)

                            <span>
                                👤 {{ $vendor->contact }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Vendor Status --}}

            <div class="hero-status-wrapper">

                @php

                    $statusClass = match($vendor->status) {

                        'Active' => 'status-active',

                        'Inactive' => 'status-inactive',

                        'Archived' => 'status-archived',

                        default => 'status-inactive',

                    };

                @endphp


                <span class="hero-status {{ $statusClass }}">
                    {{ $vendor->status }}
                </span>

            </div>

        </div>


        {{-- =========================
             HERO SUMMARY CARDS
        ========================== --}}

        <div class="hero-cards">

            <div class="hero-card">

                <span class="hero-card-label">
                    Service Type
                </span>

                <strong>
                    {{ $vendor->service_type ?: '—' }}
                </strong>

            </div>


            <div class="hero-card">

                <span class="hero-card-label">
                    Rate Per Day
                </span>

                <strong>

                    @if($vendor->rate_per_day !== null)
                        {{ number_format((float) $vendor->rate_per_day, 2) }}
                    @else
                        —
                    @endif

                </strong>

            </div>


            <div class="hero-card">

                <span class="hero-card-label">
                    Payment Terms
                </span>

                <strong>
                    {{ $vendor->payment_terms ?: '—' }}
                </strong>

            </div>


            <div class="hero-card">

                <span class="hero-card-label">
                    Status
                </span>

                <strong>
                    {{ $vendor->status }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================
         VENDOR DETAILS
    ========================== --}}

    <div class="detail-card">

        <div class="detail-header">

            <div>

                <h2>
                    Vendor Details
                </h2>

                <p>
                    Basic vendor and contact information.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            <div class="detail-item">

                <span>
                    Vendor ID
                </span>

                <strong>
                    #{{ $vendor->id }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Vendor Name
                </span>

                <strong>
                    {{ $vendor->vendor_name }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Company Name
                </span>

                <strong>
                    {{ $vendor->company_name ?: '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Phone
                </span>

                <strong>
                    {{ $vendor->phone ?: '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Contact
                </span>

                <strong>
                    {{ $vendor->contact ?: '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Email
                </span>

                <strong>
                    {{ $vendor->email ?: '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Service Type
                </span>

                <strong>
                    {{ $vendor->service_type ?: '—' }}
                </strong>

            </div>


            <div class="detail-item full-width">

                <span>
                    Address
                </span>

                <strong>
                    {{ $vendor->address ?: '—' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================
         SUPPLIED VEHICLES
    ========================== --}}

    <div class="detail-card">

        <div class="detail-header">

            <div>

                <h2>
                    Supplied Vehicle(s)
                </h2>

                <p>
                    Vehicle plate numbers supplied by this vendor.
                </p>

            </div>

        </div>


        <div class="vehicle-box">

            @if($vendor->supplied_vehicle_plates)

                @php

                    $plates = preg_split(
                        '/[\r\n,]+/',
                        $vendor->supplied_vehicle_plates
                    );

                @endphp


                <div class="plate-list">

                    @foreach($plates as $plate)

                        @if(trim($plate) !== '')

                            <span class="plate-badge">
                                {{ trim($plate) }}
                            </span>

                        @endif

                    @endforeach

                </div>

            @else

                <span class="no-data">
                    No supplied vehicles recorded.
                </span>

            @endif

        </div>

    </div>
    {{-- =========================
         RATE STRUCTURE
    ========================== --}}

    <div class="detail-card">

        <div class="detail-header">

            <div>

                <h2>
                    Rate Structure
                </h2>

                <p>
                    Vendor contract rates and custom pricing.
                </p>

            </div>

        </div>


        <div class="rate-grid">

            <div class="rate-box">

                <span>
                    Rate Per Day
                </span>

                <strong>

                    @if($vendor->rate_per_day !== null)
                        {{ number_format((float) $vendor->rate_per_day, 2) }}
                    @else
                        —
                    @endif

                </strong>

            </div>


            <div class="rate-box">

                <span>
                    Rate Per Month
                </span>

                <strong>

                    @if($vendor->rate_per_month !== null)
                        {{ number_format((float) $vendor->rate_per_month, 2) }}
                    @else
                        —
                    @endif

                </strong>

            </div>


            <div class="rate-box">

                <span>
                    Rate Per Trip
                </span>

                <strong>

                    @if($vendor->rate_per_trip !== null)
                        {{ number_format((float) $vendor->rate_per_trip, 2) }}
                    @else
                        —
                    @endif

                </strong>

            </div>


            <div class="rate-box">

                <span>
                    Custom Rate
                </span>

                <strong>

                    @if($vendor->custom_rate !== null)
                        {{ number_format((float) $vendor->custom_rate, 2) }}
                    @else
                        —
                    @endif

                </strong>

            </div>


            <div class="rate-box full-width">

                <span>
                    Custom Rate Label
                </span>

                <strong>
                    {{ $vendor->custom_rate_label ?: '—' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================
         PAYMENT & REGISTRATION
    ========================== --}}

    <div class="detail-card">

        <div class="detail-header">

            <div>

                <h2>
                    Payment & Registration
                </h2>

                <p>
                    Payment terms and vendor registration information.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            <div class="detail-item">

                <span>
                    Payment Terms
                </span>

                <strong>
                    {{ $vendor->payment_terms ?: '—' }}
                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Payment Terms Days
                </span>

                <strong>

                    @if($vendor->payment_terms_days !== null)

                        {{ $vendor->payment_terms_days }} Days

                    @else

                        —

                    @endif

                </strong>

            </div>


            <div class="detail-item">

                <span>
                    Status
                </span>

                <strong>
                    {{ $vendor->status }}
                </strong>

            </div>


            <div class="detail-item full-width">

                <span>
                    Tax / Registration Data
                </span>

                <strong>
                    {{ $vendor->tax_registration_data ?: '—' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================
         NOTES
    ========================== --}}

    <div class="detail-card">

        <div class="detail-header">

            <div>

                <h2>
                    Notes
                </h2>

                <p>
                    Additional vendor information.
                </p>

            </div>

        </div>


        <div class="notes-box">
            {{ $vendor->notes ?: 'No additional notes recorded.' }}
        </div>

    </div>


</div>

@endsection