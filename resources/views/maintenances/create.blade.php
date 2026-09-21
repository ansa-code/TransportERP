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

            <h1>
                Add Maintenance Record
            </h1>

            <p>
                Create a new repair, workshop and maintenance record for a vehicle.
            </p>

        </div>

        <a href="{{ route('maintenances.index') }}"
           class="maintenance-cancel-top">
            Cancel
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())

        <div class="maintenance-error-box">

            <div class="maintenance-error-title">
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


    {{-- MAIN FORM --}}
        <form action="{{ route('maintenances.store') }}"
      method="POST"
      enctype="multipart/form-data">
        @csrf


        {{-- =========================
             BASIC INFORMATION
        ========================== --}}

        <div class="maintenance-form-card">

            <div class="maintenance-form-card-header">

                <div>

                    <h2>
                        Maintenance Information
                    </h2>

                    <p>
                        Enter the basic details of the maintenance job.
                    </p>

                </div>

                <span class="maintenance-required-note">
                    * Required
                </span>

            </div>


            <div class="maintenance-form-grid">


                {{-- VEHICLE --}}
                <div class="maintenance-field">

                    <label for="vehicle_id">
                        Vehicle <span>*</span>
                    </label>

                    <select name="vehicle_id"
                            id="vehicle_id"
                            class="maintenance-input"
                            required>

                        <option value="">
                            Select Vehicle
                        </option>

                        @foreach ($vehicles as $vehicle)

                            <option value="{{ $vehicle->id }}"
                                {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>

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

                    <input type="date"
                           name="maintenance_date"
                           id="maintenance_date"
                           class="maintenance-input"
                           value="{{ old('maintenance_date', date('Y-m-d')) }}"
                           required>

                    @error('maintenance_date')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- REPAIR TYPE --}}
                <div class="maintenance-field">

                    <label>
                        Repair Type <span>*</span>
                    </label>

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

                        $oldRepairTypes = old('repair_type', []);

                        if (!is_array($oldRepairTypes)) {
                            $oldRepairTypes = [];
                        }

                    @endphp


                    <div class="repair-type-select"
                         id="repairTypeSelect">


                        <button type="button"
                                class="repair-type-trigger"
                                id="repairTypeTrigger">

                            <span id="repairTypeText">
                                Select Repair Types
                            </span>

                            <span class="repair-type-arrow">
                                ▼
                            </span>

                        </button>


                        <div class="repair-type-dropdown"
                             id="repairTypeDropdown">

                            @foreach ($repairTypes as $type)

                                <label class="repair-type-option">

                                    <input type="checkbox"
                                           name="repair_type[]"
                                           value="{{ $type }}"
                                           {{ in_array($type, $oldRepairTypes) ? 'checked' : '' }}>

                                    <span class="repair-type-check"></span>

                                    <span class="repair-type-option-text">
                                        {{ $type }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>


                    <div class="repair-type-selected"
                         id="repairTypeSelected">
                    </div>


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

                    <input type="text"
                           name="workshop"
                           id="workshop"
                           class="maintenance-input"
                           value="{{ old('workshop') }}"
                           placeholder="Enter workshop or vendor name">

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

                    <input type="number"
                           name="odometer"
                           id="odometer"
                           class="maintenance-input"
                           value="{{ old('odometer') }}"
                           min="0"
                           placeholder="Current odometer">

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

                    <select name="status"
                            id="status"
                            class="maintenance-input"
                            required>

                        <option value="Open"
                            {{ old('status', 'Open') == 'Open' ? 'selected' : '' }}>
                            Open
                        </option>

                        <option value="In Progress"
                            {{ old('status') == 'In Progress' ? 'selected' : '' }}>
                            In Progress
                        </option>

                        <option value="Completed"
                            {{ old('status') == 'Completed' ? 'selected' : '' }}>
                            Completed
                        </option>

                        <option value="Cancelled"
                            {{ old('status') == 'Cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>

                    </select>

                    @error('status')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

            </div>

        </div>


        {{-- =========================
             COST INFORMATION
        ========================== --}}

        <div class="maintenance-form-card">

            <div class="maintenance-form-card-header">

                <div>

                    <h2>
                        Cost Details
                    </h2>

                    <p>
                        Record parts, labour and other maintenance costs separately.
                    </p>

                </div>

            </div>


            <div class="maintenance-cost-grid">


                {{-- PARTS COST --}}
                <div class="maintenance-field">

                    <label for="parts_cost">
                        Parts Cost <span>*</span>
                    </label>

                    <div class="maintenance-money-input">

                        <span>
                            AED
                        </span>

                        <input type="number"
                               name="parts_cost"
                               id="parts_cost"
                               class="maintenance-input"
                               value="{{ old('parts_cost', '0') }}"
                               min="0"
                               step="0.01"
                               placeholder="0.00"
                               required>

                    </div>

                    @error('parts_cost')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- LABOUR COST --}}
                <div class="maintenance-field">

                    <label for="labour_cost">
                        Labour Cost <span>*</span>
                    </label>

                    <div class="maintenance-money-input">

                        <span>
                            AED
                        </span>

                        <input type="number"
                               name="labour_cost"
                               id="labour_cost"
                               class="maintenance-input"
                               value="{{ old('labour_cost', '0') }}"
                               min="0"
                               step="0.01"
                               placeholder="0.00"
                               required>

                    </div>

                    @error('labour_cost')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- OTHER COST --}}
                <div class="maintenance-field">

                    <label for="other_cost">
                        Other Cost <span>*</span>
                    </label>

                    <div class="maintenance-money-input">

                        <span>
                            AED
                        </span>

                        <input type="number"
                               name="other_cost"
                               id="other_cost"
                               class="maintenance-input"
                               value="{{ old('other_cost', '0') }}"
                               min="0"
                               step="0.01"
                               placeholder="0.00"
                               required>

                    </div>

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

                    <div class="maintenance-money-input total">

                        <span>
                            AED
                        </span>

                        <input type="number"
                               name="total_cost"
                               id="total_cost"
                               class="maintenance-input maintenance-total-input"
                               value="{{ old('total_cost', '0.00') }}"
                               step="0.01"
                               readonly>

                    </div>

                    <small class="maintenance-help-text">
                        Automatically calculated from Parts + Labour + Other.
                    </small>

                </div>

            </div>

        </div>


        {{-- =========================
             SERVICE SCHEDULE
        ========================== --}}

        <div class="maintenance-form-card">

            <div class="maintenance-form-card-header">

                <div>

                    <h2>
                        Service Schedule & Downtime
                    </h2>

                    <p>
                        Record downtime and the next planned service date.
                    </p>

                </div>

            </div>


            <div class="maintenance-form-grid">


                {{-- OUT OF SERVICE START --}}
                <div class="maintenance-field">

                    <label for="out_of_service_start">
                        Out-of-Service Start
                    </label>

                    <input type="date"
                           name="out_of_service_start"
                           id="out_of_service_start"
                           class="maintenance-input"
                           value="{{ old('out_of_service_start') }}">

                    @error('out_of_service_start')

                        <small class="maintenance-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                {{-- OUT OF SERVICE END --}}
                <div class="maintenance-field">

                    <label for="out_of_service_end">
                        Out-of-Service End
                    </label>

                    <input type="date"
                           name="out_of_service_end"
                           id="out_of_service_end"
                           class="maintenance-input"
                           value="{{ old('out_of_service_end') }}">

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

                    <input type="date"
                           name="next_service_date"
                           id="next_service_date"
                           class="maintenance-input"
                           value="{{ old('next_service_date') }}">

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

    <input type="file"
           name="invoice_receipt"
           id="invoice_receipt"
           class="maintenance-input"
           accept=".jpg,.jpeg,.png,.pdf">

    <small class="maintenance-help-text">
        JPG, JPEG, PNG or PDF. Maximum 5 MB.
    </small>

    @error('invoice_receipt')

        <small class="maintenance-field-error">
            {{ $message }}
        </small>

    @enderror

