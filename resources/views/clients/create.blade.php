@extends('layouts.app')

@section('content')

<style>
    .client-form-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        border: 1px solid #e9ecef;
    }

    .form-header {
        margin-bottom: 25px;
    }

    .form-header h1 {
        margin: 0;
        color: #1d3557;
    }

    .form-header p {
        color: #6c757d;
        margin-top: 6px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 7px;
        color: #343a40;
        font-size: 14px;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.10);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-actions {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .save-btn {
        background: #198754;
        color: white;
        border: none;
        padding: 11px 22px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .save-btn:hover {
        background: #157347;
    }

    .error-box {
        background: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .error-box ul {
        margin: 8px 0 0 20px;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }
    }
</style>

<div class="client-form-page">

    <div class="form-card">

        <div class="form-header">
            <h1>Add Client</h1>
            <p>Create a new client profile</p>
        </div>

        @if ($errors->any())

            <div class="error-box">

                <strong>Please fix the following:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif

        <form action="{{ route('clients.store') }}" method="POST">

            @csrf

            <div class="form-grid">

                {{-- Client Code --}}

                <div class="form-group">

                    <label>
                        Client Code <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="client_code"
                        value="{{ old('client_code') }}"
                        class="form-control"
                        placeholder="e.g. CLI-001"
                        required
                    >

                </div>


                {{-- Company Name --}}

                <div class="form-group">

                    <label>
                        Company Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        value="{{ old('company_name') }}"
                        class="form-control"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- Client Name --}}

                <div class="form-group">

                    <label>
                        Client Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="client_name"
                        value="{{ old('client_name') }}"
                        class="form-control"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- Contact Person --}}

                <div class="form-group">

                    <label>Contact Person</label>

                    <input
                        type="text"
                        name="contact_person"
                        value="{{ old('contact_person') }}"
                        class="form-control"
                        maxlength="255"
                        placeholder="Primary contact person"
                    >

                </div>


                {{-- Mobile --}}

                <div class="form-group">

                    <label>Mobile</label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-control"
                        maxlength="50"
                        placeholder="+971..."
                    >

                </div>


                {{-- Email --}}

                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control"
                        maxlength="255"
                        placeholder="client@example.com"
                    >

                </div>


                {{-- Trade Licence --}}

                <div class="form-group">

                    <label>Trade Licence</label>

                    <input
                        type="text"
                        name="trade_licence"
                        value="{{ old('trade_licence') }}"
                        class="form-control"
                        maxlength="255"
                    >

                </div>


                {{-- Trade Licence Expiry --}}

                <div class="form-group">

                    <label>Trade Licence Expiry</label>

                    <input
                        type="date"
                        name="trade_licence_expiry"
                        value="{{ old('trade_licence_expiry') }}"
                        class="form-control"
                    >

                </div>


                {{-- TRN --}}

                <div class="form-group">

                    <label>TRN</label>

                    <input
                        type="text"
                        name="trn"
                        value="{{ old('trn') }}"
                        class="form-control"
                        maxlength="255"
                    >

                </div>


                {{-- Contract Start --}}

                <div class="form-group">

                    <label>Contract Start</label>

                    <input
                        type="date"
                        name="contract_start"
                        value="{{ old('contract_start') }}"
                        class="form-control"
                    >

                </div>


                {{-- Contract End --}}

                <div class="form-group">

                    <label>Contract End</label>

                    <input
                        type="date"
                        name="contract_end"
                        value="{{ old('contract_end') }}"
                        class="form-control"
                    >

                </div>


                {{-- Billing Type --}}

                <div class="form-group">

                    <label>
                        Billing Type <span class="required">*</span>
                    </label>

                    <select
                        name="billing_type"
                        class="form-control"
                        required
                    >

                        <option value="">Select Billing Type</option>

                        <option value="Per Trip"
                            {{ old('billing_type') == 'Per Trip' ? 'selected' : '' }}>
                            Per Trip
                        </option>

                        <option value="Weekly"
                            {{ old('billing_type') == 'Weekly' ? 'selected' : '' }}>
                            Weekly
                        </option>

                        <option value="Monthly"
                            {{ old('billing_type') == 'Monthly' ? 'selected' : '' }}>
                            Monthly
                        </option>

                        <option value="Fixed Rent"
                            {{ old('billing_type') == 'Fixed Rent' ? 'selected' : '' }}>
                            Fixed Rent
                        </option>

                        <option value="Usage"
                            {{ old('billing_type') == 'Usage' ? 'selected' : '' }}>
                            Usage
                        </option>

                        <option value="Mixed"
                            {{ old('billing_type') == 'Mixed' ? 'selected' : '' }}>
                            Mixed
                        </option>

                        <option value="Custom"
                            {{ old('billing_type') == 'Custom' ? 'selected' : '' }}>
                            Custom
                        </option>

                    </select>

                </div>


                {{-- VAT Applicable --}}

                <div class="form-group">

                    <label>
                        VAT Applicable <span class="required">*</span>
                    </label>

                    <select
                        name="vat_applicable"
                        class="form-control"
                        required
                    >

                        <option value="1"
                            {{ old('vat_applicable', '1') == '1' ? 'selected' : '' }}>
                            Yes
                        </option>

                        <option value="0"
                            {{ old('vat_applicable') == '0' ? 'selected' : '' }}>
                            No
                        </option>

                    </select>

                </div>


                {{-- Payment Terms --}}

                <div class="form-group">

                    <label>Payment Terms</label>

                    <input
                        type="text"
                        name="payment_terms"
                        value="{{ old('payment_terms') }}"
                        class="form-control"
                        maxlength="255"
                        placeholder="e.g. 30 Days"
                    >

                </div>


                {{-- Credit Days --}}

                <div class="form-group">

                    <label>Credit Days</label>

                    <input
                        type="number"
                        name="credit_days"
                        value="{{ old('credit_days', 0) }}"
                        class="form-control"
                        min="0"
                    >

                </div>


                {{-- Credit Limit --}}

                <div class="form-group">

                    <label>Credit Limit</label>

                    <input
                        type="number"
                        name="credit_limit"
                        value="{{ old('credit_limit', 0) }}"
                        class="form-control"
                        min="0"
                        step="0.01"
                    >

                </div>
                {{-- Fuel Reimbursement Rule --}}

                <div class="form-group">

                    <label>Fuel Reimbursement Rule</label>

                    <select
                        name="fuel_reimbursement_rule"
                        class="form-control"
                    >

                        <option value="None"
                            {{ old('fuel_reimbursement_rule', 'None') == 'None' ? 'selected' : '' }}>
                            None
                        </option>

                        <option value="Actual Cost"
                            {{ old('fuel_reimbursement_rule') == 'Actual Cost' ? 'selected' : '' }}>
                            Actual Cost
                        </option>

                        <option value="Markup"
                            {{ old('fuel_reimbursement_rule') == 'Markup' ? 'selected' : '' }}>
                            Markup
                        </option>

                        <option value="Fixed"
                            {{ old('fuel_reimbursement_rule') == 'Fixed' ? 'selected' : '' }}>
                            Fixed
                        </option>

                        <option value="Custom"
                            {{ old('fuel_reimbursement_rule') == 'Custom' ? 'selected' : '' }}>
                            Custom
                        </option>

                    </select>

                </div>


                {{-- Status --}}

                <div class="form-group">

                    <label>
                        Status <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-control"
                        required
                    >

                        <option value="Active"
                            {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="Inactive"
                            {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="Archived"
                            {{ old('status') == 'Archived' ? 'selected' : '' }}>
                            Archived
                        </option>

                    </select>

                </div>


                {{-- Address --}}

                <div class="form-group full">

                    <label>Address</label>

                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        class="form-control"
                        placeholder="Enter complete address"
                    >

                </div>


                {{-- City --}}

                <div class="form-group">

                    <label>City</label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city') }}"
                        class="form-control"
                        maxlength="255"
                    >

                </div>


                {{-- Country --}}

                <div class="form-group">

                    <label>Country</label>

                    <input
                        type="text"
                        name="country"
                        value="{{ old('country') }}"
                        class="form-control"
                        maxlength="255"
                        placeholder="e.g. UAE"
                    >

                </div>


                {{-- Notes --}}

                <div class="form-group full">

                    <label>Notes</label>

                    <textarea
                        name="notes"
                        class="form-control"
                        placeholder="Additional client notes..."
                    >{{ old('notes') }}</textarea>

                </div>

            </div>


            {{-- Form Actions --}}

            <div class="form-actions">

                <button
                    type="submit"
                    class="save-btn"
                >
                    Save Client
                </button>

            </div>

        </form>

    </div>

</div>

@endsection