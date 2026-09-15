@extends('layouts.app')

@section('content')

<style>
    .document-edit-page {
        padding: 28px 32px;
    }

    .document-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .document-edit-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .document-edit-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .btn-primary-erp,
    .btn-secondary-erp {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .btn-primary-erp {
        border: 1px solid #193b8f;
        background: linear-gradient(135deg, #101d42, #193b8f);
        color: #fff;
    }

    .btn-primary-erp:hover {
        background: #101d42;
        color: #fff;
    }

    .btn-secondary-erp {
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
    }

    .btn-secondary-erp:hover {
        background: #f8fafc;
        color: #101d42;
    }

    .document-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .form-section {
        padding: 24px;
        border-bottom: 1px solid #eef0f3;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-heading {
        margin: 0 0 18px;
        color: #101d42;
        font-size: 17px;
        font-weight: 700;
    }

    .section-description {
        margin: -10px 0 18px;
        color: #64748b;
        font-size: 13px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
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
        font-weight: 600;
    }

    .required {
        color: #dc3545;
    }

    .form-control-erp,
    .form-select-erp,
    .form-textarea-erp {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #1f2937;
        font-size: 13px;
        outline: none;
        transition: .2s;
        box-sizing: border-box;
    }

    .form-control-erp,
    .form-select-erp {
        height: 38px;
        padding: 0 11px;
    }

    .form-textarea-erp {
        min-height: 105px;
        padding: 10px 11px;
        resize: vertical;
    }

    .form-control-erp:focus,
    .form-select-erp:focus,
    .form-textarea-erp:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .form-hint {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 11px;
    }

    .current-file-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 13px 15px;
        margin-bottom: 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .current-file-info {
        min-width: 0;
    }

    .current-file-label {
        margin-bottom: 4px;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .current-file-name {
        overflow: hidden;
        color: #101d42;
        font-size: 13px;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 6px 11px;
        border-radius: 6px;
        background: rgba(37, 99, 235, .10);
        color: #193b8f;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .file-link:hover {
        background: rgba(37, 99, 235, .16);
        color: #101d42;
    }

    .entity-select {
        display: none;
    }

    .entity-select.active {
        display: block;
    }

    .validation-errors {
        padding: 14px 17px;
        margin-bottom: 20px;
        background: #fff5f5;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #991b1b;
        font-size: 13px;
    }

    .validation-errors ul {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding: 18px 24px;
        background: #fafafa;
        border-top: 1px solid #eef0f3;
    }

    @media (max-width: 768px) {
        .document-edit-page {
            padding: 20px 15px;
        }

        .document-edit-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions a {
            flex: 1;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .current-file-box {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-footer {
            flex-direction: column-reverse;
        }

        .form-footer a,
        .form-footer button {
            width: 100%;
        }
    }
</style>

<div class="document-edit-page">

    <div class="document-edit-header">
        <div>
            <h1 class="document-edit-title">Edit Document</h1>
            <p class="document-edit-subtitle">
                Update document details and expiry information.
            </p>
        </div>

        <div class="header-actions">
            <a href="{{ route('documents.show', $document) }}" class="btn-secondary-erp">
                View Document
            </a>

            <a href="{{ route('documents.index') }}" class="btn-secondary-erp">
                ← Back
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="validation-errors">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('documents.update', $document) }}"
        method="POST"
        class="document-form-card"
    >
        @csrf
        @method('PUT')

        <div class="form-section">
            <h2 class="section-heading">Basic Information</h2>

            <p class="section-description">
                Update the document type, number and validity dates.
            </p>

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">
                        Document Type <span class="required">*</span>
                    </label>

                    <select
                        name="document_type"
                        class="form-select-erp"
                        required
                    >
                        @foreach([
                            'Visa',
                            'License',
                            'Registration',
                            'Insurance',
                            'Contract',
                            'Other'
                        ] as $type)
                            <option
                                value="{{ $type }}"
                                {{ old('document_type', $document->document_type) === $type ? 'selected' : '' }}
                            >
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Document Number
                    </label>

                    <input
                        type="text"
                        name="document_number"
                        class="form-control-erp"
                        value="{{ old('document_number', $document->document_number) }}"
                        maxlength="150"
                        placeholder="Enter document number"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Issue Date
                    </label>

                    <input
                        type="date"
                        name="issue_date"
                        class="form-control-erp"
                        value="{{ old('issue_date', optional($document->issue_date)->format('Y-m-d')) }}"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Expiry Date
                    </label>

                    <input
                        type="date"
                        name="expiry_date"
                        class="form-control-erp"
                        value="{{ old('expiry_date', optional($document->expiry_date)->format('Y-m-d')) }}"
                    >
                </div>

            </div>
        </div>

        <div class="form-section">
            <h2 class="section-heading">Related Entity</h2>

            <p class="section-description">
                Select the Vehicle, Driver, Client or Vendor associated with this document.
            </p>

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">
                        Related To <span class="required">*</span>
                    </label>

                    @php
                        $currentRelatedType = match ($document->related_type) {
                            \App\Models\Vehicle::class => 'vehicle',
                            \App\Models\Driver::class => 'driver',
                            \App\Models\Client::class => 'client',
                            \App\Models\Vendor::class => 'vendor',
                            default => old('related_type', 'vehicle'),
                        };
                    @endphp

                    <select
                        name="related_type"
                        id="related_type"
                        class="form-select-erp"
                        required
                    >
                        <option value="">Select entity type</option>
                        <option value="vehicle" {{ old('related_type', $currentRelatedType) === 'vehicle' ? 'selected' : '' }}>
                            Vehicle
                        </option>
                        <option value="driver" {{ old('related_type', $currentRelatedType) === 'driver' ? 'selected' : '' }}>
                            Driver
                        </option>
                        <option value="client" {{ old('related_type', $currentRelatedType) === 'client' ? 'selected' : '' }}>
                            Client
                        </option>
                        <option value="vendor" {{ old('related_type', $currentRelatedType) === 'vendor' ? 'selected' : '' }}>
                            Vendor
                        </option>
                    </select>
                </div>

                <div class="form-group">

                    <div
                        id="vehicle-wrapper"
                        class="entity-select"
                    >
                        <label class="form-label">
                            Vehicle <span class="required">*</span>
                        </label>

                        <select
                            name="vehicle_id"
                            id="vehicle_id"
                            class="form-select-erp"
                        >
                            <option value="">Select vehicle</option>

                            @foreach($vehicles as $vehicle)
                                <option
                                    value="{{ $vehicle->id }}"
                                    {{ old('related_type', $currentRelatedType) === 'vehicle' && (string) old('related_id', $document->related_id) === (string) $vehicle->id ? 'selected' : '' }}
                                >
                                    {{ $vehicle->plate_number ?? $vehicle->vehicle_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div
                        id="driver-wrapper"
                        class="entity-select"
                    >
                        <label class="form-label">
                            Driver <span class="required">*</span>
                        </label>

                        <select
                            name="driver_id"
                            id="driver_id"
                            class="form-select-erp"
                        >
                            <option value="">Select driver</option>

                            @foreach($drivers as $driver)
                                <option
                                    value="{{ $driver->id }}"
                                    {{ old('related_type', $currentRelatedType) === 'driver' && (string) old('related_id', $document->related_id) === (string) $driver->id ? 'selected' : '' }}
                                >
                                    {{ $driver->driver_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div
                        id="client-wrapper"
                        class="entity-select"
                    >
                        <label class="form-label">
                            Client <span class="required">*</span>
                        </label>

                        <select
                            name="client_id"
                            id="client_id"
                            class="form-select-erp"
                        >
                            <option value="">Select client</option>

                            @foreach($clients as $client)
                                <option
                                    value="{{ $client->id }}"
                                    {{ old('related_type', $currentRelatedType) === 'client' && (string) old('related_id', $document->related_id) === (string) $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->client_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div
                        id="vendor-wrapper"
                        class="entity-select"
                    >
                        <label class="form-label">
                            Vendor <span class="required">*</span>
                        </label>

                        <select
                            name="vendor_id"
                            id="vendor_id"
                            class="form-select-erp"
                        >
                            <option value="">Select vendor</option>

                            @foreach($vendors as $vendor)
                                <option
                                    value="{{ $vendor->id }}"
                                    {{ old('related_type', $currentRelatedType) === 'vendor' && (string) old('related_id', $document->related_id) === (string) $vendor->id ? 'selected' : '' }}
                                >
                                    {{ $vendor->vendor_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

            </div>
        </div>
        <div class="form-section">
            <h2 class="section-heading">File & Status</h2>

            <div class="current-file-box">
                <div class="current-file-info">
                    <div class="current-file-label">Current File</div>

                    <div class="current-file-name">
                        {{ $document->original_file_name ?? 'Document file' }}
                    </div>
                </div>

                @if($document->file_path)
                    <a
                        href="{{ asset('storage/' . $document->file_path) }}"
                        target="_blank"
                        class="file-link"
                    >
                        View File
                    </a>
                @endif
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">
                        Status <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select-erp"
                        required
                    >
                        <option
                            value="active"
                            {{ old('status', $document->status) === 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="expired"
                            {{ old('status', $document->status) === 'expired' ? 'selected' : '' }}
                        >
                            Expired
                        </option>

                        <option
                            value="archived"
                            {{ old('status', $document->status) === 'archived' ? 'selected' : '' }}
                        >
                            Archived
                        </option>
                    </select>

                    <div class="form-hint">
                        Status can be updated without replacing the current file.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Current Version
                    </label>

                    <input
                        type="text"
                        class="form-control-erp"
                        value="Version {{ $document->version }}"
                        readonly
                    >

                    <div class="form-hint">
                        Version history is preserved when replacements are added.
                    </div>
                </div>

            </div>
        </div>

        <div class="form-section">
            <h2 class="section-heading">Notes</h2>

            <div class="form-group">
                <label class="form-label">
                    Notes
                </label>

                <textarea
                    name="notes"
                    class="form-textarea-erp"
                    placeholder="Add any additional notes..."
                >{{ old('notes', $document->notes) }}</textarea>
            </div>
        </div>

        <div class="form-footer">

            <a
                href="{{ route('documents.index') }}"
                class="btn-secondary-erp"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn-primary-erp"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const relatedType = document.getElementById('related_type');

        const wrappers = {
            vehicle: document.getElementById('vehicle-wrapper'),
            driver: document.getElementById('driver-wrapper'),
            client: document.getElementById('client-wrapper'),
            vendor: document.getElementById('vendor-wrapper')
        };

        const selects = {
            vehicle: document.getElementById('vehicle_id'),
            driver: document.getElementById('driver_id'),
            client: document.getElementById('client_id'),
            vendor: document.getElementById('vendor_id')
        };

        function updateRelatedEntity() {

            Object.keys(wrappers).forEach(function (type) {

                wrappers[type].classList.remove('active');

                if (selects[type]) {
                    selects[type].removeAttribute('name');
                }

            });

            const selectedType = relatedType.value;

            if (wrappers[selectedType]) {
                wrappers[selectedType].classList.add('active');
            }

            if (selects[selectedType]) {
                selects[selectedType].setAttribute('name', 'related_id');
            }
        }

        relatedType.addEventListener('change', updateRelatedEntity);

        updateRelatedEntity();
    });
</script>

@endsection