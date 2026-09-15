@extends('layouts.app')

@section('content')

<style>
    .vehicle-form-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .vehicle-form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        border: 1px solid #e9ecef;
    }

    .vehicle-form-header {
        margin-bottom: 25px;
    }

    .vehicle-form-header h1 {
        margin: 0;
        color: #1d3557;
        font-size: 28px;
    }

    .vehicle-form-header p {
        margin: 6px 0 0;
        color: #6c757d;
        font-size: 14px;
    }

    .error-box {
        background: #f8d7da;
        color: #842029;
        padding: 12px 16px;
        border-radius: 7px;
        margin-bottom: 20px;
        border: 1px solid #f1aeb5;
    }

    .error-box ul {
        margin: 0;
        padding-left: 20px;
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
        background: white;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.10);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .input-row {
        display: grid;
        grid-template-columns: 1fr 150px;
        gap: 10px;
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

    .update-btn:hover {
        background: #0b5ed7;
    }

    .cancel-btn {
        background: #6c757d;
        color: white;
        text-decoration: none;
        padding: 11px 20px;
        border-radius: 7px;
        font-weight: bold;
    }

    .cancel-btn:hover {
        background: #5c636a;
        color: white;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .input-row {
            grid-template-columns: 1fr;
        }
    }
</style>


<div class="vehicle-form-page">

    <div class="vehicle-form-card">

        <div class="vehicle-form-header">

            <h1>Edit Vehicle</h1>

            <p>Update vehicle profile, ownership and fleet information.</p>

        </div>


        @if ($errors->any())

            <div class="error-box">

                <strong>Please fix the following errors:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        <form
            action="{{ route('vehicles.update', $vehicle->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="form-grid">


                {{-- Vehicle Code --}}

                <div class="form-group">

                    <label>
                        Vehicle ID / Code <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="vehicle_code"
                        value="{{ old('vehicle_code', $vehicle->vehicle_code) }}"
                        class="form-control"
                        required
                    >

                </div>


                {{-- Vehicle Type --}}

                <div class="form-group">

                    <label>
                        Vehicle Type <span class="required">*</span>
                    </label>

                    <select
                        name="vehicle_type"
                        class="form-control"
                        required
                    >

                        <option value="">Select Vehicle Type</option>

                        <option value="Heavy Truck"
                            {{ old('vehicle_type', $vehicle->vehicle_type) == 'Heavy Truck' ? 'selected' : '' }}>
                            Heavy Truck
                        </option>

                        <option value="Small Truck"
                            {{ old('vehicle_type', $vehicle->vehicle_type) == 'Small Truck' ? 'selected' : '' }}>
                            Small Truck
                        </option>

                        <option value="Van"
                            {{ old('vehicle_type', $vehicle->vehicle_type) == 'Van' ? 'selected' : '' }}>
                            Van
                        </option>

                        <option value="Pickup"
                            {{ old('vehicle_type', $vehicle->vehicle_type) == 'Pickup' ? 'selected' : '' }}>
                            Pickup
                        </option>

                        <option value="Trailer"
                            {{ old('vehicle_type', $vehicle->vehicle_type) == 'Trailer' ? 'selected' : '' }}>
                            Trailer
                        </option>

                        <option value="Other"
                            {{ old('vehicle_type', $vehicle->vehicle_type) == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                </div>


                {{-- Brand --}}

                <div class="form-group">

                    <label>Brand</label>

                    <input
                        type="text"
                        name="brand"
                        value="{{ old('brand', $vehicle->brand) }}"
                        class="form-control"
                        maxlength="100"
                    >

                </div>


                {{-- Model --}}

                <div class="form-group">

                    <label>Model</label>

                    <input
                        type="text"
                        name="model"
                        value="{{ old('model', $vehicle->model) }}"
                        class="form-control"
                        maxlength="100"
                    >

                </div>


                {{-- Plate Number --}}

                <div class="form-group">

                    <label>
                        Plate Number <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="plate_number"
                        value="{{ old('plate_number', $vehicle->plate_number) }}"
                        class="form-control"
                        required
                    >

                </div>


                {{-- Category --}}

                <div class="form-group">

                    <label>Category</label>

                    <input
                        type="text"
                        name="category"
                        value="{{ old('category', $vehicle->category) }}"
                        class="form-control"
                    >

                </div>


                {{-- Load Capacity --}}

                <div class="form-group">

                    <label>Load Capacity</label>

                    <div class="input-row">

                        <input
                            type="number"
                            name="load_capacity"
                            value="{{ old('load_capacity', $vehicle->load_capacity) }}"
                            class="form-control"
                            min="0"
                            step="0.01"
                        >

                        <select
                            name="load_capacity_unit"
                            class="form-control"
                        >

                            <option value="">Unit</option>

                            <option value="KG"
                                {{ old('load_capacity_unit', $vehicle->load_capacity_unit) == 'KG' ? 'selected' : '' }}>
                                KG
                            </option>

                            <option value="TON"
                                {{ old('load_capacity_unit', $vehicle->load_capacity_unit) == 'TON' ? 'selected' : '' }}>
                                Ton
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Ownership Type --}}

                <div class="form-group">

                    <label>
                        Ownership Type <span class="required">*</span>
                    </label>

                    <select
                        name="ownership_type"
                        id="ownership_type"
                        class="form-control"
                        required
                    >

                        <option value="">Select Ownership Type</option>

                        <option value="Company Owned"
                            {{ old('ownership_type', $vehicle->ownership_type) == 'Company Owned' ? 'selected' : '' }}>
                            Company Owned
                        </option>

                        <option value="Hired"
                            {{ old('ownership_type', $vehicle->ownership_type) == 'Hired' ? 'selected' : '' }}>
                            Hired
                        </option>

                        <option value="Financed"
                            {{ old('ownership_type', $vehicle->ownership_type) == 'Financed' ? 'selected' : '' }}>
                            Financed
                        </option>

                        <option value="Other"
                            {{ old('ownership_type', $vehicle->ownership_type) == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                </div>


                {{-- Vendor / Owner --}}

                <div class="form-group">

                    <label>
                        Vendor / Owner
                        <span
                            id="vendor-required"
                            class="required"
                            style="display:none;"
                        >*</span>
                    </label>

                    <input
                        type="text"
                        name="vendor_owner"
                        id="vendor_owner"
                        value="{{ old('vendor_owner', $vehicle->vendor_owner) }}"
                        class="form-control"
                    >

                </div>


                {{-- Registered Company Name --}}

                <div class="form-group">

                    <label>Registered Company Name</label>

                    <input
                        type="text"
                        name="registered_company_name"
                        value="{{ old('registered_company_name', $vehicle->registered_company_name) }}"
                        class="form-control"
                    >

                </div>


                {{-- Bank Instalment Amount --}}

                <div class="form-group">

                    <label>
                        Bank Instalment Amount
                        <span
                            id="instalment-required"
                            class="required"
                            style="display:none;"
                        >*</span>
                    </label>

                    <input
                        type="number"
                        name="bank_instalment_amount"
                        id="bank_instalment_amount"
                        value="{{ old('bank_instalment_amount', $vehicle->bank_instalment_amount) }}"
                        class="form-control"
                        min="0"
                        step="0.01"
                    >

                </div>


                {{-- Instalment Duration --}}

                <div class="form-group">

                    <label>
                        Instalment Duration
                        <span
                            id="duration-required"
                            class="required"
                            style="display:none;"
                        >*</span>
                    </label>

                    <div class="input-row">

                        <input
                            type="number"
                            name="instalment_duration"
                            id="instalment_duration"
                            value="{{ old('instalment_duration', $vehicle->instalment_duration) }}"
                            class="form-control"
                            min="1"
                        >

                        <select
                            name="instalment_duration_unit"
                            class="form-control"
                        >

                            <option value="Months"
                                {{ old('instalment_duration_unit', $vehicle->instalment_duration_unit ?? 'Months') == 'Months' ? 'selected' : '' }}>
                                Months
                            </option>

                            <option value="Years"
                                {{ old('instalment_duration_unit', $vehicle->instalment_duration_unit) == 'Years' ? 'selected' : '' }}>
                                Years
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Registration Expiry --}}

                <div class="form-group">

                    <label>Registration Expiry</label>

                    <input
                        type="date"
                        name="registration_expiry"
                        value="{{ old('registration_expiry', $vehicle->registration_expiry) }}"
                        class="form-control"
                    >

                </div>


                {{-- Insurance Expiry --}}

                <div class="form-group">

                    <label>Insurance Expiry</label>

                    <input
                        type="date"
                        name="insurance_expiry"
                        value="{{ old('insurance_expiry', $vehicle->insurance_expiry) }}"
                        class="form-control"
                    >

                </div>


                {{-- Current Odometer --}}

                <div class="form-group">

                    <label>Current Odometer</label>

                    <input
                        type="number"
                        name="current_odometer"
                        value="{{ old('current_odometer', $vehicle->current_odometer) }}"
                        class="form-control"
                        min="0"
                        step="1"
                    >

                </div>


                {{-- Status --}}

                <div class="form-group">

                    <label>
                        Status <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-control"
                        required
                    >

                        <option value="Active"
                            {{ old('status', $vehicle->status) == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="Assigned"
                            {{ old('status', $vehicle->status) == 'Assigned' ? 'selected' : '' }}>
                            Assigned
                        </option>

                        <option value="Idle"
                            {{ old('status', $vehicle->status) == 'Idle' ? 'selected' : '' }}>
                            Idle
                        </option>

                        <option value="Maintenance"
                            {{ old('status', $vehicle->status) == 'Maintenance' ? 'selected' : '' }}>
                            Maintenance
                        </option>

                        <option value="Inactive"
                            {{ old('status', $vehicle->status) == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="Archived"
                            {{ old('status', $vehicle->status) == 'Archived' ? 'selected' : '' }}>
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
                    >{{ old('remarks', $vehicle->remarks) }}</textarea>

                </div>


            </div>


            <div class="form-actions">

                <button
                    type="submit"
                    class="update-btn"
                >
                    Update Vehicle
                </button>

                <a
                    href="{{ route('vehicles.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const ownershipType =
        document.getElementById('ownership_type');

    const vendorOwner =
        document.getElementById('vendor_owner');

    const vendorRequired =
        document.getElementById('vendor-required');

    const instalmentAmount =
        document.getElementById('bank_instalment_amount');

    const instalmentDuration =
        document.getElementById('instalment_duration');

    const instalmentRequired =
        document.getElementById('instalment-required');

    const durationRequired =
        document.getElementById('duration-required');


    function updateConditionalFields() {

        const value = ownershipType.value;


        // Hired vehicle
        if (value === 'Hired') {

            vendorOwner.required = true;
            vendorRequired.style.display = 'inline';

        } else {

            vendorOwner.required = false;
            vendorRequired.style.display = 'none';

        }


        // Financed vehicle
        if (value === 'Financed') {

            instalmentAmount.required = true;
            instalmentDuration.required = true;

            instalmentRequired.style.display = 'inline';
            durationRequired.style.display = 'inline';

        } else {

            instalmentAmount.required = false;
            instalmentDuration.required = false;

            instalmentRequired.style.display = 'none';
            durationRequired.style.display = 'none';

        }

    }


    ownershipType.addEventListener(
        'change',
        updateConditionalFields
    );


    updateConditionalFields();

});

</script>

@endsection