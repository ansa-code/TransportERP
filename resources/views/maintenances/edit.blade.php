@extends('layouts.app')

@section('content')

<div class="maintenance-form-page">

    {{-- PAGE HEADER --}}
    <div class="maintenance-form-header">

        <div>

            <div class="maintenance-back">
                <a href="{{ route('maintenances.index') }}">
                    ← Back to Maintenance
                </a>
            </div>

            <h1>Edit Maintenance</h1>

            <p>
                Update maintenance job, repair types, costs and service information.
            </p>

        </div>

        <div class="maintenance-number-box">
            <span>Maintenance No.</span>
            <strong>{{ $maintenance->maintenance_no }}</strong>
        </div>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())

        <div class="maintenance-alert">

            <div class="maintenance-alert-title">
                Please fix the following errors:
            </div>

            <ul>
                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach
            </ul>

        </div>

    @endif


    @php

        $repairTypes = [
            'Preventive',
            'Corrective',
            'Breakdown',
            'Tyre',
            'Battery',
            'Washing',
            'Brake',
            'Oil Change',
            'AC Repair',
            'Electrical',
            'Other',
        ];

        $selectedRepairTypes = old(
            'repair_type',
            $maintenance->repair_type
                ? array_map('trim', explode(',', $maintenance->repair_type))
                : []
        );

        if (!is_array($selectedRepairTypes)) {
            $selectedRepairTypes = [];
        }

    @endphp


    <form
        action="{{ route('maintenances.update', $maintenance->id) }}"
        method="POST"
        enctype="multipart/form-data"
        id="maintenanceForm"
    >

        @csrf

        @method('PUT')


        <div class="maintenance-form-card">

            <div class="maintenance-form-card-header">

                <div>
                    <h2>Maintenance Information</h2>

                    <p>
                        Vehicle and repair job details
                    </p>
                </div>

            </div>


            <div class="maintenance-form-grid">


                {{-- MAINTENANCE NUMBER --}}
                <div class="maintenance-field">

                    <label for="maintenance_no">
                        Maintenance No.
                    </label>

                    <input
                        type="text"
                        id="maintenance_no"
                        class="maintenance-input"
                        value="{{ $maintenance->maintenance_no }}"
                        readonly
                    >

                    <small class="maintenance-help-text">
                        Auto-generated maintenance number.
                    </small>

                </div>


                {{-- VEHICLE --}}
                <div class="maintenance-field">

                    <label for="vehicle_id">
                        Vehicle <span>*</span>
                    </label>

                    <select
                        name="vehicle_id"
                        id="vehicle_id"
                        class="maintenance-input"
                        required
                    >

                        <option value="">
                            Select Vehicle
                        </option>

                        @foreach ($vehicles as $vehicle)

                            <option
                                value="{{ $vehicle->id }}"
                                {{ old('vehicle_id', $maintenance->vehicle_id) == $vehicle->id ? 'selected' : '' }}
                            >
                                {{ $vehicle->plate_number }}
                            </option>

                        @endforeach

                    </select>

                    @error('vehicle_id')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- MAINTENANCE DATE --}}
                <div class="maintenance-field">

                    <label for="maintenance_date">
                        Maintenance Date <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="maintenance_date"
                        id="maintenance_date"
                        class="maintenance-input"
                        value="{{ old('maintenance_date', optional($maintenance->maintenance_date)->format('Y-m-d')) }}"
                        required
                    >

                    @error('maintenance_date')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- REPAIR TYPE --}}
                <div class="maintenance-field repair-type-field">

                    <label>
                        Repair Type <span>*</span>
                    </label>

                    <div
                        class="repair-type-select"
                        id="repairTypeSelect"
                    >

                        <button
                            type="button"
                            class="repair-type-trigger"
                            id="repairTypeTrigger"
                        >

                            <span
                                id="repairTypePlaceholder"
                                class="{{ count($selectedRepairTypes) ? 'has-selection' : '' }}"
                            >
                                {{ count($selectedRepairTypes) ? count($selectedRepairTypes) . ' repair type(s) selected' : 'Select repair type(s)' }}
                            </span>

                            <span class="repair-type-arrow">
                                ▼
                            </span>

                        </button>


                        <div
                            class="repair-type-dropdown"
                            id="repairTypeDropdown"
                        >

                            @foreach ($repairTypes as $type)

                                <label class="repair-type-option">

                                    <input
                                        type="checkbox"
                                        name="repair_type[]"
                                        value="{{ $type }}"
                                        {{ in_array($type, $selectedRepairTypes) ? 'checked' : '' }}
                                    >

                                    <span class="repair-type-check"></span>

                                    <span class="repair-type-option-text">
                                        {{ $type }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>


                    <div
                        class="repair-type-selected"
                        id="repairTypeSelected"
                    ></div>


                    <small class="maintenance-help-text">
                        Select one or more repair types.
                    </small>


                    @error('repair_type')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror


                    @error('repair_type.*')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- WORKSHOP --}}
                <div class="maintenance-field">

                    <label for="workshop">
                        Workshop / Vendor
                    </label>

                    <input
                        type="text"
                        name="workshop"
                        id="workshop"
                        class="maintenance-input"
                        value="{{ old('workshop', $maintenance->workshop) }}"
                        placeholder="Enter workshop or vendor name"
                    >

                    @error('workshop')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- ODOMETER --}}
                <div class="maintenance-field">

                    <label for="odometer">
                        Odometer
                    </label>

                    <input
                        type="number"
                        name="odometer"
                        id="odometer"
                        class="maintenance-input"
                        value="{{ old('odometer', $maintenance->odometer) }}"
                        min="0"
                        placeholder="Current odometer"
                    >

                    @error('odometer')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- STATUS --}}
                <div class="maintenance-field">

                    <label for="status">
                        Status <span>*</span>
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="maintenance-input"
                        required
                    >

                        <option
                            value="Open"
                            {{ old('status', $maintenance->status) == 'Open' ? 'selected' : '' }}
                        >
                            Open
                        </option>

                        <option
                            value="In Progress"
                            {{ old('status', $maintenance->status) == 'In Progress' ? 'selected' : '' }}
                        >
                            In Progress
                        </option>

                        <option
                            value="Completed"
                            {{ old('status', $maintenance->status) == 'Completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                        <option
                            value="Cancelled"
                            {{ old('status', $maintenance->status) == 'Cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                    @error('status')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- PARTS COST --}}
                <div class="maintenance-field">

                    <label for="parts_cost">
                        Parts Cost
                    </label>

                    <input
                        type="number"
                        name="parts_cost"
                        id="parts_cost"
                        class="maintenance-input cost-input"
                        value="{{ old('parts_cost', $maintenance->parts_cost) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                    @error('parts_cost')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- LABOUR COST --}}
                <div class="maintenance-field">

                    <label for="labour_cost">
                        Labour Cost
                    </label>

                    <input
                        type="number"
                        name="labour_cost"
                        id="labour_cost"
                        class="maintenance-input cost-input"
                        value="{{ old('labour_cost', $maintenance->labour_cost) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                    @error('labour_cost')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- OTHER COST --}}
                <div class="maintenance-field">

                    <label for="other_cost">
                        Other Cost
                    </label>

                    <input
                        type="number"
                        name="other_cost"
                        id="other_cost"
                        class="maintenance-input cost-input"
                        value="{{ old('other_cost', $maintenance->other_cost) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                    @error('other_cost')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- TOTAL COST --}}
                <div class="maintenance-field">

                    <label for="total_cost">
                        Total Cost
                    </label>

                    <input
                        type="number"
                        name="total_cost"
                        id="total_cost"
                        class="maintenance-input maintenance-total-input"
                        value="{{ old('total_cost', $maintenance->total_cost) }}"
                        readonly
                    >

                    <small class="maintenance-help-text">
                        Automatically calculated from Parts + Labour + Other Cost.
                    </small>

                </div>


                {{-- OUT OF SERVICE START --}}
                <div class="maintenance-field">

                    <label for="out_of_service_start">
                        Out of Service Start
                    </label>

                    <input
                        type="date"
                        name="out_of_service_start"
                        id="out_of_service_start"
                        class="maintenance-input"
                        value="{{ old('out_of_service_start', optional($maintenance->out_of_service_start)->format('Y-m-d')) }}"
                    >

                    @error('out_of_service_start')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- OUT OF SERVICE END --}}
                <div class="maintenance-field">

                    <label for="out_of_service_end">
                        Out of Service End
                    </label>

                    <input
                        type="date"
                        name="out_of_service_end"
                        id="out_of_service_end"
                        class="maintenance-input"
                        value="{{ old('out_of_service_end', optional($maintenance->out_of_service_end)->format('Y-m-d')) }}"
                    >

                    @error('out_of_service_end')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- NEXT SERVICE DATE --}}
                <div class="maintenance-field">

                    <label for="next_service_date">
                        Next Service Date
                    </label>

                    <input
                        type="date"
                        name="next_service_date"
                        id="next_service_date"
                        class="maintenance-input"
                        value="{{ old('next_service_date', optional($maintenance->next_service_date)->format('Y-m-d')) }}"
                    >

                    @error('next_service_date')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- INVOICE / RECEIPT --}}
                <div class="maintenance-field">

                    <label for="invoice_receipt">
                        Invoice / Receipt
                    </label>

                    <input
                        type="file"
                        name="invoice_receipt"
                        id="invoice_receipt"
                        class="maintenance-input"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >

                    @if ($maintenance->invoice_receipt)

                        <small class="maintenance-help-text">
                            Existing file is already attached. Upload a new file only if you want to replace it.
                        </small>

                    @else

                        <small class="maintenance-help-text">
                            JPG, JPEG, PNG or PDF. Maximum 5 MB.
                        </small>

                    @endif

                    @error('invoice_receipt')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- REMARKS --}}
                <div class="maintenance-field maintenance-field-full">

                    <label for="remarks">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        id="remarks"
                        class="maintenance-textarea"
                        rows="5"
                        placeholder="Enter maintenance remarks..."
                    >{{ old('remarks', $maintenance->remarks) }}</textarea>

                    @error('remarks')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

            </div>


            {{-- FORM ACTIONS --}}

            <div class="maintenance-form-actions">

                <a
                    href="{{ route('maintenances.index') }}"
                    class="maintenance-cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="maintenance-save-btn"
                >
                    Update Maintenance
                </button>

            </div>

        </div>

    </form>

