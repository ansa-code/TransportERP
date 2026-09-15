@extends('layouts.app')

@section('content')

<style>
    .document-form-page {
        padding: 0;
    }

    .document-form-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .document-form-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .document-form-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #101d42;
    }

    .document-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 11px;
        box-shadow: 0 4px 16px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .form-section {
        padding: 22px 24px;
        border-bottom: 1px solid #eef0f3;
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .form-section-title {
        margin: 0 0 4px;
        color: #101d42;
        font-size: 16px;
        font-weight: 700;
    }

    .form-section-subtitle {
        margin: 0 0 18px;
        color: #94a3b8;
        font-size: 11px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        color: #475569;
        font-size: 12px;
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #374151;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        outline: none;
        transition: .2s;
    }

    .form-input,
    .form-select {
        height: 38px;
        padding: 0 11px;
    }

    .form-textarea {
        min-height: 95px;
        padding: 10px 11px;
        resize: vertical;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.10);
    }

    .form-help {
        color: #94a3b8;
        font-size: 10px;
        line-height: 1.4;
    }

    .field-error {
        color: #dc3545;
        font-size: 11px;
        font-weight: 600;
    }

    .validation-box {
        margin-bottom: 20px;
        padding: 15px 18px;
        border: 1px solid #f5c2c7;
        border-radius: 8px;
        background: #f8d7da;
        color: #842029;
    }

    .validation-box-title {
        margin-bottom: 7px;
        font-size: 14px;
        font-weight: 700;
    }

    .validation-box ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        line-height: 1.7;
    }

    .related-select-wrapper {
        display: none;
    }

    .related-select-wrapper.active {
        display: flex;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 18px 24px;
        background: #f8fafc;
        border-top: 1px solid #eef0f3;
    }

    .cancel-btn,
    .submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 105px;
        height: 38px;
        padding: 0 15px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }

    .cancel-btn {
        border: 1px solid #d1d5db;
        background: #fff;
        color: #475569;
    }

    .cancel-btn:hover {
        background: #f1f5f9;
        color: #101d42;
    }

    .submit-btn {
        border: 1px solid #193b8f;
        background: linear-gradient(
            135deg,
            #101d42,
            #193b8f
        );
        color: #fff;
    }

    .submit-btn:hover {
        background: #101d42;
    }

    @media (max-width: 700px) {

        .document-form-header {
            flex-direction: column;
        }

        .back-btn {
            width: 100%;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-footer {
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .submit-btn {
            width: 100%;
        }
    }
</style>


<div class="document-form-page">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="document-form-header">

        <div>

            <h1 class="document-form-title">
                Upload Document
            </h1>

            <p class="document-form-subtitle">
                Add a document and link it to a vehicle, driver, client or vendor.
            </p>

        </div>


        <a
            href="{{ route('documents.index') }}"
            class="back-btn"
        >
            ← Back to Documents
        </a>

    </div>


    <!-- =========================================================
         VALIDATION ERRORS
    ========================================================== -->

    @if ($errors->any())

        <div class="validation-box">

            <div class="validation-box-title">
                Please fix the following errors:
            </div>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- =========================================================
         FORM
    ========================================================== -->

    <div class="document-form-card">

        <form
            action="{{ route('documents.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <!-- =================================================
                 BASIC INFORMATION
            ================================================== -->

            <div class="form-section">

                <h2 class="form-section-title">
                    Basic Information
                </h2>

                <p class="form-section-subtitle">
                    Enter the main details of the document.
                </p>


                <div class="form-grid">


                    <!-- DOCUMENT TYPE -->

                    <div class="form-group">

                        <label
                            for="document_type"
                            class="form-label"
                        >
                            Document Type
                            <span class="required">*</span>
                        </label>

                        <select
                            name="document_type"
                            id="document_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Document Type
                            </option>

                            <option
                                value="Visa"
                                {{ old('document_type') === 'Visa' ? 'selected' : '' }}
                            >
                                Visa
                            </option>

                            <option
                                value="License"
                                {{ old('document_type') === 'License' ? 'selected' : '' }}
                            >
                                License
                            </option>

                            <option
                                value="Registration"
                                {{ old('document_type') === 'Registration' ? 'selected' : '' }}
                            >
                                Registration
                            </option>

                            <option
                                value="Insurance"
                                {{ old('document_type') === 'Insurance' ? 'selected' : '' }}
                            >
                                Insurance
                            </option>

                            <option
                                value="Contract"
                                {{ old('document_type') === 'Contract' ? 'selected' : '' }}
                            >
                                Contract
                            </option>

                            <option
                                value="Other"
                                {{ old('document_type') === 'Other' ? 'selected' : '' }}
                            >
                                Other
                            </option>

                        </select>

                        @error('document_type')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- DOCUMENT NUMBER -->

                    <div class="form-group">

                        <label
                            for="document_number"
                            class="form-label"
                        >
                            Document Number
                        </label>

                        <input
                            type="text"
                            name="document_number"
                            id="document_number"
                            class="form-input"
                            value="{{ old('document_number') }}"
                            placeholder="e.g. INS-2026-001"
                        >

                        @error('document_number')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- ISSUE DATE -->

                    <div class="form-group">

                        <label
                            for="issue_date"
                            class="form-label"
                        >
                            Issue Date
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            id="issue_date"
                            class="form-input"
                            value="{{ old('issue_date') }}"
                        >

                        @error('issue_date')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- EXPIRY DATE -->

                    <div class="form-group">

                        <label
                            for="expiry_date"
                            class="form-label"
                        >
                            Expiry Date
                        </label>

                        <input
                            type="date"
                            name="expiry_date"
                            id="expiry_date"
                            class="form-input"
                            value="{{ old('expiry_date') }}"
                        >

                        <span class="form-help">
                            Leave empty if this document has no expiry date.
                        </span>

                        @error('expiry_date')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                </div>

            </div>


            <!-- =================================================
                 RELATED ENTITY
            ================================================== -->

            <div class="form-section">

                <h2 class="form-section-title">
                    Related Entity
                </h2>

                <p class="form-section-subtitle">
                    Select the record this document belongs to.
                </p>


                <div class="form-grid">


                    <!-- RELATED TYPE -->

                    <div class="form-group">

                        <label
                            for="related_type"
                            class="form-label"
                        >
                            Related Type
                            <span class="required">*</span>
                        </label>

                        <select
                            name="related_type"
                            id="related_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Related Type
                            </option>

                            <option
                                value="vehicle"
                                {{ old('related_type') === 'vehicle' ? 'selected' : '' }}
                            >
                                Vehicle
                            </option>

                            <option
                                value="driver"
                                {{ old('related_type') === 'driver' ? 'selected' : '' }}
                            >
                                Driver
                            </option>

                            <option
                                value="client"
                                {{ old('related_type') === 'client' ? 'selected' : '' }}
                            >
                                Client
                            </option>

                            <option
                                value="vendor"
                                {{ old('related_type') === 'vendor' ? 'selected' : '' }}
                            >
                                Vendor
                            </option>

                        </select>

                        @error('related_type')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- =================================================
                         IMPORTANT:
                         ALL OPTIONS USE THE SAME name="related_id"
                    ================================================== -->


                    <!-- VEHICLE -->

                    <div
                        class="form-group related-select-wrapper"
                        id="vehicle-wrapper"
                    >

                        <label
                            for="vehicle_related_id"
                            class="form-label"
                        >
                            Vehicle
                            <span class="required">*</span>
                        </label>

                        <select
                            id="vehicle_related_id"
                            class="form-select related-record-select"
                            data-type="vehicle"
                            disabled
                        >

                            <option value="">
                                Select Vehicle
                            </option>

                            @foreach($vehicles as $vehicle)

                                <option
                                    value="{{ $vehicle->id }}"
                                    {{ old('related_type') === 'vehicle' && old('related_id') == $vehicle->id ? 'selected' : '' }}
                                >
                                    {{ $vehicle->plate_number ?? $vehicle->vehicle_code ?? 'Vehicle #'.$vehicle->id }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- DRIVER -->

                    <div
                        class="form-group related-select-wrapper"
                        id="driver-wrapper"
                    >

                        <label
                            for="driver_related_id"
                            class="form-label"
                        >
                            Driver
                            <span class="required">*</span>
                        </label>

                        <select
                            id="driver_related_id"
                            class="form-select related-record-select"
                            data-type="driver"
                            disabled
                        >

                            <option value="">
                                Select Driver
                            </option>

                            @foreach($drivers as $driver)

                                <option
                                    value="{{ $driver->id }}"
                                    {{ old('related_type') === 'driver' && old('related_id') == $driver->id ? 'selected' : '' }}
                                >
                                    {{ $driver->driver_name ?? 'Driver #'.$driver->id }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- CLIENT -->

                    <div
                        class="form-group related-select-wrapper"
                        id="client-wrapper"
                    >

                        <label
                            for="client_related_id"
                            class="form-label"
                        >
                            Client
                            <span class="required">*</span>
                        </label>

                        <select
                            id="client_related_id"
                            class="form-select related-record-select"
                            data-type="client"
                            disabled
                        >

                            <option value="">
                                Select Client
                            </option>

                            @foreach($clients as $client)

                                <option
                                    value="{{ $client->id }}"
                                    {{ old('related_type') === 'client' && old('related_id') == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->client_name ?? $client->company_name ?? 'Client #'.$client->id }}
                                </option>

                            @endforeach

                        </select>

                    </div>
<!-- REAL SUBMITTED RELATED ID -->

                    <input
                        type="hidden"
                        name="related_id"
                        id="related_id"
                        value="{{ old('related_id') }}"
                    >

                    @error('related_id')
                        <div class="form-group full-width">
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        </div>
                    @enderror


                </div>

            </div>


                    <!-- =================================================
                 FILE & STATUS
            ================================================== -->

            <div class="form-section">

                <h2 class="form-section-title">
                    File & Status
                </h2>

                <p class="form-section-subtitle">
                    Upload the document file and set its current status.
                </p>


                <div class="form-grid">


                    <!-- FILE -->

                    <div class="form-group">

                        <label
                            for="file"
                            class="form-label"
                        >
                            Document File
                            <span class="required">*</span>
                        </label>

                        <input
                            type="file"
                            name="file"
                            id="file"
                            class="form-input"
                            accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
                            required
                        >

                        <span class="form-help">
                            Allowed: PDF, JPG, JPEG, PNG, WEBP, DOC, DOCX. Maximum 10 MB.
                        </span>

                        @error('file')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- STATUS -->

                    <div class="form-group">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                            <span class="required">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="expired"
                                {{ old('status') === 'expired' ? 'selected' : '' }}
                            >
                                Expired
                            </option>

                            <option
                                value="archived"
                                {{ old('status') === 'archived' ? 'selected' : '' }}
                            >
                                Archived
                            </option>

                        </select>

                        @error('status')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- NOTES -->

                    <div class="form-group full-width">

                        <label
                            for="notes"
                            class="form-label"
                        >
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            class="form-textarea"
                            placeholder="Add any additional notes about this document..."
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                </div>

            </div>


            <!-- =================================================
                 FOOTER ACTIONS
            ================================================== -->

            <div class="form-footer">

                <a
                    href="{{ route('documents.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="submit-btn"
                >
                    Upload Document
                </button>

            </div>


        </form>

    </div>

</div>


<!-- =========================================================
     RELATED ENTITY JAVASCRIPT
========================================================== -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const relatedType = document.getElementById('related_type');
    const relatedId = document.getElementById('related_id');

    const wrappers = {
        vehicle: document.getElementById('vehicle-wrapper'),
        driver: document.getElementById('driver-wrapper'),
        client: document.getElementById('client-wrapper'),
        vendor: document.getElementById('vendor-wrapper')
    };

    const selects = {
        vehicle: document.getElementById('vehicle_related_id'),
        driver: document.getElementById('driver_related_id'),
        client: document.getElementById('client_related_id'),
        vendor: document.getElementById('vendor_related_id')
    };


    function resetSelectors() {

        Object.keys(selects).forEach(function (type) {

            const select = selects[type];

            if (!select) {
                return;
            }

            select.disabled = true;

            select.value = '';

            if (wrappers[type]) {
                wrappers[type].classList.remove('active');
            }

        });

    }


    function showSelectedType() {

        const selectedType = relatedType.value;

        resetSelectors();

        if (!selectedType) {

            relatedId.value = '';

            return;
        }


        const wrapper = wrappers[selectedType];
        const select = selects[selectedType];

        if (!wrapper || !select) {

            relatedId.value = '';

            return;
        }


        wrapper.classList.add('active');

        select.disabled = false;


        /*
         * Restore old related_id after Laravel validation failure.
         */

        const oldRelatedId = relatedId.value;

        if (oldRelatedId) {

            const optionExists = Array.from(select.options)
                .some(function (option) {
                    return option.value === String(oldRelatedId);
                });

            if (optionExists) {
                select.value = oldRelatedId;
            }

        }


        relatedId.value = select.value || '';
    }


    Object.keys(selects).forEach(function (type) {

        const select = selects[type];

        if (!select) {
            return;
        }


        select.addEventListener('change', function () {

            /*
             * This is the important fix:
             * selected entity ID is copied into the
             * real form field named "related_id".
             */

            relatedId.value = this.value || '';

        });

    });


    relatedType.addEventListener('change', function () {

        /*
         * Changing Vehicle -> Driver etc.
         * clears the old entity ID.
         */

        relatedId.value = '';

        showSelectedType();

    });


    /*
     * Initialize the correct dropdown when the page loads.
     * This also restores old() values after validation errors.
     */

    showSelectedType();


    /*
     * Final safety check before form submission.
     */

    const form = relatedType.closest('form');

    form.addEventListener('submit', function (event) {

        const selectedType = relatedType.value;

        if (!selectedType) {

            event.preventDefault();

            relatedType.focus();

            return;
        }


        const selectedDropdown = selects[selectedType];

        if (!selectedDropdown || !selectedDropdown.value) {

            event.preventDefault();

            selectedDropdown?.focus();

            alert('Please select the related ' + selectedType + '.');

            return;
        }


        relatedId.value = selectedDropdown.value;

    });

});
</script>

@endsection