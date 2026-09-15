@extends('layouts.app')

@section('content')

@php
    $hasReportFilters = request()->filled('start_date')
        || request()->filled('end_date')
        || request()->filled('status')
        || request()->filled('client_id')
        || request()->filled('vehicle_id')
        || request()->filled('driver_id')
        || request()->filled('vendor_id')
        || request()->filled('assignment_id');
@endphp

@php
    $appliedPeriod = request('period');

    $periodLabels = [
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'quarterly' => 'Quarterly',
        'yearly' => 'Yearly',
        'custom' => 'Custom Range',
    ];

    $appliedPeriodLabel = $periodLabels[$appliedPeriod ?? ''] ?? 'Monthly';

    if (($appliedPeriod ?? '') === 'custom' && (request('start_date') || request('end_date'))) {
        $customFrom = request('start_date') ?: '—';
        $customTo = request('end_date') ?: '—';
        $appliedPeriodLabel .= ' (' . $customFrom . ' to ' . $customTo . ')';
    } elseif (request('start_date') || request('end_date')) {
        $appliedPeriodLabel .= ' (' . (request('start_date') ?: '—') . ' to ' . (request('end_date') ?: '—') . ')';
    }
@endphp

<style>

/* =========================================================
   REPORTS CENTER
========================================================= */

.reports-page {
    padding: 4px 0 30px;
}

.reports-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.reports-header-left h2 {
    margin: 0;
    color: #101d42;
    font-size: 28px;
    font-weight: 800;
    line-height: 1.2;
}

.reports-header-left p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.reports-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.report-date-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 38px;
    padding: 0 13px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
}

.report-export-btn {
    height: 38px;
    border: 0;
    border-radius: 8px;
    padding: 0 15px;
    background: #2563eb;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: .18s ease;
}

.report-export-btn:hover {
    background: #1d4ed8;
}


/* =========================================================
   MAIN TWO COLUMN AREA
========================================================= */

.reports-layout {
    display: grid;
    grid-template-columns: 285px minmax(0, 1fr);
    gap: 22px;
    align-items: start;
}


/* =========================================================
   REPORT CATALOGUE
========================================================= */

.report-menu {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, .05);
}

.report-menu-heading {
    margin-bottom: 18px;
}

.report-menu-heading h3 {
    margin: 0;
    color: #101d42;
    font-size: 16px;
    font-weight: 800;
}

.report-menu-heading p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 11px;
    line-height: 1.5;
}

.report-category {
    margin-bottom: 10px;
    border: 1px solid #eef2f7;
    border-radius: 9px;
    overflow: hidden;
    background: #ffffff;
}

.report-category.open {
    border-color: #dbe3ef;
}

.report-category:last-child {
    margin-bottom: 0;
}

.report-category-title {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    min-height: 36px;
    margin: 0;
    padding: 0 11px;
    border: 0;
    background: #f8fafc;
    color: #101d42;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .07em;
    text-transform: uppercase;
    cursor: pointer;
    user-select: none;
}

.report-category-title::after {
    content: '⌄';
    color: #64748b;
    font-size: 13px;
    line-height: 1;
    transform: rotate(-90deg);
    transition: .18s ease;
}

.report-category.open .report-category-title::after {
    transform: rotate(0deg);
}

.report-category:not(.open) .report-option,
.report-category:not(.open) .report-category-note {
    display: none;
}

.report-category.open .report-option {
    display: flex;
}

.report-option {
    width: 100%;
    min-height: 38px;
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 10px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: #475569;
    text-align: left;
    font-family: inherit;
    font-size: 12px;
    cursor: pointer;
    transition: .18s ease;
}

.report-option:hover {
    background: #f1f5f9;
    color: #101d42;
}

.report-category .report-option {
    width: calc(100% - 8px);
    margin: 2px 4px;
    padding-left: 16px;
}

.report-option.active {
    background: #e8efff;
    color: #1d4ed8;
    font-weight: 700;
}

.report-option-icon {
    width: 22px;
    min-width: 22px;
    text-align: center;
    font-size: 14px;
}


/* =========================================================
   REPORT BASIS
========================================================= */

.report-basis-box {
    margin-top: 20px;
    padding: 13px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}

.report-basis-title {
    margin-bottom: 5px;
    color: #101d42;
    font-size: 11px;
    font-weight: 800;
}

.report-basis-box p {
    margin: 0;
    color: #64748b;
    font-size: 10px;
    line-height: 1.6;
}


/* =========================================================
   REPORT MAIN CARD
========================================================= */

.report-main {
    min-width: 0;
}

.report-card {
    min-width: 0;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, .05);
}

.report-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 20px;
}

.report-card-header-left {
    min-width: 0;
}

.report-eyebrow {
    margin-bottom: 5px;
    color: #2563eb;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .1em;
}

.report-card-header h3 {
    margin: 0;
    color: #101d42;
    font-size: 21px;
    font-weight: 800;
    line-height: 1.3;
}

.report-card-header p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.report-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.report-action-btn {
    height: 36px;
    padding: 0 12px;
    border: 1px solid #dbe3ef;
    border-radius: 7px;
    background: #ffffff;
    color: #334155;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: .18s ease;
}

.report-action-btn:hover {
    background: #f8fafc;
}

.report-action-btn.primary {
    border-color: #2563eb;
    background: #2563eb;
    color: #ffffff;
}

.report-action-btn.primary:hover {
    border-color: #1d4ed8;
    background: #1d4ed8;
}


/* =========================================================
   FILTERS
========================================================= */

.report-filters {
    display: flex;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 22px;
    padding: 15px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    color: #64748b;
    font-size: 10px;
    font-weight: 700;
}

.filter-control {
    width: 145px;
    height: 38px;
    padding: 0 10px;
    border: 1px solid #dbe3ef;
    border-radius: 7px;
    background: #ffffff;
    color: #334155;
    font-family: inherit;
    font-size: 12px;
    outline: none;
}

.filter-control:focus {
    border-color: #2563eb;
}

.apply-filter {
    height: 38px;
    padding: 0 15px;
    border: 0;
    border-radius: 7px;
    background: #2563eb;
    color: #ffffff;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: .18s ease;
}

.apply-filter:hover {
    background: #1d4ed8;
}

.clear-filter {
    height: 38px;
    min-width: 110px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 12px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: #e2e8f0;
    color: #334155;
    text-decoration: none;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    transition: .18s ease;
}

.clear-filter:hover {
    background: #cbd5e1;
    color: #1e293b;
}

.filter-clear {
    flex: 0 0 auto;
}


/* =========================================================
   INITIAL EMPTY STATE
========================================================= */

.report-empty {
    min-height: 310px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 40px 20px;
    border: 1px dashed #dbe3ef;
    border-radius: 12px;
    background: #fcfdff;
    text-align: center;
}

.report-empty-icon {
    width: 62px;
    height: 62px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    border-radius: 15px;
    background: #e8efff;
    font-size: 28px;
}

.report-empty h3 {
    margin: 0;
    color: #101d42;
    font-size: 17px;
    font-weight: 800;
}

.report-empty p {
    max-width: 500px;
    margin: 7px 0 0;
    color: #64748b;
    font-size: 12px;
    line-height: 1.6;
}


/* =========================================================
   REPORT INFORMATION
========================================================= */

.applied-period {
    display:flex;
    align-items:center;
    justify-content:flex-start;
    gap:10px;
    margin-top:14px;
    margin-bottom:14px;
    padding:10px 13px;
    border:1px solid #dbeafe;
    border-radius:9px;
    background:#eff6ff;
    color:#475569;
    font-size:12px;
}

.applied-period span {
    color:#64748b;
    font-weight:700;
}

.applied-period strong {
    color:#101d42;
    font-weight:800;
}

.report-info {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
}

.report-info-item {
    padding: 13px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 9px;
}

.report-info-item span {
    display: block;
    margin-bottom: 4px;
    color: #64748b;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.report-info-item strong {
    display: block;
    color: #101d42;
    font-size: 12px;
    font-weight: 800;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1050px) {

    .reports-layout {
        grid-template-columns: 245px minmax(0, 1fr);
    }

    .report-card {
        padding: 18px;
    }

    .filter-control {
        width: 130px;
    }

}

