@extends('layouts.app')

@section('content')

<style>
    .maintenance-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* =========================
       HEADER
    ========================= */

    .maintenance-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .maintenance-title h1 {
        margin: 0;
        color: #0f172a;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .maintenance-title p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .add-repair-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        height: 42px;
        padding: 0 18px;
        border: 0;
        border-radius: 12px;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(37, 99, 235, .20);
    }

    .add-repair-btn:hover {
        background: #1d4ed8;
        color: #ffffff;
    }


    /* =========================
       KPI CARDS
    ========================= */

    .maintenance-kpis {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 18px;
    }

    .maintenance-card {
        background: #ffffff;
        border: 1px solid #e4eaf3;
        border-radius: 20px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, .06);
    }

    .maintenance-kpi {
        min-height: 126px;
        padding: 20px;
    }

    .maintenance-kpi-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .maintenance-kpi-value {
        margin-top: 9px;
        color: #0f172a;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.04em;
    }

    .maintenance-kpi-note {
        margin-top: 10px;
        color: #10b981;
        font-size: 12px;
        font-weight: 700;
    }

    .maintenance-kpi-note.danger {
        color: #ef4444;
    }


    /* =========================
       FILTER BAR
    ========================= */

    .maintenance-filter-card {
        margin-bottom: 18px;
        padding: 16px 20px;
    }

    .maintenance-filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .maintenance-search {
        width: 300px;
        height: 38px;
        padding: 0 12px;

        border: 1px solid #dbe3ef;
        border-radius: 10px;

        background: #f8fafc;
        color: #334155;

        font-size: 12px;
        outline: none;
        box-sizing: border-box;
    }

    .maintenance-search:focus {
        border-color: #93c5fd;
        background: #ffffff;
    }

    .maintenance-repair-filter {
        width: 190px;
        height: 38px;
        padding: 0 12px;

        border: 1px solid #dbe3ef;
        border-radius: 10px;

        background: #ffffff;
        color: #334155;

        font-size: 12px;
        outline: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .maintenance-repair-filter:focus {
        border-color: #93c5fd;
    }

    .maintenance-clear {
        width: 70px !important;
        height: 38px !important;
        padding: 0 !important;

        display: inline-flex !important;
        align-items: center;
        justify-content: center;

        background: #6c757d;
        color: #ffffff;

        border-radius: 8px;
        text-decoration: none;

        font-size: 13px;
        font-weight: 700;

        white-space: nowrap;
        flex-shrink: 0;
        box-sizing: border-box;
    }

    .maintenance-clear:hover {
        background: #5c636a;
        color: #ffffff;
    }


    /* =========================
       MAIN CONTENT GRID
    ========================= */

    .maintenance-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(300px, 0.85fr);
        gap: 18px;
        align-items: start;
    }

    .maintenance-table-card {
        padding: 20px;
        min-width: 0;
    }

    .maintenance-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 16px;
    }

    .maintenance-card-title {
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
    }


    /* =========================
       TABLE
    ========================= */

    .maintenance-table-wrap {
        width: 100%;
        overflow: hidden;
        box-sizing: border-box;
    }

    .maintenance-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 12px;
    }

    .maintenance-table th {
        padding: 11px 6px;
        text-align: left;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .maintenance-table td {
        padding: 13px 6px;
        color: #334155;
        font-weight: 600;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
        overflow: hidden;
        box-sizing: border-box;
    }

    .maintenance-table tr:last-child td {
        border-bottom: 0;
    }


    /* =========================
       COLUMN WIDTHS
       TOTAL = 100%
    ========================= */

    .maintenance-table th:nth-child(1),
    .maintenance-table td:nth-child(1) {
        width: 18%;
    }

    .maintenance-table th:nth-child(2),
    .maintenance-table td:nth-child(2) {
        width: 13%;
    }

    .maintenance-table th:nth-child(3),
    .maintenance-table td:nth-child(3) {
        width: 17%;
    }

    .maintenance-table th:nth-child(4),
    .maintenance-table td:nth-child(4) {
        width: 13%;
    }

    .maintenance-table th:nth-child(5),
    .maintenance-table td:nth-child(5) {
        width: 15%;
    }

    .maintenance-table th:nth-child(6),
    .maintenance-table td:nth-child(6) {
        width: 24%;
    }


    /* =========================
       TABLE CONTENT
    ========================= */

    .plate-link {
        display: inline-block;
        color: #101d42;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    .plate-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    .maintenance-number {
        margin-top: 3px;
        color: #2563eb;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .repair-name {
        color: #334155;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .maintenance-workshop {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .maintenance-cost {
        color: #0f172a;
        font-weight: 800;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    /* =========================
       STATUS
    ========================= */

    .maintenance-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
        max-width: 100%;
        box-sizing: border-box;
    }

    .maintenance-status.open {
        background: #fee2e2;
        color: #b91c1c;
    }

    .maintenance-status.progress {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .maintenance-status.completed {
        background: #dcfce7;
        color: #15803d;
    }

    .maintenance-status.cancelled {
        background: #f1f5f9;
        color: #475569;
    }

    .maintenance-status.default {
        background: #fef3c7;
        color: #b45309;
    }


    /* =========================
       ACTIONS
    ========================= */

    .maintenance-actions {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .maintenance-edit,
    .maintenance-delete {
        flex: 0 0 54px;

        width: 54px;
        min-width: 54px;
        max-width: 54px;

        height: 30px;
        min-height: 30px;
        max-height: 30px;

        padding: 0 !important;
        margin: 0 !important;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        box-sizing: border-box;
        border-radius: 8px;

        font-size: 11px;
        line-height: 1;
        font-weight: 700;
        text-align: center;
        white-space: nowrap;
    }

    .maintenance-edit {
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        border: 0;
    }

    .maintenance-edit:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .maintenance-delete {
        background: #dc3545;
        color: #ffffff;
        border: 0;
        cursor: pointer;
    }

    .maintenance-delete:hover {
        background: #bb2d3b;
    }


    /* =========================
       FUEL EFFICIENCY CARD
    ========================= */

    .fuel-card {
        padding: 20px;
        min-width: 0;
    }

    .fuel-efficiency-ring {
        width: 172px;
        height: 172px;
        margin: 18px auto 20px;
        border-radius: 50%;

        display: grid;
        place-items: center;
    }

    .fuel-efficiency-inner {
        width: 126px;
        height: 126px;
        border-radius: 50%;
        background: #ffffff;

        display: grid;
        place-items: center;

        color: #0f172a;
        font-size: 27px;
        font-weight: 800;
        text-align: center;
    }

    .fuel-metric {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;

        padding: 13px 0;
        border-bottom: 1px solid #eef2f7;

        color: #475569;
        font-size: 13px;
        font-weight: 700;
    }

    .fuel-metric:last-child {
        border-bottom: 0;
    }

    .fuel-metric strong {
        color: #334155;
        text-align: right;
        max-width: 55%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }


    /* =========================
       EMPTY STATE
    ========================= */

    .maintenance-empty {
        padding: 35px 15px;
        text-align: center;
        color: #64748b;
        font-size: 13px;
    }


    /* =========================
       PAGINATION
    ========================= */

    .maintenance-pagination {
        margin-top: 18px;
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1100px) {

        .maintenance-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .maintenance-main-grid {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 700px) {

        .maintenance-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .maintenance-kpis {
            grid-template-columns: 1fr;
        }

        .maintenance-filter-form {
            width: 100%;
            flex-wrap: wrap;
        }

        .maintenance-search {
            width: 100%;
            flex: 1 1 100%;
        }

        .maintenance-repair-filter {
            flex: 1;
            width: auto;
        }

        .maintenance-clear {
            width: 70px !important;
        }

        .maintenance-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .maintenance-actions {
            gap: 4px;
        }

        .maintenance-edit,
        .maintenance-delete {
            flex: 0 0 48px;
            width: 48px;
            min-width: 48px;
            max-width: 48px;
        }

        .fuel-efficiency-ring {
            width: 150px;
            height: 150px;
        }

        .fuel-efficiency-inner {
            width: 110px;
            height: 110px;
        }
    }
</style>


<div class="maintenance-page">

    {{-- =========================
         PAGE HEADER
    ========================= --}}

    <div class="maintenance-header">

        <div class="maintenance-title">

            <h1>Maintenance &amp; Fuel</h1>

            <p>
                Repair records, workshop invoices, fuel logs and downtime tracking
            </p>

        </div>

        <a
            href="{{ route('maintenances.create') }}"
            class="add-repair-btn"
        >
            + Add Repair
        </a>

    </div>


    {{-- =========================
         KPI CARDS
    ========================= --}}

    <div class="maintenance-kpis">

        <div class="maintenance-card maintenance-kpi">

            <div class="maintenance-kpi-label">
                Open Repairs
            </div>

            <div class="maintenance-kpi-value">
                {{ $openRepairs ?? '—' }}
            </div>

            <div class="maintenance-kpi-note danger">
                In workshops
            </div>

        </div>


        <div class="maintenance-card maintenance-kpi">

            <div class="maintenance-kpi-label">
                Monthly Repair Cost
            </div>

            <div class="maintenance-kpi-value">

                @if(isset($monthlyRepairCost))
                    AED {{ number_format($monthlyRepairCost, 0) }}
                @else
                    —
                @endif

            </div>

            <div class="maintenance-kpi-note">
                Current month
            </div>

        </div>


        <div class="maintenance-card maintenance-kpi">

            <div class="maintenance-kpi-label">
                Fuel Cost
            </div>

            <div class="maintenance-kpi-value">

                @if(isset($fuelCost))
                    AED {{ number_format($fuelCost, 0) }}
                @else
                    —
                @endif

            </div>

            <div class="maintenance-kpi-note">
                Current month
            </div>

        </div>


        <div class="maintenance-card maintenance-kpi">

            <div class="maintenance-kpi-label">
                Avg Downtime
            </div>

            <div class="maintenance-kpi-value">

                @if(isset($averageDowntime))
                    {{ number_format($averageDowntime, 1) }} Days
                @else
                    —
                @endif

            </div>

            <div class="maintenance-kpi-note">
                Per repair
            </div>

        </div>

    </div>


    {{-- =========================
         FILTER BAR
         BETWEEN KPI + MAIN GRID
    ========================= --}}

    <div class="maintenance-card maintenance-filter-card">

        <form
            action="{{ route('maintenances.index') }}"
            method="GET"
            class="maintenance-filter-form"
        >

            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                class="maintenance-search"
                placeholder="🔍 Search vehicle, workshop or repair..."
            >


            <select
                name="repair_type"
                class="maintenance-repair-filter"
                onchange="this.form.submit()"
            >

                <option value="">
                    All Repair Types
                </option>

                @foreach($repairTypes as $type)

                    <option
                        value="{{ $type }}"
                        {{ ($repairType ?? '') === $type ? 'selected' : '' }}
                    >
                        {{ $type }}
                    </option>

                @endforeach

            </select>


            @if($search || $repairType)

                <a
                    href="{{ route('maintenances.index') }}"
                    class="maintenance-clear"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>


    {{-- =========================
         MAIN CONTENT GRID
         LEFT = MAINTENANCE JOBS
         RIGHT = FUEL EFFICIENCY
    ========================= --}}

    <div class="maintenance-main-grid">


        {{-- =========================
             LEFT SIDE
             MAINTENANCE JOBS
        ========================= --}}

        <div class="maintenance-card maintenance-table-card">

            <div class="maintenance-card-header">

                <div class="maintenance-card-title">
                    Maintenance Jobs
                </div>

            </div>


            <div class="maintenance-table-wrap">

                <table class="maintenance-table">

                    <thead>

                        <tr>
                            <th>Plate Number</th>
                            <th>Repair</th>
                            <th>Workshop</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($maintenances as $maintenance)

                            <tr>

                                {{-- PLATE NUMBER → SHOW --}}
                                <td>

                                    <a
                                        href="{{ route('maintenances.show', $maintenance->id) }}"
                                        class="plate-link"
                                    >
                                        {{ $maintenance->vehicle->plate_number ?? '—' }}
                                    </a>

                                    <div class="maintenance-number">
                                        {{ $maintenance->maintenance_no ?? '—' }}
                                    </div>

                                </td>


                                {{-- REPAIR --}}
                                <td>

                                    <div class="repair-name">
                                        {{ $maintenance->repair_type ?? '—' }}
                                    </div>

                                </td>


                                {{-- WORKSHOP --}}
                                <td>

                                    <div class="maintenance-workshop">
                                        {{ $maintenance->workshop ?: 'Internal Workshop' }}
                                    </div>

                                </td>


                                {{-- COST --}}
                                <td>

                                    <div class="maintenance-cost">
                                        AED {{ number_format((float) $maintenance->total_cost, 2) }}
                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @php
                                        $statusClass = match($maintenance->status) {
                                            'Open' => 'open',
                                            'In Progress' => 'progress',
                                            'Completed' => 'completed',
                                            'Cancelled' => 'cancelled',
                                            default => 'default',
                                        };
                                    @endphp

                                    <span class="maintenance-status {{ $statusClass }}">
                                        {{ $maintenance->status ?? 'Open' }}
                                    </span>

                                </td>


                                {{-- ACTIONS --}}
                                <td>

                                    <div class="maintenance-actions">

                                        <a
                                            href="{{ route('maintenances.edit', $maintenance->id) }}"
                                            class="maintenance-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                        action="{{ route('maintenances.destroy', $maintenance->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this maintenance record?');"
                                            style="display:inline;"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="maintenance-delete"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6">

                                           <div class="maintenance-empty">
                                        No maintenance records found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if($maintenances->hasPages())

                <div class="maintenance-pagination">
                    {{ $maintenances->links() }}
                </div>

            @endif

        </div>
        {{-- =========================
             RIGHT SIDE
             FUEL EFFICIENCY
        ========================= --}}

        <div class="maintenance-card fuel-card">

            <div class="maintenance-card-header">

                <div class="maintenance-card-title">
                    Fuel Efficiency
                </div>

            </div>


            @php

                $efficiency = isset($fuelEfficiency)
                    ? (float) $fuelEfficiency
                    : null;

                /*
                 * Ring fill:
                 *
                 * 12.5 km/L = 12.5%
                 * 40 km/L   = 40%
                 * 100 km/L  = 100%
                 *
                 * 40 is ONLY the color threshold.
                 */

                $efficiencyFill = $efficiency !== null
                    ? max(0, min(100, $efficiency))
                    : 0;

                $efficiencyColor =
                    ($efficiency !== null && $efficiency >= 40)
                        ? '#10b981'
                        : '#ef4444';

            @endphp


            <div
                class="fuel-efficiency-ring"
                style="
                    background: conic-gradient(
                        {{ $efficiencyColor }} 0 {{ $efficiencyFill }}%,
                        #ffffff {{ $efficiencyFill }}% 100%
                    );
                "
            >

                <div class="fuel-efficiency-inner">

                    @if($efficiency !== null)

                        {{ number_format($efficiency, 1) }} km/L

                    @else

                        —

                    @endif

                </div>

            </div>


            <div class="fuel-metric">

                <span>
                    Fuel Records
                </span>

                <strong>
                    {{ $fuelRecordsCount ?? '—' }}
                </strong>

            </div>


            <div class="fuel-metric">

                <span>
                    Efficiency
                </span>

                <strong>

                    @if($efficiency !== null)

                        {{ number_format($efficiency, 1) }} km/L

                    @else

                        —

                    @endif

                </strong>

            </div>

        </div>

    </div>

</div>


{{-- =========================
     FILTER AUTO SUBMIT
========================= --}}

<script>
    document
        .querySelectorAll('.maintenance-repair-filter')
        .forEach(function (select) {

            select.addEventListener('change', function () {
                this.form.submit();
            });

        });
</script>

@endsection