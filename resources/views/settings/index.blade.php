@extends('layouts.app')

@section('title', 'Settings')

@section('content')

<style>
    .settings-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        color: #101d42;
        font-size: 28px;
        font-weight: 700;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .settings-hero {
        background: linear-gradient(135deg, #101d42, #193b8f);
        border-radius: 16px;
        padding: 26px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(16, 29, 66, .15);
    }

    .settings-hero h2 {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 700;
    }

    .settings-hero p {
        margin: 0;
        color: rgba(255, 255, 255, .78);
        font-size: 14px;
        line-height: 1.6;
    }

    .settings-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .05);
    }

    .settings-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .settings-card-header h3 {
        margin: 0;
        color: #101d42;
        font-size: 17px;
        font-weight: 700;
    }

    .settings-card-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .settings-card-body {
        padding: 22px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 38px;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #fff;
        color: #1e293b;
        font-size: 14px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        box-sizing: border-box;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .form-help {
        display: block;
        margin-top: 6px;
        color: #64748b;
        font-size: 12px;
    }

    .input-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .vat-status {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
    }

    .vat-status input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2563eb;
    }

    .vat-status label {
        margin: 0;
        cursor: pointer;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
    }

    .save-section {
        display: flex;
        justify-content: flex-end;
        padding-top: 4px;
    }

    .btn-save {
        border: none;
        border-radius: 7px;
        padding: 10px 20px;
        background: #193b8f;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .btn-save:hover {
        background: #102d72;
        transform: translateY(-1px);
    }

    

   

    @media (max-width: 768px) {
        .settings-page {
            width: 100%;
        }

        .page-header {
            flex-direction: column;
        }

        .input-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .settings-hero {
            padding: 20px;
        }

        .settings-card-body {
            padding: 18px;
        }

        .save-section {
            justify-content: stretch;
        }

        .btn-save {
            width: 100%;
        }
    }
</style>

<div class="settings-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle">
                Manage core system and financial configuration.
            </p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="settings-hero">
        <h2>System Configuration</h2>
        <p>
            Configure the base currency and VAT settings used across the
            Transport ERP system.
        </p>
    </div>

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        {{-- FINANCIAL SETTINGS --}}
        <div class="settings-card">

            <div class="settings-card-header">
                <h3>Financial Settings</h3>
                <p>
                    These settings control the default financial configuration
                    of the ERP.
                </p>
            </div>

            <div class="settings-card-body">

                <div class="input-row">

                    {{-- BASE CURRENCY --}}
                    <div class="form-group">
                        <label for="base_currency" class="form-label">
                            Base Currency
                        </label>

                        <select
                            name="base_currency"
                            id="base_currency"
                            class="form-select"
                            required
                        >
                            <option
                                value="AED"
                                {{ old('base_currency', $settings['base_currency']) === 'AED' ? 'selected' : '' }}
                            >
                                AED — UAE Dirham
                            </option>

                            <option
                                value="PKR"
                                {{ old('base_currency', $settings['base_currency']) === 'PKR' ? 'selected' : '' }}
                            >
                                PKR — Pakistani Rupee
                            </option>

                            <option
                                value="USD"
                                {{ old('base_currency', $settings['base_currency']) === 'USD' ? 'selected' : '' }}
                            >
                                USD — US Dollar
                            </option>
                        </select>

                        <span class="form-help">
                            Default currency for monetary values throughout the ERP.
                        </span>
                    </div>

                    {{-- VAT RATE --}}
                    <div class="form-group">
                        <label for="vat_rate" class="form-label">
                            VAT Rate (%)
                        </label>

                        <input
                            type="number"
                            name="vat_rate"
                            id="vat_rate"
                            class="form-control"
                            value="{{ old('vat_rate', $settings['vat_rate']) }}"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                        >

                        <span class="form-help">
                            Default VAT percentage applied to applicable financial records.
                        </span>
                    </div>

                </div>

                {{-- VAT ENABLED --}}
                <div class="form-group">
                    <label class="form-label">
                        VAT Configuration
                    </label>

                    <div class="vat-status">
                        <input
                            type="hidden"
                            name="vat_enabled"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="vat_enabled"
                            id="vat_enabled"
                            value="1"
                            {{ old('vat_enabled', $settings['vat_enabled']) == '1' ? 'checked' : '' }}
                        >

                        <label for="vat_enabled">
                            Enable VAT in the system
                        </label>
                    </div>

                    <span class="form-help">
                        When enabled, the configured VAT rate is used as the default.
                    </span>
                </div>

            </div>
        </div>

        {{-- SAVE --}}
        <div class="save-section">
            <button type="submit" class="btn-save">
                Save Settings
            </button>
        </div>

    </form>

</div>

@endsection