@media (max-width: 800px) {

    .reports-layout {
        grid-template-columns: 1fr;
    }

    .report-menu {
        order: 2;
    }

    .report-main {
        order: 1;
    }

    .reports-header {
        flex-direction: column;
    }

    .reports-header-right {
        width: 100%;
    }

    .report-date-badge {
        flex: 1;
    }

    .report-export-btn {
        flex: 1;
    }

    .report-card-header {
        flex-direction: column;
    }

    .report-header-actions {
        width: 100%;
    }

    .report-action-btn {
        flex: 1;
    }

}

@media (max-width: 600px) {

    .reports-page {
        padding-bottom: 20px;
    }

    .reports-header-left h2 {
        font-size: 23px;
    }

    .reports-header-right {
        flex-direction: column;
        align-items: stretch;
    }

    .report-date-badge,
    .report-export-btn {
        width: 100%;
        justify-content: center;
    }

    .report-card {
        padding: 14px;
        border-radius: 10px;
    }

    .report-filters {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        width: 100%;
    }

    .filter-control,
    .apply-filter,
    .clear-filter {
        width: 100%;
    }

    .report-info {
        grid-template-columns: 1fr;
    }

}


/* =========================================================
   PRINT
========================================================= */

@media print {

    .sidebar,
    .navbar,
    .reports-header-right,
    .report-menu,
    .report-filters,
    .report-header-actions {
        display: none !important;
    }

    .reports-layout {
        display: block;
    }

    .report-card {
        border: 0;
        box-shadow: none;
        padding: 0;
    }

}

</style>


<div class="reports-page">


    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="reports-header">

        <div class="reports-header-left">

            <h2>
                Reports & Analytics
            </h2>

            <p>
                Management reports, operational analysis,
                profitability and compliance insights.
            </p>

        </div>


        <div class="reports-header-right">

            <div class="report-date-badge">
                📅
                {{ now()->format('d M Y') }}
            </div>

            <button
                type="button"
                class="report-export-btn"
                id="topExportReportBtn"
            >
                📤 Export Report
            </button>

        </div>

    </div>


    <!-- =====================================================
         REPORTS LAYOUT
    ====================================================== -->

    <div class="reports-layout">


        <!-- =================================================
             LEFT REPORT CATALOGUE
        ================================================== -->

        <aside class="report-menu">

            <div class="report-menu-heading">

                <h3>
                    Report Catalogue
                </h3>

                <p>
                    Select a report category and report
                    to view its analysis.
                </p>

            </div>


            <div class="report-category open">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="true">
                    <span>Executive</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="executive-kpi"
                    data-code="RPT-001"
                    data-title="Executive KPI Summary"
                >
                    <span class="report-option-icon">📊</span>
                    Executive KPI Summary
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="monthly-revenue-expense"
                    data-code="RPT-002"
                    data-title="Monthly Revenue vs Expense"
                >
                    <span class="report-option-icon">📈</span>
                    Monthly Revenue vs Expense
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="monthly-pnl"
                    data-code="RPT-003"
                    data-title="Monthly P&amp;L"
                >
                    <span class="report-option-icon">💰</span>
                    Monthly P&amp;L
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="cash-receivable"
                    data-code="RPT-004"
                    data-title="Cash / Receivable Summary"
                >
                    <span class="report-option-icon">💵</span>
                    Cash / Receivable Summary
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="outstanding-reimbursement"
                    data-code="RPT-005"
                    data-title="Outstanding Reimbursement"
                >
                    <span class="report-option-icon">🔁</span>
                    Outstanding Reimbursement
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="top-low-profit-vehicles"
                    data-code="RPT-006"
                    data-title="Top / Low Profit Vehicles"
                >
                    <span class="report-option-icon">🚛</span>
                    Top / Low Profit Vehicles
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="top-clients"
                    data-code="RPT-007"
                    data-title="Top Clients"
                >
                    <span class="report-option-icon">🏢</span>
                    Top Clients
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="risk-expiry"
                    data-code="RPT-008"
                    data-title="Risk and Expiry Summary"
                >
                    <span class="report-option-icon">⚠️</span>
                    Risk and Expiry Summary
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Fleet</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-master"
                    data-code="RPT-009"
                    data-title="Vehicle Master"
                >
                    <span class="report-option-icon">🚚</span>
                    Vehicle Master
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-status"
                    data-code="RPT-010"
                    data-title="Vehicle Status"
                >
                    <span class="report-option-icon">●</span>
                    Vehicle Status
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-type-summary"
                    data-code="RPT-011"
                    data-title="Vehicle Type Summary"
                >
                    <span class="report-option-icon">🚛</span>
                    Vehicle Type Summary
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-utilisation"
                    data-code="RPT-012"
                    data-title="Vehicle Utilisation"
                >
                    <span class="report-option-icon">📊</span>
                    Vehicle Utilisation
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="idle-vehicle"
                    data-code="RPT-013"
                    data-title="Idle Vehicle"
                >
                    <span class="report-option-icon">⏸️</span>
                    Idle Vehicle
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-revenue"
                    data-code="RPT-014"
                    data-title="Vehicle Revenue"
                >
                    <span class="report-option-icon">💰</span>
                    Vehicle Revenue
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-expense"
                    data-code="RPT-015"
                    data-title="Vehicle Expense"
                >
                    <span class="report-option-icon">💳</span>
                    Vehicle Expense
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-profitability"
                    data-code="RPT-016"
                    data-title="Vehicle Profitability"
                >
                    <span class="report-option-icon">📈</span>
                    Vehicle Profitability
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="fuel-cost-by-vehicle"
                    data-code="RPT-017"
                    data-title="Fuel Cost by Vehicle"
                >
                    <span class="report-option-icon">⛽</span>
                    Fuel Cost by Vehicle
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="maintenance-cost-by-vehicle"
                    data-code="RPT-018"
                    data-title="Maintenance Cost by Vehicle"
                >
                    <span class="report-option-icon">🛠️</span>
                    Maintenance Cost by Vehicle
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="fine-history-by-vehicle"
                    data-code="RPT-019"
                    data-title="Fine History by Vehicle"
                >
                    <span class="report-option-icon">⚠️</span>
                    Fine History by Vehicle
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="fleet-health"
                    data-code="RPT-020"
                    data-title="Fleet Health / High Cost"
                >
                    <span class="report-option-icon">❤️</span>
                    Fleet Health / High Cost
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Driver &amp; HR</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="driver-master"
                    data-code="RPT-021"
                    data-title="Driver Master"
                >
                    <span class="report-option-icon">👤</span>
                    Driver Master
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="driver-status"
                    data-code="RPT-022"
                    data-title="Driver Status"
                >
                    <span class="report-option-icon">●</span>
                    Driver Status
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="assignment-trip-history"
                    data-code="RPT-023"
                    data-title="Assignment / Trip History"
                >
                    <span class="report-option-icon">🗂️</span>
                    Assignment / Trip History
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="driver-leave"
                    data-code="RPT-024"
                    data-title="Leave Report"
                >
                    <span class="report-option-icon">📅</span>
                    Leave Report
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="salary-report"
                    data-code="RPT-025"
                    data-title="Salary Report"
                >
                    <span class="report-option-icon">💰</span>
                    Salary Report
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="advance-history"
                    data-code="RPT-026"
                    data-title="Advance History"
                >
                    <span class="report-option-icon">💵</span>
                    Advance History
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="outstanding-advance"
                    data-code="RPT-027"
                    data-title="Outstanding Advance"
                >
                    <span class="report-option-icon">🧾</span>
                    Outstanding Advance
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="driver-fine-deduction"
                    data-code="RPT-028"
                    data-title="Fine / Deduction Report"
                >
                    <span class="report-option-icon">⚠️</span>
                    Fine / Deduction Report
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="driver-document-expiry"
                    data-code="RPT-029"
                    data-title="Document Expiry"
                >
                    <span class="report-option-icon">📄</span>
                    Document Expiry
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="driver-performance"
                    data-code="RPT-030"
                    data-title="Driver Performance"
                >
                    <span class="report-option-icon">📈</span>
                    Driver Performance
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Client</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-master"
                    data-code="RPT-031"
                    data-title="Client Master"
                >
                    <span class="report-option-icon">🏢</span>
                    Client Master
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-contract-expiry"
                    data-code="RPT-032"
                    data-title="Contract Expiry"
                >
                    <span class="report-option-icon">📋</span>
                    Contract Expiry
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-revenue"
                    data-code="RPT-033"
                    data-title="Client Revenue"
                >
                    <span class="report-option-icon">💰</span>
                    Client Revenue
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-expense"
                    data-code="RPT-034"
                    data-title="Client Expense"
                >
                    <span class="report-option-icon">💳</span>
                    Client Expense
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-profitability"
                    data-code="RPT-035"
                    data-title="Client Profitability"
                >
                    <span class="report-option-icon">📈</span>
                    Client Profitability
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-outstanding"
                    data-code="RPT-036"
                    data-title="Client Outstanding"
                >
                    <span class="report-option-icon">🧾</span>
                    Client Outstanding
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-ledger"
                    data-code="RPT-037"
                    data-title="Client Ledger"
                >
                    <span class="report-option-icon">📒</span>
                    Client Ledger
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-fuel-reimbursement"
                    data-code="RPT-038"
                    data-title="Fuel Reimbursement Recovery"
                >
                    <span class="report-option-icon">⛽</span>
                    Fuel Reimbursement Recovery
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="top-low-margin-clients"
                    data-code="RPT-039"
                    data-title="Top / Low Margin Clients"
                >
                    <span class="report-option-icon">📊</span>
                    Top / Low Margin Clients
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Vendor</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="vendor-master"
                    data-code="RPT-040"
                    data-title="Vendor Master"
                >
                    <span class="report-option-icon">🏢</span>
                    Vendor Master
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vendor-vehicle"
                    data-code="RPT-041"
                    data-title="Vehicles Supplied"
                >
                    <span class="report-option-icon">🚚</span>
                    Vehicles Supplied
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vendor-rate"
                    data-code="RPT-042"
                    data-title="Vendor Rates"
                >
                    <span class="report-option-icon">💰</span>
                    Vendor Rates
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vendor-payable"
                    data-code="RPT-043"
                    data-title="Vendor Payable"
                >
                    <span class="report-option-icon">💳</span>
                    Vendor Payable
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vendor-ledger"
                    data-code="RPT-044"
                    data-title="Vendor Ledger"
                >
                    <span class="report-option-icon">📒</span>
                    Vendor Ledger
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="workshop-spend"
                    data-code="RPT-045"
                    data-title="Workshop Spend"
                >
                    <span class="report-option-icon">🛠️</span>
                    Workshop Spend
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Operations</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="assignment-register"
                    data-code="RPT-046"
                    data-title="Assignment Register"
                >
                    <span class="report-option-icon">📋</span>
                    Assignment Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="active-assignments"
                    data-code="RPT-047"
                    data-title="Active Assignments"
                >
                    <span class="report-option-icon">▶️</span>
                    Active Assignments
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="completed-assignments"
                    data-code="RPT-048"
                    data-title="Completed Assignments"
                >
                    <span class="report-option-icon">✅</span>
                    Completed Assignments
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="trip-register"
                    data-code="RPT-049"
                    data-title="Trip Register"
                >
                    <span class="report-option-icon">🚚</span>
                    Trip Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="trip-status"
                    data-code="RPT-050"
                    data-title="Trip Status"
                >
                    <span class="report-option-icon">●</span>
                    Trip Status
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="trip-profitability"
                    data-code="RPT-051"
                    data-title="Trip Profitability"
                >
                    <span class="report-option-icon">📈</span>
                    Trip Profitability
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="route-profitability"
                    data-code="RPT-052"
                    data-title="Route Profitability"
                >
                    <span class="report-option-icon">🗺️</span>
                    Route Profitability
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-driver-history"
                    data-code="RPT-053"
                    data-title="Vehicle / Driver Assignment History"
                >
                    <span class="report-option-icon">🕘</span>
                    Vehicle / Driver Assignment History
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="cancelled-trips"
                    data-code="RPT-054"
                    data-title="Cancelled Trips"
                >
                    <span class="report-option-icon">✕</span>
                    Cancelled Trips
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Fuel &amp; Maintenance</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="fuel-register"
                    data-code="RPT-055"
                    data-title="Fuel Register"
                >
                    <span class="report-option-icon">⛽</span>
                    Fuel Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="fuel-consumption"
                    data-code="RPT-056"
                    data-title="Fuel Consumption"
                >
                    <span class="report-option-icon">📊</span>
                    Fuel Consumption
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="fuel-cost-trend"
                    data-code="RPT-057"
                    data-title="Fuel Cost Trend"
                >
                    <span class="report-option-icon">📈</span>
                    Fuel Cost Trend
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="reimbursable-fuel"
                    data-code="RPT-058"
                    data-title="Reimbursable Fuel"
                >
                    <span class="report-option-icon">🔁</span>
                    Reimbursable Fuel
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="fuel-recovery-ageing"
                    data-code="RPT-059"
                    data-title="Fuel Recovery Ageing"
                >
                    <span class="report-option-icon">⌛</span>
                    Fuel Recovery Ageing
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="maintenance-register"
                    data-code="RPT-060"
                    data-title="Maintenance Register"
                >
                    <span class="report-option-icon">🛠️</span>
                    Maintenance Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="maintenance-cost-trend"
                    data-code="RPT-061"
                    data-title="Maintenance Trend"
                >
                    <span class="report-option-icon">📈</span>
                    Maintenance Trend
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vehicle-downtime"
                    data-code="RPT-062"
                    data-title="Vehicle Downtime"
                >
                    <span class="report-option-icon">⏱️</span>
                    Vehicle Downtime
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="preventive-maintenance-due"
                    data-code="RPT-063"
                    data-title="Preventive Maintenance Due"
                >
                    <span class="report-option-icon">🔧</span>
                    Preventive Maintenance Due
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Finance</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="invoice-register"
                    data-code="RPT-064"
                    data-title="Invoice Register"
                >
                    <span class="report-option-icon">🧾</span>
                    Invoice Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="invoice-ageing"
                    data-code="RPT-065"
                    data-title="Invoice Ageing"
                >
                    <span class="report-option-icon">⌛</span>
                    Invoice Ageing
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="outstanding-invoices"
                    data-code="RPT-066"
                    data-title="Outstanding Invoices"
                >
                    <span class="report-option-icon">💰</span>
                    Outstanding Invoices
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="payment-receipt"
                    data-code="RPT-067"
                    data-title="Payment Receipt Register"
                >
                    <span class="report-option-icon">💵</span>
                    Payment Receipt Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="vat-summary"
                    data-code="RPT-068"
                    data-title="VAT Summary"
                >
                    <span class="report-option-icon">🧮</span>
                    VAT Summary
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="expense-register"
                    data-code="RPT-069"
                    data-title="Expense Register"
                >
                    <span class="report-option-icon">💳</span>
                    Expense Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="expense-category"
                    data-code="RPT-070"
                    data-title="Expense by Category"
                >
                    <span class="report-option-icon">📊</span>
                    Expense by Category
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="payroll-summary"
                    data-code="RPT-071"
                    data-title="Payroll Register"
                >
                    <span class="report-option-icon">👥</span>
                    Payroll Register
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="payroll-deduction"
                    data-code="RPT-072"
                    data-title="Payroll Deduction"
                >
                    <span class="report-option-icon">💵</span>
                    Payroll Deduction
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="revenue-report"
                    data-code="RPT-073"
                    data-title="Revenue Report"
                >
                    <span class="report-option-icon">💰</span>
                    Revenue Report
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="monthly-pnl-finance"
                    data-code="RPT-074"
                    data-title="Monthly P&amp;L"
                >
                    <span class="report-option-icon">📈</span>
                    Monthly P&amp;L
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="client-vehicle-profitability"
                    data-code="RPT-075"
                    data-title="Client / Vehicle Profitability"
                >
                    <span class="report-option-icon">📊</span>
                    Client / Vehicle Profitability
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Compliance</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="documents"
                    data-code="RPT-076"
                    data-title="All Documents"
                >
                    <span class="report-option-icon">📄</span>
                    All Documents
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="expiry-7-days"
                    data-code="RPT-077"
                    data-title="Expiring in 7 Days"
                >
                    <span class="report-option-icon">⚠️</span>
                    Expiring in 7 Days
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="expiry-15-days"
                    data-code="RPT-078"
                    data-title="Expiring in 15 Days"
                >
                    <span class="report-option-icon">⚠️</span>
                    Expiring in 15 Days
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="expiry-30-days"
                    data-code="RPT-079"
                    data-title="Expiring in 30 Days"
                >
                    <span class="report-option-icon">⚠️</span>
                    Expiring in 30 Days
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="expired-documents"
                    data-code="RPT-080"
                    data-title="Expired Documents"
                >
                    <span class="report-option-icon">⛔</span>
                    Expired Documents
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="missing-documents"
                    data-code="RPT-081"
                    data-title="Missing Documents"
                >
                    <span class="report-option-icon">❗</span>
                    Missing Documents
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="expiry-by-type"
                    data-code="RPT-082"
                    data-title="Passport / Visa / Licence / Registration / Insurance / Trade Licence Expiry"
                >
                    <span class="report-option-icon">📑</span>
                    Passport / Visa / Licence / Registration / Insurance / Trade Licence Expiry
                </button>

            </div>


            <div class="report-category">

                <div class="report-category-title" role="button" tabindex="0" aria-expanded="false">
                    <span>Audit</span>
                </div>

                <button
                    type="button"
                    class="report-option"
                    data-report="user-activity"
                    data-code="RPT-083"
                    data-title="User Activity"
                >
                    <span class="report-option-icon">🔎</span>
                    User Activity
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="record-change-history"
                    data-code="RPT-084"
                    data-title="Record Change History"
                >
                    <span class="report-option-icon">🕘</span>
                    Record Change History
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="archived-records"
                    data-code="RPT-085"
                    data-title="Archived Records"
                >
                    <span class="report-option-icon">🗄️</span>
                    Archived Records
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="payment-activity"
                    data-code="RPT-086"
                    data-title="Payment Activity"
                >
                    <span class="report-option-icon">💵</span>
                    Payment Activity
                </button>

                <button
                    type="button"
                    class="report-option"
                    data-report="permission-login-activity"
                    data-code="RPT-087"
                    data-title="Permission / Login Activity"
                >
                    <span class="report-option-icon">🔐</span>
                    Permission / Login Activity
                </button>

            </div>


            <!-- =================================================
                 REPORT BASIS
            ================================================== -->

            <div class="report-basis-box">

                <div class="report-basis-title">
                    Report Basis
                </div>

                <p>
                    Management and profitability reports should use
                    posted / approved data. Allocation rules for salary,
                    instalments, insurance, registration and shared
                    overheads must be consistently documented.
                </p>

            </div>


            <!-- =================================================
                 CATALOGUE FOOTER
            ================================================== -->

            <div class="report-catalogue-footer">

                <span>
                    87 Reports
                </span>

                <span>
                    RPT-001 → RPT-087
                </span>

            </div>

        </aside>


        <!-- =====================================================
             MAIN REPORT AREA
        ====================================================== -->

        <main class="report-main">

            <div class="report-card">


                <!-- =================================================
                     REPORT HEADER
                ================================================== -->

                <div class="report-card-header">

                    <div class="report-card-header-left">

                        <div class="report-eyebrow">
                            REPORT CENTER
                        </div>

                        <h3 id="selectedReportTitle">
                            Select a Report
                        </h3>

                        <p id="selectedReportDescription">
                            Choose a report from the catalogue to view
                            live ERP data and analysis.
                        </p>

                    </div>


                    <div class="report-header-actions">

                        <button
                            type="button"
                            class="report-action-btn"
                            id="printReportBtn"
                        >
                            🖨 Print
                        </button>

                        <button
                            type="button"
                            class="report-action-btn primary"
                            id="exportReportBtn"
                        >
                            📤 Export
                        </button>

                    </div>

                </div>


                <!-- =================================================
                     FILTERS
                ================================================== -->

                <form
                    method="GET"
                    action="{{ route('reports.index') }}"
                    class="report-filters"
                    id="reportFilters"
                >

                    <input
                        type="hidden"
                        name="report"
                        id="selectedReport"
                        value="{{ request('report') }}"
                    >


                    <div class="filter-group">

                        <label for="period">
                            Period
                        </label>

                        <select
                            name="period"
                            id="period"
                            class="filter-control"
                        >

                            <option value="daily"
                                {{ request('period') === 'daily' ? 'selected' : '' }}>
                                Daily
                            </option>

                            <option value="weekly"
                                {{ request('period') === 'weekly' ? 'selected' : '' }}>
                                Weekly
                            </option>

                            <option value="monthly"
                                {{ request('period') === 'monthly' ? 'selected' : '' }}>
                                Monthly
                            </option>

                            <option value="quarterly"
                                {{ request('period') === 'quarterly' ? 'selected' : '' }}>
                                Quarterly
                            </option>

                            <option value="yearly"
                                {{ request('period') === 'yearly' ? 'selected' : '' }}>
                                Yearly
                            </option>

                            <option value="custom"
                                {{ request('period', 'monthly') === 'custom' ? 'selected' : '' }}>
                                Custom Range
                            </option>

                        </select>

                    </div>


                    <div class="filter-group">

                        <label for="date_from">
                            From
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            id="start_date"
                            class="filter-control"
                            value="{{ request('start_date') }}"
                        >

                    </div>


                    <div class="filter-group">

                        <label for="date_to">
                            To
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            id="end_date"
                            class="filter-control"
                            value="{{ request('end_date') }}"
                        >

                    </div>


                    <div class="filter-group">

                        <label for="status">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="filter-control"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                            <option value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="completed"
                                {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="paid"
                                {{ request('status') === 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="overdue"
                                {{ request('status') === 'overdue' ? 'selected' : '' }}>
                                Overdue
                            </option>

                        </select>

                    </div>


                    <div class="filter-group">

                        <label for="client_id">
                            Client
                        </label>

                        <select
                            name="client_id"
                            id="client_id"
                            class="filter-control"
                        >

                            <option value="">
                                All Clients
                            </option>

                            @isset($clients)

                                @foreach($clients as $client)

                                    <option
                                        value="{{ $client->id }}"
                                        {{ (string) request('client_id') === (string) $client->id ? 'selected' : '' }}
                                    >
                                        {{ $client->client_name }}
                                    </option>

                                @endforeach

                            @endisset

                        </select>

                    </div>


                    <div class="filter-group">

                        <label for="vehicle_id">Vehicle</label>

                        <select name="vehicle_id" id="vehicle_id" class="filter-control">
                            <option value="">All Vehicles</option>
                            @isset($vehicles)
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ (string) request('vehicle_id') === (string) $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->plate_number }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>

                    </div>

                    <div class="filter-group">

                        <label for="driver_id">Driver</label>

                        <select name="driver_id" id="driver_id" class="filter-control">
                            <option value="">All Drivers</option>
                            @isset($drivers)
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ (string) request('driver_id') === (string) $driver->id ? 'selected' : '' }}>
                                        {{ $driver->driver_name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>

                    </div>

                    <div class="filter-group">

                        <label for="vendor_id">Vendor</label>

                        <select name="vendor_id" id="vendor_id" class="filter-control">
                            <option value="">All Vendors</option>
                            @isset($vendors)
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ (string) request('vendor_id') === (string) $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->vendor_name ?? $vendor->name ?? ('Vendor #' . $vendor->id) }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>

                    </div>

                    <div class="filter-group">

                        <label for="assignment_id">Assignment</label>

                        <select name="assignment_id" id="assignment_id" class="filter-control">
                            <option value="">All Assignments</option>
                            @isset($assignments)
                                @foreach($assignments as $assignment)
                                    <option value="{{ $assignment->id }}" {{ (string) request('assignment_id') === (string) $assignment->id ? 'selected' : '' }}>
                                        {{ $assignment->assignment_no ?? ('Assignment #' . $assignment->id) }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>

                    </div>

                    <div class="filter-group filter-submit">

                        <button
                            type="submit"
                            class="apply-filter"
                        >
                            Apply Filters
                        </button>

                    </div>

                    @if($hasReportFilters)
                        <div class="filter-group filter-clear">

                            <a
                                href="{{ route('reports.index', ['report' => request('report')]) }}"
                                class="clear-filter"
                            >
                                Clear All Filters
                            </a>

                        </div>
                    @endif

                </form>


                <!-- =================================================
                     APPLIED PERIOD
                ================================================== -->

                @if(request()->has('period') || request()->filled('start_date') || request()->filled('end_date'))
                    <div class="applied-period">
                        <span>Applied Period</span>
                        <strong>{{ $appliedPeriodLabel }}</strong>
                    </div>
                @endif


                <!-- =================================================
                     SELECTED REPORT INFORMATION
                ================================================== -->

                <div
                    class="report-info"
                    id="reportInfo"
                    style="display:none;"
                >

                    <div class="report-info-item">

                        <span>
                            Report
                        </span>

                        <strong id="infoReportName">
                            —
                        </strong>

                    </div>


                    <div class="report-info-item">

                        <span>
                            Report Code
                        </span>

                        <strong id="infoReportCode">
                            —
                        </strong>

                    </div>


                    <div class="report-info-item">

                        <span>
                            Period
                        </span>

                        <strong id="infoPeriod">
                            —
                        </strong>

                    </div>

                </div>


                <!-- =================================================
                     INITIAL STATE
                ================================================== -->

                <div
                    class="report-empty"
                    id="reportEmpty"
                >

                    <div class="report-empty-icon">
                        📊
                    </div>

                    <h3>
                        Select a report from the catalogue
                    </h3>

                    <p>
                        Choose any report from the left side.
                        The selected report will use live ERP data
                        according to the selected period and filters.
                    </p>

                </div>


                <!-- =================================================
                     REPORT CONTENT CONTAINER
                ================================================== -->

                <div
                    id="reportContent"
                    class="report-content"
                    style="display:none;"
                >

                </div>


            </div>

        </main>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const reportOptions =
        document.querySelectorAll('.report-option');

    const selectedReport =
        document.getElementById('selectedReport');

    const selectedReportTitle =
        document.getElementById('selectedReportTitle');

    const selectedReportDescription =
        document.getElementById('selectedReportDescription');

    const reportEmpty =
        document.getElementById('reportEmpty');

    const reportInfo =
        document.getElementById('reportInfo');

    const reportContent =
        document.getElementById('reportContent');

    const infoReportName =
        document.getElementById('infoReportName');

    const infoReportCode =
        document.getElementById('infoReportCode');

    const infoPeriod =
        document.getElementById('infoPeriod');

    const periodSelect =
        document.getElementById('period');

    const dateFrom =
        document.getElementById('start_date');

    const dateTo =
        document.getElementById('end_date');


    /* =========================================================
       REPORT DESCRIPTIONS
    ========================================================= */

    const descriptions = {
        'executive-kpi':
            'Executive KPI summary for management.',
        'monthly-revenue-expense':
            'Monthly revenue compared with operating expense.',
        'monthly-pnl':
            'Monthly profit and loss using the report basis.',
        'cash-receivable':
            'Cash, collections and receivable summary.',
        'outstanding-reimbursement':
            'Outstanding reimbursable amounts awaiting recovery.',
        'top-low-profit-vehicles':
            'Highest and lowest profit vehicles.',
        'top-clients':
            'Top clients by revenue contribution.',
        'risk-expiry':
            'Risk, compliance and document expiry summary.',
        'vehicle-master':
            'Complete vehicle master report.',
        'vehicle-status':
            'Vehicle status summary.',
        'vehicle-type-summary':
            'Vehicle count and summary by vehicle type.',
        'vehicle-utilisation':
            'Vehicle utilisation during the selected period.',
        'idle-vehicle':
            'Vehicles with limited or no recent operational activity.',
        'vehicle-revenue':
            'Revenue attributed to vehicles.',
        'vehicle-expense':
            'Expenses attributed to vehicles.',
        'vehicle-profitability':
            'Vehicle revenue, cost, profit and margin analysis.',
        'fuel-cost-by-vehicle':
            'Fuel cost grouped by vehicle.',
        'maintenance-cost-by-vehicle':
            'Maintenance cost grouped by vehicle.',
        'fine-history-by-vehicle':
            'Fine history grouped by vehicle.',
        'fleet-health':
            'Fleet health and high-cost vehicle analysis.',
        'driver-master':
            'Complete driver master report.',
        'driver-status':
            'Driver employment and availability status.',
        'assignment-trip-history':
            'Driver assignment and trip history.',
        'driver-leave':
            'Driver leave report.',
        'salary-report':
            'Driver salary and payroll report.',
        'advance-history':
            'Driver advance history.',
        'outstanding-advance':
            'Outstanding driver advances.',
        'driver-fine-deduction':
            'Driver fines and payroll deductions.',
        'driver-document-expiry':
            'Driver document expiry report.',
        'driver-performance':
            'Driver operational performance.',
        'client-master':
            'Complete client master report.',
        'client-contract-expiry':
            'Client contract expiry report.',
        'client-revenue':
            'Revenue generated from each client.',
        'client-expense':
            'Costs attributed to each client.',
        'client-profitability':
            'Client revenue, cost, profit and margin analysis.',
        'client-outstanding':
            'Outstanding client balances and invoices.',
        'client-ledger':
            'Client ledger summary.',
        'client-fuel-reimbursement':
            'Client fuel reimbursement recovery.',
        'top-low-margin-clients':
            'Highest and lowest margin clients.',
        'vendor-master':
            'Complete vendor master report.',
        'vendor-vehicle':
            'Vehicles supplied by vendors.',
        'vendor-rate':
            'Vendor rates and supplied-service rates.',
        'vendor-payable':
            'Vendor payable summary.',
        'vendor-ledger':
            'Vendor ledger summary.',
        'workshop-spend':
            'Workshop and maintenance spend by vendor.',
        'assignment-register':
            'Complete assignment register.',
        'active-assignments':
            'Currently active assignments.',
        'completed-assignments':
            'Completed assignments.',
        'trip-register':
            'Complete trip register.',
        'trip-status':
            'Trip status analysis.',
        'trip-profitability':
            'Trip revenue, recoveries, costs and profit.',
        'route-profitability':
            'Profitability by route.',
        'vehicle-driver-history':
            'Vehicle and driver assignment history.',
        'operational-performance':
            'Overall operational performance.',
        'cancelled-trips':
            'Cancelled trip analysis.',
        'fuel-register':
            'Complete fuel transaction register.',
        'fuel-consumption':
            'Fuel quantity, cost and odometer-based consumption.',
        'fuel-cost-trend':
            'Fuel cost trend across the selected period.',
        'reimbursable-fuel':
            'Reimbursable fuel and recovery status.',
        'fuel-recovery-ageing':
            'Ageing analysis of outstanding fuel recovery.',
        'maintenance-register':
            'Complete maintenance transaction register.',
        'maintenance-cost-trend':
            'Maintenance activity and cost trend.',
        'vehicle-downtime':
            'Vehicle downtime caused by maintenance or unavailable periods.',
        'preventive-maintenance-due':
            'Preventive maintenance due or upcoming.',
        'invoice-register':
            'Invoice register for the selected period.',
        'invoice-ageing':
            'Outstanding invoices grouped by ageing period.',
        'outstanding-invoices':
            'Invoices with outstanding balances.',
        'payment-receipt':
            'Payment receipt register.',
        'vat-summary':
            'VAT summary based on invoice VAT amounts.',
        'expense-register':
            'Complete expense register.',
        'expense-category':
            'Expense analysis by category.',
        'payroll-summary':
            'Payroll register and payroll cost summary.',
        'payroll-deduction':
            'Payroll deduction report.',
        'revenue-report':
            'Revenue report.',
        'monthly-pnl-finance':
            'Monthly P&L for finance.',
        'client-vehicle-profitability':
            'Profitability by client and vehicle.',
        'documents':
            'All document records.',
        'expiry-7-days':
            'Documents expiring within 7 days.',
        'expiry-15-days':
            'Documents expiring within 15 days.',
        'expiry-30-days':
            'Documents expiring within 30 days.',
        'expired-documents':
            'Documents that have already expired.',
        'missing-documents':
            'Required documents missing from master records.',
        'expiry-by-type':
            'Expiry report by passport, visa, licence, registration, insurance and trade licence.',
        'user-activity':
            'User activity and audit events.',
        'record-change-history':
            'Record change history with before/after values.',
        'archived-records':
            'Archived records retained for historical reporting.',
        'payment-activity':
            'Payment activity and payment transactions.',
        'permission-login-activity':
            'Permission changes and login activity.'
    };


    /* =========================================================
       LIVE REPORT DATA
    ========================================================= */

    const liveReportData = @json($reportData ?? []);
    const masterData = {
        vehicles: @json($vehicles ?? []),
        drivers: @json($drivers ?? []),
        clients: @json($clients ?? []),
        vendors: @json($vendors ?? []),
        assignments: @json($assignments ?? []),
    };

    const reportDataMap = {
        'executive-kpi': 'executive_kpi',
        'monthly-revenue-expense': 'revenue_expense_trend',
        'monthly-pnl': 'profit_trend',
        'cash-receivable': 'cash_receivable_summary',
        'outstanding-reimbursement': 'outstanding_reimbursement',
        'top-low-profit-vehicles': 'top_low_profit_vehicles',
        'top-clients': 'top_clients',
        'risk-expiry': 'risk_expiry_summary',
        'vehicle-master': 'vehicle_master',
        'vehicle-status': 'fleet_status_summary',
        'vehicle-type-summary': 'fleet_type_summary',
        'vehicle-utilisation': 'vehicle_utilisation',
        'idle-vehicle': 'idle_vehicle',
        'vehicle-revenue': 'vehicle_revenue',
        'vehicle-expense': 'vehicle_expense',
        'vehicle-profitability': 'vehicle_profitability',
        'fuel-cost-by-vehicle': 'fuel_by_vehicle',
        'maintenance-cost-by-vehicle': 'maintenance_by_vehicle',
        'fine-history-by-vehicle': 'fine_history_by_vehicle',
        'fleet-health': 'fleet_health',
        'driver-master': 'driver_master',
        'driver-status': 'driver_status_summary',
        'assignment-trip-history': 'assignment_trip_history',
        'driver-leave': 'leave_summary',
        'salary-report': 'salary_report',
        'advance-history': 'advance_summary',
        'outstanding-advance': 'outstanding_advance',
        'driver-fine-deduction': 'driver_fine_deduction',
        'driver-document-expiry': 'driver_expiry_summary',
        'driver-performance': 'driver_performance',
        'client-master': 'client_master',
        'client-contract-expiry': 'client_contract_expiry',
        'client-revenue': 'client_revenue_summary',
        'client-expense': 'client_expense',
        'client-profitability': 'client_profitability',
        'client-outstanding': 'client_outstanding',
        'client-ledger': 'client_ledger',
        'client-fuel-reimbursement': 'client_fuel_reimbursement',
        'top-low-margin-clients': 'top_low_margin_clients',
        'vendor-master': 'vendor_master',
        'vendor-vehicle': 'vendor_vehicle',
        'vendor-rate': 'vendor_rate',
        'vendor-payable': 'vendor_payable',
        'vendor-ledger': 'vendor_ledger',
        'workshop-spend': 'workshop_spend',
        'assignment-register': 'assignment_summary',
        'active-assignments': 'active_assignments',
        'completed-assignments': 'completed_assignments',
        'trip-register': 'trip_summary',
        'trip-status': 'trip_status',
        'trip-profitability': 'trip_profitability',
        'route-profitability': 'route_profitability',
        'vehicle-driver-history': 'vehicle_driver_history',
        'operational-performance': 'operational_performance',
        'cancelled-trips': 'cancelled_trips',
        'fuel-register': 'fuel_summary',
        'fuel-consumption': 'fuel_consumption',
        'fuel-cost-trend': 'fuel_trend',
        'reimbursable-fuel': 'reimbursable_fuel',
        'fuel-recovery-ageing': 'fuel_recovery_ageing',
        'maintenance-register': 'maintenance_summary',
        'maintenance-cost-trend': 'maintenance_cost_trend',
        'vehicle-downtime': 'vehicle_downtime',
        'preventive-maintenance-due': 'preventive_maintenance_due',
        'invoice-register': 'invoice_summary',
        'invoice-ageing': 'invoice_ageing',
        'outstanding-invoices': 'outstanding_invoices',
        'payment-receipt': 'payment_summary',
        'vat-summary': 'vat_summary',
        'expense-register': 'expense_summary',
        'expense-category': 'expense_by_category',
        'payroll-summary': 'payroll_summary',
        'payroll-deduction': 'payroll_deduction',
        'revenue-report': 'revenue_report',
        'monthly-pnl-finance': 'monthly_pnl',
        'client-vehicle-profitability': 'client_vehicle_profitability',
        'documents': 'documents',
        'expiry-7-days': 'expiry_7_days',
        'expiry-15-days': 'expiry_15_days',
        'expiry-30-days': 'expiry_30_days',
        'expired-documents': 'expired_documents',
        'missing-documents': 'missing_documents',
        'expiry-by-type': 'expiry_by_type',
        'user-activity': 'user_activity',
        'record-change-history': 'record_change_history',
        'archived-records': 'archived_records',
        'payment-activity': 'payment_activity',
        'permission-login-activity': 'permission_login_activity'
    };


    liveReportData.expiry_center = [
        ...(liveReportData.vehicle_expiry_summary || []),
        ...(liveReportData.driver_expiry_summary || []),
        ...(liveReportData.client_expiry_summary || [])
    ];

    function humanizeKey(key) {
        return String(key)
            .replace(/_/g, ' ')
            .replace(/\b\w/g, function (letter) { return letter.toUpperCase(); });
    }

    function isMoneyKey(key) {
        return /amount|revenue|cost|expense|profit|salary|fuel|maintenance|fine|payment|balance|rate|freight|reimbursement|payable|payroll|deduction|overtime|allowance/i.test(key);
    }

    function formatCell(value, key) {
        if (value === null || value === undefined || value === '') return '—';
        if (typeof value === 'boolean') return value ? 'Yes' : 'No';
        if (typeof value === 'object') return escapeHtml(JSON.stringify(value));
        if (isMoneyKey(key) && !isNaN(Number(value))) {
            return 'AED ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if (typeof value === 'number') return Number(value).toLocaleString();
        return escapeHtml(String(value));
    }

    function applyClientStatusFilters(rows) {
        if (!Array.isArray(rows)) return rows;

        const filters = {
            client_id: document.getElementById('client_id')?.value || '',
            vehicle_id: document.getElementById('vehicle_id')?.value || '',
            driver_id: document.getElementById('driver_id')?.value || '',
            vendor_id: document.getElementById('vendor_id')?.value || '',
            assignment_id: document.getElementById('assignment_id')?.value || '',
            status: document.getElementById('status')?.value || ''
        };

        return rows.filter(function (row) {
            for (const key of ['client_id', 'vehicle_id', 'driver_id', 'vendor_id', 'assignment_id']) {
                if (filters[key] && row[key] !== undefined && String(row[key]) !== String(filters[key])) return false;
            }

            if (filters.status && row.status !== undefined && String(row.status).toLowerCase() !== filters.status.toLowerCase()) return false;
            return true;
        });
    }

    function renderKpis(rows) {
        if (!rows || Array.isArray(rows) || typeof rows !== 'object') return '';
        const entries = Object.entries(rows).slice(0, 8);
        if (!entries.length) return '';
        return '<div class="report-kpi-grid">' + entries.map(function ([key, value]) {
            return '<div class="report-kpi"><span>' + escapeHtml(humanizeKey(key)) + '</span><strong>' + formatCell(value, key) + '</strong></div>';
        }).join('') + '</div>';
    }

    function getAppliedPeriodLabel() {
        const selected = periodSelect ? periodSelect.value : 'monthly';
        const labels = {
            daily: 'Daily',
            weekly: 'Weekly',
            monthly: 'Monthly',
            quarterly: 'Quarterly',
            yearly: 'Yearly',
            custom: 'Custom Range'
        };

        let label = labels[selected] || 'Monthly';

        const from = dateFrom ? dateFrom.value : '';
        const to = dateTo ? dateTo.value : '';

        if (selected === 'custom' && (from || to)) {
            label += ' (' + (from || '—') + ' to ' + (to || '—') + ')';
        } else if (from || to) {
            label += ' (' + (from || '—') + ' to ' + (to || '—') + ')';
        }

        return label;
    }

    function renderTable(rows) {
        if (!Array.isArray(rows) || !rows.length) {
            return '<div class="report-empty-data">No report records found for the selected period and filters.</div>';
        }

        const normalized = rows.map(function (row) {
            return (row && typeof row === 'object' && !Array.isArray(row)) ? row : { value: row };
        });

        const sourceKeys = [...new Set(normalized.flatMap(function (row) { return Object.keys(row); }))];
        const nonPeriodKeys = sourceKeys.filter(function (key) {
            return String(key).toLowerCase() !== 'period';
        }).slice(0, 14);
        const keys = ['period'].concat(nonPeriodKeys);
        const periodLabel = getAppliedPeriodLabel();

        let html = '<div class="report-table-wrap"><table class="report-data-table"><thead><tr>';
        keys.forEach(function (key) {
            html += '<th>' + escapeHtml(humanizeKey(key)) + '</th>';
        });
        html += '</tr></thead><tbody>';

        normalized.forEach(function (row) {
            html += '<tr>';

            keys.forEach(function (key) {
                const value = key === 'period' ? periodLabel : row[key];
                const numeric = typeof value === 'number' || (!isNaN(Number(value)) && value !== '' && isMoneyKey(key));
                let cls = '';

                if (/profit|margin/i.test(key) && numeric) {
                    cls = Number(value) >= 0 ? 'report-positive' : 'report-negative';
                }

                html += '<td class="' + cls + '">' + formatCell(value, key) + '</td>';
            });

            html += '</tr>';
        });

        html += '</tbody></table></div>';
        return html;
    }

    function renderReport(reportKey, reportName, reportCode) {
        if (!reportContent) return;

        let sourceKey = reportDataMap[reportKey];
        let data = sourceKey === 'vehicles' || sourceKey === 'drivers' || sourceKey === 'clients' || sourceKey === 'vendors' || sourceKey === 'assignments'
            ? masterData[sourceKey]
            : liveReportData[sourceKey];

        if (Array.isArray(data)) data = applyClientStatusFilters(data);

        let html = '<div class="report-results-head"><div><h4 class="report-results-title">' + escapeHtml(reportName) + '</h4><div class="report-results-meta">' + escapeHtml(reportCode || '') + ' · Live ERP data</div></div></div>';

        if (reportKey === 'executive-kpi') {
            html += '<div class="report-kpi-grid">' + [
                ['Vehicles', '{{ $totalVehicles ?? 0 }}'],
                ['Drivers', '{{ $totalDrivers ?? 0 }}'],
                ['Active Assignments', '{{ $activeAssignments ?? 0 }}'],
                ['Net Profit', 'AED {{ number_format((float) ($netProfit ?? 0), 2) }}']
            ].map(function (item) { return '<div class="report-kpi"><span>' + item[0] + '</span><strong>' + item[1] + '</strong></div>'; }).join('') + '</div>';
        } else {
            html += renderKpis(data);
        }

        html += renderTable(Array.isArray(data) ? data : []);
        reportContent.innerHTML = html;
    }

    /* =========================================================
       REPORT CATEGORY ACCORDION
    ========================================================= */

    const reportCategories =
        document.querySelectorAll('.report-category');

    reportCategories.forEach(function (category) {

        const title =
            category.querySelector('.report-category-title');

        if (!title) return;

        function toggleCategory() {

            const willOpen =
                !category.classList.contains('open');

            reportCategories.forEach(function (item) {
                item.classList.remove('open');

                const itemTitle =
                    item.querySelector('.report-category-title');

                if (itemTitle) {
                    itemTitle.setAttribute('aria-expanded', 'false');
                }
            });

            if (willOpen) {
                category.classList.add('open');
                title.setAttribute('aria-expanded', 'true');
            }
        }

        title.addEventListener('click', toggleCategory);

        title.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleCategory();
            }
        });
    });


    /* =========================================================
       REPORT SELECTION
    ========================================================= */

    reportOptions.forEach(function (option) {

        option.addEventListener('click', function () {

            reportOptions.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');

            const parentCategory =
                this.closest('.report-category');

            reportCategories.forEach(function (item) {
                item.classList.remove('open');

                const itemTitle =
                    item.querySelector('.report-category-title');

                if (itemTitle) {
                    itemTitle.setAttribute('aria-expanded', 'false');
                }
            });

            if (parentCategory) {
                parentCategory.classList.add('open');

                const parentTitle =
                    parentCategory.querySelector('.report-category-title');

                if (parentTitle) {
                    parentTitle.setAttribute('aria-expanded', 'true');
                }
            }


            const reportKey =
                this.getAttribute('data-report');

            const reportCode =
                this.getAttribute('data-code');

            const reportName =
                this.getAttribute('data-title')
                || this.textContent.trim();


            selectedReport.value =
                reportKey;


            selectedReportTitle.textContent =
                reportName;


            selectedReportDescription.textContent =
                descriptions[reportKey]
                || 'Live ERP report based on the selected period and applicable filters.';


            infoReportName.textContent =
                reportName;


            infoReportCode.textContent =
                reportCode || '—';


            if (periodSelect) {

                infoPeriod.textContent =
                    getAppliedPeriodLabel();

            }


            reportEmpty.style.display =
                'none';


            reportInfo.style.display =
                'grid';


            reportContent.style.display =
                'block';


            renderReport(reportKey, reportName, reportCode);

        });

    });


    /* =========================================================
       AUTO SELECT REPORT FROM URL
    ========================================================= */

    const existingReport =
        selectedReport
            ? selectedReport.value
            : '';


    if (existingReport) {

        const existingOption =
            document.querySelector(
                '.report-option[data-report="' +
                existingReport +
                '"]'
            );


        if (existingOption) {

            existingOption.click();

        }

    }


    /* =========================================================
       PERIOD HANDLING
    ========================================================= */

    if (periodSelect) {

        periodSelect.addEventListener(
            'change',
            function () {

                const today =
                    new Date();

                let from =
                    new Date(today);

                let to =
                    new Date(today);


                /*
                 * DAILY
                 */

                if (this.value === 'daily') {

                    from =
                        new Date(today);

                }


                /*
                 * WEEKLY
                 */

                if (this.value === 'weekly') {

                    from =
                        new Date(today);

                    from.setDate(
                        today.getDate() - 6
                    );

                }


                /*
                 * MONTHLY
                 */

                if (this.value === 'monthly') {

                    from =
                        new Date(
                            today.getFullYear(),
                            today.getMonth(),
                            1
                        );

                }


                /*
                 * QUARTERLY
                 */

                if (this.value === 'quarterly') {

                    const quarter =
                        Math.floor(
                            today.getMonth() / 3
                        );

                    from =
                        new Date(
                            today.getFullYear(),
                            quarter * 3,
                            1
                        );

                }


                /*
                 * YEARLY
                 */

                if (this.value === 'yearly') {

                    from =
                        new Date(
                            today.getFullYear(),
                            0,
                            1
                        );

                }


                /*
                 * CUSTOM
                 *
                 * Custom range ko automatically
                 * overwrite nahi karna.
                 */

                if (
                    this.value !== 'custom'
                    && dateFrom
                    && dateTo
                ) {

                    dateFrom.value =
                        formatDate(from);

                    dateTo.value =
                        formatDate(to);

                }

                if (infoPeriod) {
                    infoPeriod.textContent = getAppliedPeriodLabel();
                }

                const appliedPeriodElement = document.querySelector('.applied-period strong');
                if (appliedPeriodElement) {
                    appliedPeriodElement.textContent = getAppliedPeriodLabel();
                }

            }
        );

    }


    /* =========================================================
       DATE FORMATTER
    ========================================================= */

    function formatDate(date) {

        const year =
            date.getFullYear();

        const month =
            String(
                date.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                date.getDate()
            ).padStart(2, '0');

        return (
            year +
            '-' +
            month +
            '-' +
            day
        );

    }


    /* =========================================================
       HTML ESCAPE
    ========================================================= */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value;

        return div.innerHTML;

    }


    /* =========================================================
       PRINT REPORT
    ========================================================= */

    const printReportBtn =
        document.getElementById('printReportBtn');


    if (printReportBtn) {

        printReportBtn.addEventListener(
            'click',
            function () {

                const reportKey =
                    selectedReport
                        ? selectedReport.value
                        : '';


                if (!reportKey) {

                    alert(
                        'Please select a report first.'
                    );

                    return;

                }


                window.print();

            }
        );

    }


    /* =========================================================
       EXPORT REPORT
    ========================================================= */

    const exportReportBtn =
        document.getElementById('exportReportBtn');

    const topExportReportBtn =
        document.getElementById('topExportReportBtn');


    function exportReport() {

        const reportKey =
            selectedReport
                ? selectedReport.value
                : '';

        if (!reportKey) {
            alert('Please select a report first.');
            return;
        }

        if (!reportContent) {
            alert('Report data is not available.');
            return;
        }

        const csvRows = [];

        const reportName =
            selectedReportTitle
                ? selectedReportTitle.textContent.trim()
                : reportKey;

        const reportCode =
            infoReportCode
                ? infoReportCode.textContent.trim()
                : '';

        const period =
            infoPeriod
                ? infoPeriod.textContent.trim()
                : '';

        csvRows.push(['Report', reportName]);
        csvRows.push(['Report Code', reportCode]);
        csvRows.push(['Period', period]);
        csvRows.push([]);

        const table =
            reportContent.querySelector('.report-data-table');

        if (table) {
            Array.from(table.querySelectorAll('tr')).forEach(function (row) {
                csvRows.push(
                    Array.from(row.querySelectorAll('th, td')).map(function (cell) {
                        return cell.textContent.replace(/\s+/g, ' ').trim();
                    })
                );
            });
        } else {
            const kpis =
                reportContent.querySelectorAll('.report-kpi');

            if (kpis.length) {
                csvRows.push(['Metric', 'Value']);

                kpis.forEach(function (kpi) {
                    const label =
                        kpi.querySelector('span')?.textContent.trim() || '';

                    const value =
                        kpi.querySelector('strong')?.textContent.trim() || '';

                    csvRows.push([label, value]);
                });
            } else {
                alert('No report data is available to export.');
                return;
            }
        }

        const csv = csvRows.map(function (row) {
            return row.map(function (cell) {
                return '"' + String(cell ?? '').replace(/"/g, '""') + '"';
            }).join(',');
        }).join('\n');

        const blob =
            new Blob([csv], { type: 'text/csv;charset=utf-8;' });

        const url =
            URL.createObjectURL(blob);

        const link =
            document.createElement('a');

        link.href = url;
        link.download = (reportKey || 'report') + '.csv';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);

    }


    if (exportReportBtn) {

        exportReportBtn.addEventListener(
            'click',
            exportReport
        );

    }


    if (topExportReportBtn) {

        topExportReportBtn.addEventListener(
            'click',
            exportReport
        );

    }

});

