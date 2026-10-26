@extends('layouts.app')

@section('content')

<style>
    .assignment-edit-page {
        max-width: 900px;
        margin: 0 auto;
    }

    .assignment-edit-header {
        margin-bottom: 24px;
    }

    .assignment-edit-header h1 {
        margin: 0;
        color: #14213d;
        font-size: 28px;
        font-weight: 800;
    }

    .assignment-edit-header p {
        margin: 6px 0 0;
        color: #6c757d;
        font-size: 13px;
    }

    .assignment-form-card {
        background: #ffffff;
        border: 1px solid #e8edf2;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(31,62,94,.07);
        overflow: hidden;
    }

    .assignment-form-body {
        padding: 24px;
    }

    .assignment-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        margin-bottom: 7px;
        color: #334155;
        font-size: 13px;
        font-weight: 800;
    }

    .required-mark {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        width: 100%;
        height: 44px;
        padding: 0 13px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #ffffff;
        color: #343a40;
        font-size: 13px;
        font-weight: 600;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.10);
    }

    textarea.form-control {
        height: 110px;
        padding: 12px 13px;
        resize: vertical;
        font-family: inherit;
    }

    .readonly-field {
        background: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }

    .form-help {
        margin-top: 5px;
        color: #94a3b8;
        font-size: 11px;
    }

    .validation-error {
        margin-top: 5px;
        color: #dc3545;
        font-size: 11px;
        font-weight: 600;
    }

    .validation-summary {
        margin-bottom: 18px;
        padding: 14px 16px;
        border: 1px solid #f1aeb5;
        border-radius: 8px;
        background: #f8d7da;
        color: #842029;
        font-size: 13px;
    }

    .validation-summary ul {
        margin: 5px 0 0;
        padding-left: 20px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #edf0f3;
    }

    .back-btn,
    .update-btn {
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

    .back-btn {
        background: #6c757d;
        color: #ffffff;
    }

    .back-btn:hover {
        background: #5c636a;
        color: #ffffff;
    }

    .update-btn {
        background: #0d6efd;
        color: #ffffff;
        border: none;
        cursor: pointer;
    }

    .update-btn:hover {
        background: #0b5ed7;
    }

    @media (max-width: 650px) {
        .assignment-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .assignment-form-body {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column;
        }

        .back-btn,
        .update-btn {
            width: 100%;
        }
    }
</style>

<div class="assignment-edit-page">

    {{-- HEADER --}}
    <div class="assignment-edit-header">
        <h1>Edit Assignment</h1>
        <p>Update assignment allocation, dates, billing and status</p>
    </div>

    {{-- TOP VALIDATION SUMMARY --}}
    @if ($errors->any())
        <div class="validation-summary" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <div class="assignment-form-card">
        <div class="assignment-form-body">

            <form
                action="{{ route('assignments.update', $assignment->id) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="assignment-grid">

                    {{-- Assignment Number --}}
                    <div class="form-group">
                        <label class="form-label" for="assignment_no_display">
                            Assignment No.
                        </label>
                        <input
                            type="text"
                            id="assignment_no_display"
                            class="form-control readonly-field"
                            value="{{ $assignment->assignment_no ?? '-' }}"
                            readonly
                        >
                        <div class="form-help">Auto-generated assignment number</div>
                    </div>

                    {{-- Client --}}
                    <div class="form-group">
                        <label for="client_id" class="form-label">
                            Client <span class="required-mark">*</span>
                        </label>
                        <select name="client_id" id="client_id" class="form-select" required>
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option
                                    value="{{ $client->id }}"
                                    {{ old('client_id', $assignment->client_id) == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->client_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vehicle --}}
                    <div class="form-group">
                        <label for="vehicle_id" class="form-label">
                            Vehicle / Truck <span class="required-mark">*</span>
                        </label>
                        <select name="vehicle_id" id="vehicle_id" class="form-select" required>
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $vehicle)
                                <option
                                    value="{{ $vehicle->id }}"
                                    {{ old('vehicle_id', $assignment->vehicle_id) == $vehicle->id ? 'selected' : '' }}
                                >
                                    {{ $vehicle->plate_number ?? 'No Plate' }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Driver --}}
                    <div class="form-group">
                        <label for="driver_id" class="form-label">
                            Driver <span class="required-mark">*</span>
                        </label>
                        <select name="driver_id" id="driver_id" class="form-select" required>
                            <option value="">Select Driver</option>
                            @foreach($drivers as $driver)
                                <option
                                    value="{{ $driver->id }}"
                                    {{ old('driver_id', $assignment->driver_id) == $driver->id ? 'selected' : '' }}
                                >
                                    {{ $driver->driver_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Start Date --}}
                    <div class="form-group">
                        <label for="start_date" class="form-label">
                            Start Date <span class="required-mark">*</span>
                        </label>
                        <input
                            type="date"
                            name="start_date"
                            id="start_date"
                            class="form-control"
                            value="{{ old('start_date', $assignment->start_date ? \Illuminate\Support\Carbon::parse($assignment->start_date)->format('Y-m-d') : '') }}"
                            required
                        >
                        @error('start_date')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div class="form-group">
                        <label for="end_date" class="form-label">
                            End Date <span class="required-mark">*</span>
                        </label>
                        <input
                            type="date"
                            name="end_date"
                            id="end_date"
                            class="form-control"
                            value="{{ old('end_date', $assignment->end_date ? \Illuminate\Support\Carbon::parse($assignment->end_date)->format('Y-m-d') : '') }}"
                            required
                        >
                        @error('end_date')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rate --}}
                    <div class="form-group">
                        <label for="rate" class="form-label">
                            Rate <span class="required-mark">*</span>
                        </label>
                        <input
                            type="number"
                            name="rate"
                            id="rate"
                            class="form-control"
                            value="{{ old('rate', $assignment->rate) }}"
                            min="0"
                            max="99999999.99"
                            step="0.01"
                            required
                        >
                        @error('rate')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rate Basis --}}
                    <div class="form-group">
                        <label for="rate_basis" class="form-label">
                            Rate Basis <span class="required-mark">*</span>
                        </label>
                        <select name="rate_basis" id="rate_basis" class="form-select" required>
                            <option value="">Select Rate Basis</option>
                            <option value="Per Day" {{ old('rate_basis', $assignment->rate_basis) === 'Per Day' ? 'selected' : '' }}>Per Day</option>
                            <option value="Per Month" {{ old('rate_basis', $assignment->rate_basis) === 'Per Month' ? 'selected' : '' }}>Per Month</option>
                            <option value="Per Trip" {{ old('rate_basis', $assignment->rate_basis) === 'Per Trip' ? 'selected' : '' }}>Per Trip</option>
                            <option value="Fixed" {{ old('rate_basis', $assignment->rate_basis) === 'Fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="Custom" {{ old('rate_basis', $assignment->rate_basis) === 'Custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('rate_basis')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PART 2 CONTINUES BELOW --}}
                    
                    {{-- Fuel Rule --}}