</div>
<style>

/* =========================
   PAGE
========================= */

.maintenance-form-page {
    max-width: 1200px;
    margin: 0 auto;
    padding-bottom: 40px;
}


/* =========================
   HEADER
========================= */

.maintenance-form-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.maintenance-back {
    margin-bottom: 8px;
}

.maintenance-back a {
    color: #2563eb;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.maintenance-back a:hover {
    text-decoration: underline;
}

.maintenance-form-header h1 {
    margin: 0;
    color: #0f172a;
    font-size: 30px;
    font-weight: 800;
}

.maintenance-form-header p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 14px;
}

.maintenance-number-box {
    min-width: 190px;
    padding: 14px 16px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
}

.maintenance-number-box span {
    display: block;
    margin-bottom: 5px;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}

.maintenance-number-box strong {
    color: #1d4ed8;
    font-size: 15px;
}


/* =========================
   ALERT
========================= */

.maintenance-alert {
    margin-bottom: 20px;
    padding: 15px 18px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    color: #991b1b;
}

.maintenance-alert-title {
    margin-bottom: 7px;
    font-weight: 800;
}

.maintenance-alert ul {
    margin: 0;
    padding-left: 20px;
}

.maintenance-alert li {
    margin-bottom: 3px;
    font-size: 13px;
}


