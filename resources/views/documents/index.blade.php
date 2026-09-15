@extends('layouts.app')

@section('content')

<style>
    .documents-page {
        padding: 0;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .documents-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .documents-title {
        margin: 0 0 5px;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .documents-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .upload-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 16px;
        border: 1px solid #193b8f;
        border-radius: 7px;
        background: linear-gradient(
            135deg,
            #101d42,
            #193b8f
        );
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: .2s;
    }

    .upload-btn:hover {
        background: #101d42;
        color: #fff;
    }


    /* =========================================================
       KPI CARDS — INDEX = WHITE
    ========================================================= */

    .documents-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 24px;
    }

    .documents-kpi {
        position: relative;
        overflow: hidden;
        min-height: 112px;
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(15,23,42,.07);
    }

    .documents-kpi::after {
        content: "";
        position: absolute;
        width: 88px;
        height: 88px;
        right: -28px;
        top: -28px;
        border-radius: 50%;
        background: rgba(37,99,235,.05);
    }

    .documents-kpi-label {
        position: relative;
        z-index: 1;
        margin-bottom: 9px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .documents-kpi-value {
        position: relative;
        z-index: 1;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .documents-kpi-note {
        position: relative;
        z-index: 1;
        margin-top: 8px;
        color: #94a3b8;
        font-size: 11px;
    }


    /* =========================================================
       EXPIRY CENTER
    ========================================================= */

    .expiry-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 11px;
        box-shadow: 0 4px 16px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .expiry-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 18px 20px;
        border-bottom: 1px solid #eef0f3;
    }

    .expiry-card-title {
        margin: 0;
        color: #101d42;
        font-size: 17px;
        font-weight: 700;
    }

    .expiry-card-subtitle {
        margin: 4px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }


    /* =========================================================
       FILTERS
    ========================================================= */

    .expiry-filters {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #eef0f3;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
    }

    .filter-select {
        width: 190px;
        height: 38px;
        padding: 0 10px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #374151;
        font-size: 12px;
        outline: none;
    }

    .filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.10);
    }

    .filter-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 13px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .filter-reset:hover {
        background: #f1f5f9;
        color: #101d42;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .documents-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .documents-table th {
        padding: 13px 16px;
        background: #f8fafc;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .documents-table td {
        padding: 14px 16px;
        color: #374151;
        font-size: 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .documents-table tbody tr:hover {
        background: #fafcff;
    }

    .document-name {
        color: #101d42;
        font-weight: 700;
    }

    .document-number {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 10px;
    }

    .related-name {
        color: #1f2937;
        font-weight: 600;
    }

    .related-type {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 10px;
    }

    .expiry-date {
        color: #374151;
        font-weight: 600;
    }

    .expiry-date.expired {
        color: #dc3545;
    }

    .expiry-date.warning {
        color: #d97706;
    }


    /* =========================================================
       REMINDER BADGES
    ========================================================= */

    .reminder-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .reminder-danger {
        background: rgba(220,53,69,.12);
        color: #b42333;
    }

    .reminder-warning {
        background: rgba(245,158,11,.14);
        color: #a16207;
    }

    .reminder-normal {
        background: rgba(25,135,84,.12);
        color: #147044;
    }


    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 65px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-active {
        background: rgba(25,135,84,.12);
        color: #147044;
    }

    .status-expired {
        background: rgba(220,53,69,.12);
        color: #b42333;
    }

    .status-archived {
        background: rgba(100,116,139,.12);
        color: #475569;
    }

                /* =========================================================
   ACTIONS
========================================================= */

.document-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.action-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-height: 32px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
}


/* =========================================================
   EDIT — MEDIUM BLUE
========================================================= */

.action-edit {
    background: #2563eb !important;
    background-color: #2563eb !important;
    border: 1px solid #2563eb !important;
    color: #ffffff !important;
}

.action-edit:hover {
    background: #1d4ed8 !important;
    background-color: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
    color: #ffffff !important;
}


/* =========================================================
   DELETE — RED
========================================================= */

.action-delete {
    background: #dc3545 !important;
    background-color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    color: #ffffff !important;
}

