@extends('layouts.app')

@section('content')

<style>
    .role-show-page {
        padding: 28px 32px;
    }

    .role-show-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .role-show-title {
        margin: 0 0 5px;
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
    }

    .role-show-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .role-header-actions {
        display: flex;
        gap: 9px;
    }

    .role-back-btn,
    .role-edit-btn {
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

    .role-back-btn {
        background: #fff;
        color: #475569;
        border: 1px solid #d1d5db;
    }

    .role-back-btn:hover {
        background: #f8fafc;
        color: #334155;
    }

    .role-edit-btn {
        background: linear-gradient(135deg, #2563eb, #193b8f);
        color: #fff !important;
        border: 1px solid #193b8f;
    }

    .role-edit-btn:hover {
        background: linear-gradient(135deg, #1d4ed8, #152f73);
        color: #fff !important;
    }

    /* Hero */
    .role-hero {
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 14px;
        padding: 26px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .12);
        margin-bottom: 24px;
    }

    .role-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .role-hero-name {
        margin: 0 0 6px;
        font-size: 25px;
        font-weight: 700;
        color: #fff;
    }

    .role-hero-description {
        margin: 0;
        max-width: 700px;
        color: rgba(255, 255, 255, .78);
        font-size: 14px;
        line-height: 1.6;
    }

    .role-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .role-status-active {
        background: rgba(34, 197, 94, .20);
        color: #bbf7d0;
        border: 1px solid rgba(187, 247, 208, .25);
    }

    .role-status-inactive {
        background: rgba(239, 68, 68, .20);
        color: #fecaca;
        border: 1px solid rgba(254, 202, 202, .25);
    }

    .role-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .role-summary-card {
        padding: 17px;
        border-radius: 10px;
        background: rgba(37, 99, 235, .30);
        border: 1px solid rgba(255, 255, 255, .08);
    }

    .role-summary-label {
        margin-bottom: 6px;
        color: rgba(255, 255, 255, .68);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .role-summary-value {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
    }

    /* Details */
    .role-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .role-details-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .role-details-header {
        padding: 18px 20px;
        border-bottom: 1px solid #eef2f7;
    }

    .role-details-title {
        margin: 0;
        color: #101d42;
        font-size: 16px;
        font-weight: 700;
    }

    .role-details-body {
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

    .assigned-user-list {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .assigned-user {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 10px 12px;
        border: 1px solid #eef2f7;
        border-radius: 8px;
        background: #f8fafc;
    }

    .assigned-user-name {
        color: #101d42;
        font-size: 13px;
        font-weight: 700;
    }

    .assigned-user-email {
        margin-top: 2px;
        color: #64748b;
        font-size: 11px;
    }

    .empty-detail {
        padding: 8px 0;
        color: #94a3b8;
        font-size: 13px;
    }

    @media (max-width: 900px) {

        .role-summary-grid {
            grid-template-columns: 1fr;
        }

        .role-details-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {

        .role-show-page {
            padding: 20px 15px;
        }

        .role-show-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .role-header-actions {
            width: 100%;
        }

        .role-back-btn,
        .role-edit-btn {
            flex: 1;
        }

        .role-hero {
            padding: 20px;
        }

        .role-hero-top {
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

<div class="role-show-page">

    {{-- Page Header --}}
    <div class="role-show-header">

        <div>
            <h2 class="role-show-title">
                Role Details
            </h2>

            <p class="role-show-subtitle">
                View role information, assigned permissions and users.
            </p>
        </div>

        <div class="role-header-actions">

            <a href="{{ route('roles.index') }}"
               class="role-back-btn">
                Back
            </a>

            <a href="{{ route('roles.edit', $role) }}"
               class="role-edit-btn">
                Edit
            </a>

        </div>

    </div>

    {{-- Hero --}}
    <div class="role-hero">

        <div class="role-hero-top">

            <div>

                <h1 class="role-hero-name">
                    {{ $role->display_name }}
                </h1>

                @if($role->description)

                    <p class="role-hero-description">
                        {{ $role->description }}
                    </p>

                @else

                    <p class="role-hero-description">
                        No description has been added for this role.
                    </p>

                @endif

            </div>

            @if($role->is_active)

                <span class="role-status role-status-active">
                    Active
                </span>

            @else

                <span class="role-status role-status-inactive">
                    Inactive
                </span>

            @endif

        </div>

        <div class="role-summary-grid">

            <div class="role-summary-card">

                <div class="role-summary-label">
                    Role Key
                </div>

                <div class="role-summary-value">
                    {{ $role->name }}
                </div>

            </div>

            <div class="role-summary-card">

                <div class="role-summary-label">
                    Permissions
                </div>

                <div class="role-summary-value">
                    {{ $role->permissions->count() }}
                </div>

            </div>

            <div class="role-summary-card">

                <div class="role-summary-label">
                    Assigned Users
                </div>

                <div class="role-summary-value">
                    {{ $role->users->count() }}
                </div>

            </div>

        </div>

    </div>

    {{-- Details --}}
    <div class="role-details-grid">

        {{-- Role Information --}}
        <div class="role-details-card">

            <div class="role-details-header">

                <h3 class="role-details-title">
                    Role Information
                </h3>

            </div>

            <div class="role-details-body">

                <div class="detail-row">

                    <span class="detail-label">
                        Display Name
                    </span>

                    <span class="detail-value">
                        {{ $role->display_name }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Role Key
                    </span>

                    <span class="detail-value">
                        {{ $role->name }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Status
                    </span>

                    <span class="detail-value">
                        {{ $role->is_active ? 'Active' : 'Inactive' }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Created
                    </span>

                    <span class="detail-value">
                        {{ $role->created_at?->format('d M Y, h:i A') }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Last Updated
                    </span>

                    <span class="detail-value">
                        {{ $role->updated_at?->format('d M Y, h:i A') }}
                    </span>

                </div>

            </div>

        </div>

        {{-- Assigned Users --}}
        <div class="role-details-card">

            <div class="role-details-header">

                <h3 class="role-details-title">
                    Assigned Users
                </h3>

            </div>

            <div class="role-details-body">

                @if($role->users->isNotEmpty())

                    <div class="assigned-user-list">

                        @foreach($role->users as $user)

                            <div class="assigned-user">

                                <div>

                                    <div class="assigned-user-name">
                                        {{ $user->name }}
                                    </div>

                                    <div class="assigned-user-email">
                                        {{ $user->email }}
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-detail">
                        No users are currently assigned to this role.
                    </div>

                @endif

            </div>

        </div>

        {{-- Permissions --}}
        <div class="role-details-card" style="grid-column: 1 / -1;">

            <div class="role-details-header">

                <h3 class="role-details-title">
                    Assigned Permissions
                </h3>

            </div>

            <div class="role-details-body">

                @if($role->permissions->isNotEmpty())

                    @php
                        $permissionGroups = $role->permissions->groupBy('module');
                    @endphp

                    <div class="permission-groups">

                        @foreach($permissionGroups as $module => $permissions)

                            <div>

                                <div class="permission-group-title">
                                    {{ $module ?: 'General' }}
                                </div>

                                <div class="permission-list">

                                    @foreach($permissions as $permission)

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
                        No permissions are assigned to this role.
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection