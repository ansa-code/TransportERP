@extends('layouts.app')

@section('content')

<style>

    .advance-form-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        color: #172b4d;
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .page-subtitle {
        color: #718096;
        font-size: 14px;
        line-height: 1.6;
    }

    .form-card {
        background: white;
        border: 1px solid #e6ebf2;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 5px 18px rgba(18, 38, 63, .06);
    }

    .section-title {
        color: #172b4d;
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .required {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        min-height: 43px;
        border: 1px solid #d9e1eb;
        border-radius: 9px;
        padding: 9px 12px;
        background: white;
        color: #1e293b;
        outline: none;
        font-size: 13px;
    }

    textarea.form-control {
        min-height: 105px;
        resize: vertical;
    }

    .form-control:focus {
        border-color: #087cff;
        box-shadow: 0 0 0 3px rgba(8, 124, 255, .10);
    }

    .help-text {
        color: #94a3b8;
        font-size: 11px;
    }

    .error-text {
        color: #dc2626;
        font-size: 11px;
        font-weight: 600;
    }

    .status-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 11px 13px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.6;
    }

    .button-area {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #edf1f5;
    }

    .btn {
        min-height: 42px;
        padding: 0 18px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
    }

    .cancel-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .cancel-btn:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .save-btn {
        background: #087cff;
        color: white;
    }

    .save-btn:hover {
        background: #0668d8;
        color: white;
    }

    @media (max-width: 700px) {

        .form-card {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: auto;
        }

        .button-area {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
        }

    }

</style>


<div class="advance-form-page">

    <div class="page-header">

        <div class="page-title">
            Add Driver Advance
        </div>

        <div class="page-subtitle">
            Create a new driver advance record and set its approval status.
        </div>

    </div>


    <div class="form-card">

        <div class="section-title">
            Advance Information
        </div>

        <form
            method="POST"
            action="{{ route('driver-advances.store') }}"
        >

            @csrf

            <div class="form-grid">


                <div class="form-group">

                    <label class="form-label">
                        Driver <span class="required">*</span>
                    </label>

                    <select
                        name="driver_id"
                        class="form-control"
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
                                    - {{ $driver->driver_code }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                    @error('driver_id')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Advance Date <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="advance_date"
                        value="{{ old('advance_date', date('Y-m-d')) }}"
                        class="form-control"
                        required
                    >

                    @error('advance_date')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Amount <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="amount"
                        value="{{ old('amount') }}"
                        class="form-control"
                        min="0.01"
                        step="0.01"
                        placeholder="Enter advance amount"
                        required
                    >

                    <div class="help-text">
                        Enter the total advance amount in AED.
                    </div>

                    @error('amount')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Status <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-control"
                        required
                    >

                        <option
                            value="Pending"
                            {{ old('status', 'Pending') === 'Pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="Approved"
                            {{ old('status') === 'Approved' ? 'selected' : '' }}
                        >
                            Approved
                        </option>

                        <option
                            value="Rejected"
                            {{ old('status') === 'Rejected' ? 'selected' : '' }}
                        >
                            Rejected
                        </option>

                    </select>

                    @error('status')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Approved By
                    </label>

                    <select
                        name="approved_by"
                        id="approved_by"
                        class="form-control"
                    >

                        <option value="">
                            Select Approver
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                {{ old('approved_by') == $user->id ? 'selected' : '' }}
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                    <div class="help-text">
                        Required when Status is Approved.
                    </div>

                    @error('approved_by')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Advance Number
                    </label>

                    <div class="status-info">
                        Advance number will be generated automatically as
                        <strong>AST-ADV-YYYY-#####</strong>.
                    </div>

                </div>


                <div class="form-group full-width">

                    <label class="form-label">
                        Reason
                    </label>

                    <textarea
                        name="reason"
                        class="form-control"
                        placeholder="Enter reason for the driver advance"
                    >{{ old('reason') }}</textarea>

                    @error('reason')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group full-width">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        class="form-control"
                        placeholder="Enter any additional remarks"
                    >{{ old('remarks') }}</textarea>

                    @error('remarks')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>


            </div>


            <div class="button-area">

                <a
                    href="{{ route('driver-advances.index') }}"
                    class="btn cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn save-btn"
                >
                    Save Driver Advance
                </button>

            </div>


        </form>

    </div>

</div>


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const status = document.getElementById('status');
        const approvedBy = document.getElementById('approved_by');

        function updateApproverState() {

            if (status.value === 'Approved') {

                approvedBy.required = true;

            } else {

                approvedBy.required = false;

            }

        }

        status.addEventListener('change', updateApproverState);

        updateApproverState();

    });

</script>

@endsection