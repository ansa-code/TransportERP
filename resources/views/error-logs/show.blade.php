@extends('layouts.app')

@section('content')

<style>
    .error-show-page {
        padding: 24px;
    }

    .error-show-header {
        margin-bottom: 18px;
    }

    .error-show-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .error-show-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    /*
    |--------------------------------------------------------------------------
    | Top Actions
    |--------------------------------------------------------------------------
    */

    .error-show-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .error-action-left,
    .error-action-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .error-back-btn,
    .error-resolve-btn,
    .error-reopen-btn {
        height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .error-back-btn {
        background: #fff;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .error-back-btn:hover {
        background: #f9fafb;
        color: #101d42;
    }

    .error-resolve-btn {
        background: #16a34a;
        color: #fff;
    }

    .error-resolve-btn:hover {
        background: #15803d;
        color: #fff;
    }

    .error-reopen-btn {
        background: #f59e0b;
        color: #fff;
    }

    .error-reopen-btn:hover {
        background: #d97706;
        color: #fff;
    }

    /*
    |--------------------------------------------------------------------------
    | Hero
    |--------------------------------------------------------------------------
    */

    .error-show-hero {
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 16px;
        padding: 24px;
        color: #fff;
        margin-bottom: 24px;
    }

    .error-show-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .error-show-hero-title {
        margin: 0 0 6px;
        font-size: 22px;
        font-weight: 700;
    }

    .error-show-hero-text {
        margin: 0;
        color: rgba(255, 255, 255, .72);
        font-size: 13px;
        line-height: 1.6;
        max-width: 700px;
    }

    .error-show-status {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .error-show-status.resolved {
        background: rgba(34, 197, 94, .18);
        color: #bbf7d0;
        border: 1px solid rgba(187, 247, 208, .18);
    }

    .error-show-status.unresolved {
        background: rgba(239, 68, 68, .18);
        color: #fecaca;
        border: 1px solid rgba(254, 202, 202, .18);
    }

    /*
    |--------------------------------------------------------------------------
    | Summary Cards
    |--------------------------------------------------------------------------
    */

    .error-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .error-summary-card {
        background: rgba(37, 99, 235, .30);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 12px;
        padding: 15px 16px;
        min-width: 0;
    }

    .error-summary-label {
        margin-bottom: 6px;
        color: rgba(255, 255, 255, .68);
        font-size: 11px;
        font-weight: 600;
    }

    .error-summary-value {
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Cards
    |--------------------------------------------------------------------------
    */

    .error-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .error-detail-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .error-detail-card.full-width {
        grid-column: 1 / -1;
    }

    .error-detail-header {
        padding: 17px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .error-detail-title {
        margin: 0;
        color: #101d42;
        font-size: 16px;
        font-weight: 700;
    }

    .error-detail-body {
        padding: 20px;
    }

    .error-detail-row {
        display: grid;
        grid-template-columns: 145px minmax(0, 1fr);
        gap: 18px;
        padding: 11px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .error-detail-row:first-child {
        padding-top: 0;
    }

    .error-detail-row:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .error-detail-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
    }

    .error-detail-value {
        color: #1f2937;
        font-size: 13px;
        line-height: 1.55;
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .error-detail-muted {
        color: #6b7280;
    }

    .error-code {
        margin: 0;
        padding: 16px;
        border-radius: 10px;
        background: #0f172a;
        color: #e2e8f0;
        font-family: Consolas, Monaco, "Courier New", monospace;
        font-size: 12px;
        line-height: 1.65;
        white-space: pre-wrap;
        overflow-x: auto;
        overflow-y: auto;
        max-height: 500px;
        word-break: break-word;
    }

    .error-message-box {
        padding: 15px 16px;
        border-radius: 10px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        font-size: 13px;
        line-height: 1.6;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    /*
    |--------------------------------------------------------------------------
    | Flash Message
    |--------------------------------------------------------------------------
    */

    .error-alert {
        margin-bottom: 20px;
        padding: 13px 16px;
        border-radius: 10px;
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 13px;
        font-weight: 600;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1000px) {
        .error-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .error-detail-grid {
            grid-template-columns: 1fr;
        }

        .error-detail-card.full-width {
            grid-column: auto;
        }
    }

    @media (max-width: 768px) {
        .error-show-page {
            padding: 16px;
        }

        .error-show-title {
            font-size: 24px;
        }

        .error-show-actions {
            align-items: stretch;
        }

        .error-action-left,
        .error-action-right {
            width: 100%;
        }

        .error-back-btn,
        .error-resolve-btn,
        .error-reopen-btn {
            flex: 1;
        }

        .error-summary-grid {
            grid-template-columns: 1fr;
        }

        .error-detail-row {
            grid-template-columns: 1fr;
            gap: 5px;
        }

        .error-detail-body {
            padding: 16px;
        }
    }
</style>

<div class="error-show-page">

    {{-- Page Header --}}
    <div class="error-show-header">

        <h1 class="error-show-title">
            Error Details
        </h1>

        <p class="error-show-subtitle">
            Review the technical details and resolution status of this system error.
        </p>

    </div>

    {{-- Actions --}}
    <div class="error-show-actions">

        <div class="error-action-left">

            <a
                href="{{ route('error-logs.index') }}"
                class="error-back-btn"
            >
                ← Back to Error Logs
            </a>

        </div>

        <div class="error-action-right">

            @if($errorLog->is_resolved)

                <form
                    method="POST"
                    action="{{ route('error-logs.reopen', $errorLog->id) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="error-reopen-btn"
                    >
                        Reopen Error
                    </button>
                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('error-logs.resolve', $errorLog->id) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="error-resolve-btn"
                    >
                        Mark as Resolved
                    </button>
                </form>

            @endif

        </div>

    </div>

   

    {{-- Hero --}}
    <div class="error-show-hero">

        <div class="error-show-hero-top">

            <div>

                <h2 class="error-show-hero-title">
                    {{ class_basename($errorLog->exception_type ?? 'Unknown Error') }}
                </h2>

                <p class="error-show-hero-text">
                    {{ $errorLog->message ?: 'No error message available.' }}
                </p>

            </div>

            @if($errorLog->is_resolved)

                <span class="error-show-status resolved">
                    Resolved
                </span>

            @else

                <span class="error-show-status unresolved">
                    Unresolved
                </span>

            @endif

        </div>

        {{-- Summary Cards --}}
        <div class="error-summary-grid">

            <div class="error-summary-card">

                <div class="error-summary-label">
                    Date & Time
                </div>

                <div class="error-summary-value">
                    {{ $errorLog->created_at?->format('d M Y, h:i A') ?? '—' }}
                </div>

            </div>

            <div class="error-summary-card">

                <div class="error-summary-label">
                    User
                </div>

                <div class="error-summary-value">
                    {{ $errorLog->user?->name ?? 'System' }}
                </div>

            </div>

            <div class="error-summary-card">

                <div class="error-summary-label">
                    Method
                </div>

                <div class="error-summary-value">
                    {{ $errorLog->method ?? '—' }}
                </div>

            </div>

            <div class="error-summary-card">

                <div class="error-summary-label">
                    IP Address
                </div>

                <div class="error-summary-value">
                    {{ $errorLog->ip_address ?? '—' }}
                </div>

            </div>

        </div>

    </div>

    {{-- Technical Details --}}
    <div class="error-detail-grid">

        <div class="error-detail-card">

            <div class="error-detail-header">

                <h3 class="error-detail-title">
                    Request Information
                </h3>

            </div>

            <div class="error-detail-body">

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        URL
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->url ?? '—' }}
                    </div>

                </div>

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        HTTP Method
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->method ?? '—' }}
                    </div>

                </div>

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        IP Address
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->ip_address ?? '—' }}
                    </div>

                </div>

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        User
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->user?->name ?? 'System' }}
                    </div>

                </div>

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        User Agent
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->user_agent ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

        <div class="error-detail-card">

            <div class="error-detail-header">

                <h3 class="error-detail-title">
                    Exception Information
                </h3>

            </div>

            <div class="error-detail-body">

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        Exception Type
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->exception_type ?? '—' }}
                    </div>

                </div>

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        File
                    </div>

                    <div class="error-detail-value">
                    {{ $errorLog->file ?? '—' }}
                    </div>

                </div>

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        Line
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->line ?? '—' }}
                    </div>

                </div>

                <div class="error-detail-row">

                    <div class="error-detail-label">
                        Created
                    </div>

                    <div class="error-detail-value">
                        {{ $errorLog->created_at?->format('d M Y, h:i:s A') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>
    {{-- Error Message --}}
    <div class="error-detail-card full-width">

        <div class="error-detail-header">

            <h3 class="error-detail-title">
                Error Message
            </h3>

        </div>

        <div class="error-detail-body">

            <div class="error-message-box">
                {{ $errorLog->message ?: 'No error message available.' }}
            </div>

        </div>

    </div>

    {{-- File & Line --}}
    <div class="error-detail-card">

        <div class="error-detail-header">

            <h3 class="error-detail-title">
                Source Location
            </h3>

        </div>

        <div class="error-detail-body">

            <div class="error-detail-row">

                <div class="error-detail-label">
                    File
                </div>

                <div class="error-detail-value">
                    {{ $errorLog->file ?? '—' }}
                </div>

            </div>

            <div class="error-detail-row">

                <div class="error-detail-label">
                    Line
                </div>

                <div class="error-detail-value">
                    {{ $errorLog->line ?? '—' }}
                </div>

            </div>

        </div>

    </div>

    {{-- Resolution Information --}}
    <div class="error-detail-card">

        <div class="error-detail-header">

            <h3 class="error-detail-title">
                Resolution Information
            </h3>

        </div>

        <div class="error-detail-body">

            <div class="error-detail-row">

                <div class="error-detail-label">
                    Status
                </div>

                <div class="error-detail-value">

                    @if($errorLog->is_resolved)

                        <span class="error-status resolved">
                            Resolved
                        </span>

                    @else

                        <span class="error-status unresolved">
                            Unresolved
                        </span>

                    @endif

                </div>

            </div>

            <div class="error-detail-row">

                <div class="error-detail-label">
                    Resolved By
                </div>

                <div class="error-detail-value">
                    {{ $errorLog->resolvedBy?->name ?? '—' }}
                </div>

            </div>

            <div class="error-detail-row">

                <div class="error-detail-label">
                    Resolved At
                </div>

                <div class="error-detail-value">
                    {{ $errorLog->resolved_at?->format('d M Y, h:i:s A') ?? '—' }}
                </div>

            </div>

        </div>

    </div>

    {{-- Stack Trace --}}
    <div class="error-detail-card full-width">

        <div class="error-detail-header">

            <h3 class="error-detail-title">
                Technical Stack Trace
            </h3>

        </div>

        <div class="error-detail-body">

            @if($errorLog->trace)

                <pre class="error-code">{{ $errorLog->trace }}</pre>

            @else

                <div class="error-detail-muted">
                    No stack trace available.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection