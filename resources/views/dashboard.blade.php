@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
       DASHBOARD
    ========================================================= */

    .dashboard-page {
        padding: 0 0 30px;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .dashboard-heading h2 {
        margin: 0;
        color: #0f172a;
        font-size: 28px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.6px;
    }

    .dashboard-heading p {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .dashboard-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-date {
        height: 42px;
        display: inline-flex;
        align-items: center;
        padding: 0 14px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .new-assignment-btn {
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 17px;
        border-radius: 11px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 7px 18px rgba(37, 99, 235, .18);
        transition: .2s ease;
    }

    .new-assignment-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }


    /* =========================================================
       TOP KPI CARDS — ONLY 4
    ========================================================= */

    .dashboard-kpis {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 20px;
    }

    .dashboard-kpi {
        position: relative;
        min-height: 122px;
        padding: 19px 20px;
        overflow: hidden;

        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 17px;

        box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
    }

    .dashboard-kpi::after {
        content: "";
        position: absolute;
        width: 66px;
        height: 66px;
        right: -20px;
        top: -20px;
        border-radius: 50%;
        background: #eff6ff;
    }

    .kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .kpi-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
    }

    .kpi-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;

        background: #eff6ff;
        border-radius: 11px;

        position: relative;
        z-index: 1;

        font-size: 17px;
    }

    .kpi-value {
        margin-top: 10px;
        color: #0f172a;
        font-size: 28px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -0.8px;
    }

    .kpi-value.money {
        font-size: 25px;
    }

    .kpi-subtitle {
        margin-top: 8px;
        color: #10b981;
        font-size: 11px;
        font-weight: 700;
    }


    /* =========================================================
       CHART + ALERTS
    ========================================================= */

    .dashboard-middle {
        display: grid;
        grid-template-columns: minmax(0, 1.75fr) minmax(300px, .75fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .dashboard-card {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 17px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
        overflow: hidden;
    }

    .dashboard-card-header {
        min-height: 62px;
        padding: 14px 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;

        border-bottom: 1px solid #edf1f6;
    }

    .dashboard-card-header h3 {
        margin: 0;
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
    }

    .dashboard-card-header p {
        margin: 4px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }

    .card-header-badge {
        padding: 7px 11px;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }


    /* =========================================================
       REVENUE BAR CHART
    ========================================================= */

    .trend-content {
        height: 300px;
        padding: 18px 24px 18px;
    }

    .trend-chart {
        height: 100%;
        display: flex;
        align-items: flex-end;
        gap: 18px;
    }

    .trend-column {
        height: 100%;
        flex: 1;

        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
    }

    .trend-value {
        min-height: 16px;
        margin-bottom: 7px;

        color: #64748b;
        font-size: 9px;
        font-weight: 700;
    }

    .trend-bar-area {
        width: 100%;
        height: 215px;

        display: flex;
        align-items: flex-end;
        justify-content: center;

        border-bottom: 1px solid #e2e8f0;
    }

    .trend-bar {
        width: 70%;
        max-width: 48px;
        min-height: 5px;

        border-radius: 8px 8px 3px 3px;

        background: linear-gradient(
            180deg,
            #3b82f6 0%,
            #2563eb 100%
        );

        box-shadow: 0 7px 15px rgba(37, 99, 235, .16);
    }

    .trend-label {
        margin-top: 9px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
    }


    /* =========================================================
       CRITICAL ALERTS
    ========================================================= */

    .alerts-content {
        padding: 2px 18px 12px;
    }

    .alert-row {
        min-height: 59px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;

        border-bottom: 1px solid #edf1f6;
    }

    .alert-row:last-child {
        border-bottom: 0;
    }

    .alert-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .alert-indicator {
        width: 9px;
        height: 9px;
        min-width: 9px;
        border-radius: 50%;
        background: #f59e0b;
    }

    .alert-indicator.red {
        background: #ef4444;
    }

    .alert-indicator.green {
        background: #10b981;
    }

    .alert-indicator.blue {
        background: #2563eb;
    }

    .alert-title {
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .alert-value {
        color: #334155;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .no-alerts {
        padding: 42px 10px;
        text-align: center;
        color: #94a3b8;
        font-size: 12px;
    }


    /* =========================================================
       ACTIVE TRUCK OPERATIONS
    ========================================================= */

    .operations-card {
        margin-bottom: 0;
    }

    .operations-header {
        min-height: 70px;
    }

    .operations-filters {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .operations-filter {
        height: 37px;
        min-width: 125px;
        padding: 0 11px;

        background: #fff;
        border: 1px solid #dfe6ef;
        border-radius: 10px;

        color: #334155;
        font-size: 11px;
        font-weight: 700;

        outline: none;
        cursor: pointer;
    }

    .operations-filter:focus {
        border-color: #2563eb;
    }

    .operations-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .operations-table {
        width: 100%;
        border-collapse: collapse;
    }

    .operations-table th {
        padding: 13px 20px;

        background: #fbfcfe;
        border-bottom: 1px solid #edf1f6;

        color: #94a3b8;
        font-size: 10px;
        font-weight: 800;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .04em;

        white-space: nowrap;
    }

    .operations-table td {
        padding: 14px 20px;

        border-bottom: 1px solid #edf1f6;

        color: #475569;
        font-size: 12px;

        white-space: nowrap;
    }

    .operations-table tr:last-child td {
        border-bottom: 0;
    }

    .operation-primary {
        color: #1e293b;
        font-weight: 800;
    }

    .operation-status {
        display: inline-flex;
        align-items: center;

        padding: 5px 10px;

        border-radius: 999px;

        background: #eff6ff;
        color: #2563eb;

        font-size: 10px;
        font-weight: 800;
    }

    .no-operations {
        padding: 45px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 12px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {

        .dashboard-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-middle {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 700px) {

        .dashboard-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-actions {
            width: 100%;
        }

        .dashboard-date,
        .new-assignment-btn {
            flex: 1;
            justify-content: center;
        }

        .dashboard-kpis {
            grid-template-columns: 1fr;
        }

        .operations-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .operations-filters {
            width: 100%;
        }

        .operations-filter {
            flex: 1;
            min-width: 0;
        }

        .operations-table {
            min-width: 800px;
        }

        .trend-content {
            height: 260px;
            padding: 15px 12px;
        }

        .trend-chart {
            gap: 7px;
        }

        .trend-bar-area {
            height: 185px;
        }
    }
</style>


<div class="dashboard-page">

    <!-- =====================================================
         DASHBOARD HEADER
    ====================================================== -->

    <div class="dashboard-header">

        <div class="dashboard-heading">

            <h2>
                Executive Dashboard
            </h2>

            <p>
                Overview of revenue, fleet utilization, alerts and active operations
            </p>

        </div>


        <div class="dashboard-actions">

            <div class="dashboard-date">
                {{ now()->format('d M Y') }}
            </div>

            <a
                href="{{ route('assignments.create') }}"
                class="new-assignment-btn"
            >
                + New Assignment
            </a>

        </div>

    </div>


    <!-- =====================================================
         ONLY 4 KPI CARDS
    ====================================================== -->

    <div class="dashboard-kpis">

        <!-- TOTAL TRUCKS -->

        <div class="dashboard-kpi">

            <div class="kpi-top">

                <span class="kpi-label">
                    Total Trucks
                </span>

                <div class="kpi-icon">
                    🚛
                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($totalVehicles) }}
            </div>

            <div class="kpi-subtitle">
                ↑ {{ number_format($activeVehicles) }} active vehicles
            </div>

        </div>


        <!-- ACTIVE ASSIGNMENTS -->

        <div class="dashboard-kpi">

            <div class="kpi-top">

                <span class="kpi-label">
                    Active Assignments
                </span>

                <div class="kpi-icon">
                    🔄
                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($activeAssignments) }}
            </div>

            <div class="kpi-subtitle">
                {{ number_format($activeTrips) }} active trips
            </div>

        </div>


        <!-- MONTHLY REVENUE -->

        <div class="dashboard-kpi">

            <div class="kpi-top">

                <span class="kpi-label">
                    Monthly Revenue
                </span>

                <div class="kpi-icon">
                    💰
                </div>

            </div>

            <div class="kpi-value money">
                AED {{ number_format($monthlyRevenue, 0) }}
            </div>

            <div class="kpi-subtitle">
                ↑ This month
            </div>

        </div>


        <!-- NET PROFIT -->

        <div class="dashboard-kpi">

            <div class="kpi-top">

                <span class="kpi-label">
                    Net Profit
                </span>

                <div class="kpi-icon">
                    📈
                </div>

            </div>

            <div class="kpi-value money">
                AED {{ number_format($monthlyProfit, 0) }}
            </div>

            <div class="kpi-subtitle">
                ↑ Revenue less expenses & payroll
            </div>

        </div>

    </div>


    <!-- =====================================================
         CHART + CRITICAL ALERTS
    ====================================================== -->

    <div class="dashboard-middle">


        <!-- REVENUE & UTILIZATION TREND -->

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <div>

                    <h3>
                        Revenue & Utilization Trend
                    </h3>

                    <p>
                        Monthly business performance
                    </p>

                </div>

                <span class="card-header-badge">
                    Last 6 months
                </span>

            </div>


            <div class="trend-content">

                @php
                    $maxRevenue = max($revenueTrend ?: [1]);
                @endphp

                <div class="trend-chart">

                    @foreach($trendLabels as $index => $label)

                        @php
                            $revenue = (float) ($revenueTrend[$index] ?? 0);

                            $barHeight = $maxRevenue > 0
                                ? max(3, ($revenue / $maxRevenue) * 100)
                                : 3;
                        @endphp

                        <div class="trend-column">

                            <div class="trend-value">

                                @if($revenue > 0)
                                    AED {{ number_format($revenue / 1000, 0) }}K
                                @else
                                    AED 0
                                @endif

                            </div>

                            <div class="trend-bar-area">

                                <div
                                    class="trend-bar"
                                    style="height: {{ $barHeight }}%;"
                                ></div>

                            </div>

                            <div class="trend-label">

                                {{ \Carbon\Carbon::parse($label)->format('M') }}

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        <!-- CRITICAL ALERTS -->

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <div>
                    <h3>
                        Critical Alerts
                    </h3>
                </div>

                <span class="card-header-badge">
                    {{ $criticalAlerts->count() }} alerts
                </span>

            </div>

            <div class="alerts-content">
                @forelse($criticalAlerts as $alert)

                    @php
                        $alertIndicator = match($alert['type']) {
                            'finance' => 'red',
                            'maintenance' => 'red',
                            'fuel' => 'green',
                            'document' => 'blue',
                            default => '',
                        };
                    @endphp

                    <div class="alert-row">

                        <div class="alert-info">

                            <span class="alert-indicator {{ $alertIndicator }}"></span>

                            <span class="alert-title">
                                {{ $alert['title'] }}
                            </span>

                        </div>

                        <span class="alert-value">
                            {{ $alert['value'] }}
                        </span>

                    </div>

                @empty

                    <div class="no-alerts">
                        ✓ No critical alerts
                    </div>

                @endforelse

            </div>

        </div>

    </div>


    <!-- =====================================================
         ACTIVE TRUCK OPERATIONS — ONLY SECTION BELOW
    ====================================================== -->

    <div class="dashboard-card operations-card">

        <div class="dashboard-card-header operations-header">

            <div>
                <h3>
                    Active Truck Operations
                </h3>
            </div>


            <div class="operations-filters">

                <select
                    id="statusFilter"
                    class="operations-filter"
                >
                    <option value="all">
                        Status ▾
                    </option>

                    <option value="active">
                        Active
                    </option>

                    <option value="ongoing">
                        Ongoing
                    </option>

                </select>


                <select
                    id="clientFilter"
                    class="operations-filter"
                >
                    <option value="all">
                        Client ▾
                    </option>

                    @foreach(
                        $activeOperations
                            ->pluck('client.client_name')
                            ->filter()
                            ->unique()
                            ->sort()
                        as $clientName
                    )

                        <option value="{{ strtolower($clientName) }}">
                            {{ $clientName }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        @if($activeOperations->count())

            <div class="operations-table-wrap">

                <table class="operations-table">

                    <thead>

                        <tr>

                            <th>
                                Plate
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Capacity
                            </th>

                            <th>
                                Driver
                            </th>

                            <th>
                                Client
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody id="operationsBody">

                        @foreach($activeOperations as $operation)

                            <tr
                                data-status="{{ strtolower($operation->status ?? 'active') }}"
                                data-client="{{ strtolower($operation->client->client_name ?? '') }}"
                            >

                                <!-- PLATE -->

                                <td>

                                    <div class="operation-primary">
                                        {{ $operation->vehicle->plate_number ?? '—' }}
                                    </div>

                                </td>


                                <!-- TYPE -->

                                <td>

                                    <div class="operation-primary">
                                        {{ $operation->vehicle->vehicle_type ?? '—' }}
                                    </div>

                                </td>


                                <!-- CAPACITY -->

                                <td>

                                    <div class="operation-primary">

                                        @if($operation->vehicle)

                                            @if($operation->vehicle->load_capacity)
                                                {{ $operation->vehicle->load_capacity }}

                                                @if($operation->vehicle->load_capacity_unit)
                                                    {{ $operation->vehicle->load_capacity_unit }}
                                                @endif

                                            @elseif($operation->vehicle->capacity)

                                                {{ $operation->vehicle->capacity }}

                                            @else
                                                —
                                            @endif

                                        @else
                                            —
                                        @endif

                                    </div>

                                </td>


                                <!-- DRIVER -->

                                <td>

                                    <div class="operation-primary">
                                        {{ $operation->driver->driver_name ?? '—' }}
                                    </div>

                                </td>


                                <!-- CLIENT -->

                                <td>

                                    <div class="operation-primary">
                                        {{ $operation->client->client_name ?? '—' }}
                                    </div>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span class="operation-status">
                                        {{ $operation->status ?? 'Active' }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="no-operations">
                No active truck operations found.
            </div>

        @endif

    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {

    const statusFilter = document.getElementById('statusFilter');
    const clientFilter = document.getElementById('clientFilter');

    const rows = document.querySelectorAll(
        '#operationsBody tr'
    );


    function filterOperations() {

        const selectedStatus =
            statusFilter
                ? statusFilter.value.toLowerCase()
                : 'all';

        const selectedClient =
            clientFilter
                ? clientFilter.value.toLowerCase()
                : 'all';


        rows.forEach(function (row) {

            const rowStatus =
                (row.dataset.status || '').toLowerCase();

            const rowClient =
                (row.dataset.client || '').toLowerCase();


            const statusMatches =
                selectedStatus === 'all' ||
                rowStatus === selectedStatus;


            const clientMatches =
                selectedClient === 'all' ||
                rowClient === selectedClient;


            row.style.display =
                statusMatches && clientMatches
                    ? ''
                    : 'none';

        });

    }


    if (statusFilter) {

        statusFilter.addEventListener(
            'change',
            filterOperations
        );

    }


    if (clientFilter) {

        clientFilter.addEventListener(
            'change',
            filterOperations
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Small chart animation
    |--------------------------------------------------------------------------
    */

    const bars =
        document.querySelectorAll('.trend-bar');


    bars.forEach(function (bar, index) {

        bar.animate(
            [
                {
                    transform: 'scaleY(0)',
                    transformOrigin: 'bottom'
                },
                {
                    transform: 'scaleY(1)',
                    transformOrigin: 'bottom'
                }
            ],
            {
                duration: 600,
                delay: index * 70,
                easing: 'ease-out',
                fill: 'both'
            }
        );

    });

});
</script>


<style>

    /*
    |--------------------------------------------------------------------------
    | Final mobile adjustments
    |--------------------------------------------------------------------------
    */

    @media (max-width: 700px) {

        .dashboard-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .operations-header {
            align-items: flex-start;
        }

        .operations-filters {
            width: 100%;
        }

        .operations-filter {
            flex: 1;
        }

        .operations-table {
            min-width: 800px;
        }

    }


    @media (max-width: 480px) {

        .dashboard-heading h2 {
            font-size: 22px;
        }

        .dashboard-heading p {
            font-size: 12px;
        }

        .dashboard-actions {
            width: 100%;
            flex-direction: column;
        }

        .dashboard-date,
        .new-assignment-btn {
            width: 100%;
            justify-content: center;
        }

        .dashboard-kpi {
            min-height: 115px;
        }

        .kpi-value {
            font-size: 25px;
        }

        .kpi-value.money {
            font-size: 22px;
        }

        .trend-content {
            height: 250px;
            padding: 15px 10px;
        }

        .trend-chart {
            gap: 5px;
        }

        .trend-bar-area {
            height: 175px;
        }

        .trend-bar {
            width: 75%;
        }

    }

</style>


@endsection