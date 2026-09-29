@extends('layouts.app')

@section('content')

<style>
    .driver-form-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .driver-form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        border: 1px solid #e9ecef;
    }

    .driver-form-header {
        margin-bottom: 25px;
    }

    .driver-form-header h1 {
        margin: 0;
        color: #1d3557;
        font-size: 28px;
    }

    .driver-form-header p {
        margin-top: 6px;
        color: #6c757d;
        font-size: 14px;
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

    .form-group label {
        margin-bottom: 7px;
        font-weight: 600;
        color: #343a40;
        font-size: 14px;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #ced4da;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
    }

    .form-control:focus {
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

    .form-actions {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 10px;
    }

    .update-btn {
        background: #0d6efd;
        color: white;
        border: none;
        padding: 11px 20px;
        border-radius: 7px;
        font-weight: bold;
        cursor: pointer;
    }

    .cancel-btn {
        background: #6c757d;
        color: white;
        text-decoration: none;
        padding: 11px 20px;
        border-radius: 7px;
        font-weight: bold;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }
    }
</style>


<div class="driver-form-page">

    <div class="driver-form-card">

        <div class="driver-form-header">

            <h1>Edit Driver</h1>

            <p>Update driver profile and employment information.</p>

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


        <form action="{{ route('drivers.update', $driver->id) }}" method="POST">

            @csrf
            @method('PUT')


            <div class="form-grid">


                {{-- Driver ID / Code --}}

                <div class="form-group">

                    <label>
                        Driver ID / Code <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="driver_code"
                        value="{{ old('driver_code', $driver->driver_code) }}"
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
                        value="{{ old('driver_name', $driver->driver_name) }}"
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
                        value="{{ old('phone', $driver->phone) }}"
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
                        value="{{ old('nationality', $driver->nationality) }}"
                        class="form-control @error('nationality') is-invalid @enderror"
                    >

                    @error('nationality')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                </div>


                {{-- Passport Number --}}

                <div class="form-group">

                    <label>Passport Number</label>

                    <input
                        type="text"
                        name="passport_number"
                        value="{{ old('passport_number', $driver->passport_number) }}"
                        class="form-control @error('passport_number') is-invalid @enderror"
                    >

                    @error('passport_number')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                </div>


                {{-- Passport Held by Company --}}

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
                            {{ old('passport_held_by_company', $driver->passport_held_by_company) == 0 ? 'selected' : '' }}>
                            No
                        </option>

                        <option value="1"
                            {{ old('passport_held_by_company', $driver->passport_held_by_company) == 1 ? 'selected' : '' }}>
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

                    <input
                        type="text"
                        name="visa_type"
                        value="{{ old('visa_type', $driver->visa_type) }}"
                        class="form-control @error('visa_type') is-invalid @enderror"
                    >

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
                            {{ old('visa_provided_by', $driver->visa_provided_by) == 'Company' ? 'selected' : '' }}>
                            Company
                        </option>

                        <option value="Sponsor"
                            {{ old('visa_provided_by', $driver->visa_provided_by) == 'Sponsor' ? 'selected' : '' }}>
                            Sponsor
                        </option>

                        <option value="Other"
                            {{ old('visa_provided_by', $driver->visa_provided_by) == 'Other' ? 'selected' : '' }}>
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
                        value="{{ old('visa_expiry', $driver->visa_expiry) }}"
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
                        value="{{ old('emirates_id', $driver->emirates_id) }}"
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
                        value="{{ old('license_number', $driver->license_number) }}"
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
                        value="{{ old('license_expiry', $driver->license_expiry) }}"
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
                        step="0.01"
                        name="basic_salary"
                        value="{{ old('basic_salary', $driver->basic_salary) }}"
                        class="form-control @error('basic_salary') is-invalid @enderror"
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
                                {{ old('assigned_vehicle_id', $driver->assigned_vehicle_id) == $vehicle->id ? 'selected' : '' }}
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
                        <option value="">Select Employment Status</option>

                        <option
                            value="Active"
                            {{ old('employment_status', $driver->employment_status) == 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="On Leave"
                            {{ old('employment_status', $driver->employment_status) == 'On Leave' ? 'selected' : '' }}
                        >
                            On Leave
                        </option>

                        <option
                            value="Inactive"
                            {{ old('employment_status', $driver->employment_status) == 'Inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                        <option
                            value="Archived"
                            {{ old('employment_status', $driver->employment_status) == 'Archived' ? 'selected' : '' }}
                        >
                            Archived
                        </option>
                    </select>

                    @error('employment_status')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remarks --}}
                <div class="form-group">
                    <label>Remarks</label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control @error('remarks') is-invalid @enderror"
                    >{{ old('remarks', $driver->remarks) }}</textarea>

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
                        value="{{ old('cnic', $driver->cnic) }}"
                        class="form-control @error('cnic') is-invalid @enderror"
                        required
                    >

                    @error('cnic')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">

    <a href="{{ route('drivers.index') }}" class="cancel-btn">
        Cancel
    </a>

    <button type="submit" class="update-btn">
        Update Driver
    </button>

</div>

        </form>
    </div>
</div>

@endsection