</script>


<style>

/* =========================================================
   LIVE REPORT RESULTS
========================================================= */

.report-results-head {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:14px;
}

.report-results-title {
    margin:0;
    color:#101d42;
    font-size:15px;
    font-weight:800;
}

.report-results-meta {
    color:#64748b;
    font-size:10px;
}

.report-kpi-grid {
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:10px;
    margin-bottom:14px;
}

.report-kpi {
    padding:13px;
    border:1px solid #e5e7eb;
    border-radius:9px;
    background:#f8fafc;
}

.report-kpi span {
    display:block;
    margin-bottom:5px;
    color:#64748b;
    font-size:9px;
    font-weight:700;
    text-transform:uppercase;
}

.report-kpi strong {
    display:block;
    color:#101d42;
    font-size:16px;
    font-weight:800;
}

.report-table-wrap {
    width:100%;
    max-width:100%;
    overflow:hidden;
    border:1px solid #e5e7eb;
    border-radius:10px;
}

.report-data-table {
    width:100%;
    max-width:100%;
    min-width:0;
    table-layout:fixed;
    border-collapse:collapse;
}

.report-data-table th:first-child,
.report-data-table td:first-child {
    width: 13%;
}

.report-data-table th {
    padding:9px 8px;
    background:#f8fafc;
    border-bottom:1px solid #e5e7eb;
    color:#475569;
    font-size:9px;
    font-weight:800;
    text-align:left;
    text-transform:uppercase;
    white-space:normal;
    overflow-wrap:anywhere;
    word-break:break-word;
}

