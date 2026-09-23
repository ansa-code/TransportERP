@extends('layouts.app')

@section('content')

<style>
    .document-show-page {
        padding: 0;
    }

    .document-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .document-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .document-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .document-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .document-btn {
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
    }

    .document-btn-back {
        background: #e5e7eb;
        color: #111827;
    }

    .document-btn-edit {
        background: #2563eb;
        color: #ffffff;
    }

    .document-btn-delete {
        background: #dc2626;
        color: #ffffff;
    }

    .document-hero {
        background: linear-gradient(135deg,#101d42,#193b8f);
        border-radius: 16px;
        padding: 28px;
        color: #ffffff;
        margin-bottom: 24px;
    }

    .document-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .document-hero-title {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 700;
    }

    .document-hero-number {
        margin: 0;
        color: rgba(255,255,255,.78);
        font-size: 14px;
    }

    .document-status {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .document-status-active {
        background: rgba(34,197,94,.18);
        color: #bbf7d0;
    }

    .document-status-expired {
        background: rgba(239,68,68,.18);
        color: #fecaca;
    }

    .document-status-other {
        background: rgba(255,255,255,.15);
        color: #ffffff;
    }

    .document-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .document-summary-card {
        background: rgba(37,99,235,.30);
        border: 1px solid rgba(255,255,255,.10);
        border-radius: 12px;
        padding: 16px;
    }

    .document-summary-label {
        display: block;
        color: rgba(255,255,255,.68);
        font-size: 12px;
        margin-bottom: 6px;
    }

    .document-summary-value {
        display: block;
        color: #ffffff;
        font-size: 16px;
        font-weight: 700;
        word-break: break-word;
    }

    .document-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .document-section-title {
        margin: 0 0 18px;
        color: #101d42;
        font-size: 18px;
        font-weight: 700;
    }

    .document-details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 28px;
    }

    .document-detail-item {
        min-width: 0;
    }

    .document-detail-label {
        display: block;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .document-detail-value {
        color: #111827;
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }

    .document-file-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #f9fafb;
    }

    .document-file-info {
        min-width: 0;
    }

    .document-file-name {
        margin: 0 0 4px;
        color: #111827;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
    }

    .document-file-meta {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
    }

    .document-file-link {
        flex-shrink: 0;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        padding: 8px 13px;
        font-size: 13px;
        font-weight: 600;
    }

    .document-notes {
        margin: 0;
        color: #374151;
        line-height: 1.7;
        white-space: pre-wrap;
    }

    .document-version-table-wrapper {
        overflow-x: auto;
    }

    .document-version-table {
        width: 100%;
        border-collapse: collapse;
    }

    .document-version-table th,
    .document-version-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        white-space: nowrap;
    }

    .document-version-table th {
        background: #f9fafb;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .document-version-table td {
        color: #4b5563;
        font-size: 13px;
    }

    .document-version-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 900px) {
        .document-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .document-header,
        .document-hero-top,
        .document-file-box {
            flex-direction: column;
            align-items: stretch;
        }

        .document-details-grid {
            grid-template-columns: 1fr;
        }

        .document-summary-grid {
            grid-template-columns: 1fr;
        }

        .document-actions {
            width: 100%;
        }

        .document-btn {
            flex: 1;
        }
    }
</style>

<div class="document-show-page">

    <div class="document-header">

        <div>
            <h1 class="document-title">
                Document Details
            </h1>

            <p class="document-subtitle">
                View document information, file and version history.
            </p>
        </div>

        <div class="document-actions">

            <a
                href="{{ route('documents.index') }}"
                class="document-btn document-btn-back"
            >
                ← Back
            </a>

            <a
                href="{{ route('documents.edit', $document) }}"
                class="document-btn document-btn-edit"
            >
                Edit
            </a>

        </div>

    </div>

    <div class="document-hero">

        <div class="document-hero-top">

            <div>
                <h2 class="document-hero-title">
                    {{ $document->document_type }}
                </h2>

                <p class="document-hero-number">
                    DocumentNumber: {{ $document->document_number ?? '—' }}
                </p>
            </div>

            @if($document->status === 'active')
                <span class="document-status document-status-active">
                    Active
                </span>
            @elseif($document->status === 'expired')
                <span class="document-status document-status-expired">
                    Expired
                </span>
            @else
                <span class="document-status document-status-other">
                    {{ ucfirst($document->status) }}
                </span>
            @endif

        </div>

        <div class="document-summary-grid">

            <div class="document-summary-card">
                <span class="document-summary-label">
                    Document Type
                </span>

                <span class="document-summary-value">
                    {{ $document->document_type }}
                </span>
            </div>

            <div class="document-summary-card">
                <span class="document-summary-label">
                    Version
                </span>

                <span class="document-summary-value">
                    {{ $document->version }}
                </span>
            </div>

            <div class="document-summary-card">
                <span class="document-summary-label">
                    Issue Date
                </span>

                <span class="document-summary-value">
                    {{ $document->issue_date ? \Carbon\Carbon::parse($document->issue_date)->format('d M Y') : '—' }}
                </span>
            </div>

            <div class="document-summary-card">
                <span class="document-summary-label">
                    Expiry Date
                </span>

                <span class="document-summary-value">
                    {{ $document->expiry_date ? \Carbon\Carbon::parse($document->expiry_date)->format('d M Y') : '—' }}
                </span>
            </div>

        </div>

    </div>

    <div class="document-section">

        <h3 class="document-section-title">
            Document Information
        </h3>

        <div class="document-details-grid">

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Document Type
                </span>

                <div class="document-detail-value">
                    {{ $document->document_type }}
                </div>

            </div>

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Document Number
                </span>

                <div class="document-detail-value">
                    {{ $document->document_number ?? '—' }}
                </div>

            </div>

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Issue Date
                </span>

                <div class="document-detail-value">
                    {{ $document->issue_date ? \Carbon\Carbon::parse($document->issue_date)->format('d M Y') : '—' }}
                </div>

            </div>

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Expiry Date
                </span>

                <div class="document-detail-value">
                    {{ $document->expiry_date ? \Carbon\Carbon::parse($document->expiry_date)->format('d M Y') : '—' }}
                </div>

            </div>

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Related Type
                </span>

                <div class="document-detail-value">
                    {{ class_basename($document->related_type) }}
                </div>

            </div>

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Related ID
                </span>

                <div class="document-detail-value">
                    {{ $document->related_id }}
                </div>

            </div>

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Version
                </span>

                <div class="document-detail-value">
                    {{ $document->version }}
                </div>

            </div>

            <div class="document-detail-item">

                <span class="document-detail-label">
                    Current Version
                </span>

                <div class="document-detail-value">
                    {{ $document->is_current ? 'Yes' : 'No' }}
                </div>

            </div>

        </div>

    </div>

    <div class="document-section">

        <h3 class="document-section-title">
            Document File
        </h3>

        @if($document->file_path)

            <div class="document-file-box">

                <div class="document-file-info">

                    <p class="document-file-name">
                        {{ $document->original_file_name ?? basename($document->file_path) }}
                    </p>

                    <p class="document-file-meta">
                        {{ $document->file_mime_type ?? 'File' }}
                    </p>

                </div>

                <a
                    href="{{ route('documents.file', $document) }}"
                    target="_blank"
                    class="document-file-link"
                >
                    View File
                </a>

            </div>

        @else

            <p class="document-notes">
                No file attached to this document.
            </p>

        @endif

    </div>
    <div class="document-section">

        <h3 class="document-section-title">
            Replace Document
        </h3>

        <form
            action="{{ route('documents.replace', $document) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="document-details-grid">

                <div class="document-detail-item">

                    <label
                        for="file"
                        class="document-detail-label"
                    >
                        New Document File
                    </label>

                    <input
                        type="file"
                        id="file"
                        name="file"
                        class="form-control"
                        required
                    >

                </div>

                <div class="document-detail-item">

                    <label
                        for="notes"
                        class="document-detail-label"
                    >
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        class="form-control"
                        rows="3"
                    ></textarea>

                </div>

            </div>

            <div style="margin-top: 18px;">

                <button
                    type="submit"
                    class="document-btn document-btn-edit"
                >
                    Replace Document
                </button>

            </div>

        </form>

    </div>

    @if($document->notes)

        <div class="document-section">

            <h3 class="document-section-title">
                Notes
            </h3>

            <p class="document-notes">
                {{ $document->notes }}
            </p>

        </div>

    @endif

    <div class="document-section">

        <h3 class="document-section-title">
            Version History
        </h3>

        @if($document->parentDocument)

            <div class="document-file-box" style="margin-bottom: 18px;">

                <div class="document-file-info">

                    <p class="document-file-name">
                        Previous Version
                    </p>

                    <p class="document-file-meta">
                        Version {{ $document->parentDocument->version }}
                    </p>

                </div>

                <a
                    href="{{ route('documents.file', $document->parentDocument) }}"
                    target="_blank"
                    class="document-file-link"
                >
                    View Previous
                </a>

            </div>

        @endif

        @if($document->versions && $document->versions->count())

            <div class="document-version-table-wrapper">

                <table class="document-version-table">

                    <thead>
                        <tr>
                            <th>
                                Version
                            </th>

                            <th>
                                File
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($document->versions as $version)

                            <tr>

                                <td>
                                    {{ $version->version }}
                                </td>

                                <td>
                                    {{ $version->original_file_name ?? basename($version->file_path) }}
                                </td>

                                <td>
                                    {{ $version->is_current ? 'Current' : 'Previous' }}
                                </td>

                                <td>
                                    {{ $version->created_at ? $version->created_at->format('d M Y H:i') : '—' }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route('documents.file', $version) }}"
                                        target="_blank"
                                        class="document-version-link"
                                    >
                                        View File
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <p class="document-notes">
                No previous versions available.
            </p>

        @endif

    </div>

    <div class="document-section">

        <h3 class="document-section-title">
            Delete Document
        </h3>

        <p class="document-notes" style="margin-bottom: 16px;">
            Deleting this document will permanently remove the document record.
        </p>

        <formaction="{{ route('documents.destroy', $document) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this document?');">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="document-btn document-btn-delete"
            >
                Delete Document
            </button>

        </form>

    </div>

</div>

@endsection