.action-delete:hover {
    background: #b02a37 !important;
    background-color: #b02a37 !important;
    border-color: #b02a37 !important;
    color: #ffffff !important;
}


/* =========================================================
   CLICKABLE DOCUMENT
========================================================= */

.document-link {
    display: block;
    color: inherit;
    text-decoration: none;
}

.document-link:hover .document-name {
    color: #2563eb;
    text-decoration: underline;
}
    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-documents {
        padding: 65px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        border-radius: 50%;
        background: rgba(37,99,235,.10);
        font-size: 27px;
    }

    .empty-documents h3 {
        margin: 0 0 6px;
        color: #101d42;
        font-size: 18px;
        font-weight: 700;
    }

    .empty-documents p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1000px) {

        .documents-kpi-grid {
            grid-template-columns: repeat(2, minmax(0,1fr));
        }

    }

    @media (max-width: 700px) {

        .documents-header {
            flex-direction: column;
        }

        .upload-btn {
            width: 100%;
        }

        .documents-kpi-grid {
            grid-template-columns: 1fr;
        }

        .expiry-filters {
            align-items: stretch;
            flex-direction: column;
        }

        .filter-select {
            width: 100%;
        }

        .filter-reset {
            width: 100%;
        }

    }
</style>


<div class="documents-page">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="documents-header">

        <div>

            <h1 class="documents-title">
                Documents & Expiry Center
            </h1>

            <p class="documents-subtitle">
                Manage vehicle, driver, client and vendor documents with expiry tracking.
            </p>

        </div>


        <a
            href="{{ route('documents.create') }}"
            class="upload-btn"
        >
            + Upload Document
        </a>

    </div>


    <!-- =========================================================
         KPI CARDS
    ========================================================== -->

    <div class="documents-kpi-grid">

        <div class="documents-kpi">

            <div class="documents-kpi-label">
                Visa Expiry
            </div>

            <div class="documents-kpi-value">
                {{ $stats['visa_expiry'] ?? 0 }}
            </div>

            <div class="documents-kpi-note">
                Current visa documents
            </div>

        </div>


        <div class="documents-kpi">

            <div class="documents-kpi-label">
                License Expiry
            </div>

            <div class="documents-kpi-value">
                {{ $stats['license_expiry'] ?? 0 }}
            </div>

            <div class="documents-kpi-note">
                Current license documents
            </div>

        </div>


        <div class="documents-kpi">

            <div class="documents-kpi-label">
                Truck Registration
            </div>

            <div class="documents-kpi-value">
                {{ $stats['registration_expiry'] ?? 0 }}
            </div>

            <div class="documents-kpi-note">
                Current registration documents
            </div>

        </div>


        <div class="documents-kpi">

            <div class="documents-kpi-label">
                Insurance Expiry
            </div>

            <div class="documents-kpi-value">
                {{ $stats['insurance_expiry'] ?? 0 }}
            </div>

            <div class="documents-kpi-note">
                Current insurance documents
            </div>

        </div>

    </div>


    <!-- =========================================================
         EXPIRY CENTER
    ========================================================== -->

    <div class="expiry-card">

        <div class="expiry-card-header">

            <div>

                <h2 class="expiry-card-title">
                    Document Expiry Center
                </h2>

                <p class="expiry-card-subtitle">
                    Track active documents and upcoming expiry dates.
                </p>

            </div>

        </div>


        <!-- FILTERS -->

        <div class="expiry-filters">

            <div class="filter-group">

                <label
                    for="documentTypeFilter"
                    class="filter-label"
                >
                    Document Type
                </label>

                <select
                    id="documentTypeFilter"
                    class="filter-select"
                >

                    <option value="">
                        All Document Types
                    </option>

                    <option value="Visa">
                        Visa
                    </option>

                    <option value="License">
                        License
                    </option>

                    <option value="Registration">
                        Registration
                    </option>

                    <option value="Insurance">
                        Insurance
                    </option>

                    <option value="Contract">
                        Contract
                    </option>

                    <option value="Other">
                        Other
                    </option>

                </select>

            </div>


            <div class="filter-group">

                <label
                    for="duePeriodFilter"
                    class="filter-label"
                >
                    Due Period
                </label>

                <select
                    id="duePeriodFilter"
                    class="filter-select"
                >

                    <option value="">
                        All
                    </option>

                    <option value="overdue">
                        Overdue
                    </option>

                    <option value="7">
                        Within 7 Days
                    </option>

                    <option value="15">
                        Within 15 Days
                    </option>

                    <option value="30">
                        Within 30 Days
                    </option>

                </select>

            </div>


            <button
                type="button"
                class="filter-reset"
                id="resetDocumentFilters"
            >
                Reset
            </button>

        </div>
        <!-- =========================================================
             DOCUMENT TABLE
        ========================================================== -->

        <div class="table-wrapper">

            <table
                class="documents-table"
                id="documentsTable"
            >

                <thead>

                    <tr>

                        <th>Document</th>
                        <th>Related Entity</th>
                        <th>Issue Date</th>
                        <th>Expiry Date</th>
                        <th>Reminder</th>
                        <th>Status</th>
                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($documents as $document)

                        @php

                            /*
                             * Expiry calculation
                             */

                            $expiryDate = $document->expiry_date;

                            $daysRemaining = null;

                            if ($expiryDate) {

                                $daysRemaining = now()
                                    ->startOfDay()
                                    ->diffInDays(
                                        $expiryDate,
                                        false
                                    );

                            }


                            /*
                             * Reminder state
                             */

                            if ($daysRemaining === null) {

                                $reminderText = 'No Expiry';
                                $reminderClass = 'reminder-normal';
                                $expiryClass = '';

                            } elseif ($daysRemaining < 0) {

                                $reminderText = 'Overdue';
                                $reminderClass = 'reminder-danger';
                                $expiryClass = 'expired';

                            } elseif ($daysRemaining <= 7) {

                                $reminderText = 'Within 7 Days';
                                $reminderClass = 'reminder-danger';
                                $expiryClass = 'warning';

                            } elseif ($daysRemaining <= 15) {

                                $reminderText = 'Within 15 Days';
                                $reminderClass = 'reminder-warning';
                                $expiryClass = 'warning';

                            } elseif ($daysRemaining <= 30) {

                                $reminderText = 'Within 30 Days';
                                $reminderClass = 'reminder-warning';
                                $expiryClass = 'warning';

                            } else {

                                $reminderText = 'Normal';
                                $reminderClass = 'reminder-normal';
                                $expiryClass = '';

                            }


                            /*
                             * Related entity
                             */

                            $related = $document->related;

                            $relatedName = 'Not Available';
                            $relatedType = 'Unknown';


                            if ($related) {

                                $relatedType =
                                    class_basename(
                                        $document->related_type
                                    );


                                $relatedName = match ($relatedType) {

                                    'Vehicle' =>
                                        $related->plate_number
                                        ?? $related->vehicle_code
                                        ?? 'Vehicle #' . $related->id,

                                    'Driver' =>
                                        $related->driver_name
                                        ?? 'Driver #' . $related->id,

                                    'Client' =>
                                        $related->client_name
                                        ?? $related->company_name
                                        ?? 'Client #' . $related->id,

                                    'Vendor' =>
                                        $related->vendor_name
                                        ?? $related->company_name
                                        ?? 'Vendor #' . $related->id,

                                    default =>
                                        'Record #' . $related->id,

                                };

                            }


                            /*
                             * Status
                             */

                            $status =
                                strtolower(
                                    $document->status ?? 'active'
                                );

                        @endphp


                        <tr
                            class="document-row"
                            data-document-type="{{ strtolower($document->document_type ?? '') }}"
                            data-days="{{ $daysRemaining !== null ? $daysRemaining : '' }}"
                        >


                            <!-- =================================================
                                 DOCUMENT
                                 CLICK = SHOW PAGE
                            ================================================== -->

                            <td>

                                <a
                                    href="{{ route('documents.show', $document) }}"
                                    class="document-link"
                                >

                                    <div class="document-name">
                                        {{ $document->document_type }}
                                    </div>

                                    @if($document->document_number)

                                        <div class="document-number">
                                            {{ $document->document_number }}
                                        </div>

                                    @endif

                                </a>

                            </td>


                            <!-- =================================================
                                 RELATED ENTITY
                            ================================================== -->

                            <td>

                                <div class="related-name">
                                    {{ $relatedName }}
                                </div>

                                <div class="related-type">
                                    {{ $relatedType }}
                                </div>

                            </td>


                            <!-- =================================================
                                 ISSUE DATE
                            ================================================== -->

                            <td>

                                @if($document->issue_date)

                                    {{ $document->issue_date->format('d M Y') }}

                                @else

                                    <span style="color:#94a3b8;">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 EXPIRY DATE
                            ================================================== -->

                            <td>

                                @if($expiryDate)

                                    <span
                                        class="expiry-date {{ $expiryClass }}"
                                    >
                                        {{ $expiryDate->format('d M Y') }}
                                    </span>

                                @else

                                    <span style="color:#94a3b8;">
                                        No Expiry
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 REMINDER
                            ================================================== -->

                            <td>

                                <span
                                    class="reminder-badge {{ $reminderClass }}"
                                >
                                    {{ $reminderText }}
                                </span>

                            </td>


                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                            <td>

                                <span
                                    class="status-badge status-{{ $status }}"
                                >
                                    {{ ucfirst($status) }}
                                </span>

                            </td>


                            <!-- =================================================
                                 ACTIONS
                                 EDIT + DELETE ONLY
                            ================================================== -->

                            <td>

                                <div class="document-actions">

                                    <!-- EDIT -->

                                    <a
                                        href="{{ route('documents.edit', $document) }}"
                                        class="action-btn action-edit"
                                    >
                                        Edit
                                    </a>


                                    <!-- DELETE -->

                                    <form
                                        action="{{ route('documents.destroy', $document) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this document?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="empty-documents">

                                    <div class="empty-icon">
                                        📄
                                    </div>

                                    <h3>
                                        No Documents Found
                                    </h3>

                                    <p>
                                        No documents have been uploaded yet.
                                        Use the Upload Document button to add the first document.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- =========================================================
     FILTER JAVASCRIPT