</div>
            </div>

        </div>
        {{-- =========================
             REMARKS
        ========================== --}}

        <div class="maintenance-form-card">

            <div class="maintenance-form-card-header">

                <div>

                    <h2>
                        Remarks
                    </h2>

                    <p>
                        Add any additional notes about this maintenance job.
                    </p>

                </div>

            </div>


            <div class="maintenance-field">

                <label for="remarks">
                    Remarks
                </label>

                <textarea name="remarks"
                          id="remarks"
                          class="maintenance-textarea"
                          rows="5"
                          placeholder="Enter maintenance remarks...">{{ old('remarks') }}</textarea>

                @error('remarks')

                    <small class="maintenance-field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>

        </div>


        {{-- =========================
             FORM ACTIONS
        ========================== --}}

        <div class="maintenance-form-actions">

            <a href="{{ route('maintenances.index') }}"
               class="maintenance-cancel-btn">
                Cancel
            </a>

            <button type="submit"
                    class="maintenance-save-btn">
                Save Maintenance
            </button>

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
    padding-bottom: 30px;
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
    font-size: 13px;
    font-weight: 700;
}

.maintenance-back a:hover {
    color: #1d4ed8;
}

.maintenance-form-header h1 {
    margin: 0;
    color: #0f172a;
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.maintenance-form-header p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 14px;
}

