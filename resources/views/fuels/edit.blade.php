@extends('layouts.app')

@section('content')

<style>
    .fuel-edit-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header h1 {
        margin: 0;
        color: #111827;
        font-size: 28px;
        font-weight: 800;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, .05);
        margin-bottom: 20px;
    }

    .section-title {
        margin: 0 0 18px;
        color: #172554;
        font-size: 18px;
        font-weight: 750;
    }

    .section-subtitle {
        margin: -10px 0 20px;
        color: #64748b;
        font-size: 13px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        margin-bottom: 7px;
        color: #374151;
        font-size: 13px;
        font-weight: 650;
    }

    .required {
        color: #dc2626;
    }

    .form-control,
    .form-select,
    .form-textarea {
        width: 100%;
        height: 38px;
        padding: 0 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        outline: none;
        background: #ffffff;
        color: #1e293b;
        font-size: 13px;
        transition: .2s ease;
        box-sizing: border-box;
    }

    .form-textarea {
        height: 90px;
        padding: 10px 12px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #315f8f;
        box-shadow: 0 0 0 2px rgba(49, 95, 143, .10);
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #475569;
    }

    .field-help {
        margin-top: 5px;
        color: #94a3b8;
        font-size: 11px;
    }

    .error-message {
        margin-top: 5px;
        color: #dc2626;
        font-size: 12px;
    }

    .top-error {
        margin-bottom: 20px;
        padding: 12px 15px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 13px;
    }

    .reimbursement-box {
        padding: 16px;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        background: #f8fbff;
    }

    .radio-row {
        display: flex;
        align-items: center;
        gap: 22px;
        min-height: 38px;
    }

    .radio-option {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #374151;
        font-size: 13px;
        cursor: pointer;
    }

    .radio-option input {
        width: 15px;
        height: 15px;
        accent-color: #2563eb;
    }

    .current-receipt {
        margin-top: 8px;
        font-size: 12px;
    }

    .current-receipt a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .current-receipt a:hover {
        text-decoration: underline;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-cancel {
        background: #ffffff;
        color: #374151;
        border: 1px solid #cbd5e1;
    }

    .btn-cancel:hover {
        background: #f8fafc;
    }

    .btn-update {
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #2563eb;
    }

    .btn-update:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .page-header {
            flex-direction: column;
        }

        .form-card {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="fuel-edit-page">

    <div class="page-header">
        <div>
            <h1>Edit Fuel Record</h1>
            <p>Update fuel usage, cost and client reimbursement details.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="top-error">
            Please correct the highlighted fields and try again.
        </div>
    @endif

    <form
        action="{{ route('fuels.update', $fuel->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        <div class="form-card">

            <h2 class="section-title">Fuel Information</h2>

            <p class="section-subtitle">
                Update the basic fuel transaction and operational context.
            </p>

            <div class="form-grid">

                <div class="form-group">
                    <label for="vehicle_id">
                        Vehicle <span class="required">*</span>
                    </label>

                    <select
                        name="vehicle_id"
                        id="vehicle_id"
                        class="form-select"
                        required
                    >
                        <option value="">Select Vehicle</option>

                        @foreach($vehicles as $vehicle)
                            <option
                                value="{{ $vehicle->id }}"
                                {{ old('vehicle_id', $fuel->vehicle_id) == $vehicle->id ? 'selected' : '' }}
                            >
                                {{ $vehicle->plate_number }}
                            </option>
                        @endforeach
                    </select>

                    @error('vehicle_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="driver_id">
                        Driver
                    </label>

                    <select
                        name="driver_id"
                        id="driver_id"
                        class="form-select"
                    >
                        <option value="">Select Driver (Optional)</option>

                        @foreach($drivers as $driver)
                            <option
                                value="{{ $driver->id }}"
                                {{ old('driver_id', $fuel->driver_id) == $driver->id ? 'selected' : '' }}
                            >
                                {{ $driver->driver_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('driver_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fuel_date">
                        Date / Time <span class="required">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        name="fuel_date"
                        id="fuel_date"
                        class="form-control"
                        value="{{ old('fuel_date', $fuel->fuel_date?->format('Y-m-d\TH:i')) }}"
                        max="{{ now()->format('Y-m-d\TH:i') }}"
                        required
                    >

                    @error('fuel_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="client_id">
                        Client
                    </label>

                    <select
                        name="client_id"
                        id="client_id"
                        class="form-select"
                    >
                        <option value="">Select Client (Optional)</option>

                        @foreach($clients as $client)
                            <option
                                value="{{ $client->id }}"
                                {{ old('client_id', $fuel->client_id) == $client->id ? 'selected' : '' }}
                            >
                                {{ $client->client_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('client_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="assignment_id">
                        Assignment
                    </label>

                    <select
                        name="assignment_id"
                        id="assignment_id"
                        class="form-select"
                    >
                        <option value="">Select Assignment (Optional)</option>

                        @foreach($assignments as $assignment)
                            <option
                                value="{{ $assignment->id }}"
                                {{ old('assignment_id', $fuel->assignment_id) == $assignment->id ? 'selected' : '' }}
                            >
                                Assignment #{{ $assignment->id }}
                            </option>
                        @endforeach
                    </select>

                    @error('assignment_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="trip_id">
                        Trip
                    </label>

                    <select
                        name="trip_id"
                        id="trip_id"
                        class="form-select"
                    >
                        <option value="">Select Trip (Optional)</option>

                        @foreach($trips as $trip)
                            <option
                                value="{{ $trip->id }}"
                                {{ old('trip_id', $fuel->trip_id) == $trip->id ? 'selected' : '' }}
                            >
                                Trip #{{ $trip->id }}
                            </option>
                        @endforeach
                    </select>

                    @error('trip_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

            </div>

        </div>
        <div class="form-card">

            <h2 class="section-title">Fuel Cost & Odometer</h2>

            <p class="section-subtitle">
                Update quantity and pricing details. Total Cost is calculated automatically.
            </p>

            <div class="form-grid">

                <div class="form-group">
                    <label for="liters">
                        Quantity (Liters) <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="liters"
                        id="liters"
                        class="form-control"
                        value="{{ old('liters', $fuel->liters) }}"
                        min="0.01"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >

                    @error('liters')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price_per_liter">
                        Unit Price
                    </label>

                    <input
                        type="number"
                        name="price_per_liter"
                        id="price_per_liter"
                        class="form-control"
                        value="{{ old('price_per_liter', $fuel->price_per_liter) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                    >

                    @error('price_per_liter')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="total_amount">
                        Total Cost
                    </label>

                    <input
                        type="number"
                        name="total_amount"
                        id="total_amount"
                        class="form-control"
                        value="{{ old('total_amount', $fuel->total_amount) }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        readonly
                    >

                    <div class="field-help">
                        Calculated as Quantity × Unit Price.
                    </div>

                    @error('total_amount')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="odometer">
                        Odometer
                    </label>

                    <input
                        type="number"
                        name="odometer"
                        id="odometer"
                        class="form-control"
                        value="{{ old('odometer', $fuel->odometer) }}"
                        min="0"
                        step="1"
                        placeholder="Current odometer"
                    >

                    <div class="field-help">
                        Optional vehicle odometer reading.
                    </div>

                    @error('odometer')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fuel_station">
                        Fuel Station
                    </label>

                    <input
                        type="text"
                        name="fuel_station"
                        id="fuel_station"
                        class="form-control"
                        value="{{ old('fuel_station', $fuel->fuel_station) }}"
                        maxlength="255"
                        placeholder="Fuel station name"
                    >

                    @error('fuel_station')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="paid_by">
                        Paid By <span class="required">*</span>
                    </label>

                    <select
                        name="paid_by"
                        id="paid_by"
                        class="form-select"
                        required
                    >
                        <option value="">Select Payment Responsibility</option>

                        <option
                            value="AL SHAQRA"
                            {{ old('paid_by', $fuel->paid_by) == 'AL SHAQRA' ? 'selected' : '' }}
                        >
                            AL SHAQRA
                        </option>

                        <option
                            value="Client"
                            {{ old('paid_by', $fuel->paid_by) == 'Client' ? 'selected' : '' }}
                        >
                            Client
                        </option>

                        <option
                            value="Driver"
                            {{ old('paid_by', $fuel->paid_by) == 'Driver' ? 'selected' : '' }}
                        >
                            Driver
                        </option>

                        <option
                            value="Vendor"
                            {{ old('paid_by', $fuel->paid_by) == 'Vendor' ? 'selected' : '' }}
                        >
                            Vendor
                        </option>
                    </select>

                    @error('paid_by')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

            </div>

        </div>

        <div class="form-card">

            <h2 class="section-title">Client Reimbursement</h2>

            <p class="section-subtitle">
                Update reimbursement information for client recovery reporting.
            </p>

            <div class="reimbursement-box">

                <div class="form-grid">

                    <div class="form-group">
                        <label>
                            Reimbursable <span class="required">*</span>
                        </label>

                        <div class="radio-row">

                            <label class="radio-option">
                                <input
                                    type="radio"
                                    name="reimbursable"
                                    value="1"
                                    {{ old('reimbursable', $fuel->reimbursable ? '1' : '0') == '1' ? 'checked' : '' }}
                                >
                                Yes
                            </label>

                            <label class="radio-option">
                                <input
                                    type="radio"
                                    name="reimbursable"
                                    value="0"
                                    {{ old('reimbursable', $fuel->reimbursable ? '1' : '0') == '0' ? 'checked' : '' }}
                                >
                                No
                            </label>

                        </div>

                        @error('reimbursable')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div
                        class="form-group"
                        id="reimbursementAmountGroup"
                    >
                        <label for="reimbursement_amount">
                            Reimbursement Amount
                        </label>

                        <input
                            type="number"
                            name="reimbursement_amount"
                            id="reimbursement_amount"
                            class="form-control"
                            value="{{ old('reimbursement_amount', $fuel->reimbursement_amount) }}"
                            min="0.01"
                            step="0.01"
                            placeholder="0.00"
                        >

                        <div class="field-help">
                            Required when Reimbursable is Yes.
                        </div>

                        @error('reimbursement_amount')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>

        </div>
        <div class="form-card">

            <h2 class="section-title">Receipt & Notes</h2>

            <p class="section-subtitle">
                Replace the receipt if needed and update any relevant notes.
            </p>

            <div class="form-grid">

                <div class="form-group">

                    <label for="receipt">
                        Receipt
                    </label>

                    <input
                        type="file"
                        name="receipt"
                        id="receipt"
                        class="form-control"
                        accept=".pdf,.jpg,.jpeg,.png"
                    >

                    <div class="field-help">
                        Allowed formats: PDF, JPG, JPEG, PNG. Maximum 5 MB.
                    </div>

                    @if($fuel->receipt)
                        <div class="current-receipt">
                            Current receipt:
                            <a
                                href="{{ asset('storage/' . $fuel->receipt) }}"
                                target="_blank"
                            >
                                View Receipt
                            </a>
                        </div>
                    @endif

                    @error('receipt')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                </div>

                <div class="form-group full-width">

                    <label for="notes">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        class="form-textarea"
                        placeholder="Additional notes..."
                    >{{ old('notes', $fuel->notes) }}</textarea>

                    @error('notes')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('fuels.index') }}"
                    class="btn btn-cancel"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-update"
                >
                    Update Fuel Record
                </button>

            </div>

        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const litersInput = document.getElementById('liters');
    const priceInput = document.getElementById('price_per_liter');
    const totalInput = document.getElementById('total_amount');

    const reimbursementRadios =
        document.querySelectorAll('input[name="reimbursable"]');

    const reimbursementGroup =
        document.getElementById('reimbursementAmountGroup');

    const reimbursementInput =
        document.getElementById('reimbursement_amount');

    function calculateTotal() {

        const liters = parseFloat(litersInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;

        const total = liters * price;

        totalInput.value = total > 0
            ? total.toFixed(2)
            : '';
    }

    function toggleReimbursement() {

        const selected = document.querySelector(
            'input[name="reimbursable"]:checked'
        );

        const isReimbursable =
            selected && selected.value === '1';

        if (isReimbursable) {

            reimbursementGroup.style.display = '';
            reimbursementInput.disabled = false;
            reimbursementInput.required = true;

        } else {

            reimbursementGroup.style.display = 'none';
            reimbursementInput.disabled = true;
            reimbursementInput.required = false;
            reimbursementInput.value = '';
        }
    }

    litersInput.addEventListener('input', calculateTotal);
    priceInput.addEventListener('input', calculateTotal);

    reimbursementRadios.forEach(function (radio) {
        radio.addEventListener('change', toggleReimbursement);
    });

    calculateTotal();
    toggleReimbursement();
});
</script>

@endsection