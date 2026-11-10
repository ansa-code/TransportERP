@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
.trip-form-page {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
}

.trip-form-header {
    margin-bottom: 24px;
}

.trip-form-header h1 {
    margin: 0;
    color: #14213d;
    font-size: 28px;
    font-weight: 800;
}

.trip-form-header p {
    margin: 6px 0 0;
    color: #6c757d;
    font-size: 13px;
}

.trip-form-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 10px 24px rgba(15,23,42,.06);
}

.trip-form-section {
    margin-bottom: 26px;
}

.trip-form-section:last-child {
    margin-bottom: 0;
}

.trip-form-section-title {
    margin-bottom: 16px;
    color: #1e293b;
    font-size: 15px;
    font-weight: 800;
    padding-bottom: 9px;
    border-bottom: 1px solid #edf1f5;
}

.trip-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.trip-form-group {
    display: flex;
    flex-direction: column;
}

.trip-form-group.full-width {
    grid-column: 1 / -1;
}

.trip-form-label {
    margin-bottom: 7px;
    color: #334155;
    font-size: 12px;
    font-weight: 800;
}

.trip-form-input,
.trip-form-select,
.trip-form-textarea {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #dbe3ef;
    border-radius: 9px;
    background: #ffffff;
    color: #334155;
    font-size: 13px;
    outline: none;
    box-sizing: border-box;
}

.trip-form-input:focus,
.trip-form-select:focus,
.trip-form-textarea:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,.10);
}

.trip-form-textarea {
    min-height: 100px;
    resize: vertical;
}

.trip-form-help {
    margin-top: 5px;
    color: #94a3b8;
    font-size: 11px;
}

.trip-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 26px;
    padding-top: 20px;
    border-top: 1px solid #edf1f5;
}

.trip-cancel-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 17px;
    background: #e2e8f0;
    color: #334155;
    text-decoration: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}

.trip-cancel-btn:hover {
    background: #cbd5e1;
    color: #334155;
}

.trip-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    background: #0d6efd;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.trip-save-btn:hover {
    background: #0b5ed7;
}

.trip-error-box {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
    font-size: 12px;
}

.trip-error-box ul {
    margin: 0;
    padding-left: 18px;
}

/* Flatpickr calendar styling */

.flatpickr-calendar {
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(15,23,42,.15);
}

.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
    background: #0d6efd;
    border-color: #0d6efd;
}

.flatpickr-day.today {
    border-color: #0d6efd;
}

/* Small calendar icon */

.trip-datetime:not(.flatpickr-input) {
    padding-right: 38px !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='5' width='18' height='16' rx='2'/%3E%3Cpath d='M16 3v4M8 3v4M3 10h18'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 17px 17px;
    cursor: pointer;
}

/* Done button inside calendar */