.maintenance-cancel-top {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    padding: 0 16px;
    border: 1px solid #dbe3ef;
    border-radius: 11px;
    background: #ffffff;
    color: #475569;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
}

.maintenance-cancel-top:hover {
    background: #f8fafc;
    color: #334155;
}


/* =========================
   ERROR BOX
========================= */

.maintenance-error-box {
    margin-bottom: 20px;
    padding: 15px 18px;
    border: 1px solid #fecaca;
    border-radius: 14px;
    background: #fef2f2;
    color: #991b1b;
}

.maintenance-error-title {
    margin-bottom: 7px;
    font-size: 13px;
    font-weight: 800;
}

.maintenance-error-box ul {
    margin: 0;
    padding-left: 20px;
    font-size: 12px;
}

.maintenance-error-box li {
    margin-bottom: 3px;
}


/* =========================
   FORM CARD
========================= */

.maintenance-form-card {
    margin-bottom: 18px;
    padding: 22px;
    background: #ffffff;
    border: 1px solid #e4eaf3;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(15, 23, 42, .05);
}

.maintenance-form-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #edf1f7;
}

.maintenance-form-card-header h2 {
    margin: 0;
    color: #172033;
    font-size: 16px;
    font-weight: 800;
}

.maintenance-form-card-header p {
    margin: 5px 0 0;
    color: #7b8798;
    font-size: 12px;
}

.maintenance-required-note {
    color: #94a3b8;
    font-size: 11px;
    font-weight: 700;
}


/* =========================
   FORM GRID
========================= */

.maintenance-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.maintenance-cost-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.maintenance-field {
    min-width: 0;
}

.maintenance-field label {
    display: block;
    margin-bottom: 7px;
    color: #334155;
    font-size: 12px;
    font-weight: 800;
}

.maintenance-field label span {
    color: #dc2626;
}

.maintenance-input {
    width: 100%;
    height: 42px;
    box-sizing: border-box;
    padding: 0 12px;
    border: 1px solid #dbe3ef;
    border-radius: 10px;
    outline: none;
    background: #ffffff;
    color: #172033;
    font-family: inherit;
    font-size: 13px;
    transition: .2s ease;
}

.maintenance-input:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
}

.maintenance-input::placeholder {
    color: #a0aec0;
}

.maintenance-textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    border: 1px solid #dbe3ef;
    border-radius: 10px;
    outline: none;
    resize: vertical;
    background: #ffffff;
    color: #172033;
    font-family: inherit;
    font-size: 13px;
    transition: .2s ease;
}

