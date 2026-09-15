@extends('layouts.app')

@section('content')

<style>
    .audit-show-page {
        padding: 24px;
    }

    .audit-show-header {
        margin-bottom: 20px;
    }

    .audit-show-title {
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
        margin-bottom: 4px;
    }

    .audit-show-subtitle {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .audit-show-actions {
        display: flex;
        gap: 10px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .audit-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #d1d5db;
        color: #374151;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .audit-back-btn:hover {
        background: #f9fafb;
        color: #101d42;
    }

    .audit-hero {
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 16px;
        padding: 24px;
        color: #fff;
        margin-bottom: 24px;
    }

    .audit-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
    }

    .audit-hero-heading {
        min-width: 0;
    }

    .audit-hero-label {
        color: rgba(255, 255, 255, .65);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 6px;
    }

    .audit-hero-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 6px;
        word-break: break-word;
    }

    .audit-hero-description {
        margin: 0;
        color: rgba(255, 255, 255, .78);
        font-size: 14px;
        line-height: 1.5;
        max-width: 700px;
    }

    .audit-action-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .18);
        color: #fff;
        white-space: nowrap;
    }

    .audit-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-top: 22px;
    }

    .audit-summary-card {
        background: rgba(37, 99, 235, .30);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 12px;
        padding: 15px;
        min-width: 0;
    }

    .audit-summary-label {
        color: rgba(255, 255, 255, .65);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 6px;
    }

    .audit-summary-value {
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }

    .audit-details-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 20px;
    }

    .audit-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .audit-card.full-width {
        grid-column: 1 / -1;
    }

    .audit-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }

    .audit-card-title {
        margin: 0;
        color: #101d42;
        font-size: 16px;
        font-weight: 700;
    }

    .audit-card-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 12px;
    }

    .audit-card-body {
        padding: 20px;
    }

    .audit-info-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .audit-info-row {
        display: grid;
        grid-template-columns: 150px minmax(0, 1fr);
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .audit-info-row:first-child {
        padding-top: 0;
    }

    .audit-info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .audit-info-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    .audit-info-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 500;
        word-break: break-word;
    }

    .audit-subject-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .audit-subject-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .audit-code-block {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 15px;
        overflow-x: auto;
    }

    .audit-code-block pre {
        margin: 0;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
        line-height: 1.6;
        color: #334155;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .audit-empty-values {
        color: #94a3b8;
        font-size: 13px;
        font-style: italic;
    }

    .audit-description-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        color: #374151;
        font-size: 14px;
        line-height: 1.6;
    }

    .audit-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .audit-meta-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        padding: 13px;
    }

    .audit-meta-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .audit-meta-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }

    @media (max-width: 992px) {
        .audit-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .audit-details-grid {
            grid-template-columns: 1fr;
        }

        .audit-card.full-width {
            grid-column: auto;
        }
    }

    @media (max-width: 600px) {
        .audit-show-page {
            padding: 16px;
        }

        .audit-summary-grid {
            grid-template-columns: 1fr;
        }

        .audit-meta-grid {
            grid-template-columns: 1fr;
        }

        .audit-info-row {
            grid-template-columns: 1fr;
            gap: 5px;
        }
    }
</style>

<div class="audit-show-page">

    {{-- Page Header --}}
    <div class="audit-show-header">

        <h1 class="audit-show-title">
            Audit Log Details
        </h1>

        <p class="audit-show-subtitle">
            Detailed information about this system activity.
        </p>

        <div class="audit-show-actions">

            <a
                href="{{ route('activity-logs.index') }}"
                class="audit-back-btn"
            >
                ← Back to Audit Trail
            </a>

        </div>

    </div>

    {{-- Hero --}}
    <div class="audit-hero">

        <div class="audit-hero-top">

            <div class="audit-hero-heading">

                <div class="audit-hero-label">
                    Activity
                </div>

                <h2 class="audit-hero-title">
                    {{ ucfirst($activityLog->action) }}
                    {{ $activityLog->module }}
                </h2>

                <p class="audit-hero-description">
                    {{ $activityLog->description }}
                </p>

            </div>

            <div>
                <span class="audit-action-badge">
                    {{ $activityLog->action }}
                </span>
            </div>

        </div>

        {{-- Summary Cards --}}
        <div class="audit-summary-grid">

            <div class="audit-summary-card">

                <div class="audit-summary-label">
                    User
                </div>

                <div class="audit-summary-value">
                    {{ $activityLog->user?->name ?? 'System' }}
                </div>

            </div>

            <div class="audit-summary-card">

                <div class="audit-summary-label">
                    Module
                </div>

                <div class="audit-summary-value">
                    {{ $activityLog->module }}
                </div>

            </div>

            <div class="audit-summary-card">

                <div class="audit-summary-label">
                    Date
                </div>

                <div class="audit-summary-value">
                    {{ $activityLog->created_at?->format('d M Y') ?? '—' }}
                </div>

            </div>

            <div class="audit-summary-card">

                <div class="audit-summary-label">
                    Time
                </div>

                <div class="audit-summary-value">
                    {{ $activityLog->created_at?->format('h:i A') ?? '—' }}
                </div>

            </div>

        </div>

    </div>

    {{-- Details --}}
    <div class="audit-details-grid">

        {{-- Activity Information --}}
        <div class="audit-card">

            <div class="audit-card-header">

                <h3 class="audit-card-title">
                    Activity Information
                </h3>

                <p class="audit-card-subtitle">
                    Basic information recorded for this activity.
                </p>

            </div>

            <div class="audit-card-body">

                <div class="audit-info-list">

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            Log ID
                        </div>

                        <div class="audit-info-value">
                            #{{ $activityLog->id }}
                        </div>

                    </div>

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            Action
                        </div>

                        <div class="audit-info-value">
                            {{ ucfirst($activityLog->action) }}
                        </div>

                    </div>

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            Module
                        </div>

                        <div class="audit-info-value">
                            {{ $activityLog->module }}
                        </div>

                    </div>

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            User
                        </div>

                        <div class="audit-info-value">
                            {{ $activityLog->user?->name ?? 'System' }}
                        </div>

                    </div>

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            IP Address
                        </div>

                        <div class="audit-info-value">
                            {{ $activityLog->ip_address ?? '—' }}
                        </div>

                    </div>

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            Created At
                        </div>

                        <div class="audit-info-value">
                            {{ $activityLog->created_at?->format('d M Y, h:i:s A') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Subject Information --}}
        <div class="audit-card">

            <div class="audit-card-header">

                <h3 class="audit-card-title">
                    Affected Record
                </h3>

                <p class="audit-card-subtitle">
                    The record associated with this activity.
                </p>

            </div>

            <div class="audit-card-body">

                <div class="audit-info-list">

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            Subject Type
                        </div>

                        <div class="audit-info-value">
                            {{ $activityLog->subject_type ?? '—' }}
                        </div>

                    </div>

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            Subject ID
                        </div>

                        <div class="audit-info-value">
                            {{ $activityLog->subject_id ?? '—' }}
                        </div>

                    </div>

                    <div class="audit-info-row">

                        <div class="audit-info-label">
                            Record
                        </div>

                        <div class="audit-info-value">

                            @if($activityLog->subject)
                                {{ class_basename($activityLog->subject_type) }}
                                #{{ $activityLog->subject_id }}
                            @else
                                <span class="audit-empty-values">
                                    Record no longer available
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Description --}}
        <div class="audit-card full-width">

            <div class="audit-card-header">

                <h3 class="audit-card-title">
                    Description
                </h3>

            </div>

            <div class="audit-card-body">

                <div class="audit-description-box">
                    {{ $activityLog->description }}
                </div>

            </div>

        </div>

        {{-- Old Values --}}
        <div class="audit-card">

            <div class="audit-card-header">

                <h3 class="audit-card-title">
                    Previous Values
                </h3>

                <p class="audit-card-subtitle">
                    Data captured before the activity.
                </p>

            </div>

            <div class="audit-card-body">

                @if(!empty($activityLog->old_values))

                    <div class="audit-code-block">

                        <pre>{{ json_encode(
    $activityLog->old_values,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
) }}</pre>

                    </div>

                @else

                    <div class="audit-empty-values">
                        No previous values were recorded.
                    </div>

                @endif

            </div>

        </div>

        {{-- New Values --}}
        <div class="audit-card">

            <div class="audit-card-header">

                <h3 class="audit-card-title">
                    New Values
                </h3>

                <p class="audit-card-subtitle">
                    Data captured after the activity.
                </p>

            </div>

            <div class="audit-card-body">

                @if(!empty($activityLog->new_values))

                    <div class="audit-code-block">

                        <pre>{{ json_encode(
    $activityLog->new_values,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
) }}</pre>

                    </div>

                @else

                    <div class="audit-empty-values">
                        No new values were recorded.
                    </div>

                @endif

            </div>

        </div>

        {{-- Request Metadata --}}
        <div class="audit-card full-width">

            <div class="audit-card-header">

                <h3 class="audit-card-title">
                    Request Metadata
                </h3>

                <p class="audit-card-subtitle">
                    Technical information captured when the activity occurred.
                </p>

            </div>

            <div class="audit-card-body">

                <div class="audit-meta-grid">

                    <div class="audit-meta-item">

                        <div class="audit-meta-label">
                            IP Address
                        </div>

                        <div class="audit-meta-value">
                            {{ $activityLog->ip_address ?? '—' }}
                        </div>

                    </div>

                    <div class="audit-meta-item">

                        <div class="audit-meta-label">
                            User Agent
                        </div>

                        <div class="audit-meta-value">
                            {{ $activityLog->user_agent ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection