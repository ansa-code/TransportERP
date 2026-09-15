@extends('layouts.app')

@section('content')

<style>
    .role-form-page {
        padding: 28px 32px;
    }

    .role-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 20px;
    }

    .role-form-title {
        margin: 0 0 5px;
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
    }

    .role-form-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .role-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        height: 100%;
    }

    .role-form-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #eef2f7;
    }

    .role-form-card-title {
        margin: 0 0 4px;
        font-size: 17px;
        font-weight: 700;
        color: #101d42;
    }

    .role-form-card-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 13px;
    }

    .role-form-card-body {
        padding: 22px;
    }

    .role-label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .role-input,
    .role-textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        padding: 9px 11px;
        font-size: 14px;
        color: #1f2937;
        background: #fff;
        outline: none;
        transition: .2s;
    }

    .role-input {
        height: 38px;
    }

    .role-textarea {
        resize: vertical;
        min-height: 105px;
    }

    .role-input:focus,
    .role-textarea:focus {
        border-color: #193b8f;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .role-help {
        display: block;
        margin-top: 6px;
        color: #94a3b8;
        font-size: 12px;
    }

    .role-switch-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 18px;
    }

    .role-switch {
        width: 40px;
        height: 21px;
        cursor: pointer;
        accent-color: #193b8f;
    }

    .role-switch-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .permission-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 18px;
    }

    .permission-toolbar-title {
        font-size: 15px;
        font-weight: 700;
        color: #101d42;
    }

    .permission-toolbar-text {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 12px;
    }

    .select-all-btn {
        border: 1px solid #193b8f;
        background: #fff;
        color: #193b8f;
        border-radius: 7px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .select-all-btn:hover {
        background: #193b8f;
        color: #fff;
    }

    .permission-module {
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        padding: 15px;
        height: 100%;
        background: #fff;
    }

    .permission-module-title {
        margin-bottom: 12px;
        color: #101d42;
        font-size: 13px;
        font-weight: 700;
    }

    .permission-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 9px;
    }

    .permission-item:last-child {
        margin-bottom: 0;
    }

    .permission-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #193b8f;
        flex-shrink: 0;
    }

    .permission-label {
        color: #475569;
        font-size: 13px;
        cursor: pointer;
    }

    .permission-action {
        color: #94a3b8;
        font-size: 11px;
    }

    .role-error {
        color: #dc2626;
        margin-top: 5px;
        font-size: 12px;
    }

    .role-alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .role-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    .role-cancel-btn {
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

    .role-cancel-btn:hover {
        background: #f8fafc;
        color: #334155;
    }

    .role-submit-btn {
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

    .role-submit-btn:hover {
        background: #101d42;
    }

    @media (max-width: 768px) {
        .role-form-page {
            padding: 20px 15px;
        }

        .role-form-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .permission-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .role-form-actions {
            justify-content: stretch;
        }

        .role-cancel-btn,
        .role-submit-btn {
            flex: 1;
        }
    }
</style>

<div class="role-form-page">

    <div class="role-form-header">

        <div>
            <h2 class="role-form-title">
                Create Role
            </h2>

            <p class="role-form-subtitle">
                Create a role and assign its permissions.
            </p>
        </div>

        <a href="{{ route('roles.index') }}"
           class="role-cancel-btn">
            Back
        </a>

    </div>

    @if($errors->any())

        <div class="role-alert">
            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    @endif

    <form action="{{ route('roles.store') }}" method="POST">

        @csrf

        <div class="row g-4">

            {{-- Role Details --}}
            <div class="col-lg-5">

                <div class="role-form-card">

                    <div class="role-form-card-header">
                        <h5 class="role-form-card-title">
                            Role Details
                        </h5>

                        <p class="role-form-card-subtitle">
                            Basic information for this role.
                        </p>
                    </div>

                    <div class="role-form-card-body">

                        <div class="mb-3">

                            <label class="role-label">
                                Role Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="role-input"
                                value="{{ old('name') }}"
                                placeholder="e.g. operations-manager"
                                required
                            >

                            @error('name')
                                <div class="role-error">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="role-help">
                                Use a unique internal name.
                            </small>

                        </div>

                        <div class="mb-3">

                            <label class="role-label">
                                Display Name
                            </label>

                            <input
                                type="text"
                                name="display_name"
                                class="role-input"
                                value="{{ old('display_name') }}"
                                placeholder="e.g. Operations Manager"
                                required
                            >

                            @error('display_name')
                                <div class="role-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div>

                            <label class="role-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="role-textarea"
                                placeholder="Describe what this role is allowed to do..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="role-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="role-switch-row">

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                id="is_active"
                                class="role-switch"
                                {{ old('is_active', true) ? 'checked' : '' }}
                            >

                            <label
                                for="is_active"
                                class="role-switch-label"
                            >
                                Active Role
                            </label>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Permissions --}}
            <div class="col-lg-7">

                <div class="role-form-card">

                    <div class="role-form-card-header">

                        <div class="permission-toolbar">

                            <div>
                                <div class="permission-toolbar-title">
                                    Permissions
                                </div>

                                <small class="permission-toolbar-text">
                                    Select the permissions this role should have.
                                </small>
                            </div>

                            <button
                                type="button"
                                class="select-all-btn"
                                id="selectAllPermissions"
                            >
                                Select All
                            </button>

                        </div>

                    </div>

                    <div class="role-form-card-body">
                        @error('permissions')
                            <div class="role-error mb-3">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($permissions->isEmpty())

                            <div class="role-alert">
                                No permissions have been created yet.
                            </div>

                        @else

                            <div class="row g-3">

                                @foreach($permissions as $module => $modulePermissions)

                                    <div class="col-md-6">

                                        <div class="permission-module">

                                            <div class="permission-module-title">
                                                {{ $module ?: 'General' }}
                                            </div>

                                            @foreach($modulePermissions as $permission)

                                                <div class="permission-item">

                                                    <input
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $permission->id }}"
                                                        id="permission_{{ $permission->id }}"
                                                        class="permission-checkbox"
                                                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                                    >

                                                    <label
                                                        for="permission_{{ $permission->id }}"
                                                        class="permission-label"
                                                    >
                                                        {{ $permission->display_name }}

                                                        @if($permission->action)
                                                            <span class="permission-action">
                                                                ({{ $permission->action }})
                                                            </span>
                                                        @endif
                                                    </label>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        <div class="role-form-actions">

            <a
                href="{{ route('roles.index') }}"
                class="role-cancel-btn"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="role-submit-btn"
            >
                Create Role
            </button>

        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectAllButton =
        document.getElementById('selectAllPermissions');

    const checkboxes =
        document.querySelectorAll('.permission-checkbox');

    if (!selectAllButton) {
        return;
    }

    function updateButtonText() {

        const allChecked =
            checkboxes.length > 0 &&
            Array.from(checkboxes).every(function (checkbox) {
                return checkbox.checked;
            });

        selectAllButton.textContent =
            allChecked ? 'Unselect All' : 'Select All';
    }

    selectAllButton.addEventListener('click', function () {

        const allChecked =
            checkboxes.length > 0 &&
            Array.from(checkboxes).every(function (checkbox) {
                return checkbox.checked;
            });

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = !allChecked;
        });

        updateButtonText();
    });

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateButtonText);
    });

    updateButtonText();

});
</script>
@endpush