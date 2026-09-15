@extends('layouts.app')

@section('content')

<style>
    .user-form-page {
        padding: 28px 32px;
    }

    .user-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 20px;
    }

    .user-form-title {
        margin: 0 0 5px;
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
    }

    .user-form-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .user-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        height: 100%;
    }

    .user-form-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #eef2f7;
    }

    .user-form-card-title {
        margin: 0 0 4px;
        font-size: 17px;
        font-weight: 700;
        color: #101d42;
    }

    .user-form-card-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 13px;
    }

    .user-form-card-body {
        padding: 22px;
    }

    .user-label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .user-input {
        width: 100%;
        height: 38px;
        padding: 8px 11px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #1f2937;
        font-size: 14px;
        outline: none;
        transition: .2s;
    }

    .user-input:focus {
        border-color: #193b8f;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .user-help {
        display: block;
        margin-top: 6px;
        color: #94a3b8;
        font-size: 12px;
    }

    .user-error {
        margin-top: 5px;
        color: #dc2626;
        font-size: 12px;
    }

    .role-selection {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .role-option {
        display: flex;
        align-items: center;
        gap: 9px;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: .2s;
    }

    .role-option:hover {
        border-color: #193b8f;
        background: #f8fafc;
    }

    .role-checkbox {
        width: 16px;
        height: 16px;
        accent-color: #193b8f;
        cursor: pointer;
        flex-shrink: 0;
    }

    .role-option-label {
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .no-roles {
        padding: 13px 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #64748b;
        background: #f8fafc;
        font-size: 13px;
    }

    .user-alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .user-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    .user-cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .user-submit-btn {
        height: 38px;
        padding: 0 17px;
        border: 1px solid #193b8f;
        border-radius: 7px;
        background: linear-gradient(135deg, #101d42, #193b8f);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .user-form-page {
            padding: 20px 15px;
        }

        .user-form-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .role-selection {
            grid-template-columns: 1fr;
        }

        .user-form-actions {
            justify-content: stretch;
        }

        .user-cancel-btn,
        .user-submit-btn {
            flex: 1;
        }
    }
</style>

<div class="user-form-page">

    <div class="user-form-header">

        <div>
            <h2 class="user-form-title">
                Edit User
            </h2>

            <p class="user-form-subtitle">
                Update user account information and assigned roles.
            </p>
        </div>

        <a href="{{ route('users.index') }}"
           class="user-cancel-btn">
            Back
        </a>

    </div>

    @if($errors->any())

        <div class="user-alert">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- User Details --}}
            <div class="col-lg-6">

                <div class="user-form-card">

                    <div class="user-form-card-header">

                        <h5 class="user-form-card-title">
                            User Details
                        </h5>

                        <p class="user-form-card-subtitle">
                            Update basic account information.
                        </p>

                    </div>

                    <div class="user-form-card-body">

                        <div class="mb-3">

                            <label class="user-label">
                                Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="user-input"
                                value="{{ old('name', $user->name) }}"
                                required
                            >

                            @error('name')
                                <div class="user-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="user-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="user-input"
                                value="{{ old('email', $user->email) }}"
                                required
                            >

                            @error('email')
                                <div class="user-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="user-label">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="user-input"
                            >

                            <small class="user-help">
                                Leave blank to keep the current password.
                                Minimum 8 characters if changing it.
                            </small>

                            @error('password')
                                <div class="user-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div>

                            <label class="user-label">
                                Confirm New Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="user-input"
                            >

                        </div>

                    </div>

                </div>

            </div>

            {{-- Roles --}}
            <div class="col-lg-6">

                <div class="user-form-card">

                    <div class="user-form-card-header">

                        <h5 class="user-form-card-title">
                            Assigned Roles
                        </h5>

                        <p class="user-form-card-subtitle">
                            Update the user's active system roles.
                        </p>

                    </div>

                    <div class="user-form-card-body">

                        @php
                            $selectedRoles = old(
                                'roles',
                                $user->roles->pluck('id')->toArray()
                            );
                        @endphp

                        @error('roles')
                            <div class="user-error mb-3">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($roles->isEmpty())

                            <div class="no-roles">
                                No active roles are available.
                            </div>

                        @else

                            <div class="role-selection">

                                @foreach($roles as $role)

                                    <label class="role-option">

                                        <input
                                            type="checkbox"
                                            name="roles[]"
                                            value="{{ $role->id }}"
                                            class="role-checkbox"
                                            {{ in_array($role->id, $selectedRoles) ? 'checked' : '' }}
                                        >

                                        <span class="role-option-label">
                                            {{ $role->display_name }}
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                            @error('roles.*')
                                <div class="user-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        @endif

                    </div>

                </div>

            </div>

        </div>

        <div class="user-form-actions">

            <a href="{{ route('users.index') }}"
               class="user-cancel-btn">
                Cancel
            </a>

            <button type="submit"
                    class="user-submit-btn">
                Update User
            </button>

        </div>

    </form>

</div>

@endsection