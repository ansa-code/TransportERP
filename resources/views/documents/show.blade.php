@extends('layouts.app')

@section('content')

<style>
    .document-show-page {
        padding: 28px 32px;
    }

    .document-show-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .document-show-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .document-show-subtitle {
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
    .btn-secondary-erp,
    .btn-danger-erp {
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
        border: 1px solid transparent;
    }

    .btn-primary-erp {
        border-color: #193b8f;
        background: linear-gradient(135deg, #101d42, #193b8f);
        color: #fff;
    }

    .btn-primary-erp:hover {
        background: #101d42;
        color: #fff;
    }

    .btn-secondary-erp {
        border-color: #d1d5db;
        background: #fff;
        color: #374151;
    }

    .btn-secondary-erp:hover {
        background: #f8fafc;
        color: #101d42;
    }

    .btn-danger-erp {
        border-color: #dc3545;
        background: #dc3545;
        color: #fff;
    }

    .btn-danger-erp:hover {
        background: #b42333;
        color: #fff;
    }

    .document-hero {
        position: relative;
        overflow: hidden;
        padding: 26px;
        margin-bottom: 22px;
        border-radius: 13px;
        background: linear-gradient(135deg,#101d42,#193b8f);
        box-shadow: 0 8px 24px rgba(16,29,66,.18);
    }

    .document-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -70px;
        top: -100px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .hero-document-type {
        margin-bottom: 6px;
        color: rgba(255,255,255,.72);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .hero-document-title {
        margin: 0;
        color: #fff;
        font-size: 23px;
        font-weight: 700;
    }

    .hero-document-number {
        margin-top: 6px;
        color: rgba(255,255,255,.72);
        font-size: 13px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 78px;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-active {
        background: rgba(25,135,84,.20);
        color: #d1fae5;
    }

    .status-expired {
        background: rgba(220,53,69,.20);
        color: #fecaca;
    }

    .status-archived {
        background: rgba(148,163,184,.20);
        color: #e2e8f0;
    }

    .hero-cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0,1fr));
        gap: 12px;
    }

    .hero-card {
        padding: 15px;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 9px;
        background: rgba(37,99,235,.30);
    }

    .hero-card-label {
        margin-bottom: 6px;
        color: rgba(255,255,255,.65);
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .hero-card-value {
        overflow: hidden;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0,1fr));
        gap: 20px;
        margin-bottom: 22px;
    }

    .details-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 11px;
        box-shadow: 0 4px 16px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .details-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #eef0f3;
    }

    .details-card-title {
        margin: 0;
        color: #101d42;
        font-size: 16px;
        font-weight: 700;
    }

    .details-list {
        padding: 5px 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 13px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    .detail-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        text-align: right;
    }

    .file-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 20px;
    }

    .file-info {
        min-width: 0;
    }

    .file-label {
        margin-bottom: 5px;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .file-name {
        overflow: hidden;
        color: #101d42;
        font-size: 13px;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 7px 13px;
        border-radius: 6px;
        background: #2563eb;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .file-button:hover {
        background: #193b8f;
        color: #fff;
    }

    .notes-card {
        margin-bottom: 22px;
    }

    .notes-content {
        padding: 18px 20px;
        color: #475569;
        font-size: 13px;
        line-height: 1.65;
        white-space: pre-line;
    }

    .replace-content {
        padding: 20px;
    }

    .replace-description {
        margin: 0 0 18px;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .replace-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0,1fr));
        gap: 18px 20px;
    }

    .replace-group {
        min-width: 0;
    }

    .replace-label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
    }

    .replace-input,
    .replace-select {
        width: 100%;
        height: 38px;
        padding: 0 11px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #1f2937;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
        transition: .2s;
    }

    .replace-input[type="file"] {
        padding: 7px 10px;
    }

    .replace-input:focus,
    .replace-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.10);
    }

    .replace-hint {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 11px;
    }

    .replace-footer {
        display: flex;
        justify-content: flex-end;
        padding-top: 18px;
        margin-top: 20px;
        border-top: 1px solid #eef0f3;
    }

    .version-list {
        padding: 5px 20px 15px;
    }

    .version-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .version-item:last-child {
        border-bottom: none;
    }

    .version-info {
        min-width: 0;
    }

    .version-number {
        color: #101d42;
        font-size: 13px;
        font-weight: 700;
    }

    .version-date {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    .version-link {
        color: #2563eb;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .version-link:hover {
        color: #193b8f;
    }

    .empty-version {
        padding: 20px 0;
        color: #94a3b8;
        font-size: 13px;
        text-align: center;
    }

    .danger-zone {
        padding: 18px 20px;
        margin-bottom: 22px;
        background: #fff;
        border: 1px solid #fecaca;
        border-radius: 11px;
        box-shadow: 0 4px 16px rgba(15,23,42,.04);
    }

    .danger-zone-title {
        margin: 0 0 5px;
        color: #991b1b;
        font-size: 14px;
        font-weight: 700;
    }

    .danger-zone-text {
        margin: 0 0 14px;
        color: #64748b;
        font-size: 12px;
    }

    @media (max-width: 900px) {
        .hero-cards {
            grid-template-columns: repeat(2, minmax(0,1fr));
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .replace-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .document-show-page {
            padding: 20px 15px;
        }

        .document-show-header,
        .hero-top {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions a {
            flex: 1;
        }

        .hero-cards {
            grid-template-columns: 1fr;
        }

        .detail-row {
            flex-direction: column;
            gap: 4px;
        }

        .detail-value {
            text-align: left;
        }

        .file-card,
        .version-item {
            align-items: flex-start;
            flex-direction: column;
        }

        .replace-footer {
            justify-content: stretch;
        }

        .replace-footer button {
            width: 100%;
        }
    }
</style>

<div class="document-show-page">

    <div class="document-show-header">
        <div>
            <h1 class="document-show-title">Document Details</h1>
            <p class="document-show-subtitle">
                View document information, validity and version history.
            </p>
        </div>

        <div class="header-actions">
            <a
                href="{{ route('documents.edit', $document) }}"
                class="btn-primary-erp"
            >
                Edit
            </a>

            <a
                href="{{ route('documents.index') }}"
                class="btn-secondary-erp"
            >
                ← Back
            </a>
        </div>
    </div>

    @php
        $related = $document->related;

        $relatedType = match ($document->related_type) {
            \App\Models\Vehicle::class => 'Vehicle',
            \App\Models\Driver::class => 'Driver',
            \App\Models\Client::class => 'Client',
            \App\Models\Vendor::class => 'Vendor',
            default => class_basename($document->related_type),
        };

        $relatedName = match ($document->related_type) {
            \App\Models\Vehicle::class =>
                $related?->plate_number
                ?? $related?->vehicle_code
                ?? 'Unknown Vehicle',

            \App\Models\Driver::class =>
                $related?->driver_name
                ?? 'Unknown Driver',

            \App\Models\Client::class =>
                $related?->client_name
                ?? 'Unknown Client',

            \App\Models\Vendor::class =>
                $related?->vendor_name
                ?? 'Unknown Vendor',

            default => 'Unknown',
        };

        $statusClass = match ($document->status) {
            'expired' => 'status-expired',
            'archived' => 'status-archived',
            default => 'status-active',
        };
    @endphp

    {{-- HERO --}}
    <div class="document-hero">
        <div class="hero-content">

            <div class="hero-top">
                <div>
                    <div class="hero-document-type">
                        {{ $document->document_type }}
                    </div>

                    <h2 class="hero-document-title">
                        {{ $document->original_file_name ?? 'Document' }}
                    </h2>

                    @if($document->document_number)
                        <div class="hero-document-number">
                            Document No: {{ $document->document_number }}
                        </div>
                    @endif
                </div>

                <div>
                    <span class="status-badge {{ $statusClass }}">
                        {{ $document->status }}
                    </span>
                </div>
            </div>

            <div class="hero-cards">

                <div class="hero-card">
                    <div class="hero-card-label">
                        Related Entity
                    </div>

                    <div class="hero-card-value">
                        {{ $relatedType }}
                    </div>
                </div>

                <div class="hero-card">
                    <div class="hero-card-label">
                        Record
                    </div>

                    <div class="hero-card-value">
                        {{ $relatedName }}
                    </div>
                </div>

                <div class="hero-card">
                    <div class="hero-card-label">
                        Expiry Date
                    </div>

                    <div class="hero-card-value">
                        {{ $document->expiry_date
                            ? $document->expiry_date->format('d M Y')
                            : 'No Expiry'
                        }}
                    </div>
                </div>

                <div class="hero-card">
                    <div class="hero-card-label">
                        Version
                    </div>

                    <div class="hero-card-value">
                        v{{ $document->version }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- DETAILS --}}
    <div class="details-grid">

        <div class="details-card">

            <div class="details-card-header">
                <h3 class="details-card-title">
                    Document Information
                </h3>
            </div>

            <div class="details-list">

                <div class="detail-row">
                    <span class="detail-label">
                        Document Type
                    </span>

                    <span class="detail-value">
                        {{ $document->document_type }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Document Number
                    </span>

                    <span class="detail-value">
                        {{ $document->document_number ?: '—' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Issue Date
                    </span>

                    <span class="detail-value">
                        {{ $document->issue_date
                            ? $document->issue_date->format('d M Y')
                            : '—'
                        }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Expiry Date
                    </span>

                    <span class="detail-value">
                        {{ $document->expiry_date
                            ? $document->expiry_date->format('d M Y')
                            : 'No Expiry'
                        }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Status
                    </span>

                    <span class="detail-value">
                        {{ ucfirst($document->status) }}
                    </span>
                </div>

            </div>
        </div>

        <div class="details-card">

            <div class="details-card-header">
                <h3 class="details-card-title">
                    Related Entity
                </h3>
            </div>

            <div class="details-list">

                <div class="detail-row">
                    <span class="detail-label">
                        Entity Type
                    </span>

                    <span class="detail-value">
                        {{ $relatedType }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Record
                    </span>

                    <span class="detail-value">
                        {{ $relatedName }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Record ID
                    </span>

                    <span class="detail-value">
                        #{{ $document->related_id }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Current Version
                    </span>

                    <span class="detail-value">
                        v{{ $document->version }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">
                        Uploaded
                    </span>

                    <span class="detail-value">
                        {{ $document->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>

            </div>
        </div>

    </div>

    {{-- CURRENT FILE --}}
    <div class="details-card" style="margin-bottom:22px;">

        <div class="details-card-header">
            <h3 class="details-card-title">
                Document File
            </h3>
        </div>

        <div class="file-card">

            <div class="file-info">

                <div class="file-label">
                    Stored File
                </div>

                <div class="file-name">
                    {{ $document->original_file_name ?? 'Document file' }}
                </div>

            </div>

            @if($document->file_path)
                <a
                    href="{{ asset('storage/' . $document->file_path) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="file-button"
                >
                    Open File
                </a>
            @endif

        </div>

    </div>

    {{-- REPLACE DOCUMENT --}}
    <div class="details-card" style="margin-bottom:22px;">

        <div class="details-card-header">
            <h3 class="details-card-title">
                Replace Document
            </h3>
        </div>

        <div class="replace-content">

            <p class="replace-description">
                Upload a new file to create the next document version.
                The previous version will remain available in the version history.
            </p>

            <form
            action="{{ route('documents.replace', $document) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="replace-grid">

                    <div class="replace-group">

                        <label class="replace-label">
                            New File <span style="color:#dc3545;">*</span>
                        </label>

                        <input
                            type="file"
                            name="file"
                            class="replace-input"
                            required
                            accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
                        >

                        <div class="replace-hint">
                            PDF, JPG, PNG, WEBP, DOC or DOCX — maximum 10 MB.
                        </div>

                    </div>

                    <div class="replace-group">

                        <label class="replace-label">
                            DocumentNumber
                        </label>

                        <input
                            type="text"
                            name="document_number"
                            class="replace-input"
                            value="{{ $document->document_number }}"
                            maxlength="150"
                            placeholder="Enter document number"
                        >

                    </div>

                    <div class="replace-group">

                        <label class="replace-label">
                            Issue Date
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            class="replace-input"
                            value="{{ $document->issue_date?->format('Y-m-d') }}"
                        >

                    </div>

                    <div class="replace-group">

                        <label class="replace-label">
                            New Expiry Date
                        </label>

                        <input
                            type="date"
                            name="expiry_date"
                            class="replace-input"
                            value="{{ $document->expiry_date?->format('Y-m-d') }}"
                        >

                    </div>

                    <div class="replace-group">

                        <label class="replace-label">
                            Status <span style="color:#dc3545;">*</span>
                        </label>

                        <select
                            name="status"
                            class="replace-select"
                            required
                        >
                            <option
                                value="active"
                                {{ $document->status === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="expired"
                                {{ $document->status === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="expired"
                                {{ $document->status === 'expired' ? 'selected' : '' }}
                            >
                                Expired
                            </option>

                            <option
                                value="archived"
                                {{ $document->status === 'archived' ? 'selected' : '' }}
                            >
                                Archived
                            </option>
                        </select>

                    </div>

                    <div class="replace-group">

                        <label class="replace-label">
                            Notes
                        </label>

                        <input
                            type="text"
                            name="notes"
                            class="replace-input"
                            value="{{ $document->notes }}"
                            placeholder="Replacement notes"
                        >

                    </div>

                </div>

                <div class="replace-footer">

                    <button
                        type="submit"
                        class="btn-primary-erp"
                        onclick="return confirm('Replace this document and create a new version?');"
                    >
                        Replace & Create Version
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- NOTES --}}
    <div class="details-card notes-card">

        <div class="details-card-header">
            <h3 class="details-card-title">
                Notes
            </h3>
        </div>

        <div class="notes-content">
            {{ $document->notes ?: 'No notes have been added for this document.' }}
        </div>

    </div>
    {{-- VERSION HISTORY --}}
    <div class="details-card" style="margin-bottom:22px;">

        <div class="details-card-header">
            <h3 class="details-card-title">
                Version History
            </h3>
        </div>

        <div class="version-list">

            @if($document->parentDocument)

                <div class="version-item">

                    <div class="version-info">

                        <div class="version-number">
                            Previous Version — v{{ $document->parentDocument->version }}
                        </div>

                        <div class="version-date">
                            {{ $document->parentDocument->created_at->format('d M Y, h:i A') }}
                        </div>

                    </div>

                    @if($document->parentDocument->file_path)
                        <a
                            href="{{ asset('storage/' . $document->parentDocument->file_path) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="version-link"
                        >
                            Open File
                        </a>
                    @endif

                </div>

            @endif

            @forelse($document->versions as $version)

                <div class="version-item">

                    <div class="version-info">

                        <div class="version-number">
                            Version {{ $version->version }}

                            @if($version->is_current)
                                — Current
                            @endif
                        </div>

                        <div class="version-date">
                            {{ $version->created_at->format('d M Y, h:i A') }}
                        </div>

                    </div>

                    @if($version->file_path)
                        <a
                        href="{{ asset('storage/' . $version->file_path) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="version-link"
                        >
                            Open File
                        </a>
                    @endif

                </div>

            @empty

                @if(!$document->parentDocument)
                    <div class="empty-version">
                        No replacement versions available.
                    </div>
                @endif

            @endforelse

        </div>

    </div>

    {{-- DELETE --}}
    <div class="danger-zone">

        <h3 class="danger-zone-title">
            Document Actions
        </h3>

        <p class="danger-zone-text">
            Deleting this document will remove its stored file and associated version records.
        </p>

        <formaction="{{ route('documents.destroy', $document) }}"
            method="POST"
            onsubmit="return confirm('Are you sure you want to delete this document?');"
        >

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn-danger-erp"
            >
                Delete Document
            </button>

        </form>

    </div>

</div>

@endsection
