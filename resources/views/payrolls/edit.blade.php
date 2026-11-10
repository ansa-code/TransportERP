@extends('layouts.app')

@section('title', 'Edit Payroll')

@section('content')

<div class="payroll-page">

    <div class="page-header">
        <div>
            <h1>Edit Payroll</h1>
            <p>Update monthly payroll for {{ $payroll->driver->driver_name ?? 'Driver' }}</p>
        </div>

        <a href="{{ route('payrolls.index') }}" class="back-btn">
            ← Back to Payroll
        </a>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $existingAdvanceAllocations = $payroll->deductionAllocations
            ->where('deduction_type', 'advance')
            ->keyBy('deductible_record_id');

        $existingFineAllocations = $payroll->deductionAllocations
            ->where('deduction_type', 'fine')
            ->keyBy('deductible_record_id');
    @endphp

    <form action="{{ route('payrolls.update', $payroll->id) }}"
          method="POST"
          id="payrollForm">

        @csrf
        @method('PUT')

        {{-- PAYROLL INFORMATION --}}
        <div class="form-card">

            <div class="card-title">
                <span>📋</span>
                Payroll Information
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="driver_id">
                        Driver <span>*</span>
                    </label>

                    <select name="driver_id" id="driver_id" required>
                        <option value="">Select Driver</option>

                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}"
                                {{ old('driver_id', $payroll->driver_id) == $driver->id ? 'selected' : '' }}>
                                {{ $driver->driver_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('driver_id')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="salary_month">
                        Payroll Month <span>*</span>
                    </label>

                    <input type="month"
                           name="salary_month"
                           id="salary_month"
                           value="{{ old('salary_month', $payroll->salary_month) }}"
                           required>

                    @error('salary_month')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </div>

        {{-- EARNINGS --}}
        <div class="form-card">

            <div class="card-title">
                <span>💰</span>
                Earnings
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="basic_salary">
                        Basic Salary <span>*</span>
                    </label>

                    <input type="number"
                           name="basic_salary"
                           id="basic_salary"
                           value="{{ old('basic_salary', $payroll->basic_salary) }}"
                           min="0"
                           step="0.01"
                           required>

                    @error('basic_salary')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="allowance">Allowance</label>

                    <input type="number"
                           name="allowance"
                           id="allowance"
                           value="{{ old('allowance', $payroll->allowance ?? 0) }}"
                           min="0"
                           step="0.01">

                    @error('allowance')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="overtime">Overtime</label>

                    <input type="number"
                           name="overtime"
                           id="overtime"
                           value="{{ old('overtime', $payroll->overtime ?? 0) }}"
                           min="0"
                           step="0.01">

                    @error('overtime')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </div>

        {{-- DEDUCTIONS --}}
        <div class="form-card">

            <div class="card-title">
                <span>📉</span>
                Deductions
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="visa_deduction">Visa Deduction</label>

                    <input type="number"
                           name="visa_deduction"
                           id="visa_deduction"
                           value="{{ old('visa_deduction', $payroll->visa_deduction ?? 0) }}"
                           min="0"
                           step="0.01">

                    @error('visa_deduction')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fine_deduction">Fine Deduction</label>

                    <input type="number"
                           name="fine_deduction"
                           id="fine_deduction"
                           value="{{ old('fine_deduction', $payroll->fine_deduction ?? 0) }}"
                           min="0"
                           step="0.01"
                           readonly>

                    @error('fine_deduction')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="advance_deduction">Advance Deduction</label>

                    <input type="number"
                           name="advance_deduction"
                           id="advance_deduction"
                           value="{{ old('advance_deduction', $payroll->advance_deduction ?? 0) }}"
                           min="0"
                           step="0.01"
                           readonly>

                    @error('advance_deduction')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="other_deduction">Other Deduction</label>

                    <input type="number"
                           name="other_deduction"
                           id="other_deduction"
                           value="{{ old('other_deduction', $payroll->other_deduction ?? 0) }}"
                           min="0"
                           step="0.01">

                    @error('other_deduction')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="other_deduction_reason">
                        Other Deduction Reason
                    </label>

                    <input type="text"
                           name="other_deduction_reason"
                           id="other_deduction_reason"
                           value="{{ old('other_deduction_reason', $payroll->other_deduction_reason ?? '') }}"
                           placeholder="Enter reason if other deduction is added">

                    @error('other_deduction_reason')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ADVANCE ALLOCATION --}}
        <div class="form-card">

            <div class="card-title">
                <span>💵</span>
                Individual Salary Advance Allocation
            </div>

            <p class="section-description">
                Select individual advances and enter the amount to deduct.
                Existing payroll allocations are shown as selected.
            </p>

            <div id="advanceAllocationMessage" class="allocation-message">
                Loading advances...
            </div>

            <div class="allocation-table-wrapper">
                <table class="allocation-table">

                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Advance No.</th>
                            <th>Date</th>
                            <th>Original Amount</th>
                            <th>Remaining Amount</th>
                            <th>Deduct Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($advances as $advance)

                            @php
                                $existingAllocation = $existingAdvanceAllocations->get($advance->id);

                                $isSelected = $existingAllocation !== null;

                                $availableAmount = (float) $advance->remaining_amount
                                    + (float) ($existingAllocation->amount ?? 0);
                            @endphp

                            <tr class="advance-row"
                                data-driver="{{ $advance->driver_id }}">

                                <td>
                                    <input type="checkbox"
                                           class="allocation-checkbox advance-checkbox"
                                           data-id="{{ $advance->id }}"
                                           {{ $isSelected ? 'checked' : '' }}>
                                </td>

                                <td>{{ $advance->advance_no }}</td>

                                <td>
                                    {{ $advance->advance_date
                                        ? $advance->advance_date->format('d M Y')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ number_format((float) $advance->amount, 2) }}
                                </td>

                                <td>
                                    {{ number_format($availableAmount, 2) }}
                                </td>

                                <td>
                                    <input type="number"
                                           class="allocation-amount advance-amount"
                                           data-id="{{ $advance->id }}"
                                           data-remaining="{{ number_format($availableAmount, 2, '.', '') }}"
                                           min="0.01"
                                           max="{{ number_format($availableAmount, 2, '.', '') }}"
                                           step="0.01"
                                           value="{{ old('advance_allocations.' . $advance->id . '.amount', $existingAllocation->amount ?? '') }}"
                                           placeholder="0.00"
                                           {{ $isSelected ? '' : 'disabled' }}>
                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="allocation-total">
                Selected Advance Deduction:
                <strong id="advanceAllocationTotal">0.00</strong>
            </div>

        </div>

        {{-- FINE ALLOCATION --}}
        <div class="form-card">

            <div class="card-title">
                <span>🚧</span>
                Individual Fine Allocation
            </div>

            <p class="section-description">
                Select individual fines and enter the amount to recover.
                Existing payroll allocations are shown as selected.
            </p>

            <div id="fineAllocationMessage" class="allocation-message">
                Loading fines...
            </div>

            <div class="allocation-table-wrapper">
                <table class="allocation-table">

                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Fine No.</th>
                            <th>Date</th>
                            <th>Fine Amount</th>
                            <th>Remaining Amount</th>
                            <th>Deduct Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($fines as $fine)

                            @php
                                $existingAllocation = $existingFineAllocations->get($fine->id);

                                $alreadyAllocated = (float) $fine
                                    ->deductionAllocations()
                                    ->sum('amount');

                                $availableAmount = max(
                                    0,
                                    (float) $fine->amount
                                    - $alreadyAllocated
                                    + (float) ($existingAllocation->amount ?? 0)
                                );

                                $isSelected = $existingAllocation !== null;
                            @endphp

                            <tr class="fine-row"
                                data-driver="{{ $fine->driver_id }}">

                                <td>
                                    <input type="checkbox"
                                           class="allocation-checkbox fine-checkbox"
                                           data-id="{{ $fine->id }}"
                                           {{ $isSelected ? 'checked' : '' }}>
                                </td>

                                <td>{{ $fine->fine_number }}</td>

                                <td>
                                    {{ $fine->fine_date
                                        ? $fine->fine_date->format('d M Y')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ number_format((float) $fine->amount, 2) }}
                                </td>

                                <td>
                                    {{ number_format($availableAmount, 2) }}
                                </td>

                                <td>
                                    <input type="number"
                                           class="allocation-amount fine-amount"
                                           data-id="{{ $fine->id }}"
                                           data-remaining="{{ number_format($availableAmount, 2, '.', '') }}"
                                           min="0.01"
                                           max="{{ number_format($availableAmount, 2, '.', '') }}"
                                           step="0.01"
                                           value="{{ old('fine_allocations.' . $fine->id . '.amount', $existingAllocation->amount ?? '') }}"
                                           placeholder="0.00"
                                           {{ $isSelected ? '' : 'disabled' }}>
                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="allocation-total">
                Selected Fine Deduction:
                <strong id="fineAllocationTotal">0.00</strong>
            </div>

        </div>

        {{-- SALARY CALCULATION --}}
        <div class="form-card">

            <div class="card-title">
                <span>🧮</span>
                Salary Calculation
            </div>

            <div class="salary-summary">

                <div class="summary-box">
                    <span>Gross Salary</span>
                    <strong id="gross_salary">0.00</strong>
                </div>

                <div class="summary-box">
                    <span>Total Deductions</span>
                    <strong id="total_deductions">0.00</strong>
                </div>

                <div class="summary-box net-box">
                    <span>Net Salary</span>
                    <strong id="net_salary_display">0.00</strong>
                </div>

            </div>

            <input type="hidden"
                   name="net_salary"
                   id="net_salary"
                   value="{{ old('net_salary', $payroll->net_salary ?? 0) }}">
        </div>

        {{-- PAYMENT --}}
        <div class="form-card">

            <div class="card-title">
                <span>💳</span>
                Payment
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="payment_status">
                        Payment Status <span>*</span>
                    </label>

                    <select name="payment_status"
                            id="payment_status"
                            required>

                        <option value="Pending"
                            {{ old('payment_status', $payroll->payment_status ?? 'Pending') === 'Pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="Paid"
                            {{ old('payment_status', $payroll->payment_status ?? '') === 'Paid' ? 'selected' : '' }}>
                            Paid
                        </option>

                        <option value="Cancelled"
                            {{ old('payment_status', $payroll->payment_status ?? '') === 'Cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>
                    </select>

                    @error('payment_status')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group payment-field">
                    <label for="paid_date">Paid Date</label>

                    <input type="date"
                           name="paid_date"
                           id="paid_date"
                           value="{{ old('paid_date', $payroll->paid_date ?? '') }}">

                    @error('paid_date')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group payment-field">
                    <label for="paid_reference">Paid Reference</label>

                    <input type="text"
                           name="paid_reference"
                           id="paid_reference"
                           value="{{ old('paid_reference', $payroll->paid_reference ?? '') }}"
                           placeholder="Payment reference">

                    @error('paid_reference')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="payment-notice" id="paymentNotice">
                Existing allocations are restored and recalculated when payroll is updated.
            </div>

        </div>

        {{-- NOTES --}}
        <div class="form-card">

            <div class="card-title">
                <span>📝</span>
                Notes
            </div>

            <div class="form-group">
                <label for="notes">Remarks</label>

                <textarea name="notes"
                          id="notes"
                          rows="4"
                          placeholder="Enter additional remarks...">{{ old('notes', $payroll->notes) }}</textarea>

                @error('notes')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

        </div>

        {{-- FORM ACTIONS --}}
        <div class="form-actions">

            <a href="{{ route('payrolls.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">
                💾 Update Payroll
            </button>

        </div>

    </form>

</div>

<style>
    .payroll-page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0;
        color: #101d42;
        font-size: 25px;
        font-weight: 700;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #667085;
        font-size: 13px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border-radius: 8px;
        background: #eef3ff;
        color: #173b8f;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .alert-error {
        background: #fff1f1;
        border: 1px solid #f2caca;
        color: #b42318;
        border-radius: 9px;
        padding: 13px 16px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .alert-error strong {
        display: block;
        margin-bottom: 6px;
    }

    .alert-error ul {
        margin: 0 0 0 18px;
        padding: 0;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e9f2;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 18px;
        box-shadow: 0 4px 14px rgba(16, 29, 66, 0.05);
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #101d42;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 17px;
    }

    .form-group {
        min-width: 0;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        color: #344054;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .form-group label span {
        color: #dc3545;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d8deea;
        border-radius: 8px;
        background: #fff;
        color: #172033;
        font-size: 13px;
        outline: none;
        transition: .2s;
    }

    .form-group input,
    .form-group select {
        height: 40px;
        padding: 0 12px;
    }

    .form-group textarea {
        min-height: 95px;
        padding: 10px 12px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #3159b7;
        box-shadow: 0 0 0 3px rgba(49, 89, 183, .08);
    }

    .error-text {
        display: block;
        margin-top: 5px;
        color: #dc3545;
        font-size: 12px;
    }

    .salary-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
    }

    .summary-box {
        min-width: 0;
        background: #f6f8fc;
        border: 1px solid #e2e7f0;
        border-radius: 11px;
        padding: 17px;
    }

    .summary-box span {
        display: block;
        color: #667085;
        font-size: 12px;
        margin-bottom: 7px;
    }

    .summary-box strong {
        display: block;
        color: #101d42;
        font-size: 21px;
        line-height: 1.2;
    }

    .net-box {
        background: #eef4ff;
        border-color: #cbdcff;
    }

    .net-box strong {
        color: #1f4da8;
    }

    .section-description {
        color: #667085;
        font-size: 13px;
        margin: -10px 0 16px;
        line-height: 1.6;
    }

    .allocation-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .allocation-table {
        width: 100%;
        min-width: 750px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .allocation-table th,
    .allocation-table td {
        padding: 12px 10px;
        text-align: left;
        border-bottom: 1px solid #e8ecf3;
        white-space: nowrap;
    }

    .allocation-table th {
        background: #f6f8fc;
        color: #344054;
        font-weight: 700;
    }

    .allocation-table td {
        color: #344054;
    }

    .allocation-table input[type="checkbox"] {
        width: 17px;
        height: 17px;
        cursor: pointer;
    }

    .allocation-table input[type="number"] {
        width: 115px;
        height: 35px;
        padding: 0 8px;
        border: 1px solid #d8deea;
        border-radius: 7px;
    }

    .allocation-table input:disabled {
        background: #f1f3f7;
        cursor: not-allowed;
    }

    .allocation-message {
        padding: 12px;
        margin-bottom: 12px;
        border-radius: 8px;
        background: #f6f8fc;
        color: #667085;
        font-size: 13px;
    }

    .allocation-total {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
        padding-top: 13px;
        border-top: 1px solid #e8ecf3;
        color: #344054;
        font-size: 13px;
    }

    .allocation-total strong {
        color: #173b8f;
        font-size: 15px;
    }

    .payment-notice {
        margin-top: 16px;
        padding: 12px;
        border-radius: 8px;
        background: #eef4ff;
        color: #3159a5;
        font-size: 13px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 4px;
        margin-bottom: 20px;
    }

    .btn {
        min-width: 120px;
        height: 40px;
        padding: 0 18px;
        border: 0;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        box-sizing: border-box;
    }

    .btn-primary {
        background: #173b8f;
        color: #fff;
    }

    .btn-secondary {
        background: #eef1f6;
        color: #344054;
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .salary-summary {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .back-btn {
            width: 100%;
        }

        .form-card {
            padding: 16px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const driverSelect = document.getElementById('driver_id');
    const paymentStatus = document.getElementById('payment_status');

    const advanceDeduction = document.getElementById('advance_deduction');
    const fineDeduction = document.getElementById('fine_deduction');

    const oldAdvanceAllocations = @json(old('advance_allocations', []));
    const oldFineAllocations = @json(old('fine_allocations', []));

    /*
    |--------------------------------------------------------------------------
    | SALARY CALCULATION
    |--------------------------------------------------------------------------
    */

    const salaryFields = [
        'basic_salary',
        'allowance',
        'overtime',
        'visa_deduction',
        'fine_deduction',
        'advance_deduction',
        'other_deduction'
    ];

    function getAmount(id) {
        return parseFloat(document.getElementById(id)?.value) || 0;
    }

    function calculateSalary() {

        const gross =
            getAmount('basic_salary') +
            getAmount('allowance') +
            getAmount('overtime');

        const deductions =
            getAmount('visa_deduction') +
            getAmount('fine_deduction') +
            getAmount('advance_deduction') +
            getAmount('other_deduction');

        const net = gross - deductions;

        document.getElementById('gross_salary').textContent =
            gross.toFixed(2);

        document.getElementById('total_deductions').textContent =
            deductions.toFixed(2);

        document.getElementById('net_salary_display').textContent =
            net.toFixed(2);

        document.getElementById('net_salary').value =
            net.toFixed(2);
    }

    salaryFields.forEach(function (id) {
        document.getElementById(id)?.addEventListener(
            'input',
            calculateSalary
        );
    });

    /*
    |--------------------------------------------------------------------------
    | ALLOCATION INPUT NAMES
    |--------------------------------------------------------------------------
    */

    function configureAllocation(type) {

        const isAdvance = type === 'advance';

        const checkboxClass = isAdvance
            ? '.advance-checkbox'
            : '.fine-checkbox';

        const amountClass = isAdvance
            ? '.advance-amount'
            : '.fine-amount';

        const prefix = isAdvance
            ? 'advance_allocations'
            : 'fine_allocations';

        document.querySelectorAll(checkboxClass).forEach(function (checkbox) {

            const row = checkbox.closest('tr');
            const amountInput = row.querySelector(amountClass);
            const id = checkbox.dataset.id;

            function updateInput() {

                const selected =
                    checkbox.checked &&
                    paymentStatus.value === 'Paid';

                amountInput.disabled = !selected;

                if (selected) {
                    checkbox.name = prefix + '[' + id + '][id]';
                    checkbox.value = id;

                    amountInput.name =
                        prefix + '[' + id + '][amount]';
                } else {
                    checkbox.removeAttribute('name');
                    amountInput.removeAttribute('name');
                }
            }

            checkbox.addEventListener('change', function () {
                updateInput();
                updateAllocationTotals();
            });

            amountInput.addEventListener(
                'input',
                updateAllocationTotals
            );

            updateInput();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER FILTER
    |--------------------------------------------------------------------------
    */

    function filterDriverRecords() {

        const selectedDriver = driverSelect.value;

        document.querySelectorAll('.advance-row').forEach(function (row) {

            const matches =
                row.dataset.driver === selectedDriver;

            row.style.display = matches ? '' : 'none';
        });

        document.querySelectorAll('.fine-row').forEach(function (row) {

            const matches =
                row.dataset.driver === selectedDriver;

            row.style.display = matches ? '' : 'none';
        });

        document.getElementById('advanceAllocationMessage').textContent =
            selectedDriver
                ? 'Select the advances you want to deduct.'
                : 'Please select a driver first.';

        document.getElementById('fineAllocationMessage').textContent =
            selectedDriver
                ? 'Select the fines you want to deduct.'
                : 'Please select a driver first.';

        updateAllocationTotals();
    }

    driverSelect.addEventListener('change', filterDriverRecords);

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    function togglePaymentFields() {

        const isPaid = paymentStatus.value === 'Paid';

        document.querySelectorAll('.payment-field').forEach(function (field) {
            field.style.display = isPaid ? '' : 'none';
        });

        document.getElementById('paid_date').required = isPaid;
        document.getElementById('paid_reference').required = isPaid;

        document.querySelectorAll('.allocation-checkbox').forEach(function (checkbox) {

            const row = checkbox.closest('tr');

            const amountInput = row.querySelector('.allocation-amount');

            if (!isPaid) {
                checkbox.disabled = true;
                amountInput.disabled = true;
                checkbox.removeAttribute('name');
                amountInput.removeAttribute('name');
            } else {
                checkbox.disabled = false;
            }
        });

        if (isPaid) {
            configureAllocation('advance');
            configureAllocation('fine');
        }

        document.getElementById('paymentNotice').textContent =
            isPaid
                ? 'Selected allocations will be applied when payroll is updated.'
                : 'Advance and fine allocations are applied only when payroll is Paid.';

        updateAllocationTotals();
    }

    paymentStatus.addEventListener('change', togglePaymentFields);

    /*
    |--------------------------------------------------------------------------
    | ALLOCATION TOTALS
    |--------------------------------------------------------------------------
    */

    function updateAllocationTotals() {

        let advanceTotal = 0;
        let fineTotal = 0;

        document.querySelectorAll('.advance-checkbox:checked').forEach(function (checkbox) {

            const input = checkbox.closest('tr').querySelector('.advance-amount');

            if (!input.disabled) {
                advanceTotal += parseFloat(input.value) || 0;
            }
        });

        document.querySelectorAll('.fine-checkbox:checked').forEach(function (checkbox) {

            const input = checkbox.closest('tr').querySelector('.fine-amount');

            if (!input.disabled) {
                fineTotal += parseFloat(input.value) || 0;
            }
        });

        advanceDeduction.value = advanceTotal.toFixed(2);
        fineDeduction.value = fineTotal.toFixed(2);

        document.getElementById('advanceAllocationTotal').textContent =
            advanceTotal.toFixed(2);

        document.getElementById('fineAllocationTotal').textContent =
            fineTotal.toFixed(2);

        calculateSalary();
    }

    /*
    |--------------------------------------------------------------------------
    | OTHER DEDUCTION REASON
    |--------------------------------------------------------------------------
    */

    function toggleOtherReason() {

        const otherAmount = getAmount('other_deduction');

        document.getElementById('other_deduction_reason').required =
            otherAmount > 0;
    }

    document.getElementById('other_deduction').addEventListener(
        'input',
        toggleOtherReason
  );

  /*

|------------------------------------------------

  | INITIALIZE

|------------------------------------------------

  */

  configureAllocation('advance');
  configureAllocation('fine');

  filterDriverRecords();
  togglePaymentFields();
  toggleOtherReason();
  calculateSalary();

});

</script>

@endsection