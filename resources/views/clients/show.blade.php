@extends('layouts.app')

@section('content')

<style>

    .client-profile-page {
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


    /* ================= CLIENT HERO ================= */

    .client-hero {
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

    .client-hero::after {
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

    /* Identity + Status row */
    .hero-top {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 25px;
    }


    /* ================= CLIENT IDENTITY ================= */

    .client-identity {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .client-avatar {
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

    .client-identity-text {
        min-width: 0;
    }

    .client-identity-text h2 {
        margin: 0;
        color: white;
        font-size: 25px;
        font-weight: 800;
        line-height: 1.1;
        overflow-wrap: anywhere;
    }

    .client-meta {
        margin-top: 7px;
        color: #b7c5dc;
        font-size: 13px;
        font-weight: 600;
    }


    /* ================= STATUS RIGHT SIDE ================= */

    .client-status-wrapper {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    .client-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .client-status.active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .client-status.inactive {
        background: #f8d7da;
        color: #842029;
    }

    .client-status.archived {
        background: #e2e3e5;
        color: #41464b;
    }


    /* ================= HERO MINI CARDS ================= */

    /*
     * Cards are now below identity/status,
     * matching the Leave and updated Driver layout.
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


    /* ================= CLIENT DETAILS ================= */

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


    /* ================= BILLING ROWS ================= */

    .billing-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0f2;
    }

    .billing-row:first-child {
        padding-top: 0;
    }

    .billing-row:last-child {
        border-bottom: none;
    }

    .billing-label {
        color: #6c757d;
        font-size: 13px;
        font-weight: 600;
    }

    .billing-value {
        color: #1d3557;
        font-size: 14px;
        font-weight: 800;
        text-align: right;
        overflow-wrap: anywhere;
    }


    /* ================= STATUS BADGES ================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
    }

    .status-badge.active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-badge.inactive {
        background: #f8d7da;
        color: #842029;
    }

    .status-badge.archived {
        background: #e2e3e5;
        color: #41464b;
    }


    /* ================= NOTES ================= */

    .notes-box {
        color: #27364a;
        font-size: 14px;
        line-height: 1.6;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
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

        .client-identity {
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


<div class="client-profile-page">


    {{-- ================= PAGE HEADER ================= --}}

    <div class="profile-header">

        <div class="profile-title">

            <h1>
                Client Profile
            </h1>

            <p>
                Company, contact, contract, billing and client configuration details
            </p>

        </div>


        <div class="profile-header-actions">

            <a
                href="{{ route('clients.index') }}"
                class="back-btn"
            >
                ← Back to Clients
            </a>

            <a
                href="{{ route('clients.edit', $client->id) }}"
                class="edit-profile-btn"
            >
                Update Client
            </a>

        </div>

    </div>


    {{-- ================= CLIENT HERO ================= --}}

    <div class="client-hero">

        {{-- ================= IDENTITY + STATUS ROW ================= --}}

        <div class="hero-top">


            {{-- Client Identity --}}

            <div class="client-identity">

                <div class="client-avatar">
                    {{ strtoupper(substr($client->company_name ?: $client->client_name ?: 'C', 0, 1)) }}
                </div>


                <div class="client-identity-text">

                    <h2>
                        {{ $client->company_name ?: $client->client_name ?: 'Client' }}
                    </h2>


                    <div class="client-meta">

                        {{ $client->client_code ?: '-' }}

                        @if($client->client_name)
                            · {{ $client->client_name }}
                        @endif

                        @if($client->city)
                            · {{ $client->city }}
                        @endif

                    </div>

                </div>

            </div>


            {{-- Client Status --}}

            <div class="client-status-wrapper">

                @if($client->status === 'Active')

                    <span class="client-status active">
                        Active
                    </span>

                @elseif($client->status === 'Inactive')

                    <span class="client-status inactive">
                        Inactive
                    </span>

                @else

                    <span class="client-status archived">
                        {{ $client->status ?: 'Archived' }}
                    </span>

                @endif

            </div>

        </div>


        {{-- ================= HERO CARDS ================= --}}

        <div class="hero-cards">


            <div class="hero-card">

                <div class="hero-card-label">
                    Billing Type
                </div>

                <div class="hero-card-value">
                    {{ $client->billing_type ?: '-' }}
                </div>

            </div>


            <div class="hero-card">

                <div class="hero-card-label">
                    VAT
                </div>

                <div class="hero-card-value">
                    {{ $client->vat_applicable ? 'Applicable' : 'Not Applicable' }}
                </div>

            </div>


            <div class="hero-card">

                <div class="hero-card-label">
                    Credit Limit
                </div>

                <div class="hero-card-value">

                    @if($client->credit_limit !== null)
                        AED {{ number_format((float) $client->credit_limit, 0) }}
                    @else
                        -
                    @endif

                </div>

            </div>


        </div>

    </div>


    {{-- ================= PROFILE CONTENT ================= --}}

    <div class="profile-grid">


        {{-- ================= LEFT COLUMN ================= --}}

        <div>


            {{-- Client Details --}}

            <div class="profile-card">

                <div class="card-header">

                    <h3>
                        Client Details
                    </h3>

                </div>


                <div class="card-body">

                    <div class="details-grid">


                        <div class="detail-item">

                            <div class="detail-label">
                                Client ID
                            </div>

                            <div class="detail-value">
                                #{{ $client->id }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Client Code
                            </div>

                            <div class="detail-value">
                                {{ $client->client_code ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Company Name
                            </div>

                            <div class="detail-value">
                                {{ $client->company_name ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Client Name
                            </div>

                            <div class="detail-value">
                                {{ $client->client_name ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Contact Person
                            </div>

                            <div class="detail-value">
                                {{ $client->contact_person ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Mobile
                            </div>

                            <div class="detail-value">
                                {{ $client->phone ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Email
                            </div>

                            <div class="detail-value">
                                {{ $client->email ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Trade Licence
                            </div>

                            <div class="detail-value">
                                {{ $client->trade_licence ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Trade Licence Expiry
                            </div>

                            <div class="detail-value">

                                {{ $client->trade_licence_expiry
                                    ? $client->trade_licence_expiry->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                TRN
                            </div>

                            <div class="detail-value">
                                {{ $client->trn ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                City
                            </div>

                            <div class="detail-value">
                                {{ $client->city ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Country
                            </div>

                            <div class="detail-value">
                                {{ $client->country ?: '-' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <div class="detail-label">
                                Address
                            </div>

                            <div class="detail-value">
                                {{ $client->address ?: '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>
        {{-- ================= RIGHT COLUMN ================= --}}

        <div>


            {{-- Contract & Billing --}}

            <div class="profile-card">

                <div class="card-header">

                    <h3>
                        Contract & Billing
                    </h3>

                </div>


                <div class="card-body">


                    <div class="billing-row">

                        <span class="billing-label">
                            Contract Start
                        </span>

                        <span class="billing-value">

                            {{ $client->contract_start
                                ? $client->contract_start->format('d M Y')
                                : '-' }}

                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            Contract End
                        </span>

                        <span class="billing-value">

                            {{ $client->contract_end
                                ? $client->contract_end->format('d M Y')
                                : '-' }}

                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            Billing Type
                        </span>

                        <span class="billing-value">
                            {{ $client->billing_type ?: '-' }}
                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            VAT Applicable
                        </span>

                        <span class="billing-value">
                            {{ $client->vat_applicable ? 'Yes' : 'No' }}
                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            Payment Terms
                        </span>

                        <span class="billing-value">
                            {{ $client->payment_terms ?: '-' }}
                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            Credit Days
                        </span>

                        <span class="billing-value">

                            {{ $client->credit_days !== null
                                ? $client->credit_days . ' Days'
                                : '-' }}

                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            Credit Limit
                        </span>

                        <span class="billing-value">

                            @if($client->credit_limit !== null)

                                AED {{ number_format((float) $client->credit_limit, 2) }}

                            @else

                                -

                            @endif

                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            Fuel Reimbursement
                        </span>

                        <span class="billing-value">
                            {{ $client->fuel_reimbursement_rule ?: '-' }}
                        </span>

                    </div>


                    <div class="billing-row">

                        <span class="billing-label">
                            Status
                        </span>

                        <span class="billing-value">

                            @if($client->status === 'Active')

                                <span class="status-badge active">
                                    Active
                                </span>

                            @elseif($client->status === 'Inactive')

                                <span class="status-badge inactive">
                                    Inactive
                                </span>

                            @else

                                <span class="status-badge archived">
                                    {{ $client->status ?: 'Archived' }}
                                </span>

                            @endif

                        </span>

                    </div>


                </div>

            </div>


        </div>

    </div>


    {{-- ================= NOTES ================= --}}

    <div class="profile-card">

        <div class="card-header">

            <h3>
                Notes
            </h3>

        </div>


        <div class="card-body">

            <div class="notes-box">

                {{ $client->notes ?: 'No notes available.' }}

            </div>

        </div>

    </div>


</div>

@endsection