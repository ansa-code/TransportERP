@extends('layouts.app')

@section('title', 'Edit Traffic Fine')

@section('content')

<style>
    .traffic-fine-form-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header-left h1 {
        margin: 0;
        color: #172554;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .page-header-left p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #172554;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .form-section {
        padding: 22px 24px;
        border-bottom: 1px solid #eef2f7;
    }

    .section-title {
        margin: 0 0 18px;
        color: #172554;
        font-size: 16px;
        font-weight: 800;
    }

    .section-subtitle {
        margin: -10px 0 18px;
        color: #94a3b8;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.35px;
    }

    .required-mark {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        outline: none;
        background: #ffffff;
        color: #1e293b;
        font-size: 14px;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .form-control:disabled {
        background: #f8fafc;
        color: #64748b;
        cursor: not-allowed;
    }

    input[type="file"].form-control {
        padding: 6px 8px;
        cursor: pointer;
    }

    input[type="file"].form-control::file-selector-button {
        margin-right: 10px;
        padding: 7px 13px;
        border: 0;
        border-radius: 6px;
        background: #0b2a6f;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    input[type="file"].form-control::file-selector-button:hover {
        background: #123d96;
    }

    .form-help {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.5;
    }

    .field-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
    }

    .existing-attachment {
        margin-top: 8px;
        padding: 9px 11px;
        border: 1px solid #dbeafe;
        border-radius: 7px;
        background: #eff6ff;
        color: #1e40af;
        font-size: 12px;
        line-height: 1.5;
    }

    .existing-attachment strong {
        font-weight: 800;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 24px;
        background: #f8fafc;
    }

    .cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        height: 40px;
        padding: 0 16px;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
    }

    .cancel-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 140px;
        height: 40px;
        padding: 0 18px;
        border: 1px solid #0b2a6f;
        border-radius: 8px;
        background: #0b2a6f;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .save-btn:hover {
        background: #123d96;
    }

    .alert-error {
        margin-bottom: 20px;
        padding: 13px 16px;
        border: 1px solid #fecaca;
        border-radius: 9px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 13px;
    }

    .alert-error ul {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    @media (max-width: 760px) {
        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .back-btn {
            width: 100%;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-section {
            padding: 20px 16px;
        }

        .form-actions {
            padding: 18px 16px;
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }
    }
</style>

<div class="traffic-fine-form-page">

    <div class="page-header">

        <div class="page-header-left">

            <h1>
                Edit Traffic Fine
            </h1>

            <p>
                Update traffic fine, payment and driver deduction details.
            </p>

        </div>

        <a
            href="{{ route('traffic-fines.index') }}"
            class="back-btn"
        >
            ← Back to Traffic Fines
        </a>

    </div>

    @if($errors->any())

        <div class="alert-error">

            <strong>
                Please correct the following:
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

    <form
        action="{{ route('traffic-fines.update', $fine->id) }}"
        method="POST"
        class="form-card"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        {{-- FINE INFORMATION --}}

        <div class="form-section">

            <h2 class="section-title">
                Fine Information
            </h2>

            <p class="section-subtitle">
                Update the actual traffic challan / ticket number and fine details.
            </p>

            <div class="form-grid">

                {{-- FINE NUMBER --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="fine_number"
                    >
                        Fine Number <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        id="fine_number"
                        name="fine_number"
                        class="form-control"
                        value="{{ old('fine_number', $fine->fine_number) }}"
                        maxlength="255"
                        placeholder="Enter actual challan / ticket number"
                        required
                    >

                    <div class="form-help">
                        Enter the fine number exactly as printed on the traffic challan or ticket.
                    </div>

                    @error('fine_number')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- FINE DATE --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="fine_date"
                    >
                        Fine Date <span class="required-mark">*</span>
                    </label>

                    <input
                        type="date"
                        id="fine_date"
                        name="fine_date"
                        class="form-control"
                        value="{{ old('fine_date', $fine->fine_date?->format('Y-m-d')) }}"
                        required
                    >

                    @error('fine_date')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- VEHICLE --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="vehicle_id"
                    >
                        Vehicle
                    </label>

                    <select
                        id="vehicle_id"
                        name="vehicle_id"
                        class="form-control"
                    >

                        <option value="">
                            Select Vehicle
                        </option>

                        @foreach($vehicles as $vehicle)

                            <option
                                value="{{ $vehicle->id }}"
                                {{ old('vehicle_id', $fine->vehicle_id) == $vehicle->id ? 'selected' : '' }}
                            >
                                {{ $vehicle->plate_number }}
                            </option>

                        @endforeach

                    </select>

                    <div class="form-help">
                        Vehicle or Driver must be selected.
                    </div>

                    @error('vehicle_id')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- DRIVER --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="driver_id"
                    >
                        Driver
                    </label>

                    <select
                        id="driver_id"
                        name="driver_id"
                        class="form-control"
                    >

                        <option value="">
                            Select Driver
                        </option>

                        @foreach($drivers as $driver)

                            <option
                                value="{{ $driver->id }}"
                                {{ old('driver_id', $fine->driver_id) == $driver->id ? 'selected' : '' }}
                            >
                                {{ $driver->driver_name }}
                            </option>

                        @endforeach

                    </select>

                    <div class="form-help">
                        Vehicle or Driver must be selected.
                    </div>

                    @error('driver_id')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- AMOUNT --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="amount"
                    >
                        Amount <span class="required-mark">*</span>
                    </label>

                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        class="form-control"
                        value="{{ old('amount', $fine->amount) }}"
                        min="0.01"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >

                    @error('amount')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- REASON / LOCATION --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="reason_location"
                    >
                        Reason / Location
                    </label>

                    <input
                        type="text"
                        id="reason_location"
                        name="reason_location"
                        class="form-control"
                        value="{{ old('reason_location', $fine->reason_location) }}"
                        maxlength="255"
                        placeholder="Enter fine reason or location"
                    >

                    @error('reason_location')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>
        {{-- PAYMENT & DEDUCTION --}}

        <div class="form-section">

            <h2 class="section-title">
                Payment & Deduction
            </h2>

            <div class="form-grid">

                {{-- PAYMENT STATUS --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="payment_status"
                    >
                        Payment Status <span class="required-mark">*</span>
                    </label>

                    <select
                        id="payment_status"
                        name="payment_status"
                        class="form-control"
                        required
                    >

                        <option
                            value="Unpaid"
                            {{ old('payment_status', $fine->payment_status) === 'Unpaid' ? 'selected' : '' }}
                        >
                            Unpaid
                        </option>

                        <option
                            value="Paid"
                            {{ old('payment_status', $fine->payment_status) === 'Paid' ? 'selected' : '' }}
                        >
                            Paid
                        </option>

                        <option
                            value="Deducted"
                            {{ old('payment_status', $fine->payment_status) === 'Deducted' ? 'selected' : '' }}
                        >
                            Deducted
                        </option>

                        <option
                            value="Cancelled"
                            {{ old('payment_status', $fine->payment_status) === 'Cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                    @error('payment_status')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- DEDUCT FROM DRIVER --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="deduct_from_driver"
                    >
                        Deduct from Driver <span class="required-mark">*</span>
                    </label>

                    <select
                        id="deduct_from_driver"
                        name="deduct_from_driver"
                        class="form-control"
                        required
                    >

                        <option
                            value="0"
                            {{ old('deduct_from_driver', $fine->deduct_from_driver ? '1' : '0') == '0' ? 'selected' : '' }}
                        >
                            No
                        </option>

                        <option
                            value="1"
                            {{ old('deduct_from_driver', $fine->deduct_from_driver ? '1' : '0') == '1' ? 'selected' : '' }}
                        >
                            Yes
                        </option>

                    </select>

                    <div class="form-help">
                        Driver deduction is used for payroll processing.
                    </div>

                    @error('deduct_from_driver')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- PAID DATE --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="paid_date"
                    >
                        Paid Date
                    </label>

                    <input
                        type="date"
                        id="paid_date"
                        name="paid_date"
                        class="form-control"
                        value="{{ old('paid_date', $fine->paid_date?->format('Y-m-d')) }}"
                    >

                    <div class="form-help">
                        Required when Payment Status is Paid.
                    </div>

                    @error('paid_date')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- PAID REFERENCE --}}

                <div class="form-group">

                    <label
                        class="form-label"
                        for="paid_reference"
                    >
                        Paid Reference
                    </label>

                    <input
                        type="text"
                        id="paid_reference"
                        name="paid_reference"
                        class="form-control"
                        value="{{ old('paid_reference', $fine->paid_reference) }}"
                        maxlength="255"
                        placeholder="e.g. TXN-458721963"
                    >

                    <div class="form-help">
                        Required when Payment Status is Paid. Enter the receipt or payment transaction reference.
                    </div>

                    @error('paid_reference')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- ATTACHMENT --}}

                <div class="form-group full-width">

                    <label
                        class="form-label"
                        for="attachment"
                    >
                        Attachment
                    </label>

                    <input
                        type="file"
                        id="attachment"
                        name="attachment"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >

                    <div class="form-help">
                        Upload a new fine notice, transaction screenshot or supporting document.
                        Accepted: JPG, JPEG, PNG, PDF. Maximum size: 10 MB.
                    </div>

                    @if(!empty($fine->attachment))

                        <div class="existing-attachment">

                            <strong>
                                Existing attachment:
                            </strong>

                            {{ basename($fine->attachment) }}

                            <br>

                            Select a new file above only if you want to replace the existing attachment.

                        </div>

                    @endif

                    @error('attachment')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}

        <div class="form-actions">

            <a
                href="{{ route('traffic-fines.index') }}"
                class="cancel-btn"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="save-btn"
            >
                Update Traffic Fine
            </button>

        </div>

    </form>

</div>

@endsection