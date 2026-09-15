@extends('layouts.app')

@section('content')

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

.trip-current-pod {
    margin-top: 8px;
    font-size: 11px;
    color: #64748b;
}

.trip-current-pod strong {
    color: #334155;
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

.trip-closed-box {
    background: #fff3cd;
    color: #664d03;
    border: 1px solid #ffecb5;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
    font-size: 12px;
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


<div class="trip-form-page">


    <div class="trip-form-header">

        <h1>
            Edit Trip
        </h1>

        <p>
            Update trip details, route, resources and billing information.
        </p>

    </div>


    @if($errors->any())

        <div class="trip-error-box">

            <strong>
                Please fix the following:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @if($trip->status === 'Closed')

        <div class="trip-closed-box">

            This trip is currently closed. Reopen permission is required
            before editing it.

        </div>

    @endif


    <div class="trip-form-card">

        <form
            action="{{ route('trips.update', $trip->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            {{-- =================================================
                 TRIP INFORMATION
            ================================================== --}}

            <div class="trip-form-section">

                <div class="trip-form-section-title">
                    Trip Information
                </div>


                <div class="trip-form-grid">


                    {{-- Trip Number --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Trip Number
                        </label>

                        <input
                            type="text"
                            value="{{ $trip->trip_no }}"
                            class="trip-form-input"
                            readonly
                        >

                        <div class="trip-form-help">
                            Trip number is generated automatically.
                        </div>

                    </div>


                    {{-- Assignment --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Assignment
                        </label>

                        <select
                            name="assignment_id"
                            id="assignment_id"
                            class="trip-form-select"
                        >

                            <option value="">
                                Direct Trip — No Assignment
                            </option>

                            @foreach($assignments as $assignment)

                                <option
                                    value="{{ $assignment->id }}"
                                    data-client="{{ $assignment->client_id }}"
                                    data-vehicle="{{ $assignment->vehicle_id }}"
                                    data-driver="{{ $assignment->driver_id }}"
                                    data-rate="{{ $assignment->rate ?? '' }}"
                                    {{ old('assignment_id', $trip->assignment_id) == $assignment->id ? 'selected' : '' }}
                                >

                                    {{ $assignment->assignment_no }}

                                    —
                                    {{ $assignment->client?->client_name ?? 'Client' }}

                                    —
                                    {{ $assignment->vehicle?->plate_number
                                        ?? $assignment->vehicle?->vehicle_number
                                        ?? 'Vehicle' }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Client --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Client *
                        </label>

                        <select
                            name="client_id"
                            id="client_id"
                            class="trip-form-select"
                            required
                        >

                            <option value="">
                                Select Client
                            </option>

                            @foreach($clients as $client)

                                <option
                                    value="{{ $client->id }}"
                                    {{ old('client_id', $trip->client_id) == $client->id ? 'selected' : '' }}
                                >

                                    {{ $client->client_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Vehicle --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Vehicle / Number Plate *
                        </label>

                        <select
                            name="vehicle_id"
                            id="vehicle_id"
                            class="trip-form-select"
                            required
                        >

                            <option value="">
                                Select Vehicle
                            </option>

                            @foreach($vehicles as $vehicle)

                                <option
                                    value="{{ $vehicle->id }}"
                                    {{ old('vehicle_id', $trip->vehicle_id) == $vehicle->id ? 'selected' : '' }}
                                >

                                    {{ $vehicle->plate_number
                                        ?? $vehicle->vehicle_number
                                        ?? 'Vehicle #' . $vehicle->id }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Driver --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Driver *
                        </label>

                        <select
                            name="driver_id"
                            id="driver_id"
                            class="trip-form-select"
                            required
                        >

                            <option value="">
                                Select Driver
                            </option>

                            @foreach($drivers as $driver)

                                <option
                                    value="{{ $driver->id }}"
                                    {{ old('driver_id', $trip->driver_id) == $driver->id ? 'selected' : '' }}
                                >

                                    {{ $driver->driver_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Status *
                        </label>

                        <select
                            name="status"
                            class="trip-form-select"
                            required
                        >

                            @foreach([
                                'Planned',
                                'Assigned',
                                'In Transit',
                                'Delivered',
                                'Closed',
                                'Cancelled'
                            ] as $tripStatus)

                                <option
                                    value="{{ $tripStatus }}"
                                    {{ old('status', $trip->status) === $tripStatus ? 'selected' : '' }}
                                >
                                    {{ $tripStatus }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 DATE & ROUTE
            ================================================== --}}

            <div class="trip-form-section">

                <div class="trip-form-section-title">
                    Trip Date & Route
                </div>


                <div class="trip-form-grid">


                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Trip Start *
                        </label>

                        <input
                            type="datetime-local"
                            name="trip_start"
                            value="{{ old(
                                'trip_start',
                                $trip->trip_start
                                    ? \Carbon\Carbon::parse($trip->trip_start)->format('Y-m-d\TH:i')
                                    : ''
                            ) }}"
                            class="trip-form-input"
                            required
                        >

                    </div>


                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Trip End
                        </label>

                        <input
                            type="datetime-local"
                            name="trip_end"
                            value="{{ old(
                                'trip_end',
                                $trip->trip_end
                                    ? \Carbon\Carbon::parse($trip->trip_end)->format('Y-m-d\TH:i')
                                    : ''
                            ) }}"
                            class="trip-form-input"
                        >

                    </div>


                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Loading Point *
                        </label>

                        <input
                            type="text"
                            name="loading_point"
                            value="{{ old('loading_point', $trip->loading_point) }}"
                            class="trip-form-input"
                            required
                        >

                    </div>


                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Unloading Point *
                        </label>

                        <input
                            type="text"
                            name="unloading_point"
                            value="{{ old('unloading_point', $trip->unloading_point) }}"
                            class="trip-form-input"
                            required
                        >

                    </div>

                </div>

            </div>
            {{-- =================================================
                 BILLING
            ================================================== --}}

            <div class="trip-form-section">

                <div class="trip-form-section-title">
                    Billing
                </div>

                <div class="trip-form-grid">

                    {{-- Rate --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Rate *
                        </label>

                        <input
                            type="number"
                            name="rate"
                            id="rate"
                            value="{{ old('rate', $trip->rate) }}"
                            class="trip-form-input"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>


                    {{-- Freight Amount --}}

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Freight Amount *
                        </label>

                        <input
                            type="number"
                            name="freight_amount"
                            id="freight_amount"
                            value="{{ old('freight_amount', $trip->freight_amount) }}"
                            class="trip-form-input"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>


                    {{-- POD File --}}

                    <div class="trip-form-group full-width">

                        <label class="trip-form-label">
                            POD File
                        </label>

                        <input
                            type="file"
                            name="pod_file"
                            class="trip-form-input"
                            accept=".pdf,.jpg,.jpeg,.png"
                        >

                        @if($trip->pod_file)

                            <div class="trip-current-pod">

                                <strong>Current POD:</strong>

                                <a
                                    href="{{ asset('storage/' . $trip->pod_file) }}"
                                    target="_blank"
                                >
                                    View Current File
                                </a>

                            </div>

                        @endif

                        <div class="trip-form-help">
                            Upload a new file only if you want to replace the existing POD.
                            Accepted formats: PDF, JPG, JPEG, PNG. Maximum size: 5 MB.
                        </div>

                    </div>


                    {{-- Remarks --}}

                    <div class="trip-form-group full-width">

                        <label class="trip-form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="trip-form-textarea"
                            placeholder="Enter any additional remarks..."
                        >{{ old('remarks', $trip->remarks) }}</textarea>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 REOPEN CLOSED TRIP
            ================================================== --}}

            @if($trip->status === 'Closed')

                <div class="trip-form-section">

                    <div class="trip-form-section-title">
                        Closed Trip
                    </div>

                    <div class="trip-form-group">

                        <label class="trip-form-label">
                            Reopen Trip
                        </label>

                        <label style="
                            display:flex;
                            align-items:center;
                            gap:8px;
                            color:#475569;
                            font-size:12px;
                            font-weight:600;
                        ">

                            <input
                                type="checkbox"
                                name="reopen"
                                value="1"
                            >

                            Allow editing of this closed trip

                        </label>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="trip-form-actions">

                <a
                    href="{{ route('trips.index') }}"
                    class="trip-cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="trip-save-btn"
                >
                    Update Trip
                </button>

            </div>


        </form>

    </div>

</div>


{{-- =========================================================
     ASSIGNMENT AUTO-FILL
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

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


        if (rate && rateInput) {
            rateInput.value = rate;
        }


        if (rate && freightInput) {
            freightInput.value = rate;
        }

    });

});

</script>

@endsection