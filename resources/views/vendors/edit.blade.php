@extends('layouts.app')

@section('content')

<style>
    .vendor-form-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #172554;
        font-size: 27px;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #ffffff;
        color: #334155;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s ease;
        flex-shrink: 0;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #172554;
    }

    .error-box {
        margin-bottom: 18px;
        padding: 14px 16px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 13px;
    }

    .error-box strong {
        display: block;
        margin-bottom: 6px;
    }

    .error-box ul {
        margin: 0;
        padding-left: 18px;
    }

    .error-box li {
        margin-bottom: 3px;
    }

    .vendor-form {
        width: 100%;
    }

    .form-card {
        margin-bottom: 18px;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.04);
    }

    .card-header {
        margin-bottom: 18px;
        padding-bottom: 13px;
        border-bottom: 1px solid #eef2f7;
    }

    .card-header h2 {
        margin: 0 0 4px;
        color: #172554;
        font-size: 17px;
        font-weight: 700;
    }

    .card-header p {
        margin: 0;
        color: #64748b;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .form-group {
        min-width: 0;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
    }

    .form-group label span {
        color: #dc2626;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        outline: none;
        background: #ffffff;
        color: #1e293b;
        font-family: inherit;
        font-size: 13px;
        transition: 0.2s ease;
    }

    .form-group input,
    .form-group select {
        height: 38px;
        padding: 0 11px;
    }

    .form-group textarea {
        min-height: 82px;
        padding: 10px 11px;
        resize: vertical;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #94a3b8;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #315f8f;
        box-shadow: 0 0 0 2px rgba(49, 95, 143, 0.10);
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 11px;
    }

    /* =========================
       MULTI-SELECT DROPDOWNS
    ========================== */

    .multi-dropdown {
        position: relative;
        width: 100%;
    }

    .multi-dropdown-button {
        width: 100%;
        height: 38px;
        padding: 0 11px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
        color: #334155;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        box-sizing: border-box;
        text-align: left;
    }

    .multi-dropdown-button:hover {
        border-color: #94a3b8;
    }

    .multi-dropdown.open .multi-dropdown-button {
        border-color: #315f8f;
        box-shadow: 0 0 0 2px rgba(49, 95, 143, 0.10);
    }

    .dropdown-placeholder {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
    }

    .dropdown-arrow {
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid #64748b;
        flex-shrink: 0;
        transition: transform 0.15s ease;
    }

    .multi-dropdown.open .dropdown-arrow {
        transform: rotate(180deg);
    }

    .multi-dropdown-menu {
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        z-index: 1000;
        display: none;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.14);
        overflow: hidden;
    }

    .multi-dropdown.open .multi-dropdown-menu {
        display: block;
    }

    .dropdown-options {
        max-height: 175px;
        overflow-y: auto;
        padding: 6px;
    }

    .dropdown-option {
        display: flex;
        align-items: center;
        gap: 9px;
        min-height: 32px;
        padding: 5px 8px;
        border-radius: 6px;
        cursor: pointer;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
    }

    .dropdown-option:hover {
        background: #f1f5f9;
    }

    .dropdown-option input {
        width: 16px !important;
        height: 16px !important;
        margin: 0;
        padding: 0 !important;
        accent-color: #2563eb;
        cursor: pointer;
        flex-shrink: 0;
    }

    .dropdown-footer {
        padding: 7px 10px;
        border-top: 1px solid #e5e7eb;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
    }

    /* Vehicle Search */

    .vehicle-search-wrap {
        padding: 7px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .vehicle-search {
        width: 100% !important;
        height: 34px !important;
        padding: 0 10px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        color: #1e293b !important;
        font-size: 13px !important;
        outline: none !important;
        box-sizing: border-box !important;
    }

    .vehicle-search:focus {
        border-color: #315f8f !important;
        box-shadow: 0 0 0 2px rgba(49, 95, 143, 0.10) !important;
    }

    .vehicle-option.hidden {
        display: none;
    }

    .vehicle-empty {
        padding: 12px 8px;
        color: #64748b;
        font-size: 12px;
        text-align: center;
    }

    /* =========================
       FORM ACTIONS
    ========================== */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 4px;
        margin-bottom: 20px;
    }

    .cancel-btn,
    .update-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: auto !important;
        min-width: 78px;
        min-height: 38px;
        height: 38px;
        padding: 0 16px !important;
        box-sizing: border-box;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        flex: 0 0 auto !important;
    }

    .cancel-btn {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
    }

    .cancel-btn:hover {
        background: #f8fafc;
        color: #334155;
    }

    .update-btn {
        border: 1px solid #2563eb;
        background: #2563eb;
        color: #ffffff;
    }

    .update-btn:hover {
        border-color: #1d4ed8;
        background: #1d4ed8;
        color: #ffffff;
    }

    @media (max-width: 700px) {

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .back-btn {
            width: auto;
        }

        .form-card {
            padding: 16px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: auto;
        }

        .form-actions {
            justify-content: flex-end;
        }
    }