/* =========================
   FORM CARD
========================= */

.maintenance-form-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
    overflow: visible;
}

.maintenance-form-card-header {
    padding: 22px 24px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}

.maintenance-form-card-header h2 {
    margin: 0;
    color: #0f172a;
    font-size: 18px;
    font-weight: 800;
}

.maintenance-form-card-header p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 13px;
}


/* =========================
   FORM GRID
========================= */

.maintenance-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    padding: 24px;
}

.maintenance-field {
    min-width: 0;
}

.maintenance-field-full {
    grid-column: 1 / -1;
}

.maintenance-field label {
    display: block;
    margin-bottom: 7px;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
}

.maintenance-field label span {
    color: #dc2626;
}

.maintenance-input,
.maintenance-textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    background: #ffffff;
    color: #0f172a;
    font-size: 14px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}

.maintenance-input {
    height: 40px;
    padding: 0 12px;
}

.maintenance-textarea {
    padding: 11px 12px;
    resize: vertical;
    min-height: 110px;
    font-family: inherit;
}

.maintenance-input:focus,
.maintenance-textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
}

.maintenance-input[readonly] {
    background: #f8fafc;
    color: #475569;
    cursor: not-allowed;
}

.maintenance-help-text {
    display: block;
    margin-top: 6px;
    color: #94a3b8;
    font-size: 11px;
    line-height: 1.5;
}