.maintenance-textarea:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
}


/* =========================
   MULTIPLE REPAIR TYPE
========================= */

.repair-type-select {
    position: relative;
    width: 100%;
}

.repair-type-trigger {
    width: 100%;
    min-height: 42px;
    padding: 0 13px;
    border: 1px solid #dbe3ef;
    border-radius: 10px;
    background: #ffffff;
    color: #475569;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-align: left;
    transition: .2s ease;
}

.repair-type-trigger:hover {
    border-color: #93c5fd;
}

.repair-type-trigger:focus {
    outline: none;
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
}

.repair-type-arrow {
    color: #64748b;
    font-size: 9px;
    transition: transform .2s ease;
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
    border: 1px solid #dbe3ef;
    border-radius: 12px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, .15);
}

.repair-type-select.open .repair-type-dropdown {
    display: block;
}

.repair-type-option {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 38px;
    padding: 7px 9px;
    border-radius: 8px;
    color: #334155;
    font-size: 13px;
    font-weight: 600;
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
    box-sizing: border-box;
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    background: #ffffff;
    position: relative;
}

.repair-type-option input:checked + .repair-type-check {
    background: #2563eb;
    border-color: #2563eb;
}

.repair-type-option input:checked + .repair-type-check::after {
    content: "✓";
    position: absolute;
    left: 3px;
    top: -1px;
    color: #ffffff;
    font-size: 12px;
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
    border: 1px solid #bfdbfe;
    border-radius: 7px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 11px;
    font-weight: 700;
}

.repair-type-chip button {
    border: 0;
    padding: 0;
    background: transparent;
    color: #1d4ed8;
    font-size: 14px;
    font-weight: 800;
    line-height: 1;
    cursor: pointer;
}

.repair-type-chip button:hover {
    color: #1e40af;
}


/* =========================
   MONEY INPUT
========================= */

.maintenance-money-input {
    position: relative;
}

.maintenance-money-input > span {
    position: absolute;
    left: 12px;
    top: 50%;
    z-index: 2;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    pointer-events: none;
}

.maintenance-money-input .maintenance-input {
    padding-left: 43px;
}

.maintenance-total-input {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
    font-weight: 800;
}

.maintenance-help-text {
    display: block;
    margin-top: 6px;
    color: #94a3b8;
    font-size: 10px;
    line-height: 1.4;
}


/* =========================
   FIELD ERRORS
========================= */

.maintenance-field-error {
    display: block;
    margin-top: 6px;
    color: #dc2626;
    font-size: 11px;
    font-weight: 600;
}


/* =========================
   FORM ACTIONS
========================= */

.maintenance-form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-top: 6px;
}

.maintenance-cancel-btn,
.maintenance-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 42px;
    padding: 0 18px;
    border-radius: 10px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
}

.maintenance-cancel-btn {
    border: 1px solid #dbe3ef;
    background: #ffffff;
    color: #475569;
}

.maintenance-cancel-btn:hover {
    background: #f8fafc;
    color: #334155;
}

.maintenance-save-btn {
    border: 1px solid #2563eb;
    background: #2563eb;
    color: #ffffff;
}

.maintenance-save-btn:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 1000px) {

    .maintenance-cost-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .maintenance-form-page {
        padding: 0 4px 25px;
    }

    .maintenance-form-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .maintenance-cancel-top {
        display: none;
    }

    .maintenance-form-header h1 {
        font-size: 23px;
    }

    .maintenance-form-card {
        padding: 17px;
        border-radius: 14px;
    }

    .maintenance-form-grid,
    .maintenance-cost-grid {
        grid-template-columns: 1fr;
    }

    .maintenance-form-card-header {
        flex-direction: column;
        gap: 8px;
    }

    .maintenance-form-actions {
        justify-content: stretch;
    }

    .maintenance-cancel-btn,
    .maintenance-save-btn {
        flex: 1;
    }

}


/* =========================
   SMALL MOBILE
========================= */

@media (max-width: 420px) {

    .maintenance-form-actions {
        flex-direction: column;
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
       REPAIR TYPE MULTI SELECT
    ========================== */

    const selectBox =
        document.getElementById('repairTypeSelect');

    const trigger =
        document.getElementById('repairTypeTrigger');

    const text =
        document.getElementById('repairTypeText');

    const selectedBox =
        document.getElementById('repairTypeSelected');


    if (selectBox && trigger && text && selectedBox) {


        const checkboxes =
            selectBox.querySelectorAll(
                'input[name="repair_type[]"]'
            );


        function updateRepairTypes() {

            const selected = [];


            checkboxes.forEach(function (checkbox) {

                if (checkbox.checked) {

                    selected.push(checkbox.value);

                }

            });


            if (selected.length === 0) {

                text.textContent =
                    'Select Repair Types';

            } else {

                text.textContent =
                    selected.length +
                    ' Repair Type' +
                    (selected.length > 1 ? 's' : '') +
                    ' Selected';

            }


            selectedBox.innerHTML = '';


            selected.forEach(function (type) {

                const chip =
                    document.createElement('span');

                chip.className =
                    'repair-type-chip';


                const label =
                    document.createElement('span');

                label.textContent = type;


                const removeButton =
                    document.createElement('button');

                removeButton.type = 'button';

                removeButton.dataset.type = type;

                removeButton.textContent = '×';


                chip.appendChild(label);

                chip.appendChild(removeButton);

                selectedBox.appendChild(chip);

            });

        }


        trigger.addEventListener('click', function () {

            selectBox.classList.toggle('open');

        });


        checkboxes.forEach(function (checkbox) {

            checkbox.addEventListener(
                'change',
                updateRepairTypes
            );

        });


        selectedBox.addEventListener('click', function (event) {

            if (
                event.target.tagName.toLowerCase() ===
                'button'
            ) {

                const type =
                    event.target.dataset.type;


                checkboxes.forEach(function (checkbox) {

                    if (checkbox.value === type) {

                        checkbox.checked = false;

                    }

                });


                updateRepairTypes();

            }

        });


        document.addEventListener('click', function (event) {

            if (!selectBox.contains(event.target)) {

                selectBox.classList.remove('open');

            }

        });


        updateRepairTypes();

    }


    /* =========================
       TOTAL COST CALCULATION
    ========================== */

    const partsCost =
        document.getElementById('parts_cost');

    const labourCost =
        document.getElementById('labour_cost');

    const otherCost =
        document.getElementById('other_cost');

    const totalCost =
        document.getElementById('total_cost');


    function calculateTotalCost() {

        const parts =
            parseFloat(partsCost?.value) || 0;

        const labour =
            parseFloat(labourCost?.value) || 0;

        const other =
            parseFloat(otherCost?.value) || 0;


        const total =
            parts + labour + other;


        if (totalCost) {

            totalCost.value =
                total.toFixed(2);

        }

    }


    if (partsCost) {

        partsCost.addEventListener(
            'input',
            calculateTotalCost
        );

    }


    if (labourCost) {

        labourCost.addEventListener(
            'input',
            calculateTotalCost
        );

    }


    if (otherCost) {

        otherCost.addEventListener(
            'input',
            calculateTotalCost
        );

    }


    calculateTotalCost();


    /* =========================
       OUT OF SERVICE DATE CHECK
    ========================== */

    const serviceStart =
        document.getElementById(
            'out_of_service_start'
        );

    const serviceEnd =
        document.getElementById(
            'out_of_service_end'
        );


    if (serviceStart && serviceEnd) {

        serviceStart.addEventListener(
            'change',
            function () {

                if (serviceStart.value) {

                    serviceEnd.min =
                        serviceStart.value;

                }

                if (
                    serviceEnd.value &&
                    serviceStart.value &&
                    serviceEnd.value <
                    serviceStart.value
                ) {

                    serviceEnd.value = '';

                }

            }
        );

    }

});

</script>

@endsection