</style>

<div class="vendor-form-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h1>Edit Vendor</h1>
            <p>Update vendor information, rates and payment terms.</p>
        </div>

        <a href="{{ route('vendors.index') }}" class="back-btn">
            ← Back
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
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


    @php
        $selectedServices = old('service_type');

        if ($selectedServices === null) {
            $selectedServices = $vendor->service_type
                ? array_map('trim', explode(',', $vendor->service_type))
                : [];
        } elseif (!is_array($selectedServices)) {
            $selectedServices = [$selectedServices];
        }

        $selectedVehicles = old('supplied_vehicle_plates');

        if ($selectedVehicles === null) {
            $selectedVehicles = $vendor->supplied_vehicle_plates
                ? array_map('trim', explode(',', $vendor->supplied_vehicle_plates))
                : [];
        } elseif (!is_array($selectedVehicles)) {
            $selectedVehicles = [$selectedVehicles];
        }
    @endphp


    <form
        action="{{ route('vendors.update', $vendor->id) }}"
        method="POST"
        class="vendor-form"
    >

        @csrf
        @method('PUT')


        {{-- =========================
             VENDOR INFORMATION
        ========================== --}}

        <div class="form-card">

            <div class="card-header">

                <div>
                    <h2>Vendor Information</h2>
                    <p>Basic vendor and supplied service information.</p>
                </div>

            </div>


            <div class="form-grid">

                {{-- Vendor Name --}}
                <div class="form-group">

                    <label for="vendor_name">
                        Vendor Name <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="vendor_name"
                        name="vendor_name"
                        value="{{ old('vendor_name', $vendor->vendor_name) }}"
                        placeholder="Enter vendor name"
                        required
                    >

                </div>


                {{-- Company Name --}}
                <div class="form-group">

                    <label for="company_name">
                        Company Name
                    </label>

                    <input
                        type="text"
                        id="company_name"
                        name="company_name"
                        value="{{ old('company_name', $vendor->company_name) }}"
                    >

                </div>


                {{-- Phone --}}
                <div class="form-group">

                    <label for="phone">
                        Phone
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $vendor->phone) }}"
                        placeholder="Enter phone number"
                    >

                </div>


                {{-- Contact --}}
                <div class="form-group">

                    <label for="contact">
                        Contact
                    </label>

                    <input
                        type="text"
                        id="contact"
                        name="contact"
                        value="{{ old('contact', $vendor->contact) }}"
                        placeholder="Contact person / alternate phone"
                    >

                </div>


                {{-- Email --}}
                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $vendor->email) }}"
                        placeholder="Enter email address"
                    >

                </div>


                {{-- Service Type --}}
                <div class="form-group">

                    <label>
                        Service Type <span>*</span>
                    </label>

                    <div
                        class="multi-dropdown"
                        id="serviceDropdown"
                    >

                        <button
                            type="button"
                            class="multi-dropdown-button"
                            id="serviceDropdownButton"
                        >
                            <span
                                class="dropdown-placeholder"
                                id="serviceDropdownText"
                            >
                                Select service types
                            </span>

                            <span class="dropdown-arrow"></span>
                        </button>


                        <div class="multi-dropdown-menu">

                            <div class="dropdown-options">

                                @foreach ([
                                    'Vehicle Maintenance',
                                    'Tyre Services',
                                    'Towing & Recovery',
                                    'Spare Parts',
                                    'Fuel Supply',
                                    'Insurance Services'
                                ] as $service)

                                    <label class="dropdown-option">

                                        <input
                                            type="checkbox"
                                            name="service_type[]"
                                            value="{{ $service }}"
                                            {{ in_array($service, $selectedServices, true) ? 'checked' : '' }}
                                        >

                                        <span>
                                            {{ $service }}
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                            <div class="dropdown-footer">
                                Multiple services can be selected.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Address --}}
                <div class="form-group full-width">

                    <label for="address">
                        Address
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        placeholder="Enter vendor address"
                    >{{ old('address', $vendor->address) }}</textarea>

                </div>


                {{-- Supplied Vehicles --}}
                <div class="form-group full-width">

                    <label>
                        Vehicle(s) Supplied
                    </label>

                    <div
                        class="multi-dropdown"
                        id="vehicleDropdown"
                    >

                        <button
                            type="button"
                            class="multi-dropdown-button"
                            id="vehicleDropdownButton"
                        >
                            <span
                                class="dropdown-placeholder"
                                id="vehicleDropdownText"
                            >
                                Select vehicles
                            </span>

                            <span class="dropdown-arrow"></span>
                        </button>


                        <div class="multi-dropdown-menu">

                            <div class="vehicle-search-wrap">

                                <input
                                    type="text"
                                    id="vehicleSearch"
                                    class="vehicle-search"
                                    placeholder="Search plate number..."
                                    autocomplete="off"
                                >

                            </div>


                            <div
                                class="dropdown-options"
                                id="vehicleOptions"
                            >

                                @forelse ($vehicles as $vehicle)

                                    <label
                                        class="dropdown-option vehicle-option"
                                        data-plate="{{ strtolower($vehicle->plate_number) }}"
                                    >

                                        <input
                                            type="checkbox"
                                            name="supplied_vehicle_plates[]"
                                            value="{{ $vehicle->plate_number }}"
                                            {{ in_array($vehicle->plate_number, $selectedVehicles, true) ? 'checked' : '' }}
                                        >

                                        <span>
                                            {{ $vehicle->plate_number }}
                                        </span>

                                    </label>

                                @empty

                                    <div class="vehicle-empty">
                                        No vehicles found in the database.
                                    </div>

                                @endforelse

                            </div>


                            <div
                                class="dropdown-footer"
                                id="vehicleDropdownFooter"
                            >
                                Multiple vehicles can be selected.
                            </div>

                        </div>

                    </div>

                    <small>
                        Search existing vehicle plate numbers and select one or more vehicles.
                    </small>

                </div>

            </div>

        </div>
        {{-- =========================
             VENDOR RATES
        ========================== --}}

        <div class="form-card">

            <div class="card-header">

                <div>
                    <h2>Vendor Rates</h2>
                    <p>Configure applicable vendor contract rates.</p>
                </div>

            </div>


            <div class="form-grid">

                {{-- Rate Per Day --}}
                <div class="form-group">

                    <label for="rate_per_day">
                        Rate Per Day
                    </label>

                    <input
                        type="number"
                        id="rate_per_day"
                        name="rate_per_day"
                        value="{{ old('rate_per_day', $vendor->rate_per_day) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                </div>


                {{-- Rate Per Month --}}
                <div class="form-group">

                    <label for="rate_per_month">
                        Rate Per Month
                    </label>

                    <input
                        type="number"
                        id="rate_per_month"
                        name="rate_per_month"
                        value="{{ old('rate_per_month', $vendor->rate_per_month) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                </div>


                {{-- Rate Per Trip --}}
                <div class="form-group">

                    <label for="rate_per_trip">
                        Rate Per Trip
                    </label>

                    <input
                        type="number"
                        id="rate_per_trip"
                        name="rate_per_trip"
                        value="{{ old('rate_per_trip', $vendor->rate_per_trip) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                </div>


                {{-- Custom Rate --}}
                <div class="form-group">

                    <label for="custom_rate">
                        Custom Rate
                    </label>

                    <input
                        type="number"
                        id="custom_rate"
                        name="custom_rate"
                        value="{{ old('custom_rate', $vendor->custom_rate) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                </div>


                {{-- Custom Rate Label --}}
                <div class="form-group full-width">

                    <label for="custom_rate_label">
                        Custom Rate Label
                    </label>

                    <input
                        type="text"
                        id="custom_rate_label"
                        name="custom_rate_label"
                        value="{{ old('custom_rate_label', $vendor->custom_rate_label) }}"
                        placeholder="e.g. Per Hour / Per Service / Fixed Contract"
                    >

                </div>

            </div>

        </div>


        {{-- =========================
             PAYMENT & REGISTRATION
        ========================== --}}

        <div class="form-card">

            <div class="card-header">

                <div>
                    <h2>Payment Terms & Registration</h2>
                    <p>Update payment conditions and registration information.</p>
                </div>

            </div>


            <div class="form-grid">

                {{-- Payment Terms --}}
                <div class="form-group">

                    <label for="payment_terms">
                        Payment Terms
                    </label>

                    <input
                        type="text"
                        id="payment_terms"
                        name="payment_terms"
                        value="{{ old('payment_terms', $vendor->payment_terms) }}"
                        placeholder="e.g. Monthly / Net 30"
                    >

                </div>


                {{-- Payment Terms Days --}}
                <div class="form-group">

                    <label for="payment_terms_days">
                        Payment Terms (Days)
                    </label>

                    <input
                        type="number"
                        id="payment_terms_days"
                        name="payment_terms_days"
                        value="{{ old('payment_terms_days', $vendor->payment_terms_days) }}"
                        min="0"
                        step="1"
                        placeholder="e.g. 30"
                    >

                </div>


                {{-- Tax / Registration Data --}}
                <div class="form-group full-width">

                    <label for="tax_registration_data">
                        Tax / Registration Data
                    </label>

                    <textarea
                        id="tax_registration_data"
                        name="tax_registration_data"
                        rows="3"
                        placeholder="Enter tax number, registration details or related information"
                    >{{ old('tax_registration_data', $vendor->tax_registration_data) }}</textarea>

                </div>


                {{-- Status --}}
                <div class="form-group">

                    <label for="status">
                        Status <span>*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                    >

                        <option
                            value="Active"
                            {{ old('status', $vendor->status) === 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            {{ old('status', $vendor->status) === 'Inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                        <option
                            value="Archived"
                            {{ old('status', $vendor->status) === 'Archived' ? 'selected' : '' }}
                        >
                            Archived
                        </option>

                    </select>

                </div>


                {{-- Notes --}}
                <div class="form-group full-width">

                    <label for="notes">
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        placeholder="Enter additional notes"
                    >{{ old('notes', $vendor->notes) }}</textarea>

                </div>

            </div>

        </div>


        {{-- FORM ACTIONS --}}
        <div class="form-actions">

            <a
                href="{{ route('vendors.index') }}"
                class="cancel-btn"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="update-btn"
            >
                Update Vendor
            </button>

        </div>

    </form>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const serviceDropdown =
            document.getElementById('serviceDropdown');

        const serviceButton =
            document.getElementById('serviceDropdownButton');

        const serviceText =
            document.getElementById('serviceDropdownText');


        const vehicleDropdown =
            document.getElementById('vehicleDropdown');

        const vehicleButton =
            document.getElementById('vehicleDropdownButton');

        const vehicleText =
            document.getElementById('vehicleDropdownText');

        const vehicleSearch =
            document.getElementById('vehicleSearch');

        const vehicleOptions =
            document.querySelectorAll('.vehicle-option');

        const vehicleFooter =
            document.getElementById('vehicleDropdownFooter');


        function updateServiceText() {

            const checked =
                serviceDropdown.querySelectorAll(
                    'input[name="service_type[]"]:checked'
                );

            if (checked.length === 0) {

                serviceText.textContent =
                    'Select service types';

                return;
            }

            if (checked.length === 1) {

                serviceText.textContent =
                    checked[0].value;

                return;
            }

            serviceText.textContent =
                checked.length + ' services selected';
        }


        function updateVehicleText() {

            const checked =
                vehicleDropdown.querySelectorAll(
                    'input[name="supplied_vehicle_plates[]"]:checked'
                );

            if (checked.length === 0) {

                vehicleText.textContent =
                    'Select vehicles';

                vehicleFooter.textContent =
                    'Multiple vehicles can be selected.';

                return;
            }

            if (checked.length === 1) {

                vehicleText.textContent =
                    checked[0].value;

            } else {

                vehicleText.textContent =
                    checked.length + ' vehicles selected';
            }

            vehicleFooter.textContent =
                checked.length + ' vehicle(s) selected.';
        }


        serviceButton.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();

                vehicleDropdown.classList.remove('open');

                serviceDropdown.classList.toggle('open');
            }
        );


        vehicleButton.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();

                serviceDropdown.classList.remove('open');

                vehicleDropdown.classList.toggle('open');

                if (
                    vehicleDropdown.classList.contains('open') &&
                    vehicleSearch
                ) {
                    setTimeout(function () {
                        vehicleSearch.focus();
                    }, 50);
                }
            }
        );


        serviceDropdown
            .querySelectorAll(
                'input[name="service_type[]"]'
            )
            .forEach(function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    function () {
                        updateServiceText();
                    }
                );

            });


        vehicleDropdown
            .querySelectorAll(
                'input[name="supplied_vehicle_plates[]"]'
            )
            .forEach(function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    function () {
                        updateVehicleText();
                    }
                );

            });


        if (vehicleSearch) {

            vehicleSearch.addEventListener(
                'input',
                function () {

                    const searchValue =
                        this.value
                            .trim()
                            .toLowerCase();

                    vehicleOptions.forEach(
                        function (option) {

                            const plate =
                                option.dataset.plate || '';

                            if (
                                plate.includes(searchValue)
                            ) {
                                option.classList.remove(
                                    'hidden'
                                );
                            } else {
                                option.classList.add(
                                    'hidden'
                                );
                            }

                        }
                    );
                }
            );
        }


        document.addEventListener(
            'click',
            function () {

                serviceDropdown.classList.remove('open');

                vehicleDropdown.classList.remove('open');
            }
        );


        serviceDropdown.addEventListener(
            'click',
            function (event) {
                event.stopPropagation();
            }
        );


        vehicleDropdown.addEventListener(
            'click',
            function (event) {
                event.stopPropagation();
            }
        );


        updateServiceText();
        updateVehicleText();

    });
</script>

@endsection