.maintenance-field-error {
    display: block;
    margin-top: 6px;
    color: #dc2626;
    font-size: 12px;
    font-weight: 600;
}


/* =========================
   REPAIR TYPE SELECT
========================= */

.repair-type-field {
    position: relative;
}

.repair-type-select {
    position: relative;
}

.repair-type-trigger {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    background: #ffffff;
    color: #64748b;
    cursor: pointer;
    font-size: 14px;
    text-align: left;
}

.repair-type-trigger:hover {
    border-color: #94a3b8;
}

.repair-type-trigger:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
}

.repair-type-trigger .has-selection {
    color: #0f172a;
    font-weight: 600;
}

.repair-type-arrow {
    color: #64748b;
    font-size: 10px;
    transition: transform .2s;
}

.repair-type-select.open .repair-type-arrow {
    transform: rotate(180deg);
}

.repair-type-dropdown {
    display: none;
    position: absolute;
    z-index: 100;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    max-height: 250px;
    overflow-y: auto;
    padding: 7px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .14);
}

.repair-type-select.open .repair-type-dropdown {
    display: block;
}

.repair-type-option {
    display: flex !important;
    align-items: center;
    gap: 10px;
    margin: 0 !important;
    padding: 9px 10px;
    border-radius: 7px;
    color: #334155 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    cursor: pointer;
}

.repair-type-option:hover {
    background: #f1f5f9;
}

.repair-type-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.repair-type-check {
    width: 17px;
    height: 17px;
    flex: 0 0 17px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    background: #ffffff;
}

.repair-type-option input:checked + .repair-type-check {
    background: #2563eb;
    border-color: #2563eb;
}

.repair-type-option input:checked + .repair-type-check::after {
    content: "✓";
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
}

.repair-type-option-text {
    flex: 1;
}

.repair-type-selected {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}

.repair-type-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 999px;
    color: #1d4ed8;
    font-size: 11px;
    font-weight: 700;
}

.repair-type-chip button {
    width: 15px;
    height: 15px;
    padding: 0;
    border: 0;
    background: transparent;
    color: #2563eb;
    cursor: pointer;
    font-size: 13px;
    line-height: 15px;
}


/* =========================
   TOTAL COST
========================= */

.maintenance-total-input {
    background: #eff6ff !important;
    border-color: #bfdbfe !important;
    color: #1d4ed8 !important;
    font-weight: 800;
}


/* =========================
   FORM ACTIONS
========================= */

.maintenance-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 24px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 0 0 16px 16px;
}

.maintenance-cancel-btn,
.maintenance-save-btn {
    min-height: 40px;
    padding: 0 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}

.maintenance-cancel-btn {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
}

.maintenance-cancel-btn:hover {
    background: #f1f5f9;
}

.maintenance-save-btn {
    border: 1px solid #2563eb;
    background: #2563eb;
    color: #ffffff;
}

.maintenance-save-btn:hover {
    background: #1d4ed8;
}


/* =========================
   MOBILE
========================= */

@media (max-width: 800px) {

    .maintenance-form-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .maintenance-number-box {
        width: 100%;
        box-sizing: border-box;
    }

    .maintenance-form-grid {
        grid-template-columns: 1fr;
    }

    .maintenance-field-full {
        grid-column: auto;
    }

}