<div class="form-group">
    <label for="fuel_rule" class="form-label">
        Fuel Rule <span class="required-mark">*</span>
    </label>

    <select name="fuel_rule" id="fuel_rule" class="form-select" required>
        <option value="">Select Fuel Rule</option>

        <option value="Included"
            {{ old('fuel_rule', $assignment->fuel_rule) === 'Included' ? 'selected' : '' }}>
            Included
        </option>

        <option value="AL SHAQRA Cost"
            {{ old('fuel_rule', $assignment->fuel_rule) === 'AL SHAQRA Cost' ? 'selected' : '' }}>
            AL SHAQRA Cost
        </option>

        <option value="Client Reimbursable"
            {{ old('fuel_rule', $assignment->fuel_rule) === 'Client Reimbursable' ? 'selected' : '' }}>
            Client Reimbursable
        </option>

        <option value="Client Direct"
            {{ old('fuel_rule', $assignment->fuel_rule) === 'Client Direct' ? 'selected' : '' }}>
            Client Direct
        </option>
    </select>

    @error('fuel_rule')
        <div class="validation-error">{{ $message }}</div>
    @enderror
</div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label for="status" class="form-label">
                            Status <span class="required-mark">*</span>
                        </label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">Select Status</option>
                            @foreach(['Active', 'Completed', 'Cancelled'] as $status)
                                <option value="{{ $status }}"
                                    {{ old('status', $assignment->status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Loading Date --}}
                    <div class="form-group">
                        <label for="loading_date" class="form-label">Loading Date</label>
                        <input
                            type="date"
                            name="loading_date"
                            id="loading_date"
                            class="form-control"
                            value="{{ old('loading_date', $assignment->loading_date ? \Illuminate\Support\Carbon::parse($assignment->loading_date)->format('Y-m-d') : '') }}"
                        >
                        @error('loading_date')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Delivery Date --}}
                    <div class="form-group">
                        <label for="delivery_date" class="form-label">Delivery Date</label>
                        <input
                            type="date"
                            name="delivery_date"
                            id="delivery_date"
                            class="form-control"
                            value="{{ old('delivery_date', $assignment->delivery_date ? \Illuminate\Support\Carbon::parse($assignment->delivery_date)->format('Y-m-d') : '') }}"
                        >
                        <div class="form-help">Delivery can be on the loading date or a later date.</div>
                        @error('delivery_date')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Freight Amount --}}
                    <div class="form-group">
                        <label for="freight_amount" class="form-label">Freight Amount</label>
                        <input
                            type="number"
                            name="freight_amount"
                            id="freight_amount"
                            class="form-control"
                            value="{{ old('freight_amount', $assignment->freight_amount) }}"
                            min="0"
                            max="99999999.99"
                            step="0.01"
                        >
                        @error('freight_amount')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remarks --}}
                    <div class="form-group full-width">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea
                            name="remarks"
                            id="remarks"
                            class="form-control"
                        >{{ old('remarks', $assignment->remarks) }}</textarea>
                        @error('remarks')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="form-group full-width">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea
                            name="notes"
                            id="notes"
                            class="form-control"
                        >{{ old('notes', $assignment->notes) }}</textarea>
                        @error('notes')
                            <div class="validation-error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('assignments.index') }}" class="back-btn">
                        Cancel
                    </a>

                    <button type="submit" class="update-btn">
                        Update Assignment
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection