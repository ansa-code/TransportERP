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

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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
                        class="form-control"
                        required
                    >

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
                        class="form-control"
                        minlength="2"
                        maxlength="150"
                        required
                    >

                </div>


                {{-- Mobile --}}

                <div class="form-group">

                    <label>Mobile</label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $driver->phone) }}"
                        class="form-control"
                    >

                </div>


                {{-- Nationality --}}

                <div class="form-group">

                    <label>Nationality</label>

                    <input
                        type="text"
                        name="nationality"
                        value="{{ old('nationality', $driver->nationality) }}"
                        class="form-control"
                    >

                </div>


                {{-- Passport Number --}}

                <div class="form-group">

                    <label>Passport Number</label>

                    <input
                        type="text"
                        name="passport_number"
                        value="{{ old('passport_number', $driver->passport_number) }}"
                        class="form-control"
                    >

                </div>


                {{-- Passport Held by Company --}}

                <div class="form-group">

                    <label>
                        Passport Held by Company <span class="required">*</span>
                    </label>

                    <select
                        name="passport_held_by_company"
                        class="form-control"
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

                </div>


                {{-- Visa Type --}}

                <div class="form-group">

                    <label>Visa Type</label>

                    <input
                        type="text"
                        name="visa_type"
                        value="{{ old('visa_type', $driver->visa_type) }}"
                        class="form-control"
                    >

                </div>


                {{-- Visa Provided By --}}

                <div class="form-group">

                    <label>Visa Provided By</label>

                    <select
                        name="visa_provided_by"
                        class="form-control"
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

                </div>


                {{-- Visa Expiry --}}

                <div class="form-group">

                    <label>Visa Expiry</label>

                    <input
                        type="date"
                        name="visa_expiry"
                        value="{{ old('visa_expiry', $driver->visa_expiry) }}"
                        class="form-control"
                    >

                </div>


                {{-- Emirates ID --}}

                <div class="form-group">

                    <label>Emirates ID</label>

                    <input
                        type="text"
                        name="emirates_id"
                        value="{{ old('emirates_id', $driver->emirates_id) }}"
                        class="form-control"
                    >

                </div>


                {{-- Licence Number --}}

                <div class="form-group">

                    <label>Licence Number</label>

                    <input
                        type="text"
                        name="license_number"
                        value="{{ old('license_number', $driver->license_number) }}"
                        class="form-control"
                    >

                </div>


                {{-- Licence Expiry --}}

                <div class="form-group">

                    <label>Licence Expiry</label>

                    <input
                        type="date"
                        name="license_expiry"
                        value="{{ old('license_expiry', $driver->license_expiry) }}"
                        class="form-control"
                    >

                </div>


                {{-- Basic Salary --}}

                <div class="form-group">

                    <label>
                        Basic Salary <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="basic_salary"
                        value="{{ old('basic_salary', $driver->basic_salary) }}"
                        class="form-control"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>


                {{-- Assigned Vehicle --}}

                <div class="form-group">

                    <label>Assigned Vehicle</label>

                    <input
                        type="number"
                        name="assigned_vehicle_id"
                        value="{{ old('assigned_vehicle_id', $driver->assigned_vehicle_id) }}"
                        class="form-control"
                        min="1"
                    >

                </div>


                {{-- Employment Status --}}

                <div class="form-group">

                    <label>
                        Employment Status <span class="required">*</span>
                    </label>

                    <select
                        name="employment_status"
                        class="form-control"
                        required
                    >

                        <option value="Active"
                            {{ old('employment_status', $driver->employment_status) == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="On Leave"
                            {{ old('employment_status', $driver->employment_status) == 'On Leave' ? 'selected' : '' }}>
                            On Leave
                        </option>

                        <option value="Inactive"
                            {{ old('employment_status', $driver->employment_status) == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="Archived"
                            {{ old('employment_status', $driver->employment_status) == 'Archived' ? 'selected' : '' }}>
                            Archived
                        </option>

                    </select>

                </div>


                {{-- Remarks --}}

                <div class="form-group full-width">

                    <label>Remarks</label>

                    <textarea
                        name="remarks"
                        class="form-control"
                        rows="4"
                    >{{ old('remarks', $driver->remarks) }}</textarea>

                </div>


            </div>


            <div class="form-actions">

                <button type="submit" class="update-btn">
                    Update Driver
                </button>

                <a href="{{ route('drivers.index') }}" class="cancel-btn">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection