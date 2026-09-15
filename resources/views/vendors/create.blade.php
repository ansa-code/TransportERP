@extends('layouts.app')

@section('content')

<style>
    .vendor-create-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .page-header h2 {
        margin: 0;
        color: #101d42;
        font-size: 24px;
        font-weight: 800;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-back {
        height: 38px;
        padding: 0 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #6c757d;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
    }

    .btn-back:hover {
        background: #5c636a;
        color: #fff;
    }

    .form-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(16, 29, 66, 0.08);
        padding: 25px;
    }

    .section-title {
        color: #101d42;
        font-size: 17px;
        font-weight: 800;
        margin: 0 0 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
        margin-bottom: 28px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
    }

    .required {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        height: 38px;
        padding: 0 11px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        color: #111827;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    textarea.form-control {
        height: auto;
        min-height: 82px;
        padding: 10px 11px;
        resize: vertical;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .form-control.is-invalid {
        border-color: #dc2626;
    }

    .invalid-feedback {
        display: block;
        margin-top: 5px;
        color: #dc2626;
        font-size: 12px;
    }

    .help-text {
        margin-top: 5px;
        color: #6b7280;
        font-size: 11px;
    }

    /* =========================
       MULTI SELECT DROPDOWN
    ========================== */

    .multi-dropdown {
        position: relative;
        width: 100%;
    }

    .multi-dropdown-button {
        width: 100%;
        height: 38px;
        padding: 0 11px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        color: #374151;
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
        border-color: #9ca3af;
    }

    .multi-dropdown.open .multi-dropdown-button {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
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
        border-top: 6px solid #6b7280;
        flex-shrink: 0;
        transition: transform .15s ease;
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
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        box-shadow: 0 8px 22px rgba(16, 29, 66, .14);
        overflow: hidden;
    }

    .multi-dropdown.open .multi-dropdown-menu {
        display: block;
    }

    .dropdown-options {
        max-height: 180px;
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
        color: #374151;
        font-size: 13px;
        font-weight: 600;
    }

    .dropdown-option:hover {
        background: #f3f6fb;
    }

    .dropdown-option input {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: #2563eb;
        cursor: pointer;
        flex-shrink: 0;
    }

    .dropdown-footer {
        padding: 7px 10px;
        border-top: 1px solid #e5e7eb;
        background: #f8fafc;
        color: #6b7280;
        font-size: 11px;
    }

    /* Vehicle Search */
    .vehicle-search-wrap {
        padding: 7px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .vehicle-search {
        width: 100%;
        height: 34px;
        padding: 0 10px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #111827;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .vehicle-search:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .vehicle-option.hidden {
        display: none;
    }

    .vehicle-empty {
        padding: 12px 8px;
        color: #6b7280;
        font-size: 12px;
        text-align: center;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 9px;
        padding-top: 18px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-cancel,
    .btn-save {
        width: auto;
        min-width: 78px;
        height: 38px;
        padding: 0 16px;
        border: 0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .btn-cancel {
        background: #6c757d;
        color: #fff;
    }

    .btn-cancel:hover {
        background: #5c636a;
        color: #fff;
    }

    .btn-save {
        background: #2563eb;
        color: #fff;
    }

    .btn-save:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .alert-danger {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 8px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 13px;
    }

    .alert-danger ul {
        margin: 5px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 768px) {
        .vendor-create-page {
            width: 100%;
        }

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-card {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-actions {
            justify-content: flex-end;
        }
    }
</style>

<div class="vendor-create-page">

    <div class="page-header">
        <div>
            <h2>Add Vendor</h2>
            <p>Create a new vendor and define the services and vehicles supplied.</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('vendors.index') }}" class="btn-back">
                ← Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">

        <form action="{{ route('vendors.store') }}" method="POST">
            @csrf

            <h3 class="section-title">Basic Information</h3>

            <div class="form-grid">

                <div class="form-group">
                    <label for="vendor_name" class="form-label">
                        Vendor Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="vendor_name"
                        name="vendor_name"
                        class="form-control @error('vendor_name') is-invalid @enderror"
                        value="{{ old('vendor_name') }}"
                        placeholder="Enter vendor name"
                        required
                    >

                    @error('vendor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="company_name" class="form-label">
                        Company Name
                    </label>

                    <input
                        type="text"
                        id="company_name"
                        name="company_name"
                        class="form-control @error('company_name') is-invalid @enderror"
                        value="{{ old('company_name') }}"
                    >

                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}"
                        placeholder="Enter phone number"
                    >

                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contact" class="form-label">
                        Contact Person
                    </label>

                    <input
                        type="text"
                        id="contact"
                        name="contact"
                        class="form-control @error('contact') is-invalid @enderror"
                        value="{{ old('contact') }}"
                        placeholder="Enter contact person"
                    >

                    @error('contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Enter email address"
                    >

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">
                        Status <span class="required">*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="form-control @error('status') is-invalid @enderror"
                        required
                    >
                        <option value="">Select Status</option>
                        <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                        <option value="Archived" {{ old('status') === 'Archived' ? 'selected' : '' }}>
                            Archived
                        </option>
                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="address" class="form-label">
                        Address
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        placeholder="Enter vendor address"
                    >{{ old('address') }}</textarea>

                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <h3 class="section-title">Services & Vehicles</h3>

            <div class="form-grid">

                <div class="form-group">

                    <label class="form-label">
                        Service Type <span class="required">*</span>
                    </label>

                    @php
                        $selectedServices = old('service_type', []);

                        if (!is_array($selectedServices)) {
                            $selectedServices = [$selectedServices];
                        }
                    @endphp

                    <div class="multi-dropdown" id="serviceDropdown">

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

                                        <span>{{ $service }}</span>
                                    </label>

                                @endforeach

                            </div>

                            <div class="dropdown-footer">
                                Multiple services can be selected.
                            </div>

                        </div>

                    </div>

                    <div class="help-text">
                        Select one or more services provided by this vendor.
                    </div>

                    @error('service_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @error('service_type.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group">

                    <label class="form-label">
                        Vehicle(s) Supplied
                    </label>

                    @php
                        $selectedVehicles = old('supplied_vehicle_plates', []);

                        if (!is_array($selectedVehicles)) {
                            $selectedVehicles = [$selectedVehicles];
                        }
                    @endphp

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

                                        <span>{{ $vehicle->plate_number }}</span>
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

                    <div class="help-text">
                        Search existing vehicle plate numbers and select one or more vehicles.
                    </div>

                    @error('supplied_vehicle_plates')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @error('supplied_vehicle_plates.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            <h3 class="section-title">Rates & Payment Terms</h3>

            <div class="form-grid">

                <div class="form-group">
                    <label for="rate_per_day" class="form-label">
                        Rate Per Day
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="rate_per_day"
                        name="rate_per_day"
                        class="form-control @error('rate_per_day') is-invalid @enderror"
                        value="{{ old('rate_per_day') }}"
                    >

                    @error('rate_per_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rate_per_month" class="form-label">
                        Rate Per Month
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="rate_per_month"
                        name="rate_per_month"
                        class="form-control @error('rate_per_month') is-invalid @enderror"
                        value="{{ old('rate_per_month') }}"
                    >

                    @error('rate_per_month')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rate_per_trip" class="form-label">
                        Rate Per Trip
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="rate_per_trip"
                        name="rate_per_trip"
                        class="form-control @error('rate_per_trip') is-invalid @enderror"
                        value="{{ old('rate_per_trip') }}"
                    >

                    @error('rate_per_trip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="custom_rate" class="form-label">
                        Custom Rate
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="custom_rate"
                        name="custom_rate"
                        class="form-control @error('custom_rate') is-invalid @enderror"
                        value="{{ old('custom_rate') }}"
                    >

                    @error('custom_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="custom_rate_label" class="form-label">
                        Custom Rate Label
                    </label>

                    <input
                        type="text"
                        id="custom_rate_label"
                        name="custom_rate_label"
                        class="form-control @error('custom_rate_label') is-invalid @enderror"
                        value="{{ old('custom_rate_label') }}"
                    >

                    @error('custom_rate_label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_terms" class="form-label">
                        Payment Terms
                    </label>

                    <input
                        type="text"
                        id="payment_terms"
                        name="payment_terms"
                        class="form-control @error('payment_terms') is-invalid @enderror"
                        value="{{ old('payment_terms') }}"
                    >

                    @error('payment_terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_terms_days" class="form-label">
                        Payment Terms Days
                    </label>

                    <input
                        type="number"
                        min="0"
                        id="payment_terms_days"
                        name="payment_terms_days"
                        class="form-control @error('payment_terms_days') is-invalid @enderror"
                        value="{{ old('payment_terms_days') }}"
                    >

                    @error('payment_terms_days')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tax_registration_data" class="form-label">
                        Tax Registration Data
                    </label>

                    <textarea
                        id="tax_registration_data"
                        name="tax_registration_data"
                        class="form-control @error('tax_registration_data') is-invalid @enderror"
                    >{{ old('tax_registration_data') }}</textarea>

                    @error('tax_registration_data')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="notes" class="form-label">
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        class="form-control @error('notes') is-invalid @enderror"
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('vendors.index') }}"
                    class="btn-cancel"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-save"
                >
                    Save Vendor
                </button>

            </div>

        </form>

    </div>
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


        serviceButton.addEventListener('click', function (event) {

            event.stopPropagation();

            vehicleDropdown.classList.remove('open');

            serviceDropdown.classList.toggle('open');
        });


        vehicleButton.addEventListener('click', function (event) {

            event.stopPropagation();

            serviceDropdown.classList.remove('open');

            vehicleDropdown.classList.toggle('open');

            if (vehicleDropdown.classList.contains('open')) {

                setTimeout(function () {

                    if (vehicleSearch) {
                        vehicleSearch.focus();
                    }

                }, 50);
            }
        });


        serviceDropdown
            .querySelectorAll('input[name="service_type[]"]')
            .forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {
                    updateServiceText();
                });

            });


        vehicleDropdown
            .querySelectorAll(
                'input[name="supplied_vehicle_plates[]"]'
            )
            .forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {
                    updateVehicleText();
                });

            });


        if (vehicleSearch) {

            vehicleSearch.addEventListener('input', function () {

                const searchValue =
                    this.value.trim().toLowerCase();

                vehicleOptions.forEach(function (option) {

                    const plate =
                        option.dataset.plate || '';

                    if (plate.includes(searchValue)) {

                        option.classList.remove('hidden');

                    } else {

                        option.classList.add('hidden');
                    }

                });
            });
        }


        document.addEventListener('click', function () {

            serviceDropdown.classList.remove('open');

            vehicleDropdown.classList.remove('open');
        });


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