@extends('layouts.app')

@section('content')

<style>
    .driver-form-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        border: 1px solid #e9ecef;
    }

    .form-header {
        margin-bottom: 25px;
    }

    .form-header h1 {
        margin: 0;
        color: #1d3557;
    }

    .form-header p {
        color: #6c757d;
        margin-top: 6px;
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

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 7px;
        color: #343a40;
        font-size: 14px;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.10);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220,53,69,.10);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .field-error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 6px;
    }

    .form-actions {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .save-btn {
        background: #198754;
        color: white;
        border: none;
        padding: 11px 22px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .save-btn:hover {
        background: #157347;
    }

    .error-box {
        background: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .error-box strong {
        display: block;
        margin-bottom: 6px;
    }

    .error-box ul {
        margin: 0;
        padding-left: 20px;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }
    }
</style>

<div class="driver-form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Add Driver</h1>
            <p>Create a new driver profile</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <strong>Please fix the following:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

       <form action="{{ route('drivers.store') }}" method="POST" novalidate>
            @csrf

            <div class="form-grid">

                {{-- Driver Code --}}

                <div class="form-group">
                    <label>
                        Driver ID / Code <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="driver_code"
                        value="{{ old('driver_code') }}"
                        class="form-control @error('driver_code') is-invalid @enderror"
                        required
                    >

                    @error('driver_code')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Name --}}

                <div class="form-group">
                    <label>
                        Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="driver_name"
                        value="{{ old('driver_name') }}"
                        class="form-control @error('driver_name') is-invalid @enderror"
                        minlength="2"
                        maxlength="150"
                        required
                    >

                    @error('driver_name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mobile --}}

                <div class="form-group">
                    <label>Mobile</label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-control @error('phone') is-invalid @enderror"
                    >

                    @error('phone')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nationality --}}

                <div class="form-group">
                    <label>Nationality</label>

                    <input
                        type="text"
                        name="nationality"
                        value="{{ old('nationality') }}"
                        class="form-control @error('nationality') is-invalid @enderror"
                        placeholder="Enter nationality"
                    >

                    @error('nationality')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Passport --}}

                <div class="form-group">
                    <label>Passport Number</label>

                    <input
                        type="text"
                        name="passport_number"
                        value="{{ old('passport_number') }}"
                        class="form-control @error('passport_number') is-invalid @enderror"
                    >

                    @error('passport_number')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Passport Held --}}

                <div class="form-group">
                    <label>
                        Passport Held by Company <span class="required">*</span>
                    </label>

                    <select
                        name="passport_held_by_company"
                        class="form-control @error('passport_held_by_company') is-invalid @enderror"
                        required
                    >
                        <option value="0"
                            {{ old('passport_held_by_company', '0') == '0' ? 'selected' : '' }}>
                            No
                        </option>

                        <option value="1"
                            {{ old('passport_held_by_company') == '1' ? 'selected' : '' }}>
                            Yes
                        </option>
                    </select>

                    @error('passport_held_by_company')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Visa Type --}}

                <div class="form-group">
                    <label>Visa Type</label>

                    <select
                        name="visa_type"
                        class="form-control @error('visa_type') is-invalid @enderror"
                    >
                        <option value="">Select Visa Type</option>

                        <option value="Temporary"
                            {{ old('visa_type') == 'Temporary' ? 'selected' : '' }}>
                            Temporary
                        </option>

                        <option value="Permanent"
                            {{ old('visa_type') == 'Permanent' ? 'selected' : '' }}>
                            Permanent
                        </option>

                        <option value="Other"
                            {{ old('visa_type') == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>

                    @error('visa_type')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Visa Provided By --}}

                <div class="form-group">
                    <label>Visa Provided By</label>

                    <select
                        name="visa_provided_by"
                        class="form-control @error('visa_provided_by') is-invalid @enderror"
                    >
                        <option value="">Select</option>

                        <option value="Company"
                            {{ old('visa_provided_by') == 'Company' ? 'selected' : '' }}>
                            Company
                        </option>

                        <option value="Sponsor"
                            {{ old('visa_provided_by') == 'Sponsor' ? 'selected' : '' }}>
                            Sponsor
                        </option>

                        <option value="Other"
                            {{ old('visa_provided_by') == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>

                    @error('visa_provided_by')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Visa Expiry --}}

                <div class="form-group">
                    <label>Visa Expiry</label>

                    <input
                        type="date"
                        name="visa_expiry"
                        value="{{ old('visa_expiry') }}"
                        class="form-control @error('visa_expiry') is-invalid @enderror"
                    >

                    @error('visa_expiry')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Emirates ID --}}

                <div class="form-group">
                    <label>Emirates ID</label>

                    <input
                        type="text"
                        name="emirates_id"
                        value="{{ old('emirates_id') }}"
                        class="form-control @error('emirates_id') is-invalid @enderror"
                    >

                    @error('emirates_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Licence Number --}}

                <div class="form-group">
                    <label>Licence Number</label>

                    <input
                        type="text"
                        name="license_number"
                        value="{{ old('license_number') }}"
                        class="form-control @error('license_number') is-invalid @enderror"
                    >

                    @error('license_number')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Licence Expiry --}}

                <div class="form-group">
                    <label>Licence Expiry</label>

                    <input
                        type="date"
                        name="license_expiry"
                        value="{{ old('license_expiry') }}"
                        class="form-control @error('license_expiry') is-invalid @enderror"
                    >

                    @error('license_expiry')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Basic Salary --}}

                <div class="form-group">
                    <label>
                        Basic Salary <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="basic_salary"
                        value="{{ old('basic_salary', 0) }}"
                        class="form-control @error('basic_salary') is-invalid @enderror"
                        min="0"
                        step="0.01"
                        required
                    >

                    @error('basic_salary')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Assigned Vehicle --}}

                <div class="form-group">
                    <label>Assigned Vehicle</label>

                    <select
                        name="assigned_vehicle_id"
                        class="form-control @error('assigned_vehicle_id') is-invalid @enderror"
                    >
                        <option value="">Select Vehicle</option>

                        @foreach($vehicles as $vehicle)
                            <option
                                value="{{ $vehicle->id }}"
                                {{ old('assigned_vehicle_id') == $vehicle->id ? 'selected' : '' }}
                            >
                                {{ $vehicle->plate_number }}
                            </option>
                        @endforeach
                    </select>

                    @error('assigned_vehicle_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Employment Status --}}

                <div class="form-group">
                    <label>
                        Employment Status <span class="required">*</span>
                    </label>

                    <select
                        name="employment_status"
                        class="form-control @error('employment_status') is-invalid @enderror"
                        required
                    >
                        <option value="Active"
                            {{ old('employment_status', 'Active') == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="On Leave"
                            {{ old('employment_status') == 'On Leave' ? 'selected' : '' }}>
                            On Leave
                        </option>

                        <option value="Inactive"
                            {{ old('employment_status') == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="Archived"
                            {{ old('employment_status') == 'Archived' ? 'selected' : '' }}>
                            Archived
                        </option>
                    </select>

                    @error('employment_status')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remarks --}}

                <div class="form-group full">
                    <label>Remarks</label>

                    <textarea
                        name="remarks"
                        class="form-control @error('remarks') is-invalid @enderror"
                    >{{ old('remarks') }}</textarea>

                    @error('remarks')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CNIC --}}

                <div class="form-group">
                    <label>
                        CNIC <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="cnic"
                        value="{{ old('cnic') }}"
                        class="form-control @error('cnic') is-invalid @enderror"
                        required
                    >

                    @error('cnic')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Address --}}

                <div class="form-group">
                    <label>Address</label>

                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        class="form-control @error('address') is-invalid @enderror"
                    >

                    @error('address')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Date of Birth --}}

                <div class="form-group">
                    <label>Date of Birth</label>

                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                    max="{{ now()->subYears(18)->toDateString() }}"
                        class="form-control @error('date_of_birth') is-invalid @enderror"
                    >

                    @error('date_of_birth')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Joining Date --}}

                <div class="form-group">
                    <label>Joining Date</label>

                    <input
                        type="date"
                        name="joining_date"
                        value="{{ old('joining_date') }}"
                        class="form-control @error('joining_date') is-invalid @enderror"
                    >

                    @error('joining_date')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}

                <div class="form-group">
                    <label>Status</label>

                    <select
                        name="status"
                        class="form-control @error('status') is-invalid @enderror"
                    >
                        <option value="Active"
                            {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="Inactive"
                            {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Notes --}}

                <div class="form-group full">
                    <label>Notes</label>

                    <textarea
                        name="notes"
                        class="form-control @error('notes') is-invalid @enderror"
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">
                    Save Driver
                </button>
            </div>

        </form>

    </div>

</div>

@endsection