@extends('layouts.app')

@section('content')

<style>

    .assignment-create-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ================= HEADER ================= */

    .assignment-form-header {
        margin-bottom: 22px;
    }

    .assignment-form-header h1 {
        margin: 0;
        color: #14213d;
        font-size: 28px;
        font-weight: 800;
    }

    .assignment-form-header p {
        margin: 6px 0 0;
        color: #6c757d;
        font-size: 13px;
    }


    /* ================= FORM CARD ================= */

    .assignment-form-card {
        background: #ffffff;
        border: 1px solid #e5eaf0;
        border-radius: 18px;
        box-shadow: 0 5px 18px rgba(31,62,94,.07);
        overflow: hidden;
    }

    .assignment-section {
        padding: 22px;
        border-bottom: 1px solid #edf0f3;
    }

    .assignment-section:last-child {
        border-bottom: none;
    }

    .assignment-section-title {
        margin: 0 0 17px;
        color: #1d3557;
        font-size: 17px;
        font-weight: 800;
    }


    /* ================= GRID ================= */

    .assignment-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
    }

    .assignment-field {
        min-width: 0;
    }

    .assignment-field.full-width {
        grid-column: 1 / -1;
    }


    /* ================= LABELS ================= */

    .assignment-field label {
        display: block;
        margin-bottom: 7px;
        color: #495057;
        font-size: 12px;
        font-weight: 800;
    }

    .required-mark {
        color: #dc3545;
    }


    /* ================= INPUTS ================= */

    .assignment-input,
    .assignment-select,
    .assignment-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #ffffff;
        color: #343a40;
        font-size: 13px;
        outline: none;
    }

    .assignment-input,
    .assignment-select {
        height: 43px;
        padding: 0 13px;
    }

    .assignment-textarea {
        min-height: 100px;
        padding: 11px 13px;
        resize: vertical;
        font-family: inherit;
    }

    .assignment-input:focus,
    .assignment-select:focus,
    .assignment-textarea:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.10);
    }


    /* ================= READ ONLY ================= */

    .assignment-readonly {
        background: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }


    /* ================= HELP TEXT ================= */

    .assignment-help {
        margin-top: 6px;
        color: #8a94a0;
        font-size: 11px;
    }


    /* ================= ERRORS ================= */

    .assignment-error {
        margin-top: 5px;
        color: #dc3545;
        font-size: 11px;
        font-weight: 600;
    }


    /* ================= BUTTONS ================= */

    .assignment-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 9px;
        padding: 20px 22px;
        background: #f8f9fa;
    }

    .assignment-back-btn,
    .assignment-save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        box-sizing: border-box;
    }

    .assignment-back-btn {
        background: #6c757d;
        color: #ffffff;
    }

    .assignment-back-btn:hover {
        background: #5c636a;
        color: #ffffff;
    }

    .assignment-save-btn {
        background: #0d6efd;
        color: #ffffff;
        border: none;
        cursor: pointer;
    }

    .assignment-save-btn:hover {
        background: #0b5ed7;
    }


    /* ================= RESPONSIVE ================= */

    @media (max-width: 700px) {

        .assignment-grid {
            grid-template-columns: 1fr;
        }

        .assignment-field.full-width {
            grid-column: auto;
        }

        .assignment-section {
            padding: 17px;
        }

        .assignment-form-actions {
            padding: 17px;
            flex-direction: column-reverse;
        }

        .assignment-back-btn,
        .assignment-save-btn {
            width: 100%;
        }

    }

</style>