.report-data-table td {
    padding:9px 8px;
    border-bottom:1px solid #eef2f7;
    color:#334155;
    font-size:11px;
    line-height:1.35;
    vertical-align:top;
    white-space:normal;
    overflow-wrap:anywhere;
    word-break:break-word;
}

.report-data-table tbody tr:last-child td { border-bottom:0; }

.report-empty-data {
    padding:35px 20px;
    border:1px dashed #dbe3ef;
    border-radius:10px;
    background:#fcfdff;
    color:#64748b;
    font-size:12px;
    text-align:center;
}

.report-positive { color:#15803d !important; font-weight:800; }
.report-negative { color:#b91c1c !important; font-weight:800; }

@media (max-width:800px) {
    .report-kpi-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
}

/* =========================================================
   REPORT CONTENT
========================================================= */

.report-content {
    width: 100%;
    min-width: 0;
}

.report-loading-state {
    min-height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 35px 20px;
    border: 1px dashed #dbe3ef;
    border-radius: 12px;
    background: #fcfdff;
    text-align: center;
}

.report-loading-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    border-radius: 14px;
    background: #e8efff;
    font-size: 26px;
}

.report-loading-state h4 {
    margin: 0;
    color: #101d42;
    font-size: 16px;
    font-weight: 800;
}

.report-loading-state p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 12px;
    line-height: 1.6;
}


/* =========================================================
   CATALOGUE FOOTER
========================================================= */

.report-catalogue-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-top: 16px;
    padding-top: 13px;
    border-top: 1px solid #e5e7eb;
    color: #64748b;
    font-size: 10px;
    font-weight: 700;
}


/* =========================================================
   PRINT
========================================================= */

@media print {

    .sidebar,
    .navbar,
    .reports-header-right,
    .report-menu,
    .report-filters,
    .report-header-actions {
        display: none !important;
    }

    .reports-layout {
        display: block !important;
    }

    .report-card {
        width: 100%;
        border: 0;
        box-shadow: none;
        padding: 0;
    }

    .report-info {
        display: grid !important;
    }

    .report-content {
        display: block !important;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 800px) {

    .report-catalogue-footer {
        flex-wrap: wrap;
    }

    .report-category-title {
        min-height: 40px;
    }

}

</style>


@endsection