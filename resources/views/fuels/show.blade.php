@extends('layouts.app')

@section('content')

<style>
    .fuel-show-page {
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
        align-items: center;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-back {
        background: #ffffff;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-back:hover {
        background: #f8fafc;
    }

    .btn-edit {
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #2563eb;
    }

    .btn-edit:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .fuel-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        padding: 28px;
        border-radius: 18px;
        color: #ffffff;
        background: linear-gradient(135deg, #101d42, #193b8f);
        box-shadow: 0 12px 30px rgba(16, 29, 66, .15);
    }

    .fuel-hero::before {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        top: -120px;
        right: -80px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }

    .fuel-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 25px;
        margin-bottom: 24px;
    }

    .hero-label {
        margin-bottom: 7px;
        color: rgba(255,255,255,.62);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .hero-title {
        margin: 0;
        color: #ffffff;
        font-size: 26px;
        font-weight: 800;
    }

    .hero-meta {
        margin-top: 8px;
        color: rgba(255,255,255,.70);
        font-size: 13px;
    }

    .hero-amount {
        text-align: right;
    }

    .hero-amount-label {
        color: rgba(255,255,255,.60);
        font-size: 11px;
        font-weight: 650;
        text-transform: uppercase;
    }

    .hero-amount-value {
        margin-top: 5px;
        color: #ffffff;
        font-size: 25px;
        font-weight: 800;
    }

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
        margin-bottom: 7px;
        color: rgba(255,255,255,.68);
        font-size: 11px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .summary-value {
        color: #ffffff;
        font-size: 16px;
        font-weight: 750;
    }

    .summary-value.large {
        font-size: 20px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .detail-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 5px 16px rgba(15,23,42,.04);
    }

    .detail-card.full-width {
        grid-column: 1 / -1;
    }

    .detail-title {
        margin: 0 0 18px;
        color: #172554;
        font-size: 17px;
        font-weight: 750;
    }

    .detail-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 28px;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-label {
        margin-bottom: 5px;
        color: #64748b;
        font-size: 11px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .detail-value {
        color: #1e293b;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }

    .muted {
        color: #94a3b8 !important;
    }

    .linked-value {
        color: #2563eb;
        text-decoration: none;
        font-weight: 700;
    }

    .linked-value:hover {
        text-decoration: underline;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 26px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
    }

    .reimbursable-yes {
        background: #ecfdf5;
        color: #047857;
    }

    .reimbursable-no {
        background: #f1f5f9;
        color: #64748b;
    }

    .paid-al-shaqra {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .paid-client {
        background: #ecfdf5;
        color: #047857;
    }

    .paid-driver {
        background: #fff7ed;
        color: #c2410c;
    }

    .paid-vendor {
        background: #f5f3ff;
        color: #6d28d9;
    }
    .receipt-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
    }

    .receipt-name {
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        word-break: break-all;
    }

    .receipt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 12px;
        border-radius: 6px;
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #2563eb;
        text-decoration: none;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .receipt-btn:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .notes-box {
        padding: 15px;
        border-radius: 9px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-line;
    }

    @media (max-width: 1000px) {
        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .detail-card.full-width {
            grid-column: auto;
        }
    }

    @media (max-width: 650px) {
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

        .fuel-hero {
            padding: 20px;
        }

        .fuel-hero-content {
            flex-direction: column;
        }

        .hero-amount {
            text-align: left;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .detail-list {
            grid-template-columns: 1fr;
        }

        .receipt-box {
            align-items: flex-start;
            flex-direction: column;
        }

        .receipt-btn {
            width: 100%;
        }
    }
</style>

<div class="fuel-show-page">

    <div class="page-header">

        <div>
            <h1>Fuel Record Details</h1>

            <p>
                View complete fuel transaction and reimbursement information.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('fuels.index') }}"
                class="btn btn-back"
            >
                Back to Fuel
            </a>

            <a
                href="{{ route('fuels.edit', $fuel->id) }}"
                class="btn btn-edit"
            >
                Edit Fuel
            </a>

        </div>

    </div>

    <div class="fuel-hero">

        <div class="fuel-hero-content">

            <div>
                <div class="hero-label">
                    Fuel Entry
                </div>

                <h2 class="hero-title">
                    {{ $fuel->fuel_entry_no }}
                </h2>

                <div class="hero-meta">
                    {{ $fuel->fuel_date?->format('d M Y, h:i A') ?? '—' }}
                    @if($fuel->vehicle)
                        · {{ $fuel->vehicle->plate_number }}
                    @endif
                </div>
            </div>

            <div class="hero-amount">

                <div class="hero-amount-label">
                    Total Fuel Cost
                </div>

                <div class="hero-amount-value">
                    {{ number_format((float) $fuel->total_amount, 2) }}
                </div>

            </div>

        </div>

        <div class="summary-grid">

            <div class="summary-card">

                <div class="summary-label">
                    Vehicle
                </div>

                <div class="summary-value">
                    {{ $fuel->vehicle?->plate_number ?? '—' }}
                </div>

            </div>

            <div class="summary-card">

                <div class="summary-label">
                    Driver
                </div>

                <div class="summary-value">
                    {{ $fuel->driver?->driver_name ?? 'Not Assigned' }}
                </div>

            </div>

            <div class="summary-card">

                <div class="summary-label">
                    Paid By
                </div>

                <div class="summary-value">
                    {{ $fuel->paid_by ?? '—' }}
                </div>

            </div>

            <div class="summary-card">

                <div class="summary-label">
                    Reimbursement
                </div>

                <div class="summary-value">
                    {{ $fuel->reimbursable ? 'Yes' : 'No' }}
                </div>

            </div>

        </div>

    </div>
    <div class="details-grid">

        <div class="detail-card">

            <h3 class="detail-title">
                Fuel Details
            </h3>

            <div class="detail-list">

                <div class="detail-item">
                    <div class="detail-label">Fuel Entry No.</div>
                    <div class="detail-value">
                        {{ $fuel->fuel_entry_no }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Date / Time</div>
                    <div class="detail-value">
                        {{ $fuel->fuel_date?->format('d M Y, h:i A') ?? '—' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Quantity</div>
                    <div class="detail-value">
                        {{ number_format((float) $fuel->liters, 2) }} L
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Unit Price</div>
                    <div class="detail-value">
                        {{ number_format((float) $fuel->price_per_liter, 2) }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Total Cost</div>
                    <div class="detail-value">
                        {{ number_format((float) $fuel->total_amount, 2) }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Odometer</div>
                    <div class="detail-value">
                        {{ $fuel->odometer !== null ? number_format($fuel->odometer) : '—' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Fuel Station</div>
                    <div class="detail-value">
                        {{ $fuel->fuel_station ?: '—' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Paid By</div>
                    <div class="detail-value">
                        @php
                            $paidByClass = match ($fuel->paid_by) {
                                'AL SHAQRA' => 'paid-al-shaqra',
                                'Client' => 'paid-client',
                                'Driver' => 'paid-driver',
                                'Vendor' => 'paid-vendor',
                                default => 'paid-al-shaqra',
                            };
                        @endphp

                        <span class="status-badge {{ $paidByClass }}">
                            {{ $fuel->paid_by ?? '—' }}
                        </span>
                    </div>
                </div>

            </div>

        </div>

        <div class="detail-card">

            <h3 class="detail-title">
                Operational Context
            </h3>

            <div class="detail-list">

                <div class="detail-item">
                    <div class="detail-label">Vehicle</div>
                    <div class="detail-value">
                        @if($fuel->vehicle)
                            <a
                                href="{{ route('vehicles.show', $fuel->vehicle->id) }}"
                                class="linked-value"
                            >
                                {{ $fuel->vehicle->plate_number }}
                            </a>
                        @else
                            <span class="muted">Not Available</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Driver</div>
                    <div class="detail-value">
                        {{ $fuel->driver?->driver_name ?? 'Not Assigned' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Client</div>
                    <div class="detail-value">
                        {{ $fuel->client?->client_name ?? 'Not Linked' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Assignment</div>
                    <div class="detail-value">
                        {{ $fuel->assignment ? 'Assignment #' . $fuel->assignment->id : 'Not Linked' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Trip</div>
                    <div class="detail-value">
                        {{ $fuel->trip ? 'Trip #' . $fuel->trip->id : 'Not Linked' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Reimbursable</div>
                    <div class="detail-value">

                        @if($fuel->reimbursable)

                            <span class="status-badge reimbursable-yes">
                                Yes
                            </span>

                        @else

                            <span class="status-badge reimbursable-no">
                                No
                            </span>

                        @endif

                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Reimbursement Amount</div>
                    <div class="detail-value">
                        @if($fuel->reimbursement_amount !== null)
                            {{ number_format((float) $fuel->reimbursement_amount, 2) }}
                        @else
                            <span class="muted">Not Applicable</span>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <div class="detail-card">

            <h3 class="detail-title">
                Receipt
            </h3>

            @if($fuel->receipt)

                <div class="receipt-box">

                    <div class="receipt-name">
                        {{ basename($fuel->receipt) }}
                    </div>

                    <a
                        href="{{ asset('storage/' . $fuel->receipt) }}"
                        target="_blank"
                        class="receipt-btn"
                    >
                        View Receipt
                    </a>

                </div>

            @else

                <div class="notes-box">
                    No receipt uploaded for this fuel record.
                </div>

            @endif

        </div>

        <div class="detail-card">

            <h3 class="detail-title">
                Notes
            </h3>

            @if($fuel->notes)

                <div class="notes-box">
                    {{ $fuel->notes }}
                </div>

            @else

                <div class="notes-box muted">
                    No notes added for this fuel record.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection