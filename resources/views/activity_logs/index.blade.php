@extends('layouts.app')

@section('content')

<style>
    .audit-page {
        padding: 24px;
    }

    .audit-header {
        margin-bottom: 24px;
    }

    .audit-title {
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
        margin: 0 0 4px;
    }

    .audit-subtitle {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .audit-hero {
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 16px;
        padding: 24px;
        color: #fff;
        margin-bottom: 24px;
    }

    .audit-hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .audit-hero-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .audit-hero-text {
        margin: 0;
        color: rgba(255, 255, 255, .75);
        font-size: 14px;
    }

    .audit-kpi {
        min-width: 150px;
        background: rgba(37, 99, 235, .30);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 12px;
        padding: 16px 20px;
    }

    .audit-kpi-label {
        font-size: 12px;
        color: rgba(255, 255, 255, .70);
        margin-bottom: 5px;
    }

    .audit-kpi-value {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
    }

    .audit-filters {
        background: #fff;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
        border: 1px solid #e5e7eb;
    }

    .audit-filter-row {
        display: flex;
        align-items: end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .audit-filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .audit-filter-group.search-group {
        width: 300px;
    }

    .audit-filter-group.filter-group {
        width: 170px;
    }

    .audit-filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
    }

    .audit-input,
    .audit-select {
        height: 38px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0 11px;
        font-size: 13px;
        color: #1f2937;
        background: #fff;
        outline: none;
        width: 100%;
        box-sizing: border-box;
    }

    .audit-input:focus,
    .audit-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, .10);
    }

    .audit-reset {
        height: 38px;
        padding: 0 15px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }

    .audit-reset:hover {
        background: #f9fafb;
        color: #101d42;
    }

    .audit-table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .audit-table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .audit-table-title {
        margin: 0;
        color: #101d42;
        font-size: 17px;
        font-weight: 700;
    }

    .audit-table-count {
        font-size: 12px;
        color: #6b7280;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive Table
    |--------------------------------------------------------------------------
    */

    .audit-table-wrapper {
        width: 100%;
        overflow: hidden;
    }

    .audit-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .audit-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        padding: 13px 12px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
        text-align: left;
    }

    .audit-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 12px;
        color: #374151;
        overflow: hidden;
    }

    .audit-table tbody tr {
        transition: background .15s ease;
    }

    .audit-table tbody tr:hover {
        background: #f8fafc;
    }

    .audit-table tbody tr:last-child td {
        border-bottom: none;
    }

    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */

    .audit-table th:nth-child(1),
    .audit-table td:nth-child(1) {
        width: 13%;
    }

    .audit-table th:nth-child(2),
    .audit-table td:nth-child(2) {
        width: 10%;
    }

    .audit-table th:nth-child(3),
    .audit-table td:nth-child(3) {
        width: 10%;
    }

    .audit-table th:nth-child(4),
    .audit-table td:nth-child(4) {
        width: 11%;
    }

    .audit-table th:nth-child(5),
    .audit-table td:nth-child(5) {
        width: 31%;
    }

    .audit-table th:nth-child(6),
    .audit-table td:nth-child(6) {
        width: 13%;
    }

    .audit-table th:nth-child(7),
    .audit-table td:nth-child(7) {
        width: 12%;
    }

    .audit-log-link {
        color: #101d42;
        text-decoration: none;
        font-weight: 600;
    }

    .audit-log-link:hover {
        color: #2563eb;
    }

    .audit-user {
        font-weight: 600;
        color: #1f2937;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .audit-muted {
        color: #6b7280;
    }

    .audit-description {
        line-height: 1.45;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .audit-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .audit-badge.created {
        background: #dcfce7;
        color: #166534;
    }

    .audit-badge.updated {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .audit-badge.deleted {
        background: #fee2e2;
        color: #b91c1c;
    }

    .audit-badge.emailed {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .audit-badge.default {
        background: #f1f5f9;
        color: #475569;
    }

    .audit-module {
        font-weight: 600;
        color: #334155;
    }

    .audit-date {
        white-space: nowrap;
    }

    .audit-view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        padding: 0 10px;
        border-radius: 7px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .audit-view-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .audit-empty {
        padding: 45px 20px;
        text-align: center;
        color: #6b7280;
    }

    .audit-empty-title {
        color: #374151;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .audit-empty-text {
        margin: 0;
        font-size: 13px;
    }

    .audit-pagination {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 1100px) {
        .audit-table th,
        .audit-table td {
            padding-left: 8px;
            padding-right: 8px;
        }

        .audit-table {
            font-size: 11px;
        }

        .audit-table th {
            font-size: 10px;
        }

        .audit-view-btn {
            padding: 0 8px;
        }
    }

    @media (max-width: 768px) {
        .audit-page {
            padding: 16px;
        }

        .audit-filter-group.search-group,
        .audit-filter-group.filter-group {
            width: 100%;
        }

        .audit-reset {
            width: 100%;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile table becomes stacked cards
        |--------------------------------------------------------------------------
        */

        .audit-table-wrapper {
            overflow: visible;
        }

        .audit-table,
        .audit-table thead,
        .audit-table tbody,
        .audit-table th,
        .audit-table td,
        .audit-table tr {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .audit-table thead {
            display: none;
        }

        .audit-table tr {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .audit-table td {
            width: 100% !important;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            padding: 8px 0;
            border-bottom: none;
            overflow: visible;
        }

        .audit-table td::before {
            content: attr(data-label);
            flex: 0 0 100px;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .audit-description {
            text-align: right;
            max-width: 65%;
        }

        .audit-view-btn {
            margin-left: auto;
        }
    }
</style>

<div class="audit-page">

    {{-- Page Header --}}
    <div class="audit-header">

        <h1 class="audit-title">
            Audit Trail
        </h1>

        <p class="audit-subtitle">
            Track important activities and changes made throughout the ERP.
        </p>

    </div>

    {{-- Hero --}}
    <div class="audit-hero">

        <div class="audit-hero-content">

            <div>
                <h2 class="audit-hero-title">
                    Activity Log Center
                </h2>

                <p class="audit-hero-text">
                    Review system activities, record changes, and user actions.
                </p>
            </div>

            <div class="audit-kpi">

                <div class="audit-kpi-label">
                    Total Activities
                </div>

                <div class="audit-kpi-value">
                    {{ number_format($totalLogs) }}
                </div>

            </div>

        </div>

    </div>

    {{-- Filters --}}
    <div class="audit-filters">

        <form
            method="GET"
            action="{{ route('activity-logs.index') }}"
            id="auditFilterForm"
        >

            <div class="audit-filter-row">

                {{-- Search --}}
                <div class="audit-filter-group search-group">

                    <label
                        for="auditSearch"
                        class="audit-filter-label"
                    >
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        id="auditSearch"
                        class="audit-input"
                        value="{{ $search }}"
                        placeholder="Search activity..."
                        autocomplete="off"
                    >

                </div>

                {{-- Action --}}
                <div class="audit-filter-group filter-group">

                    <label
                        for="auditAction"
                        class="audit-filter-label"
                    >
                        Action
                    </label>

                    <select
                        name="action"
                        id="auditAction"
                        class="audit-select"
                    >
                        <option value="">
                            All Actions
                        </option>

                        @foreach($actions as $availableAction)

                            <option
                                value="{{ $availableAction }}"
                                {{ $action === $availableAction ? 'selected' : '' }}
                            >
                                {{ ucfirst($availableAction) }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Module --}}
                <div class="audit-filter-group filter-group">

                    <label
                        for="auditModule"
                        class="audit-filter-label"
                    >
                        Module
                    </label>

                    <select
                        name="module"
                        id="auditModule"
                        class="audit-select"
                    >
                        <option value="">
                            All Modules
                        </option>

                        @foreach($modules as $availableModule)

                            <option
                                value="{{ $availableModule }}"
                                {{ $module === $availableModule ? 'selected' : '' }}
                            >
                                {{ $availableModule }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Reset --}}
                <a
                    href="{{ route('activity-logs.index') }}"
                    class="audit-reset"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- Activity Table --}}
    <div class="audit-table-card">

        <div class="audit-table-header">

            <h3 class="audit-table-title">
                Activity History
            </h3>

            <span class="audit-table-count">
                {{ $logs->total() }} record(s)
            </span>

        </div>

        <div class="audit-table-wrapper">

            <table class="audit-table">

                <thead>

                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($logs as $log)

                    @php
                        $badgeClass = match($log->action) {
                            'created' => 'created',
                            'updated' => 'updated',
                            'deleted' => 'deleted',
                            'emailed' => 'emailed',
                            default => 'default',
                        };
                    @endphp

                    <tr>

                        {{-- Date --}}
                        <td data-label="Date & Time">

                            <div>
                                <a
                                    href="{{ route('activity-logs.show', $log) }}"
                                    class="audit-log-link"
                                >
                                    {{ $log->created_at?->format('d M Y') }}
                                </a>

                                <div class="audit-muted">
                                    {{ $log->created_at?->format('h:i A') }}
                                </div>
                            </div>

                        </td>

                        {{-- User --}}
                        <td data-label="User">

                            <div class="audit-user">
                                {{ $log->user?->name ?? 'System' }}
                            </div>

                        </td>

                        {{-- Action --}}
                        <td data-label="Action">

                            <span class="audit-badge {{ $badgeClass }}">
                                {{ $log->action }}
                            </span>

                        </td>

                        {{-- Module --}}
                        <td data-label="Module">

                            <span class="audit-module">
                                {{ $log->module }}
                            </span>

                        </td>

                        {{-- Description --}}
                        <td data-label="Description">

                            <div class="audit-description">
                                {{ $log->description }}
                            </div>

                        </td>

                        {{-- IP --}}
                        <td data-label="IP Address">

                            <span class="audit-muted">
                                {{ $log->ip_address ?? '—' }}
                            </span>

                        </td>

                        {{-- View --}}
                        <td data-label="Action">

                            <a
                                href="{{ route('activity-logs.show', $log) }}"
                                class="audit-view-btn"
                            >
                                View
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7">

                            <div class="audit-empty">

                                <div class="audit-empty-title">
                                    No activity logs found
                                </div>

                                <p class="audit-empty-text">
                                    There are no audit records matching the selected filters.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())

            <div class="audit-pagination">
                {{ $logs->links() }}
            </div>

        @endif

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('auditFilterForm');
        const search = document.getElementById('auditSearch');
        const action = document.getElementById('auditAction');
        const module = document.getElementById('auditModule');

        let searchTimer;

        /*
        |--------------------------------------------------------------------------
        | Automatic Search
        |--------------------------------------------------------------------------
        */

        if (search) {
            search.addEventListener('input', function () {

                clearTimeout(searchTimer);

                searchTimer = setTimeout(function () {
                    form.submit();
                }, 400);

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Action Filter
        |--------------------------------------------------------------------------
        */

        if (action) {
    action.addEventListener('change', function () {
        form.submit();
    });
}

/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
-
    | Module Filter
|
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
-
*/

if (module) {
    module.addEventListener('change', function () {
        form.submit();
    });
}
});
</script>
@endsection