<div class="assignment-create-page">


    {{-- ================= HEADER ================= --}}

    <div class="assignment-form-header">

        <h1>
            Create Assignment
        </h1>

        <p>
            Create a long-running client allocation for a vehicle and driver
        </p>

    </div>


    {{-- ================= FORM ================= --}}

    <div class="assignment-form-card">

        <form
            action="{{ route('assignments.store') }}"
            method="POST"
        >

            @csrf


            {{-- =================================================
                 ASSIGNMENT INFORMATION
            ================================================== --}}

            <div class="assignment-section">

                <h2 class="assignment-section-title">
                    Assignment Information
                </h2>


                <div class="assignment-grid">


                    {{-- Assignment Number --}}

                    <div class="assignment-field">

                        <label>
                            Assignment No.
                        </label>

                        <input
                            type="text"
                            class="assignment-input assignment-readonly"
                            value="Auto Generated"
                            readonly
                        >

                        <div class="assignment-help">
                            Format: AST-ASG-YYYY-#####
                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="assignment-field">

                        <label for="status">
                            Status <span class="required-mark">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="assignment-select"
                        >

                            <option value="Planned"
                                {{ old('status', 'Planned') === 'Planned' ? 'selected' : '' }}>
                                Planned
                            </option>

                            <option value="Active"
                                {{ old('status') === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Suspended"
                                {{ old('status') === 'Suspended' ? 'selected' : '' }}>
                                Suspended
                            </option>

                            <option value="Completed"
                                {{ old('status') === 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="Cancelled"
                                {{ old('status') === 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                        @error('status')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Client --}}

                    <div class="assignment-field">

                        <label for="client_id">
                            Client <span class="required-mark">*</span>
                        </label>

                        <select
                            id="client_id"
                            name="client_id"
                            class="assignment-select"
                            required
                        >

                            <option value="">
                                Select Client
                            </option>

                            @foreach($clients as $client)

                                <option
                                    value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->client_name }}
                                </option>

                            @endforeach

                        </select>

                        @error('client_id')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Vehicle --}}

                    <div class="assignment-field">

                        <label for="vehicle_id">
                            Vehicle <span class="required-mark">*</span>
                        </label>

                        <select
                            id="vehicle_id"
                            name="vehicle_id"
                            class="assignment-select"
                            required
                        >

                            <option value="">
                                Select Vehicle
                            </option>
                @foreach($vehicles as $vehicle)

    <option
        value="{{ $vehicle->id }}"
        {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}
    >
        {{ $vehicle->plate_number ?? 'No Plate' }}
    </option>

               @endforeach

                        </select>

                        @error('vehicle_id')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Driver --}}

                    <div class="assignment-field">

                        <label for="driver_id">
                            Driver <span class="required-mark">*</span>
                        </label>

                        <select
                            id="driver_id"
                            name="driver_id"
                            class="assignment-select"
                            required
                        >

                            <option value="">
                                Select Driver
                            </option>

                            @foreach($drivers as $driver)

                                <option
                                    value="{{ $driver->id }}"
                                    {{ old('driver_id') == $driver->id ? 'selected' : '' }}
                                >
                                    {{ $driver->driver_name }}
                                </option>

                            @endforeach

                        </select>

                        @error('driver_id')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                </div>

            </div>


            {{-- =================================================
                 DATE RANGE & RATE
            ================================================== --}}

            <div class="assignment-section">

                <h2 class="assignment-section-title">
                    Contract & Rate
                </h2>


                <div class="assignment-grid">


                    {{-- Start Date --}}

                    <div class="assignment-field">

                        <label for="start_date">
                            Start Date <span class="required-mark">*</span>
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            value="{{ old('start_date') }}"
                            class="assignment-input"
                            required
                        >

                        @error('start_date')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- End Date --}}

                    <div class="assignment-field">

                        <label for="end_date">
                            End Date <span class="required-mark">*</span>
                        </label>

                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            value="{{ old('end_date') }}"
                            class="assignment-input"
                            required
                        >

                        @error('end_date')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Rate --}}

                    <div class="assignment-field">

                        <label for="rate">
                            Rate <span class="required-mark">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="rate"
                            name="rate"
                            value="{{ old('rate') }}"
                            class="assignment-input"
                            placeholder="0.00"
                            required
                        >

                        @error('rate')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Rate Basis --}}

                    <div class="assignment-field">

                        <label for="rate_basis">
                            Rate Basis <span class="required-mark">*</span>
                        </label>

                        <select
                            id="rate_basis"
                            name="rate_basis"
                            class="assignment-select"
                            required
                        >

                            <option value="">
                                Select Rate Basis
                            </option>

                            <option value="Per Day"
                                {{ old('rate_basis') === 'Per Day' ? 'selected' : '' }}>
                                Per Day
                            </option>

                            <option value="Per Month"
                                {{ old('rate_basis', 'Monthly') === 'Monthly' || old('rate_basis') === 'Per Month' ? 'selected' : '' }}>
                                Per Month
                            </option>

                            <option value="Per Trip"
                                {{ old('rate_basis') === 'Per Trip' ? 'selected' : '' }}>
                                Per Trip
                            </option>

                            <option value="Fixed"
                                {{ old('rate_basis') === 'Fixed' ? 'selected' : '' }}>
                                Fixed
                            </option>

                            <option value="Custom"
                                {{ old('rate_basis') === 'Custom' ? 'selected' : '' }}>
                                Custom
                            </option>

                        </select>

                        @error('rate_basis')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Fuel Rule --}}

                    <div class="assignment-field full-width">

                        <label for="fuel_rule">
                            Fuel Rule
                        </label>

                        <select
                            id="fuel_rule"
                            name="fuel_rule"
                            class="assignment-select"
                        >

                            <option value="">
                                Select Fuel Rule
                            </option>

                            <option value="Included"
                                {{ old('fuel_rule') === 'Included' ? 'selected' : '' }}>
                                Included
                            </option>

                            <option value="AL SHAQRA Cost"
                                {{ old('fuel_rule') === 'AL SHAQRA Cost' ? 'selected' : '' }}>
                                AL SHAQRA Cost
                            </option>

                            <option value="Client Reimbursable"
                                {{ old('fuel_rule') === 'Client Reimbursable' ? 'selected' : '' }}>
                                Client Reimbursable
                            </option>

                            <option value="Client Direct"
                                {{ old('fuel_rule') === 'Client Direct' ? 'selected' : '' }}>
                                Client Direct
                            </option>

                        </select>

                        @error('fuel_rule')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                </div>

            </div>


            {{-- =================================================
                 REMARKS
            ================================================== --}}

            <div class="assignment-section">

                <h2 class="assignment-section-title">
                    Remarks
                </h2>


                <div class="assignment-grid">

                    <div class="assignment-field full-width">

                        <label for="remarks">
                            Remarks
                        </label>

                        <textarea
                            id="remarks"
                            name="remarks"
                            class="assignment-textarea"
                            placeholder="Add assignment remarks..."
                        >{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="assignment-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>
        

                    {{-- Loading Date --}}

                    <div class="assignment-field">

                        <label for="loading_date">
                            Loading Date
                        </label>

                        <input
                            type="date"
                            id="loading_date"
                            name="loading_date"
                            value="{{ old('loading_date') }}"
                            class="assignment-input"
                        >

                    </div>


                    {{-- Delivery Date --}}

                    <div class="assignment-field">

                        <label for="delivery_date">
                            Delivery Date
                        </label>

                        <input
                            type="date"
                            id="delivery_date"
                            name="delivery_date"
                            value="{{ old('delivery_date') }}"
                            class="assignment-input"
                        >

                    </div>


                    {{-- Existing Freight --}}

                    <div class="assignment-field">

                        <label for="freight_amount">
                            Freight Amount
                        </label>

                        <input
                        type="number"
                            step="0.01"
                            min="0"
                            id="freight_amount"
                            name="freight_amount"
                            value="{{ old('freight_amount') }}"
                            class="assignment-input"
                            placeholder="0.00"
                        >

                    </div>


                    {{-- Existing Notes --}}

                    <div class="assignment-field">

                        <label for="notes">
                            Notes
                        </label>

                        <input
                            type="text"
                            id="notes"
                            name="notes"
                            value="{{ old('notes') }}"
                            class="assignment-input"
                            placeholder="Additional notes"
                        >

                    </div>
                    </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="assignment-form-actions">

                <a
                    href="{{ route('assignments.index') }}"
                    class="assignment-back-btn"
                >
                    ← Back
                </a>


                <button
                    type="submit"
                    class="assignment-save-btn"
                >
                    Save Assignment
                </button>

            </div>


        </form>

    </div>

</div>

@endsection