========================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const typeFilter =
        document.getElementById('documentTypeFilter');

    const dueFilter =
        document.getElementById('duePeriodFilter');

    const resetButton =
        document.getElementById('resetDocumentFilters');

    const rows =
        document.querySelectorAll('.document-row');


    function filterDocuments() {

        const selectedType =
            typeFilter
                ? typeFilter.value.toLowerCase()
                : '';

        const selectedDue =
            dueFilter
                ? dueFilter.value
                : '';


        rows.forEach(function (row) {

            const rowType =
                row.dataset.documentType || '';

            const daysValue =
                row.dataset.days;

            const days =
                daysValue !== ''
                    ? parseInt(daysValue, 10)
                    : null;


            let typeMatches = true;
            let dueMatches = true;


            /*
             * Document Type
             */

            if (selectedType !== '') {

                typeMatches =
                    rowType === selectedType;

            }


            /*
             * Due Period
             */

            if (selectedDue !== '') {

                if (days === null) {

                    dueMatches = false;

                } else if (selectedDue === 'overdue') {

                    dueMatches = days < 0;

                } else {

                    const limit =
                        parseInt(selectedDue, 10);

                    dueMatches =
                        days >= 0 &&
                        days <= limit;

                }

            }


            row.style.display =
                typeMatches && dueMatches
                    ? ''
                    : 'none';

        });

    }


    /*
     * Document Type filter
     */

    if (typeFilter) {

        typeFilter.addEventListener(
            'change',
            filterDocuments
        );

    }


    /*
     * Due Period filter
     */

    if (dueFilter) {

        dueFilter.addEventListener(
            'change',
            filterDocuments
        );

    }


    /*
     * Reset filters
     */

    if (resetButton) {

        resetButton.addEventListener(
            'click',
            function () {

                if (typeFilter) {
                    typeFilter.value = '';
                }

                if (dueFilter) {
                    dueFilter.value = '';
                }

                filterDocuments();

            }
        );

    }

});

</script>

@endsection