.flatpickr-done-btn {
    display: block;
    width: calc(100% - 16px);
    margin: 8px;
    padding: 8px 12px;
    background: #0d6efd;
    color: #ffffff;
    border: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.flatpickr-done-btn:hover {
    background: #0b5ed7;
}

@media (max-width: 700px) {
    .trip-form-grid {
        grid-template-columns: 1fr;
    }

    .trip-form-group.full-width {
        grid-column: auto;
    }

    .trip-form-card {
        padding: 18px;
    }

    .trip-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .trip-cancel-btn,
    .trip-save-btn {
        width: 100%;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="trip-form-page">

    <div class="trip-form-header">
        <h1>Create Trip</h1>
        <p>Create a movement-level trip under an assignment or directly for a client.</p>
    </div>

    @if($errors->any())
        <div class="trip-error-box">
            <strong>Please fix the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="trip-form-card">

        <form
            action="{{ route('trips.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            {{-- ASSIGNMENT & RESOURCES --}}

            <div class="trip-form-section">

                <div class="trip-form-section-title">
                    Assignment & Resources
                </div>

                <div class="trip-form-grid">

                    <div class="trip-form-group full-width">

                        <label class="trip-form-label">Assignment</label>

                        <select
                            name="assignment_id"
                            id="assignment_id"
                            class="trip-form-select"
                        >
                            <option value="">Direct Trip — No Assignment</option>

                            @foreach($assignments as $assignment)
                                <option
                                    value="{{ $assignment->id }}"
                                    data-client="{{ $assignment->client_id }}"
                                    data-vehicle="{{ $assignment->vehicle_id }}"
                                    data-driver="{{ $assignment->driver_id }}"
                                    data-rate="{{ $assignment->rate ?? '' }}"
                                    {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}
                                >
                                    {{ $assignment->assignment_no }}
                                    — {{ $assignment->client?->client_name ?? 'Client' }}
                                    — {{ $assignment->vehicle?->plate_number ?? $assignment->vehicle?->vehicle_number ?? 'Vehicle' }}
                                </option>
                            @endforeach

                        </select>

                        <div class="trip-form-help">
                            Select an active assignment to auto-fill client, vehicle and driver.
                        </div>

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Client *</label>

                        <select
                            name="client_id"
                            id="client_id"
                            class="trip-form-select"
                            required
                        >
                            <option value="">Select Client</option>

                            @foreach($clients as $client)
                                <option
                                    value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->client_name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Vehicle / Number Plate *</label>

                        <select
                            name="vehicle_id"
                            id="vehicle_id"
                            class="trip-form-select"
                            required
                        >
                            <option value="">Select Vehicle</option>

                            @foreach($vehicles as $vehicle)
                                <option
                                    value="{{ $vehicle->id }}"
                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}
                                >
                                    {{ $vehicle->plate_number ?? $vehicle->vehicle_number ?? 'Vehicle #' . $vehicle->id }}
                                </option>
                            @endforeach

                        </select>

                        @error('vehicle_id')
                            <div style="color:#dc3545; font-size:12px; margin-top:5px;">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Driver *</label>

                        <select
                            name="driver_id"
                            id="driver_id"
                            class="trip-form-select"
                            required
                        >
                            <option value="">Select Driver</option>

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
                            <div style="color:#dc3545; font-size:12px; margin-top:5px;">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Status *</label>

                        <select name="status" class="trip-form-select" required>

                            <option value="Planned" {{ old('status', 'Planned') === 'Planned' ? 'selected' : '' }}>
                                Planned
                            </option>

                            <option value="Assigned" {{ old('status') === 'Assigned' ? 'selected' : '' }}>
                                Assigned
                            </option>

                            <option value="In Transit" {{ old('status') === 'In Transit' ? 'selected' : '' }}>
                                In Transit
                            </option>

                            <option value="Delivered" {{ old('status') === 'Delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>

                            <option value="Closed" {{ old('status') === 'Closed' ? 'selected' : '' }}>
                                Closed
                            </option>

                            <option value="Cancelled" {{ old('status') === 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            {{-- TRIP DATE & ROUTE --}}

            <div class="trip-form-section">

                <div class="trip-form-section-title">
                    Trip Date & Route
                </div>

                <div class="trip-form-grid">

                    <div class="trip-form-group">

                        <label class="trip-form-label">Trip Start *</label>

                        <input
                            type="text"
                            name="trip_start"
                            id="trip_start"
                            value="{{ old('trip_start') }}"
                            class="trip-form-input trip-datetime"
                            placeholder="Select trip start date & time"
                            autocomplete="off"
                            required
                        >

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Trip End</label>

                        <input
                            type="text"
                            name="trip_end"
                            id="trip_end"
                            value="{{ old('trip_end') }}"
                            class="trip-form-input trip-datetime"
                            placeholder="Select trip end date & time"
                            autocomplete="off"
                        >

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Loading Point</label>

                        <input
                            type="text"
                            name="loading_point"
                            value="{{ old('loading_point') }}"
                            class="trip-form-input"
                            placeholder="Enter loading location"
                            maxlength="255"
                        >

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Unloading Point</label>

                        <input
                            type="text"
                            name="unloading_point"
                            value="{{ old('unloading_point') }}"
                            class="trip-form-input"
                            placeholder="Enter unloading location"
                            maxlength="255"
                        >

                    </div>

                </div>

            </div>
            {{-- BILLING --}}

            <div class="trip-form-section">

                <div class="trip-form-section-title">
                    Billing
                </div>

                <div class="trip-form-grid">

                    <div class="trip-form-group">

                        <label class="trip-form-label">Rate *</label>

                        <input
                            type="number"
                            name="rate"
                            id="rate"
                            value="{{ old('rate') }}"
                            class="trip-form-input"
                            min="0"
                            max="99999999.99"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">Freight Amount *</label>

                        <input
                            type="number"
                            name="freight_amount"
                            id="freight_amount"
                            value="{{ old('freight_amount') }}"
                            class="trip-form-input"
                            min="0"
                            max="99999999.99"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                    </div>

                    <div class="trip-form-group full-width">

                        <label class="trip-form-label">POD File</label>

                        <input
                            type="file"
                            name="pod_file"
                            class="trip-form-input"
                            accept=".pdf,.jpg,.jpeg,.png"
                        >

                        <div class="trip-form-help">
                            Accepted formats: PDF, JPG, JPEG, PNG. Maximum size: 5 MB.
                        </div>

                    </div>

                    <div class="trip-form-group full-width">

                        <label class="trip-form-label">Remarks</label>

                        <textarea
                            name="remarks"
                            class="trip-form-textarea"
                            placeholder="Enter any additional remarks..."
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

            {{-- FORM ACTIONS --}}

            <div class="trip-form-actions">

                <a
                    href="{{ route('trips.index') }}"
                    class="trip-cancel-btn"
                >
                    Cancel
                </a>

                <button type="submit" class="trip-save-btn">
                    Create Trip
                </button>

            </div>

        </form>

    </div>

</div>

{{-- ASSIGNMENT AUTO-FILL AND CALENDAR --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | FLATPICKR DATE & TIME PICKER
    |--------------------------------------------------------------------------
    */

    if (typeof flatpickr !== 'undefined') {

        flatpickr(".trip-datetime", {

            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "d-m-Y h:i K",
            time_24hr: false,
            minuteIncrement: 5,
            allowInput: true,
            disableMobile: true,
            clickOpens: true,

            onReady: function (selectedDates, dateStr, instance) {

                const doneButton = document.createElement('button');

                doneButton.type = 'button';
                doneButton.className = 'flatpickr-done-btn';
                doneButton.textContent = 'Done';

                doneButton.addEventListener('click', function () {
                    instance.close();
                });

                instance.calendarContainer.appendChild(doneButton);

            }

        });

    } else {

        console.error('Flatpickr library failed to load.');

    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT AUTO-FILL
    |--------------------------------------------------------------------------
    */

    const assignmentSelect =
        document.getElementById('assignment_id');

    const clientSelect =
        document.getElementById('client_id');

    const vehicleSelect =
        document.getElementById('vehicle_id');

    const driverSelect =
        document.getElementById('driver_id');

    const rateInput =
        document.getElementById('rate');

    const freightInput =
        document.getElementById('freight_amount');

    if (!assignmentSelect) {
        return;
    }

    assignmentSelect.addEventListener('change', function () {

        const selectedOption =
            this.options[this.selectedIndex];

        if (!this.value) {
            return;
        }

        const clientId =
            selectedOption.dataset.client;

        const vehicleId =
            selectedOption.dataset.vehicle;

        const driverId =
            selectedOption.dataset.driver;

        const rate =
            selectedOption.dataset.rate;

        if (clientId && clientSelect) {
            clientSelect.value = clientId;
        }

        if (vehicleId && vehicleSelect) {
            vehicleSelect.value = vehicleId;
        }

        if (driverId && driverSelect) {
            driverSelect.value = driverId;
        }

        if (rate && rateInput && !rateInput.value) {
            rateInput.value = rate;
        }

        if (rate && freightInput && !freightInput.value) {
            freightInput.value = rate;
        }

    });

    /*
    |--------------------------------------------------------------------------
    | TRIGGER AUTO-FILL FOR OLD VALUE
    |--------------------------------------------------------------------------
    */

    if (assignmentSelect.value) {

        assignmentSelect.dispatchEvent(
            new Event('change')
        );

    }

});

</script>

@endsection