@media (max-width: 520px) {

    .maintenance-form-page {
        padding-bottom: 20px;
    }

    .maintenance-form-header h1 {
        font-size: 24px;
    }

    .maintenance-form-card-header,
    .maintenance-form-grid,
    .maintenance-form-actions {
        padding-left: 16px;
        padding-right: 16px;
    }

    .maintenance-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .maintenance-cancel-btn,
    .maintenance-save-btn {
        width: 100%;
    }

}

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================
       REPAIR TYPE DROPDOWN
    ========================== */

    const repairTypeSelect = document.getElementById('repairTypeSelect');
    const repairTypeTrigger = document.getElementById('repairTypeTrigger');
    const repairTypePlaceholder = document.getElementById('repairTypePlaceholder');
    const repairTypeDropdown = document.getElementById('repairTypeDropdown');
    const repairTypeSelected = document.getElementById('repairTypeSelected');

    function updateRepairTypeDisplay() {

        if (!repairTypeDropdown || !repairTypePlaceholder || !repairTypeSelected) {
            return;
        }

        const checkedBoxes = repairTypeDropdown.querySelectorAll(
            'input[name="repair_type[]"]:checked'
        );

        repairTypeSelected.innerHTML = '';

        if (checkedBoxes.length === 0) {

            repairTypePlaceholder.textContent = 'Select repair type(s)';
            repairTypePlaceholder.classList.remove('has-selection');

            return;
        }

        repairTypePlaceholder.textContent =
            checkedBoxes.length + ' repair type(s) selected';

        repairTypePlaceholder.classList.add('has-selection');

        checkedBoxes.forEach(function (checkbox) {

            const chip = document.createElement('span');

            chip.className = 'repair-type-chip';

            chip.innerHTML =
                '<span>' +
                checkbox.value +
                '</span>' +
                '<button type="button" aria-label="Remove">' +
                '×' +
                '</button>';

            const removeButton = chip.querySelector('button');

            removeButton.addEventListener('click', function (event) {

                event.stopPropagation();

                checkbox.checked = false;

                updateRepairTypeDisplay();

            });

            repairTypeSelected.appendChild(chip);

        });

    }


    if (repairTypeTrigger && repairTypeSelect) {

        repairTypeTrigger.addEventListener('click', function (event) {

            event.stopPropagation();

            repairTypeSelect.classList.toggle('open');

        });

    }


    if (repairTypeDropdown) {

        repairTypeDropdown
            .querySelectorAll('input[name="repair_type[]"]')
            .forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {

                    updateRepairTypeDisplay();

                });

            });

    }


    document.addEventListener('click', function (event) {

        if (
            repairTypeSelect &&
            !repairTypeSelect.contains(event.target)
        ) {

            repairTypeSelect.classList.remove('open');

        }

    });


    updateRepairTypeDisplay();


    /* =========================
       TOTAL COST
    ========================== */

    const partsCost = document.getElementById('parts_cost');
    const labourCost = document.getElementById('labour_cost');
    const otherCost = document.getElementById('other_cost');
    const totalCost = document.getElementById('total_cost');

    function calculateTotalCost() {

        const parts = parseFloat(partsCost?.value) || 0;
        const labour = parseFloat(labourCost?.value) || 0;
        const other = parseFloat(otherCost?.value) || 0;

        const total = parts + labour + other;

        if (totalCost) {
            totalCost.value = total.toFixed(2);
        }

    }


    [partsCost, labourCost, otherCost].forEach(function (input) {

        if (input) {

            input.addEventListener('input', calculateTotalCost);

        }

    });


    calculateTotalCost();


    /* =========================
       OUT OF SERVICE DATES
    ========================== */

    const outStart = document.getElementById('out_of_service_start');
    const outEnd = document.getElementById('out_of_service_end');

    function updateOutOfServiceDates() {

        if (!outStart || !outEnd) {
            return;
        }

        if (outStart.value) {
            outEnd.min = outStart.value;
        } else {
            outEnd.removeAttribute('min');
        }

        if (outEnd.value) {
            outStart.max = outEnd.value;
        } else {
            outStart.removeAttribute('max');
        }

    }


    if (outStart) {
        outStart.addEventListener('change', updateOutOfServiceDates);
    }

    if (outEnd) {
        outEnd.addEventListener('change', updateOutOfServiceDates);
    }

    updateOutOfServiceDates();


    /* =========================
       FORM VALIDATION
    ========================== */

    const maintenanceForm = document.getElementById('maintenanceForm');

    if (maintenanceForm) {

        maintenanceForm.addEventListener('submit', function (event) {

            const selectedRepairTypes = document.querySelectorAll(
                'input[name="repair_type[]"]:checked'
            );

            if (selectedRepairTypes.length === 0) {

                event.preventDefault();

                alert('Please select at least one repair type.');

                if (repairTypeSelect) {
                    repairTypeSelect.classList.add('open');
                }

                return;

            }


            if (
                outStart &&
                outEnd &&
                outStart.value &&
                outEnd.value &&
                outEnd.value < outStart.value
            ) {

                event.preventDefault();

                alert('Out of Service End cannot be before Out of Service Start.');

                return;

            }

        });

    }

});

</script>

@endsection