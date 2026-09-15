@extends('layouts.app')

@section('title', 'Add Leave')

@section('content')

<style>
    .leave-form-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #101d42;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border: 1px solid #d8dee8;
        border-radius: 9px;
        background: #fff;
        color: #334155;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #101d42;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 26px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
    }

    .section-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 18px;
        font-weight: 800;
    }

    .section-subtitle {
        margin: 0 0 22px;
        color: #64748b;
        font-size: 13px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        margin-bottom: 7px;
        color: #334155;
        font-size: 13px;
        font-weight: 750;
    }

    .required {
        color: #dc2626;
    }

    .form-control,
    .form-select,
    .form-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d8dee8;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-size: 13px;
        outline: none;
        transition: .2s ease;
    }

    .form-control,
    .form-select {
        height: 42px;
        padding: 0 12px;
    }

    .form-textarea {
        min-height: 110px;
        padding: 11px 12px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .form-help {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 11px;
    }

    .error-text {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
    }

    .approval-box {
        margin-top: 24px;
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        background: #f8fafc;
    }

    .approval-box-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 15px;
        font-weight: 800;
    }

    .approval-box-text {
        margin: 0 0 18px;
        color: #64748b;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .cancel-btn,
    .save-btn {
        height: 40px;
        padding: 0 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
    }

    .cancel-btn {
        background: #fff;
        color: #475569;
        border-color: #d8dee8;
    }

    .cancel-btn:hover {
        background: #f8fafc;
    }

    .save-btn {
        background: #101d42;
        color: #fff;
        border-color: #101d42;
    }

    .save-btn:hover {
        background: #193b8f;
        border-color: #193b8f;
    }

    @media (max-width: 700px) {
        .page-header {
            flex-direction: column;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }

        .form-card {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }
    }
</style>

<div class="leave-form-page">

    <div class="page-header">

        <div>
            <h1 class="page-title">Add Leave</h1>

            <p class="page-subtitle">
                Create a driver leave request and manage its approval
            </p>
        </div>

        <a
            href="{{ route('leaves.index') }}"
            class="back-btn"
        >
            ← Back to Leave
        </a>

    </div>

    <div class="form-card">

        <h2 class="section-title">
            Leave Information
        </h2>

        <p class="section-subtitle">
            Enter the driver leave details below.
        </p>

        <form
            method="POST"
            action="{{ route('leaves.store') }}"
        >

            @csrf

            <div class="form-grid">

                <div class="form-group">

                    <label class="form-label" for="driver_id">
                        Driver <span class="required">*</span>
                    </label>

                    <select
                        name="driver_id"
                        id="driver_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Active Driver
                        </option>

                        @foreach($drivers as $driver)

                            <option
                                value="{{ $driver->id }}"
                                {{ old('driver_id') == $driver->id ? 'selected' : '' }}
                            >
                                {{ $driver->driver_name }}
                                @if($driver->driver_code)
                                    — {{ $driver->driver_code }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                    @error('driver_id')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label class="form-label" for="leave_type">
                        Leave Type <span class="required">*</span>
                    </label>

                    <select
                        name="leave_type"
                        id="leave_type"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Leave Type
                        </option>

                        <option
                            value="Annual"
                            {{ old('leave_type') === 'Annual' ? 'selected' : '' }}
                        >
                            Annual
                        </option>

                        <option
                            value="Sick"
                            {{ old('leave_type') === 'Sick' ? 'selected' : '' }}
                        >
                            Sick
                        </option>

                        <option
                            value="Emergency"
                            {{ old('leave_type') === 'Emergency' ? 'selected' : '' }}
                        >
                            Emergency
                        </option>

                        <option
                            value="Unpaid"
                            {{ old('leave_type') === 'Unpaid' ? 'selected' : '' }}
                        >
                            Unpaid
                        </option>

                        <option
                            value="Other"
                            {{ old('leave_type') === 'Other' ? 'selected' : '' }}
                        >
                            Other
                        </option>

                    </select>

                    @error('leave_type')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label class="form-label" for="start_date">
                        Start Date <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        id="start_date"
                        class="form-control"
                        value="{{ old('start_date') }}"
                        required
                    >

                    @error('start_date')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label class="form-label" for="end_date">
                        End Date <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        id="end_date"
                        class="form-control"
                        value="{{ old('end_date') }}"
                        required
                    >

                    <div class="form-help">
                        End Date must be on or after Start Date.
                    </div>

                    @error('end_date')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label class="form-label" for="approval_status">
                        Approval Status <span class="required">*</span>
                    </label>

                    <select
                        name="approval_status"
                        id="approval_status"
                        class="form-select"
                        required
                    >

                        <option
                            value="Pending"
                            {{ old('approval_status', 'Pending') === 'Pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="Approved"
                            {{ old('approval_status') === 'Approved' ? 'selected' : '' }}
                        >
                            Approved
                        </option>

                        <option
                            value="Rejected"
                            {{ old('approval_status') === 'Rejected' ? 'selected' : '' }}
                        >
                            Rejected
                        </option>

                        <option
                            value="Cancelled"
                            {{ old('approval_status') === 'Cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                    @error('approval_status')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label class="form-label" for="replacement_driver_id">
                        Replacement Driver
                    </label>

                    <select
                        name="replacement_driver_id"
                        id="replacement_driver_id"
                        class="form-select"
                    >

                        <option value="">
                            No Replacement Driver
                        </option>

                        @foreach($drivers as $driver)

                            <option
                                value="{{ $driver->id }}"
                                {{ old('replacement_driver_id') == $driver->id ? 'selected' : '' }}
                            >
                                {{ $driver->driver_name }}
                                @if($driver->driver_code)
                                    — {{ $driver->driver_code }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                    <div class="form-help">
                        Replacement driver cannot be the same driver.
                    </div>

                    @error('replacement_driver_id')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>
                <div class="form-group full-width">

                    <label class="form-label" for="approval_notes">
                        Approval Notes
                    </label>

                    <textarea
                        name="approval_notes"
                        id="approval_notes"
                        class="form-textarea"
                        placeholder="Enter approval notes..."
                    >{{ old('approval_notes') }}</textarea>

                    <div class="form-help" id="approval-notes-help">
                        Approval notes are required when the leave is rejected.
                    </div>

                    @error('approval_notes')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="approval-box">

                <h3 class="approval-box-title">
                    Leave Approval
                </h3>

                <p class="approval-box-text">
                    Select the current approval status. Rejected leaves must include approval notes.
                </p>

                <div id="approval-status-message"></div>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('leaves.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Save Leave
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const approvalStatus = document.getElementById('approval_status');
        const approvalNotes = document.getElementById('approval_notes');
        const approvalNotesHelp = document.getElementById('approval-notes-help');
        const statusMessage = document.getElementById('approval-status-message');

        function updateApprovalFields() {

            if (!approvalStatus || !approvalNotes) {
                return;
            }

            const status = approvalStatus.value;

            if (status === 'Rejected') {

                approvalNotes.required = true;

                approvalNotesHelp.textContent =
                    'Approval Notes are required for rejected leave.';

                if (statusMessage) {
                    statusMessage.innerHTML =
                        '<div style="color:#b91c1c;font-size:12px;font-weight:700;">' +
                        'Rejected leave requires approval notes.' +
                        '</div>';
                }

            } else {

                approvalNotes.required = false;

                approvalNotesHelp.textContent =
                    'Approval notes are optional unless the leave is rejected.';

                if (statusMessage) {
                    statusMessage.innerHTML = '';
                }
            }
        }

        if (approvalStatus) {
            approvalStatus.addEventListener(
                'change',
                updateApprovalFields
            );

            updateApprovalFields();
        }

        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        function updateEndDateMinimum() {

            if (
                startDate &&
                endDate &&
                startDate.value
            ) {
                endDate.min = startDate.value;

                if (
                    endDate.value &&
                    endDate.value < startDate.value
                ) {
                    endDate.value = '';
                }
            }
        }

        if (startDate) {
            startDate.addEventListener(
                'change',
                updateEndDateMinimum
            );

            updateEndDateMinimum();
        }

    });
</script>

@endsection