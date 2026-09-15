@extends('layouts.app')

@section('content')

<div class="expense-create-page">

    <div class="page-header">
        <div>
            <h1>Add Expense</h1>
            <p>Record expense details, cost breakdown, payment and recovery information.</p>
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

        $selectedExpenseTypes = old('expense_type', []);

        if (!is_array($selectedExpenseTypes)) {
            $selectedExpenseTypes = [];
        }
    @endphp


    <form
        action="{{ route('expenses.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="expenseForm"
    >

        @csrf


        {{-- EXPENSE INFORMATION --}}
        <div class="form-card">

            <div class="card-heading">
                <div>
                    <h2>Expense Information</h2>
                    <p>Basic expense and operational reference details</p>
                </div>
            </div>


            <div class="form-grid">

                {{-- EXPENSE NO --}}
                <div class="form-group">

                    <label>
                        Expense No.
                    </label>

                    <input
                        type="text"
                        value="Auto Generated"
                        class="readonly-input"
                        readonly
                    >

                    <small>
                        Format: AST-EXP-YYYY-#####
                    </small>

                </div>


                {{-- EXPENSE DATE --}}
                <div class="form-group">

                    <label for="expense_date">
                        Expense Date <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="expense_date"
                        id="expense_date"
                        value="{{ old('expense_date', date('Y-m-d')) }}"
                        required
                    >

                </div>


                {{-- CATEGORY --}}
                <div class="form-group">

                    <label for="category">
                        Category <span>*</span>
                    </label>

                    <select
                        name="category"
                        id="category"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>

                        @foreach(array_keys($expenseTypes) as $category)

                            <option
                                value="{{ $category }}"
                                {{ old('category') === $category ? 'selected' : '' }}
                            >
                                {{ $category }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- EXPENSE TYPE --}}
                <div class="form-group expense-type-group">

                    <label>
                        Expense Type <span>*</span>
                    </label>

                    <div
                        class="multi-select-box"
                        id="expenseTypeBox"
                    >

                        <button
                            type="button"
                            class="multi-select-trigger"
                            id="expenseTypeTrigger"
                        >
                            <span id="expenseTypeText">
                                Select Expense Type
                            </span>

                            <span class="multi-select-arrow">
                                ▼
                            </span>
                        </button>


                        <div
                            class="multi-select-dropdown"
                            id="expenseTypeDropdown"
                        >

                            <div class="type-empty">
                                Select a category first.
                            </div>

                        </div>

                    </div>

                    <small>
                        You can select multiple expense types.
                    </small>

                </div>


                {{-- VEHICLE --}}
                <div class="form-group">

                    <label for="vehicle_id">
                        Vehicle
                    </label>

                    <select
                        name="vehicle_id"
                        id="vehicle_id"
                    >

                        <option value="">
                            Select Vehicle
                        </option>

                        @foreach($vehicles as $vehicle)

                            <option
                                value="{{ $vehicle->id }}"
                                {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}
                            >
                                {{ $vehicle->plate_number }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- DRIVER --}}
                <div class="form-group">

                    <label for="driver_id">
                        Driver
                    </label>

                    <select
                        name="driver_id"
                        id="driver_id"
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

                </div>


                {{-- CLIENT --}}
                <div class="form-group">

                    <label for="client_id">
                        Client
                    </label>

                    <select
                        name="client_id"
                        id="client_id"
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

                </div>


                {{-- ASSIGNMENT --}}
                <div class="form-group">

                    <label for="assignment_id">
                        Assignment
                    </label>

                    <select
                        name="assignment_id"
                        id="assignment_id"
                    >

                        <option value="">
                            Select Assignment
                        </option>

                        @foreach($assignments as $assignment)

                            <option
                                value="{{ $assignment->id }}"
                                {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}
                            >
                                Assignment #{{ $assignment->id }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Optional operational reference.
                    </small>

                </div>


                {{-- TRIP --}}
                <div class="form-group">

                    <label for="trip_id">
                        Trip
                    </label>

                    <select
                        name="trip_id"
                        id="trip_id"
                    >

                        <option value="">
                            Select Trip
                        </option>

                        @foreach($trips as $trip)

                            <option
                                value="{{ $trip->id }}"
                                {{ old('trip_id') == $trip->id ? 'selected' : '' }}
                            >
                                Trip #{{ $trip->id }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Optional trip reference.
                    </small>

                </div>

            </div>

        </div>


        {{-- COST BREAKDOWN --}}
        <div class="form-card">

            <div class="card-heading">
                <div>
                    <h2>Cost Breakdown</h2>
                    <p>Enter individual costs. Total Amount is calculated automatically.</p>
                </div>
            </div>


            <div class="form-grid">

                {{-- PARTS COST --}}
                <div class="form-group">

                    <label for="parts_cost">
                        Parts / Base Cost
                    </label>

                    <input
                        type="number"
                        name="parts_cost"
                        id="parts_cost"
                        step="0.01"
                        min="0"
                        value="{{ old('parts_cost', '0.00') }}"
                        placeholder="0.00"
                    >

                </div>


                {{-- LABOUR COST --}}
                <div class="form-group">

                    <label for="labour_cost">
                        Labour Cost
                    </label>

                    <input
                        type="number"
                        name="labour_cost"
                        id="labour_cost"
                        step="0.01"
                        min="0"
                        value="{{ old('labour_cost', '0.00') }}"
                        placeholder="0.00"
                    >

                </div>


                {{-- OTHER COST --}}
                <div class="form-group">

                    <label for="other_cost">
                        Other Cost
                    </label>

                    <input
                        type="number"
                        name="other_cost"
                        id="other_cost"
                        step="0.01"
                        min="0"
                        value="{{ old('other_cost', '0.00') }}"
                        placeholder="0.00"
                    >

                </div>


                {{-- TOTAL --}}
                <div class="form-group">

                    <label for="amount">
                        Total Amount <span>*</span>
                    </label>

                    <input
                        type="number"
                        name="amount"
                        id="amount"
                        step="0.01"
                        min="0.01"
                        value="{{ old('amount', '0.00') }}"
                        class="total-input"
                        readonly
                        required
                    >

                    <small>
                        Parts / Base + Labour + Other
                    </small>

                </div>

            </div>

        </div>


        {{-- PAYMENT --}}
        <div class="form-card">

            <div class="card-heading">
                <div>
                    <h2>Payment & Recovery</h2>
                    <p>Payment status and client recovery information</p>
                </div>
            </div>


            <div class="form-grid">

                <div class="form-group">

                    <label for="payment_status">
                        Payment Status <span>*</span>
                    </label>

                    <select
                        name="payment_status"
                        id="payment_status"
                        required
                    >

                        <option
                            value="Pending"
                            {{ old('payment_status', 'Pending') === 'Pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="Paid"
                            {{ old('payment_status') === 'Paid' ? 'selected' : '' }}
                        >
                            Paid
                        </option>

                        <option
                            value="Cancelled"
                            {{ old('payment_status') === 'Cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="payment_method_reference">
                        Payment Method / Reference
                    </label>

                    <input
                        type="text"
                        name="payment_method_reference"
                        id="payment_method_reference"
                        value="{{ old('payment_method_reference') }}"
                        placeholder="e.g. Bank Transfer / Ref #"
                    >

                    <small>
                        Required when Payment Status is Paid.
                    </small>

                </div>


                <div class="form-group">

                    <label for="reimbursable">
                        Reimbursable <span>*</span>
                    </label>

                    <select
                        name="reimbursable"
                        id="reimbursable"
                        required
                    >

                        <option
                            value="0"
                            {{ old('reimbursable', '0') == '0' ? 'selected' : '' }}
                        >
                            No
                        </option>

                        <option
                            value="1"
                            {{ old('reimbursable') == '1' ? 'selected' : '' }}
                        >
                            Yes
                        </option>

                    </select>

                    <small>
                        Yes means the expense may be recovered from the client.
                    </small>

                </div>


                <div class="form-group">

                    <label for="receipt">
                        Receipt
                    </label>

                    <input
                        type="file"
                        name="receipt"
                        id="receipt"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >

                    <small>
                        Optional JPG, JPEG, PNG or PDF.
                    </small>

                </div>

            </div>

        </div>
        {{-- REMARKS --}}
        <div class="form-card">

            <div class="card-heading">
                <div>
                    <h2>Remarks</h2>
                    <p>Additional information for audit and record keeping</p>
                </div>
            </div>

            <div class="form-group full-width">

                <label for="remarks">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    id="remarks"
                    rows="5"
                    placeholder="Enter any additional remarks..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="form-actions">

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
                Save Expense
            </button>

        </div>

    </form>

</div>


<style>

    .expense-create-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 10px 0 40px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        font-size: 30px;
        color: #0f172a;
    }

    .page-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        color: #334155;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .back-btn:hover {
        background: #f8fafc;
    }

    .error-box {
        margin-bottom: 20px;
        padding: 14px 18px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 14px;
    }

    .error-box ul {
        margin: 8px 0 0 20px;
        padding: 0;
    }

    .error-box li {
        margin-bottom: 4px;
    }

    .form-card {
        margin-bottom: 20px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    }

    .card-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-heading h2 {
        margin: 0 0 4px;
        color: #0f172a;
        font-size: 18px;
    }

    .card-heading p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .form-group {
        min-width: 0;
    }

    .full-width {
        width: 100%;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .form-group label span {
        color: #dc2626;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        color: #0f172a;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: 0.2s ease;
    }

    .form-group input,
    .form-group select {
        height: 38px;
        padding: 0 12px;
    }

    .form-group textarea {
        min-height: 115px;
        padding: 11px 12px;
        resize: vertical;
        line-height: 1.5;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .readonly-input {
        background: #f8fafc !important;
        color: #64748b !important;
        cursor: not-allowed;
    }

    .total-input {
        background: #eff6ff !important;
        border-color: #93c5fd !important;
        color: #1d4ed8 !important;
        font-weight: 800;
    }


    /* MULTI SELECT */

    .multi-select-box {
        position: relative;
        width: 100%;
    }

    .multi-select-trigger {
        width: 100%;
        height: 38px;
        padding: 0 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        text-align: left;
    }

    .multi-select-trigger:hover {
        border-color: #94a3b8;
    }

    .multi-select-arrow {
        color: #64748b;
        font-size: 11px;
    }

    .multi-select-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        z-index: 100;
        max-height: 260px;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #ffffff;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
    }

    .multi-select-dropdown.open {
        display: block;
    }

    .type-option {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 38px;
        padding: 0 10px;
        border-radius: 7px;
        cursor: pointer;
        color: #334155;
        font-size: 14px;
    }

    .type-option:hover {
        background: #f8fafc;
    }

    .type-option input {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: #2563eb;
        cursor: pointer;
    }

    .type-empty {
        padding: 12px 10px;
        color: #94a3b8;
        font-size: 13px;
    }

    .selected-type-count {
        color: #2563eb;
        font-weight: 700;
    }


    .form-group small {
        display: block;
        margin-top: 6px;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.4;
    }


    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .cancel-btn,
    .save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .cancel-btn {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
    }

    .cancel-btn:hover {
        background: #f8fafc;
    }

    .save-btn {
        border: 1px solid #2563eb;
        background: #2563eb;
        color: #ffffff;
    }

    .save-btn:hover {
        background: #1d4ed8;
    }


    @media (max-width: 800px) {

        .expense-create-page {
            padding: 10px 0 30px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 500px) {

        .form-card {
            padding: 18px;
            border-radius: 10px;
        }

        .page-header h1 {
            font-size: 25px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }

    }

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const categorySelect =
        document.getElementById('category');

    const expenseTypeBox =
        document.getElementById('expenseTypeBox');

    const expenseTypeTrigger =
        document.getElementById('expenseTypeTrigger');

    const expenseTypeText =
        document.getElementById('expenseTypeText');

    const expenseTypeDropdown =
        document.getElementById('expenseTypeDropdown');

    const partsCost =
        document.getElementById('parts_cost');

    const labourCost =
        document.getElementById('labour_cost');

    const otherCost =
        document.getElementById('other_cost');

    const totalAmount =
        document.getElementById('amount');

    const paymentStatus =
        document.getElementById('payment_status');

    const paymentReference =
        document.getElementById('payment_method_reference');

    const expenseForm =
        document.getElementById('expenseForm');


    const expenseTypes =
        @json($expenseTypes);

    const oldExpenseTypes =
        @json($selectedExpenseTypes);


    /*
     * Build the Expense Type
     * checkbox list according to Category.
     */
    function loadExpenseTypes() {

        const selectedCategory =
            categorySelect.value;

        expenseTypeDropdown.innerHTML = '';


        if (
            !selectedCategory ||
            !expenseTypes[selectedCategory]
        ) {

            expenseTypeDropdown.innerHTML =
                '<div class="type-empty">' +
                'Select a category first.' +
                '</div>';

            updateExpenseTypeText();

            return;
        }


        expenseTypes[selectedCategory].forEach(
            function (type) {

                const label =
                    document.createElement('label');

                label.className =
                    'type-option';


                const checkbox =
                    document.createElement('input');

                checkbox.type =
                    'checkbox';

                checkbox.name =
                    'expense_type[]';

                checkbox.value =
                    type;


                if (
                    oldExpenseTypes.includes(type)
                ) {
                    checkbox.checked = true;
                }


                checkbox.addEventListener(
                    'change',
                    updateExpenseTypeText
                );


                const text =
                    document.createElement('span');

                text.textContent =
                    type;


                label.appendChild(
                    checkbox
                );

                label.appendChild(
                    text
                );

                expenseTypeDropdown.appendChild(
                    label
                );

            }
        );


        updateExpenseTypeText();

    }


    /*
     * Show selected types in the trigger.
     */
    function updateExpenseTypeText() {

        const checked =
            expenseTypeDropdown.querySelectorAll(
                'input[type="checkbox"]:checked'
            );


        if (checked.length === 0) {

            expenseTypeText.textContent =
                'Select Expense Type';

            expenseTypeText.classList.remove(
                'selected-type-count'
            );

            return;
        }


        const names = [];


        checked.forEach(function (checkbox) {

            names.push(
                checkbox.value
            );

        });


        if (names.length <= 2) {

            expenseTypeText.textContent =
                names.join(', ');

        } else {

            expenseTypeText.textContent =
                names.length +
                ' Expense Types Selected';

        }


        expenseTypeText.classList.add(
            'selected-type-count'
        );

    }


    /*
     * Open / close Expense Type dropdown.
     */
    expenseTypeTrigger.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

            expenseTypeDropdown.classList.toggle(
                'open'
            );

        }
    );


    /*
     * Close dropdown when clicking outside.
     */
    document.addEventListener(
        'click',
        function (event) {

            if (
                !expenseTypeBox.contains(event.target)
            ) {
                expenseTypeDropdown.classList.remove(
                    'open'
                );
            }

        }
    );


    categorySelect.addEventListener(
        'change',
        function () {

            /*
             * Clear previous category's
             * selected types.
             */
            oldExpenseTypes.length = 0;

            loadExpenseTypes();

        }
    );


    loadExpenseTypes();


    /*
     * Calculate total amount.
     */
    function calculateTotal() {

        const parts =
            parseFloat(partsCost.value) || 0;

        const labour =
            parseFloat(labourCost.value) || 0;

        const other =
            parseFloat(otherCost.value) || 0;

        const total =
            parts +
            labour +
            other;

        totalAmount.value =
            total.toFixed(2);

    }


    partsCost.addEventListener(
        'input',
        calculateTotal
    );

    labourCost.addEventListener(
        'input',
        calculateTotal
    );

    otherCost.addEventListener(
        'input',
        calculateTotal
    );


    calculateTotal();


    /*
     * Paid status requires
     * payment method/reference.
     */
    function updatePaymentRequirement() {

        if (
            paymentStatus.value === 'Paid'
        ) {

            paymentReference.required =
                true;

            paymentReference.placeholder =
                'Required: payment method / reference';

        } else {

            paymentReference.required =
                false;

            paymentReference.placeholder =
                'e.g. Bank Transfer / Ref #';

        }

    }


    paymentStatus.addEventListener(
        'change',
        updatePaymentRequirement
    );


    updatePaymentRequirement();


    /*
     * Final form validation.
     */
    expenseForm.addEventListener(
        'submit',
        function (event) {

            calculateTotal();


            const selectedTypes =
                expenseTypeDropdown.querySelectorAll(
                    'input[type="checkbox"]:checked'
                );


            if (!categorySelect.value) {

                event.preventDefault();

                alert(
                    'Please select an Expense Category.'
                );

                categorySelect.focus();

                return;
            }


            if (selectedTypes.length === 0) {

                event.preventDefault();

                alert(
                    'Please select at least one Expense Type.'
                );

                expenseTypeTrigger.focus();

                return;
            }


            const total =
                parseFloat(totalAmount.value) || 0;


            if (total <= 0) {

                event.preventDefault();

                alert(
                    'Total Amount must be greater than 0.'
                );

                partsCost.focus();

                return;
            }


            if (
                paymentStatus.value === 'Paid' &&
                !paymentReference.value.trim()
            ) {

                event.preventDefault();

                alert(
                    'Payment Method / Reference is required when Payment Status is Paid.'
                );

                paymentReference.focus();

                return;
            }

        }
    );

});

</script>

@endsection