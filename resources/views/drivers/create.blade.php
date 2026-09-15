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

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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

        <form action="{{ route('drivers.store') }}" method="POST">

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
                        value="{{ old('driver_name') }}"
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
                        value="{{ old('phone') }}"
                        class="form-control"
                    >

                </div>


                {{-- Nationality --}}

                <div class="form-group">

                    <label>Nationality</label>

                    <input
                        type="text"
                        name="nationality"
                        value="{{ old('nationality') }}"
                        class="form-control"
                        placeholder="Enter nationality"
                    >

                </div>


                {{-- Passport --}}

                <div class="form-group">

                    <label>Passport Number</label>

                    <input
                        type="text"
                        name="passport_number"
                        value="{{ old('passport_number') }}"
                        class="form-control"
                    >

                </div>


                {{-- Passport Held --}}

                <div class="form-group">

                    <label>
                        Passport Held by Company <span class="required">*</span>
                    </label>

                    <select
                        name="passport_held_by_company"
                        class="form-control"
                        required
                    >

                        <option value="0">
                            No
                        </option>

                        <option value="1"
                            {{ old('passport_held_by_company') == '1' ? 'selected' : '' }}>
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
                        value="{{ old('visa_type') }}"
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

                </div>


                {{-- Visa Expiry --}}

                <div class="form-group">

                    <label>Visa Expiry</label>

                    <input
                        type="date"
                        name="visa_expiry"
                        value="{{ old('visa_expiry') }}"
                        class="form-control"
                    >

                </div>


                {{-- Emirates ID --}}

                <div class="form-group">

                    <label>Emirates ID</label>

                    <input
                        type="text"
                        name="emirates_id"
                        value="{{ old('emirates_id') }}"
                        class="form-control"
                    >

                </div>


                {{-- Licence Number --}}

                <div class="form-group">

                    <label>Licence Number</label>

                    <input
                        type="text"
                        name="license_number"
                        value="{{ old('license_number') }}"
                        class="form-control"
                    >

                </div>


                {{-- Licence Expiry --}}

                <div class="form-group">

                    <label>Licence Expiry</label>

                    <input
                        type="date"
                        name="license_expiry"
                        value="{{ old('license_expiry') }}"
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
                        value="{{ old('basic_salary', 0) }}"
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
                        value="{{ old('assigned_vehicle_id') }}"
                        class="form-control"
                        placeholder="Vehicle ID"
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

                </div>


                {{-- Remarks --}}

                <div class="form-group full">

                    <label>Remarks</label>

                    <textarea
                        name="remarks"
                        class="form-control"
                    >{{ old('remarks') }}</textarea>

                </div>


                {{-- Old fields retained --}}

                <div class="form-group">

                    <label>CNIC</label>

                    <input
                        type="text"
                        name="cnic"
                        value="{{ old('cnic') }}"
                        class="form-control"
                    >

                </div>


                <div class="form-group">

                    <label>Address</label>

                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        class="form-control"
                    >

                </div>


                <div class="form-group">

                    <label>Date of Birth</label>

                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        class="form-control"
                    >

                </div>


                <div class="form-group">

                    <label>Joining Date</label>

                    <input
                        type="date"
                        name="joining_date"
                        value="{{ old('joining_date') }}"
                        class="form-control"
                    >

                </div>


                <div class="form-group">

                    <label>Status</label>

                    <select
                        name="status"
                        class="form-control"
                    >

                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                    </select>

                </div>


                <div class="form-group full">

                    <label>Notes</label>

                    <textarea
                        name="notes"
                        class="form-control"
                    >{{ old('notes') }}</textarea>

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
