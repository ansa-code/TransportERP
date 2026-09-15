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

    .update-btn {
        background: #0d6efd;
        color: white;
        border: none;
        padding: 11px 22px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .update-btn:hover {
        background: #0b5ed7;
    }

    .cancel-btn {
        display: inline-block;
        margin-left: 8px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        padding: 11px 22px;
        border-radius: 6px;
        font-weight: bold;
    }

    .cancel-btn:hover {
        background: #5c636a;
    }

    .error-box {
        background: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    @media(max-width: 700px) {
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
            <h1>Edit Client</h1>
            <p>Update client profile, contract and billing information</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <strong>Please fix the following errors:</strong>

                <ul style="margin:8px 0 0 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">

                {{-- 1. Client Code --}}
                <div class="form-group">
                    <label>
                        Client Code <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="client_code"
                        class="form-control"
                        value="{{ old('client_code', $client->client_code) }}"
                        required
                    >
                </div>

                {{-- 2. Company Name --}}
                <div class="form-group">
                    <label>
                        Company Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        value="{{ old('company_name', $client->company_name) }}"
                        required
                    >
                </div>

                {{-- 3. Client Name --}}
                <div class="form-group">
                    <label>
                        Client Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="client_name"
                        class="form-control"
                        value="{{ old('client_name', $client->client_name) }}"
                        required
                    >
                </div>

                {{-- 4. Contact Person --}}
                <div class="form-group">
                    <label>Contact Person</label>

                    <input
                        type="text"
                        name="contact_person"
                        class="form-control"
                        value="{{ old('contact_person', $client->contact_person) }}"
                    >
                </div>

                {{-- 5. Mobile --}}
                <div class="form-group">
                    <label>Mobile</label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $client->phone) }}"
                    >
                </div>

                {{-- 6. Email --}}
                <div class="form-group">
                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $client->email) }}"
                    >
                </div>

                {{-- 7. Trade Licence --}}
                <div class="form-group">
                    <label>Trade Licence</label>

                    <input
                        type="text"
                        name="trade_licence"
                        class="form-control"
                        value="{{ old('trade_licence', $client->trade_licence) }}"
                    >
                </div>

                {{-- 8. Trade Licence Expiry --}}
                <div class="form-group">
                    <label>Trade Licence Expiry</label>

                    <input
                        type="date"
                        name="trade_licence_expiry"
                        class="form-control"
                        value="{{ old('trade_licence_expiry', optional($client->trade_licence_expiry)->format('Y-m-d')) }}"
                    >
                </div>

                {{-- 9. TRN --}}
                <div class="form-group">
                    <label>TRN</label>

                    <input
                        type="text"
                        name="trn"
                        class="form-control"
                        value="{{ old('trn', $client->trn) }}"
                    >
                </div>

                {{-- 10. Contract Start --}}
                <div class="form-group">
                    <label>Contract Start</label>

                    <input
                        type="date"
                        name="contract_start"
                        class="form-control"
                        value="{{ old('contract_start', optional($client->contract_start)->format('Y-m-d')) }}"
                    >
                </div>

                {{-- 11. Contract End --}}
                <div class="form-group">
                    <label>Contract End</label>

                    <input
                        type="date"
                        name="contract_end"
                        class="form-control"
                        value="{{ old('contract_end', optional($client->contract_end)->format('Y-m-d')) }}"
                    >
                </div>
                {{-- 12. Billing Type --}}
                <div class="form-group">
                    <label>
                        Billing Type <span class="required">*</span>
                    </label>

                    <select name="billing_type" class="form-control" required>
                        <option value="">Select Billing Type</option>

                        @foreach ([
                            'Per Trip',
                            'Weekly',
                            'Monthly',
                            'Fixed Rent',
                            'Usage',
                            'Mixed',
                            'Custom'
                        ] as $billingType)

                            <option
                                value="{{ $billingType }}"
                                {{ old('billing_type', $client->billing_type) == $billingType ? 'selected' : '' }}
                            >
                                {{ $billingType }}
                            </option>

                        @endforeach
                    </select>
                </div>

                {{-- 13. VAT Applicable --}}
                <div class="form-group">
                    <label>
                        VAT Applicable <span class="required">*</span>
                    </label>

                    <select name="vat_applicable" class="form-control" required>
                        <option value="1"
                            {{ old('vat_applicable', $client->vat_applicable) == 1 ? 'selected' : '' }}>
                            Yes
                        </option>

                        <option value="0"
                            {{ old('vat_applicable', $client->vat_applicable) == 0 ? 'selected' : '' }}>
                            No
                        </option>
                    </select>
                </div>

                {{-- 14. Payment Terms --}}
                <div class="form-group">
                    <label>Payment Terms</label>

                    <input
                        type="text"
                        name="payment_terms"
                        class="form-control"
                        value="{{ old('payment_terms', $client->payment_terms) }}"
                        placeholder="e.g. 30 Days"
                    >
                </div>

                {{-- 15. Credit Days --}}
                <div class="form-group">
                    <label>Credit Days</label>

                    <input
                        type="number"
                        name="credit_days"
                        class="form-control"
                        min="0"
                        value="{{ old('credit_days', $client->credit_days) }}"
                    >
                </div>

                {{-- 16. Credit Limit --}}
                <div class="form-group">
                    <label>Credit Limit</label>

                    <input
                        type="number"
                        name="credit_limit"
                        class="form-control"
                        min="0"
                        step="0.01"
                        value="{{ old('credit_limit', $client->credit_limit) }}"
                        placeholder="AED"
                    >
                </div>

                {{-- 17. Fuel Reimbursement Rule --}}
                <div class="form-group">
                    <label>Fuel Reimbursement Rule</label>

                    <select name="fuel_reimbursement_rule" class="form-control">
                        <option value="">Select Rule</option>

                        @foreach ([
                            'None',
                            'Actual Cost',
                            'Markup',
                            'Fixed',
                            'Custom'
                        ] as $fuelRule)

                            <option
                                value="{{ $fuelRule }}"
                                {{ old('fuel_reimbursement_rule', $client->fuel_reimbursement_rule) == $fuelRule ? 'selected' : '' }}
                            >
                                {{ $fuelRule }}
                            </option>

                        @endforeach
                    </select>
                </div>

                {{-- 18. Status --}}
                <div class="form-group">
                    <label>
                        Status <span class="required">*</span>
                    </label>

                    <select name="status" class="form-control" required>
                        @foreach ([
                            'Active',
                            'Inactive',
                            'Archived'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                {{ old('status', $client->status) == $status ? 'selected' : '' }}
                            >
                                {{ $status }}
                            </option>

                        @endforeach
                    </select>
                </div>

                {{-- 19. Address --}}
                <div class="form-group full">
                    <label>Address</label>

                    <textarea
                        name="address"
                        class="form-control"
                        placeholder="Enter client address"
                    >{{ old('address', $client->address) }}</textarea>
                </div>

                {{-- 20. City --}}
                <div class="form-group">
                    <label>City</label>

                    <input
                        type="text"
                        name="city"
                        class="form-control"
                        value="{{ old('city', $client->city) }}"
                    >
                </div>

                {{-- 21. Country --}}
                <div class="form-group">
                    <label>Country</label>

                    <input
                        type="text"
                        name="country"
                        class="form-control"
                        value="{{ old('country', $client->country) }}"
                    >
                </div>

                {{-- 22. Notes --}}
                <div class="form-group full">
                    <label>Notes</label>

                    <textarea
                        name="notes"
                        class="form-control"
                        placeholder="Additional notes"
                    >{{ old('notes', $client->notes) }}</textarea>
                </div>

            </div>

            <div class="form-actions">

                <button type="submit" class="update-btn">
                    Update Client
                </button>

                <a href="{{ route('clients.index') }}" class="cancel-btn">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection