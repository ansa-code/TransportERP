@extends('layouts.app')

@section('content')

<style>
    .alerts-page {
        padding: 28px 32px;
    }

    .alerts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .alerts-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .alerts-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .mark-all-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border: 1px solid #193b8f;
        border-radius: 7px;
        background: linear-gradient(135deg, #101d42, #193b8f);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .mark-all-btn:hover {
        background: #101d42;
    }

    .alerts-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .alert-card {
        position: relative;
        padding: 18px 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-left: 5px solid #2563eb;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
        transition: .2s;
    }

    .alert-card:hover {
        box-shadow: 0 6px 20px rgba(15, 23, 42, .09);
    }

    .alert-card.danger {
        border-left-color: #dc3545;
    }

    .alert-card.warning {
        border-left-color: #f59e0b;
    }

    .alert-card.info {
        border-left-color: #2563eb;
    }

    .alert-card.success {
        border-left-color: #198754;
    }

    .alert-card.read {
        opacity: .72;
        background: #fafafa;
    }

    .alert-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
    }

    .alert-main {
        min-width: 0;
    }

    .alert-title-row {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 7px;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        flex: 0 0 8px;
        border-radius: 50%;
        background: #2563eb;
    }

    .alert-card.danger .unread-dot {
        background: #dc3545;
    }

    .alert-title {
        color: #101d42;
        font-size: 16px;
        font-weight: 700;
    }

    .alert-message {
        color: #475569;
        font-size: 14px;
        line-height: 1.55;
    }

    .alert-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 5px 12px;
        margin-top: 11px;
        color: #94a3b8;
        font-size: 12px;
    }

    .alert-meta strong {
        color: #64748b;
        font-weight: 600;
    }

    .alert-type {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 68px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .alert-type.danger {
        background: rgba(220, 53, 69, .14);
        color: #b42333;
    }

    .alert-type.warning {
        background: rgba(245, 158, 11, .16);
        color: #a16207;
    }

    .alert-type.info {
        background: rgba(37, 99, 235, .14);
        color: #193b8f;
    }

    .alert-type.success {
        background: rgba(25, 135, 84, .14);
        color: #147044;
    }

    .alert-actions {
        margin-top: 15px;
    }

    .read-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 6px 11px;
        border: none;
        border-radius: 6px;
        background: rgba(37, 99, 235, .85);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .read-btn:hover {
        background: #2563eb;
    }

    .read-label {
        display: inline-flex;
        align-items: center;
        min-height: 32px;
        padding: 6px 11px;
        border-radius: 6px;
        background: rgba(25, 135, 84, .10);
        color: #198754;
        font-size: 12px;
        font-weight: 600;
    }

    .empty-alerts {
        padding: 60px 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
    }

    .empty-alerts-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        border-radius: 50%;
        background: rgba(37, 99, 235, .12);
        font-size: 27px;
    }

    .empty-alerts h3 {
        margin: 0 0 6px;
        color: #101d42;
        font-size: 18px;
        font-weight: 700;
    }

    .empty-alerts p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    @media (max-width: 768px) {

        .alerts-page {
            padding: 20px 15px;
        }

        .alerts-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .mark-all-btn {
            width: 100%;
        }

        .alert-top {
            flex-direction: column;
        }

        .alert-type {
            align-self: flex-start;
        }
    }
</style>


<div class="alerts-page">

    {{-- Header --}}
    <div class="alerts-header">

        <div>
            <h1 class="alerts-title">
                Alerts & Notifications
            </h1>

            <p class="alerts-subtitle">
                Review important alerts across your transport operations.
            </p>
        </div>

        @if($alerts->where('status', 'unread')->count() > 0)

            <form
                action="{{ route('alerts.read-all') }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="mark-all-btn"
                >
                    ✓ Mark All as Read
                </button>
            </form>

        @endif

    </div>


    {{-- Alert List --}}
    @if($alerts->count() > 0)

        <div class="alerts-list">

            @foreach($alerts as $alert)

                @php
                    $alertType = $alert->type ?: 'warning';
                    $isUnread = $alert->status === 'unread';
                @endphp

                <div class="alert-card {{ $alertType }} {{ !$isUnread ? 'read' : '' }}">

                    <div class="alert-top">

                        <div class="alert-main">

                            <div class="alert-title-row">

                                @if($isUnread)
                                    <span class="unread-dot"></span>
                                @endif

                                <div class="alert-title">
                                    {{ $alert->title }}
                                </div>

                            </div>

                            <div class="alert-message">
                                {{ $alert->message }}
                            </div>

                            <div class="alert-meta">

                                <span>
                                    Module:
                                    <strong>
                                        {{ $alert->module }}
                                    </strong>
                                </span>

                                @if($alert->alert_date)

                                    <span>
                                        Date:
                                        <strong>
                                            {{ $alert->alert_date->format('d M Y') }}
                                        </strong>
                                    </span>

                                @endif

                                <span>
                                    Created:
                                    <strong>
                                        {{ $alert->created_at->format('d M Y, h:i A') }}
                                    </strong>
                                </span>

                            </div>

                        </div>


                        <div>

                            <span class="alert-type {{ $alertType }}">
                                {{ $alertType }}
                            </span>

                        </div>

                    </div>


                    <div class="alert-actions">

                        @if($isUnread)

                            <form
                                action="{{ route('alerts.read', $alert) }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="read-btn"
                                >
                                    ✓ Mark as Read
                                </button>

                            </form>

                        @else

                            <span class="read-label">
                                ✓ Read
                            </span>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty-alerts">

            <div class="empty-alerts-icon">
                🔔
            </div>

            <h3>
                No Alerts
            </h3>

            <p>
                Everything looks good. There are currently no alerts to review.
            </p>

        </div>

    @endif

</div>

@endsection