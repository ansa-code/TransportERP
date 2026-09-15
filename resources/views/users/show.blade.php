@extends('layouts.app')

@section('content')

<style>
    .user-show-page {
        padding: 28px 32px;
    }

    .user-show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .user-show-title {
        margin: 0 0 5px;
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
    }

    .user-show-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .user-header-actions {
        display: flex;
        gap: 9px;
    }

    .user-back-btn,
    .user-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .user-back-btn {
        background: #fff;
        color: #475569;
        border: 1px solid #d1d5db;
    }

    .user-back-btn:hover {
        background: #f8fafc;
        color: #334155;
    }

    .user-edit-btn {
        background: linear-gradient(135deg, #2563eb, #193b8f);
        color: #fff !important;
        border: 1px solid #193b8f;
    }

    .user-edit-btn:hover {
        background: linear-gradient(135deg, #1d4ed8, #152f73);
        color: #fff !important;
    }

    /* Hero */
    .user-hero {
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 14px;
        padding: 26px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .12);
        margin-bottom: 24px;
    }

    .user-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .user-hero-name {
        margin: 0 0 6px;
        font-size: 25px;
        font-weight: 700;
        color: #fff;
    }

    .user-hero-email {
        margin: 0;
        color: rgba(255, 255, 255, .78);
        font-size: 14px;
    }

    .user-account-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 20px;
        background: rgba(34, 197, 94, .20);
        color: #bbf7d0;
        border: 1px solid rgba(187, 247, 208, .25);
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .user-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .user-summary-card {
        padding: 17px;
        border-radius: 10px;
        background: rgba(37, 99, 235, .30);
        border: 1px solid rgba(255, 255, 255, .08);
    }

    .user-summary-label {
        margin-bottom: 6px;
        color: rgba(255, 255, 255, .68);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .user-summary-value {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
    }

    /* Details */
    .user-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .user-details-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .user-details-header {
        padding: 18px 20px;
        border-bottom: 1px solid #eef2f7;
    }

    .user-details-title {
        margin: 0;
        color: #101d42;
        font-size: 16px;
        font-weight: 700;
    }

    .user-details-body {
        padding: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding: 11px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .detail-row:first-child {
        padding-top: 0;
    }

    .detail-row:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .detail-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .detail-value {
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
    }

    .role-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 6px;
        background: rgba(37, 99, 235, .30);
        color: #193b8f;
        font-size: 12px;
        font-weight: 600;
    }

    .permission-groups {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .permission-group-title {
        margin-bottom: 9px;
        color: #193b8f;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .permission-list {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .permission-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 9px;
        border-radius: 6px;
        background: rgba(37, 99, 235, .30);
        color: #193b8f;
        font-size: 11px;
        font-weight: 600;
    }

    .empty-detail {
        padding: 8px 0;
        color: #94a3b8;
        font-size: 13px;
    }

    @media (max-width: 900px) {

        .user-summary-grid {
            grid-template-columns: 1fr;
        }

        .user-details-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {

        .user-show-page {
            padding: 20px 15px;
        }

        .user-show-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .user-header-actions {
            width: 100%;
        }

        .user-back-btn,
        .user-edit-btn {
            flex: 1;
        }

        .user-hero {
            padding: 20px;
        }

        .user-hero-top {
            flex-direction: column;
        }

        .detail-row {
            flex-direction: column;
            gap: 5px;
        }

        .detail-value {
            text-align: left;
        }
    }
</style>

<div class="user-show-page">

    {{-- Page Header --}}
    <div class="user-show-header">

        <div>
            <h2 class="user-show-title">
                User Details
            </h2>

            <p class="user-show-subtitle">
                View user account information, roles and permissions.
            </p>
        </div>

        <div class="user-header-actions">

            <a href="{{ route('users.index') }}"
               class="user-back-btn">
                Back
            </a>

            <a href="{{ route('users.edit', $user) }}"
               class="user-edit-btn">
                Edit
            </a>

        </div>

    </div>

    {{-- Hero --}}
    <div class="user-hero">

        <div class="user-hero-top">

            <div>

                <h1 class="user-hero-name">
                    {{ $user->name }}
                </h1>

                <p class="user-hero-email">
                    {{ $user->email }}
                </p>

            </div>

            <span class="user-account-status">
                Active Account
            </span>

        </div>

        <div class="user-summary-grid">

            <div class="user-summary-card">

                <div class="user-summary-label">
                    User ID
                </div>

                <div class="user-summary-value">
                    #{{ $user->id }}
                </div>

            </div>

            <div class="user-summary-card">

                <div class="user-summary-label">
                    Assigned Roles
                </div>

                <div class="user-summary-value">
                    {{ $user->roles->count() }}
                </div>

            </div>

            <div class="user-summary-card">

                <div class="user-summary-label">
                    Total Permissions
                </div>

                <div class="user-summary-value">
                    {{ $user->roles->flatMap->permissions->unique('id')->count() }}
                </div>

            </div>

        </div>

    </div>

    {{-- Details --}}
    <div class="user-details-grid">

        {{-- Account Information --}}
        <div class="user-details-card">

            <div class="user-details-header">

                <h3 class="user-details-title">
                    Account Information
                </h3>

            </div>

            <div class="user-details-body">

                <div class="detail-row">

                    <span class="detail-label">
                        Name
                    </span>

                    <span class="detail-value">
                        {{ $user->name }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Email
                    </span>

                    <span class="detail-value">
                        {{ $user->email }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Email Verified
                    </span>

                    <span class="detail-value">
                        {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Created
                    </span>

                    <span class="detail-value">
                        {{ $user->created_at?->format('d M Y, h:i A') }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Last Updated
                    </span>

                    <span class="detail-value">
                        {{ $user->updated_at?->format('d M Y, h:i A') }}
                    </span>

                </div>

            </div>

        </div>

        {{-- Assigned Roles --}}
        <div class="user-details-card">

            <div class="user-details-header">

                <h3 class="user-details-title">
                    Assigned Roles
                </h3>

            </div>

            <div class="user-details-body">

                @if($user->roles->isNotEmpty())

                    <div class="role-list">

                        @foreach($user->roles as $role)

                            <span class="role-badge">
                                {{ $role->display_name }}
                            </span>

                        @endforeach

                    </div>

                @else

                    <div class="empty-detail">
                        No roles are currently assigned to this user.
                    </div>

                @endif

            </div>

        </div>

        {{-- Effective Permissions --}}
        <div class="user-details-card"
             style="grid-column: 1 / -1;">

            <div class="user-details-header">

                <h3 class="user-details-title">
                    Effective Permissions
                </h3>

            </div>

            <div class="user-details-body">

                @php
                    $permissions = $user->roles
                        ->flatMap->permissions
                        ->where('is_active', true)
                        ->unique('id')
                        ->sortBy([
                            ['module', 'asc'],
                            ['display_name', 'asc'],
                        ]);

                    $permissionGroups = $permissions->groupBy('module');
                @endphp

                @if($permissionGroups->isNotEmpty())

                    <div class="permission-groups">

                        @foreach($permissionGroups as $module => $modulePermissions)

                            <div>

                                <div class="permission-group-title">
                                    {{ $module ?: 'General' }}
                                </div>

                                <div class="permission-list">

                                    @foreach($modulePermissions as $permission)

                                        <span class="permission-badge">
                                            {{ $permission->display_name }}
                                        </span>

                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-detail">
                        No active permissions are available for this user.
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection