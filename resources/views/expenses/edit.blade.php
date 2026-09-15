@extends('layouts.app')

@section('content')

@php
    $expenseTypes = [
        'Fuel' => [
            'Fuel Purchase',
            'Diesel',
            'Petrol',
            'Other',
        ],
        'Maintenance' => [
            'Tyre',
            'Battery',
            'Washing',
            'Oil Change',
            'AC Repair',
            'Brake',
            'Electrical',
            'Spare Parts',
            'Other',
        ],
        'Visa' => [
            'Visa Fee',
            'Medical',
            'Emirates ID',
            'Other',
        ],
        'Fine' => [
            'Traffic Fine',
            'Other',
        ],
        'Registration' => [
            'Registration Fee',
            'Renewal',
            'Inspection',
            'Other',
        ],
        'Insurance' => [
            'Insurance Premium',
            'Renewal',
            'Other',
        ],
        'Admin' => [
            'Office',
            'Stationery',
            'Other',
        ],
        'Other' => [
            'Other',
        ],
    ];

    $selectedExpenseTypes = old(
        'expense_type',
        is_array($expense->expense_type)
            ? $expense->expense_type
            : ($expense->expense_type ? [$expense->expense_type] : [])
    );
@endphp

<style>
    .expense-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        color: #374151;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #f9fafb;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 26px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    }

    .section-title {
        margin: 0 0 18px;
        font-size: 18px;
        font-weight: 700;
        color: #172033;
    }

    .section-subtitle {
        margin: -10px 0 20px;
        color: #6b7280;
        font-size: 13px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 22px;
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
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .required {
        color: #dc2626;
    }

    .form-control {
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        color: #111827;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
    }

    textarea.form-control {
        height: 105px;
        padding: 11px 12px;
        resize: vertical;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .form-control[readonly] {
        background: #f3f4f6;
        color: #6b7280;
        cursor: not-allowed;
    }

    .help-text {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
    }

    .error-box {
        margin-bottom: 22px;
        padding: 14px 16px;
        border: 1px solid #fecaca;
        border-radius: 9px;
        background: #fef2f2;
        color: #991b1b;
    }

    .error-box strong {
        display: block;
        margin-bottom: 7px;
    }

    .error-box ul {
        margin: 0;
        padding-left: 18px;
    }

    .expense-type-box {
        position: relative;
    }

    .expense-type-select {
        min-height: 40px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        padding: 7px 38px 7px 10px;
        cursor: pointer;
        position: relative;
        box-sizing: border-box;
    }

    .expense-type-select::after {
        content: '▼';
        position: absolute;
        right: 13px;
        top: 12px;
        font-size: 10px;
        color: #6b7280;
    }

    .selected-types {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        min-height: 24px;
        align-items: center;
    }

    .selected-placeholder {
        color: #9ca3af;
        font-size: 14px;
    }

    .type-chip {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 6px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
    }

    .expense-type-options {
        display: none;
        position: absolute;
        z-index: 30;
        left: 0;
        right: 0;
        top: calc(100% + 5px);
        max-height: 220px;
        overflow-y: auto;
        padding: 8px;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
    }

    .expense-type-options.open {
        display: block;
    }

    .type-option {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 9px 8px;
        border-radius: 7px;
        cursor: pointer;
        font-size: 13px;
        color: #374151;
    }

    .type-option:hover {
        background: #f3f4f6;
    }

    .type-option input {
        width: 15px;
        height: 15px;
        accent-color: #2563eb;
    }

    .cost-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .cost-box {
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #f9fafb;
    }

    .cost-box.total-box {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .cost-label {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        font-weight: 700;
        color: #4b5563;
    }

    .cost-input {
        width: 100%;
        height: 40px;
        padding: 0 10px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        box-sizing: border-box;
        font-size: 14px;
        outline: none;
    }

    .cost-input:focus {
        border-color: #2563eb;
    }

    .cost-input[readonly] {
        background: #f3f4f6;
        font-weight: 700;
        color: #1d4ed8;
    }

    .action-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid #e5e7eb;
    }

    .cancel-btn,
    .save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        height: 40px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .cancel-btn {
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
    }

    .cancel-btn:hover {
        background: #f9fafb;
    }

    .save-btn {
        border: 1px solid #2563eb;
        background: #2563eb;
        color: #fff;
    }

    .save-btn:hover {
        background: #1d4ed8;
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .cost-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-card {
            padding: 18px;
        }

        .cost-grid {
            grid-template-columns: 1fr;
        }

        .action-row {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }
    }
</style>

<div class="expense-page">

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Expense</h1>
            <p class="page-subtitle">
                Update expense details, cost breakdown and payment information
            </p>
        </div>

        <a href="{{ route('expenses.index') }}" class="back-btn">
            ← Back to Expenses
        </a>
    </div>

    @if($errors->any())
        <div class="error-box">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">

        <form
            action="{{ route('expenses.update', $expense->id) }}"
            method="POST"
            enctype="multipart/form-data"
            id="expenseEditForm"
        >

            @csrf
            @method('PUT')

            <div class="form-section">

                <h2 class="section-title">Expense Information</h2>

                <p class="section-subtitle">
                    Update the basic expense record and operational references.
                </p>

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Expense No.</label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $expense->expense_no }}"
                            readonly
                        >

                        <div class="help-text">
                            Expense number is system generated and cannot be changed.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Expense Date <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="expense_date"
                            class="form-control"
                            value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Category <span class="required">*</span>
                        </label>

                        <select
                            name="category"
                            id="category"
                            class="form-control"
                            required
                        >
                            <option value="">Select Category</option>

                            @foreach(array_keys($expenseTypes) as $category)
                                <option
                                    value="{{ $category }}"
                                    {{ old('category', $expense->category) === $category ? 'selected' : '' }}
                                >
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Expense Type <span class="required">*</span>
                        </label>

                        <div class="expense-type-box">

                            <div
                                class="expense-type-select"
                                id="expenseTypeSelect"
                            >
                                <div
                                    class="selected-types"
                                    id="selectedTypes"
                                ></div>
                            </div>

                            <div
                                class="expense-type-options"
                                id="expenseTypeOptions"
                            ></div>

                        </div>

                        <div class="help-text">
                            Select one or multiple expense types.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Vehicle</label>

                        <select
                            name="vehicle_id"
                            class="form-control"
                        >
                            <option value="">Select Vehicle</option>

                            @foreach($vehicles as $vehicle)
                                <option
                                    value="{{ $vehicle->id }}"
                                    {{ (string) old('vehicle_id', $expense->vehicle_id) === (string) $vehicle->id ? 'selected' : '' }}
                                >
                                    {{ $vehicle->plate_number }}
                                </option>
                            @endforeach
                        </select>

                        <div class="help-text">
                            Optional vehicle reference.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Driver</label>

                        <select
                            name="driver_id"
                            class="form-control"
                        >
                            <option value="">Select Driver</option>

                            @foreach($drivers as $driver)
                                <option
                                    value="{{ $driver->id }}"
                                    {{ (string) old('driver_id', $expense->driver_id) === (string) $driver->id ? 'selected' : '' }}
                                >
                                    {{ $driver->driver_name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="help-text">
                            Optional driver reference.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Client</label>

                        <select
                            name="client_id"
                            class="form-control"
                        >
                            <option value="">Select Client</option>

                            @foreach($clients as $client)
                                <option
                                    value="{{ $client->id }}"
                                    {{ (string) old('client_id', $expense->client_id) === (string) $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->client_name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="help-text">
                            Optional client reference for recovery/invoicing.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Assignment</label>

                        <select
                            name="assignment_id"
                            class="form-control"
                        >
                            <option value="">Select Assignment</option>

                            @foreach($assignments as $assignment)
                                <option
                                    value="{{ $assignment->id }}"
                                    {{ (string) old('assignment_id', $expense->assignment_id) === (string) $assignment->id ? 'selected' : '' }}
                                >
                                    Assignment #{{ $assignment->id }}
                                </option>
                            @endforeach
                        </select>

                        <div class="help-text">
                            Optional operational reference; recommended where applicable.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Trip</label>

                        <select
                            name="trip_id"
                            class="form-control"
                        >
                            <option value="">Select Trip</option>

                            @foreach($trips as $trip)
                                <option
                                    value="{{ $trip->id }}"
                                    {{ (string) old('trip_id', $expense->trip_id) === (string) $trip->id ? 'selected' : '' }}
                                >
                                    Trip #{{ $trip->id }}
                                </option>
                            @endforeach
                        </select>

                        <div class="help-text">
                            Optional trip reference for operational reporting.
                        </div>
                    </div>

                </div>

            </div>

            <div class="form-section">

                <h2 class="section-title">Cost Breakdown</h2>

                <p class="section-subtitle">
                    Enter individual costs. Total Amount is calculated automatically.
                </p>

                <div class="cost-grid">

                    <div class="cost-box">
                        <label class="cost-label">Parts Cost</label>

                        <input
                            type="number"
                            name="parts_cost"
                            id="parts_cost"
                            class="cost-input"
                            min="0"
                            step="0.01"
                            value="{{ old('parts_cost', $expense->parts_cost ?? 0) }}"
                        >
                    </div>

                    <div class="cost-box">
                        <label class="cost-label">Labour Cost</label>

                        <input
                            type="number"
                            name="labour_cost"
                            id="labour_cost"
                            class="cost-input"
                            min="0"
                            step="0.01"
                            value="{{ old('labour_cost', $expense->labour_cost ?? 0) }}"
                        >
                    </div>

                    <div class="cost-box">
                        <label class="cost-label">Other Cost</label>

                        <input
                            type="number"
                            name="other_cost"
                            id="other_cost"
                            class="cost-input"
                            min="0"
                            step="0.01"
                            value="{{ old('other_cost', $expense->other_cost ?? 0) }}"
                        >
                    </div>

                    <div class="cost-box total-box">
                        <label class="cost-label">Total Amount</label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            class="cost-input"
                            value="{{ old('amount', $expense->amount ?? 0) }}"
                            readonly
                        >

                        <div class="help-text">
                            Automatically calculated.
                        </div>
                    </div>

                </div>

            </div>
            <div class="form-section">

                <h2 class="section-title">Payment Information</h2>

                <p class="section-subtitle">
                    Update payment status and reimbursement information.
                </p>

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">
                            Payment Status <span class="required">*</span>
                        </label>

                        <select
                            name="payment_status"
                            id="payment_status"
                            class="form-control"
                            required
                        >
                            <option
                                value="Pending"
                                {{ old('payment_status', $expense->payment_status) === 'Pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="Paid"
                                {{ old('payment_status', $expense->payment_status) === 'Paid' ? 'selected' : '' }}
                            >
                                Paid
                            </option>

                            <option
                                value="Cancelled"
                                {{ old('payment_status', $expense->payment_status) === 'Cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Payment Method / Reference
                            <span
                                class="required"
                                id="paymentReferenceRequired"
                            >*</span>
                        </label>

                        <input
                            type="text"
                            name="payment_method_reference"
                            id="payment_method_reference"
                            class="form-control"
                            value="{{ old('payment_method_reference', $expense->payment_method_reference) }}"
                            placeholder="e.g. Cash / Bank / Reference No."
                        >

                        <div class="help-text">
                            Required when Payment Status is Paid.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Reimbursable <span class="required">*</span>
                        </label>

                        @php
                            $reimbursableValue = old(
                                'reimbursable',
                                $expense->reimbursable ? '1' : '0'
                            );
                        @endphp

                        <select
                            name="reimbursable"
                            class="form-control"
                            required
                        >
                            <option
                                value="0"
                                {{ $reimbursableValue === '0' ? 'selected' : '' }}
                            >
                                No
                            </option>

                            <option
                                value="1"
                                {{ $reimbursableValue === '1' ? 'selected' : '' }}
                            >
                                Yes
                            </option>
                        </select>

                        <div class="help-text">
                            Mark Yes if the expense can be recovered from the client.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Replace Receipt</label>

                        <input
                            type="file"
                            name="receipt"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.pdf"
                        >

                        @if($expense->receipt)
                            <div class="help-text">
                                Existing receipt is already attached. Upload a new file only if you want to replace it.
                            </div>
                        @else
                            <div class="help-text">
                                Optional: JPG, JPEG, PNG or PDF.
                            </div>
                        @endif
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Remarks</label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            placeholder="Enter any additional remarks or audit notes..."
                        >{{ old('remarks', $expense->remarks) }}</textarea>
                    </div>

                </div>

            </div>

            <div class="action-row">

                <a
                    href="{{ route('expenses.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Update Expense
                </button>

            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const expenseTypes = @json($expenseTypes);
    const oldExpenseTypes = @json(array_values($selectedExpenseTypes));

    const categorySelect = document.getElementById('category');
    const expenseTypeSelect = document.getElementById('expenseTypeSelect');
    const expenseTypeOptions = document.getElementById('expenseTypeOptions');
    const selectedTypes = document.getElementById('selectedTypes');

    const partsCost = document.getElementById('parts_cost');
    const labourCost = document.getElementById('labour_cost');
    const otherCost = document.getElementById('other_cost');
    const amount = document.getElementById('amount');

    const paymentStatus = document.getElementById('payment_status');
    const paymentReference = document.getElementById('payment_method_reference');
    const paymentReferenceRequired = document.getElementById('paymentReferenceRequired');

    function renderSelectedTypes() {

        selectedTypes.innerHTML = '';

        const checked = expenseTypeOptions.querySelectorAll(
            'input[name="expense_type[]"]:checked'
        );

        if (checked.length === 0) {
            const placeholder = document.createElement('span');

            placeholder.className = 'selected-placeholder';
            placeholder.textContent = 'Select Expense Type';

            selectedTypes.appendChild(placeholder);

            return;
        }

        checked.forEach(function (checkbox) {

            const chip = document.createElement('span');

            chip.className = 'type-chip';
            chip.textContent = checkbox.value;

            selectedTypes.appendChild(chip);
        });
    }

    function renderExpenseTypes(preserveSelection = true) {

        const category = categorySelect.value;

        expenseTypeOptions.innerHTML = '';

        if (!category || !expenseTypes[category]) {
            renderSelectedTypes();
            return;
        }

        const currentChecked = [];

        if (preserveSelection) {
            expenseTypeOptions
                .querySelectorAll('input[name="expense_type[]"]:checked')
                .forEach(function (checkbox) {
                    currentChecked.push(checkbox.value);
                });
        }

        let valuesToSelect = [];

        if (preserveSelection && currentChecked.length > 0) {
            valuesToSelect = currentChecked;
        } else {
            valuesToSelect = oldExpenseTypes;
        }

        expenseTypes[category].forEach(function (type) {

            const label = document.createElement('label');

            label.className = 'type-option';

            const checkbox = document.createElement('input');

            checkbox.type = 'checkbox';
            checkbox.name = 'expense_type[]';
            checkbox.value = type;

            if (valuesToSelect.includes(type)) {
                checkbox.checked = true;
            }

            checkbox.addEventListener('change', function () {
                renderSelectedTypes();
            });

            const text = document.createElement('span');

            text.textContent = type;

            label.appendChild(checkbox);
            label.appendChild(text);

            expenseTypeOptions.appendChild(label);
        });

        renderSelectedTypes();
    }

    function calculateTotal() {

        const parts = parseFloat(partsCost.value) || 0;
        const labour = parseFloat(labourCost.value) || 0;
        const other = parseFloat(otherCost.value) || 0;

        const total = parts + labour + other;

        amount.value = total.toFixed(2);
    }

    function updatePaymentReference() {

        if (paymentStatus.value === 'Paid') {

            paymentReference.required = true;
            paymentReferenceRequired.style.display = 'inline';

        } else {

            paymentReference.required = false;
            paymentReferenceRequired.style.display = 'none';
        }
    }

    expenseTypeSelect.addEventListener('click', function (event) {

        event.stopPropagation();

        expenseTypeOptions.classList.toggle('open');
    });

    expenseTypeOptions.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    document.addEventListener('click', function () {
        expenseTypeOptions.classList.remove('open');
    });

    categorySelect.addEventListener('change', function () {

        oldExpenseTypes.length = 0;

        renderExpenseTypes(false);
    });

    partsCost.addEventListener('input', calculateTotal);
    labourCost.addEventListener('input', calculateTotal);
    otherCost.addEventListener('input', calculateTotal);

    paymentStatus.addEventListener('change', updatePaymentReference);

    const initialCategory = categorySelect.value;

    if (initialCategory) {
        renderExpenseTypes(true);
    } else {
        renderSelectedTypes();
    }

    calculateTotal();
    updatePaymentReference();

    document.getElementById('expenseEditForm').addEventListener('submit', function (event) {

        const selected = expenseTypeOptions.querySelectorAll(
            'input[name="expense_type[]"]:checked'
        );

        if (selected.length === 0) {

            event.preventDefault();

            alert('Please select at least one Expense Type.');

            expenseTypeSelect.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            return;
        }

        calculateTotal();

        const total = parseFloat(amount.value) || 0;

        if (total <= 0) {

            event.preventDefault();

            alert('Total Amount must be greater than 0.');

            return;
        }

        if (
            paymentStatus.value === 'Paid' &&
            paymentReference.value.trim() === ''
        ) {

            event.preventDefault();

            alert('Payment Method / Reference is required when Payment Status is Paid.');

            paymentReference.focus();

            return;
        }
    });

});
</script>

@endsection