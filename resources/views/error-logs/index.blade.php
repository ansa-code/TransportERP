@extends('layouts.app')

@section('content')

<style>
    .error-page {
        padding: 24px;
    }

    .error-header {
        margin-bottom: 24px;
    }

    .error-title {
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
        margin: 0 0 4px;
    }

    .error-subtitle {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .error-hero {
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 16px;
        padding: 24px;
        color: #fff;
        margin-bottom: 24px;
    }

    .error-hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .error-hero-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .error-hero-text {
        margin: 0;
        color: rgba(255, 255, 255, .75);
        font-size: 14px;
    }

    .error-kpi-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .error-kpi {
        min-width: 150px;
        background: rgba(37, 99, 235, .30);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 12px;
        padding: 16px 20px;
    }

    .error-kpi-label {
        font-size: 12px;
        color: rgba(255, 255, 255, .70);
        margin-bottom: 5px;
    }

    .error-kpi-value {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
    }

    .error-filters {
        background: #fff;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
        border: 1px solid #e5e7eb;
    }

    .error-filter-row {
        display: flex;
        align-items: end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .error-filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .error-filter-group.search-group {
        width: 300px;
    }

    .error-filter-group.filter-group {
        width: 170px;
    }

    .error-filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
    }

    .error-input,
    .error-select {
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

    .error-input:focus,
    .error-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, .10);
    }

    .error-reset {
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

    .error-reset:hover {
        background: #f9fafb;
        color: #101d42;
    }

    .error-table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .error-table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .error-table-title {
        margin: 0;
        color: #101d42;
        font-size: 17px;
        font-weight: 700;
    }

    .error-table-count {
        font-size: 12px;
        color: #6b7280;
    }

    .error-table-wrapper {
        width: 100%;
        overflow: hidden;
    }

    .error-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .error-table th {
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

    .error-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 12px;
        color: #374151;
        overflow: hidden;
    }

    .error-table tbody tr {
        transition: background .15s ease;
    }

    .error-table tbody tr:hover {
        background: #f8fafc;
    }

    .error-table tbody tr:last-child td {
        border-bottom: none;
    }

     /*
|--------------------------------------------------------------------------
| Column Widths
|--------------------------------------------------------------------------
*/

.error-table th:nth-child(1),
.error-table td:nth-child(1) {
    width: 13%;
}

.error-table th:nth-child(2),
.error-table td:nth-child(2) {
    width: 10%;
}

.error-table th:nth-child(3),
.error-table td:nth-child(3) {
    width: 15%;
}

.error-table th:nth-child(4),
.error-table td:nth-child(4) {
    width: 25%;
}

.error-table th:nth-child(5),
.error-table td:nth-child(5) {
    width: 14%;
}

.error-table th:nth-child(6),
.error-table td:nth-child(6) {
    width: 11%;
}

.error-table th:nth-child(7),
.error-table td:nth-child(7) {
    width: 12%;
}

    .error-log-link {
        color: #101d42;
        text-decoration: none;
        font-weight: 600;
    }

    .error-log-link:hover {
        color: #2563eb;
    }

    .error-user {
        font-weight: 600;
        color: #1f2937;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .error-muted {
        color: #6b7280;
    }

    .error-message {
        line-height: 1.45;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .error-type {
        display: inline-block;
        max-width: 100%;
        padding: 5px 8px;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        font-size: 10px;
        font-weight: 700;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: middle;
    }

    .error-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .error-status.resolved {
        background: #dcfce7;
        color: #166534;
    }

    .error-status.unresolved {
        background: #fee2e2;
        color: #b91c1c;
    }

    .error-date {
        white-space: nowrap;
    }

    .error-view-btn {
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

    .error-view-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .error-empty {
        padding: 45px 20px;
        text-align: center;
        color: #6b7280;
    }

    .error-empty-title {
        color: #374151;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .error-empty-text {
        margin: 0;
        font-size: 13px;
    }

    .error-pagination {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
    }
    @media (max-width: 1100px) {
        .error-table th,
        .error-table td {
            padding-left: 8px;
            padding-right: 8px;
        }

        .error-table {
            font-size: 11px;
        }

        .error-table th {
            font-size: 10px;
        }

        .error-view-btn {
            padding: 0 8px;
        }
    }

    @media (max-width: 768px) {
        .error-page {
            padding: 16px;
        }

        .error-filter-group.search-group,
        .error-filter-group.filter-group {
            width: 100%;
        }

        .error-reset {
            width: 100%;
        }

        .error-kpi-group {
            width: 100%;
        }

        .error-kpi {
            flex: 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile table becomes stacked cards
        |--------------------------------------------------------------------------
        */

        .error-table-wrapper {
            overflow: visible;
        }

        .error-table,
        .error-table thead,
        .error-table tbody,
        .error-table th,
        .error-table td,
        .error-table tr {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .error-table thead {
            display: none;
        }

        .error-table tr {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .error-table td {
            width: 100% !important;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            padding: 8px 0;
            border-bottom: none;
            overflow: visible;
        }

        .error-table td::before {
            content: attr(data-label);
            flex: 0 0 100px;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .error-message {
            text-align: right;
            max-width: 65%;
        }

        .error-view-btn {
            margin-left: auto;
        }
    }
</style>

<div class="error-page">

    {{-- Page Header --}}
    <div class="error-header">

        <h1 class="error-title">
            Error Log
        </h1>

        <p class="error-subtitle">
            Review system errors and technical issues captured automatically by the ERP.
        </p>

    </div>

    {{-- Hero --}}
    <div class="error-hero">

        <div class="error-hero-content">

            <div>
                <h2 class="error-hero-title">
                    Error Log Center
                </h2>

                <p class="error-hero-text">
                    Monitor system errors, investigate technical issues, and track their resolution.
                </p>
            </div>

            <div class="error-kpi-group">

                <div class="error-kpi">

                    <div class="error-kpi-label">
                        Total Errors
                    </div>

                    <div class="error-kpi-value">
                        {{ number_format($errorLogs->count()) }}
                    </div>

                </div>

                <div class="error-kpi">

                    <div class="error-kpi-label">
                        Unresolved
                    </div>

                    <div class="error-kpi-value">
                        {{ number_format(\App\Models\ErrorLog::where('is_resolved', false)->count()) }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Filters --}}
    <div class="error-filters">

        <form
            method="GET"
            action="{{ route('error-logs.index') }}"
            id="errorFilterForm"
        >

            <div class="error-filter-row">

                {{-- Search --}}
                <div class="error-filter-group search-group">

                    <label
                        for="errorSearch"
                        class="error-filter-label"
                    >
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        id="errorSearch"
                        class="error-input"
                        value="{{ request('search') }}"
                        placeholder="Search error, type or URL..."
                        autocomplete="off"
                    >

                </div>

                {{-- Status --}}
                <div class="error-filter-group filter-group">

                    <label
                        for="errorStatus"
                        class="error-filter-label"
                    >
                        Status
                    </label>

                    <select
                        name="status"
                        id="errorStatus"
                        class="error-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="unresolved"
                            {{ request('status') === 'unresolved' ? 'selected' : '' }}
                        >
                            Unresolved
                        </option>

                        <option
                            value="resolved"
                            {{ request('status') === 'resolved' ? 'selected' : '' }}
                        >
                            Resolved
                        </option>

                    </select>

                </div>

                {{-- Reset --}}
                <a
                    href="{{ route('error-logs.index') }}"
                    class="error-reset"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- Error Table --}}
    <div class="error-table-card">

        <div class="error-table-header">

            <h3 class="error-table-title">
                Error History
            </h3>

            <span class="error-table-count">
                {{ $errorLogs->count() }} record(s)
            </span>

        </div>

        <div class="error-table-wrapper">

            <table class="error-table">

                <thead>

                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Error Type</th>
                        <th>Message</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($errorLogs as $errorLog)

                    <tr>

                        {{-- Date --}}
                        <td data-label="Date & Time">

                            <div>

                                <a
                                    href="{{ route('error-logs.show', $errorLog->id) }}"
                                    class="error-log-link"
                                >
                                    {{ $errorLog->created_at?->format('d M Y') }}
                                </a>

                                <div class="error-muted">
                                    {{ $errorLog->created_at?->format('h:i A') }}
                                </div>

                            </div>

                        </td>

                        {{-- User --}}
                        <td data-label="User">

                            <div class="error-user">
                                {{ $errorLog->user?->name ?? 'System' }}
                            </div>

                        </td>

                        {{-- Error Type --}}
                        <td data-label="Error Type">

                            <span
                                class="error-type"
                                title="{{ $errorLog->exception_type }}"
                            >
                                {{ class_basename($errorLog->exception_type ?? 'Unknown Error') }}
                            </span>

                        </td>

                        {{-- Message --}}
                        <td data-label="Message">

                            <div class="error-message">
                                {{ $errorLog->message ?: 'No error message available.' }}
                            </div>

                        </td>

                        {{-- URL --}}
                        <td data-label="URL">

                            <span
                                class="error-muted"
                                title="{{ $errorLog->url }}"
                            >
                                {{ \Illuminate\Support\Str::limit($errorLog->url ?? '—', 45) }}
                            </span>

                        </td>

                        {{-- Status --}}
                        <td data-label="Status">

                            @if($errorLog->is_resolved)

                                <span class="error-status resolved">
                                    Resolved
                                </span>

                            @else

                                <span class="error-status unresolved">
                                    Unresolved
                                </span>

                            @endif

                        </td>

                        {{-- View --}}
                        <td data-label="Action">

                            <a
                                href="{{ route('error-logs.show', $errorLog->id) }}"
                                class="error-view-btn"
                            >
                                View
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7">

                            <div class="error-empty">

                                <div class="error-empty-title">
                                    No error logs found
                                </div>

                                <p class="error-empty-text">
                                    There are no system errors matching the selected filters.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

       

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('errorFilterForm');
        const search = document.getElementById('errorSearch');
        const status = document.getElementById('errorStatus');

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
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (status) {

            status.addEventListener('change', function () {
                form.submit();
            });

        }

    });
</script>

@endsection