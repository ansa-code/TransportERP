@extends('layouts.app')

@section('content')

<style>
    .client-page {
        width: 100%;
    }

    /* =========================
       HEADER
    ========================== */

    .client-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        gap: 20px;
    }

    .client-title h1 {
        margin: 0;
        color: #0f172a;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .client-title p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .add-client-btn {
        height: 40px;
        border: 0;
        border-radius: 12px;
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        padding: 0 16px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 18px rgba(37, 99, 235, .22);
        font-size: 13px;
        white-space: nowrap;
    }

    .add-client-btn:hover {
        background: #1d4ed8;
    }


    /* =========================
       FILTERS
    ========================== */

    .client-filters {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .client-search {
        height: 40px;
        border: 1px solid #dbe3ef;
        background: #f8fafc;
        border-radius: 12px;
        padding: 0 14px;
        color: #334155;
        font-size: 13px;
        min-width: 260px;
        flex: 1;
    }

    .client-search:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .client-select {
        height: 40px;
        border: 1px solid #dbe3ef;
        background: #fff;
        border-radius: 12px;
        padding: 0 12px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        min-width: 142px;
    }

    .client-select:focus {
        outline: none;
        border-color: #2563eb;
    }


    /* =========================
       KPI CARDS
    ========================== */

    .client-kpis {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 18px;
    }

    .client-kpi {
        background: #fff;
        border: 1px solid #e4eaf3;
        border-radius: 22px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
        min-height: 126px;
        padding: 19px;
    }

    .client-kpi-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .client-kpi-value {
        color: #0f172a;
        font-size: 29px;
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-top: 10px;
    }

    .client-kpi-note {
        color: #10b981;
        font-size: 12px;
        font-weight: 700;
        margin-top: 12px;
    }

    .client-kpi-note.warning {
        color: #f59e0b;
    }


    /* =========================
       DIRECTORY CARD
    ========================== */

    .directory-card {
        background: #fff;
        border: 1px solid #e4eaf3;
        border-radius: 22px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
        padding: 20px;
    }

    .directory-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .directory-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }

    .arabic-badge {
        display: inline-flex;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 800;
        background: #eff6ff;
        color: #1d4ed8;
        direction: rtl;
    }


    /* =========================
       TABLE
    ========================== */

    .directory-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .directory-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        table-layout: fixed;
    }

    .directory-table th {
        text-align: left;
        padding: 12px;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .06em;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .directory-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #eef2f7;
        font-weight: 600;
        color: #334155;
        vertical-align: middle;
        word-break: break-word;
    }

    .directory-table tr:last-child td {
        border-bottom: none;
    }

    .directory-table tr:hover td {
        background: #f8fafc;
    }

    .directory-table th:nth-child(1),
    .directory-table td:nth-child(1) {
        width: 21%;
    }

    .directory-table th:nth-child(2),
    .directory-table td:nth-child(2) {
        width: 13%;
    }

    .directory-table th:nth-child(3),
    .directory-table td:nth-child(3) {
        width: 14%;
    }

    .directory-table th:nth-child(4),
    .directory-table td:nth-child(4) {
        width: 11%;
    }

    .directory-table th:nth-child(5),
    .directory-table td:nth-child(5) {
        width: 15%;
    }

    .directory-table th:nth-child(6),
    .directory-table td:nth-child(6) {
        width: 11%;
    }

    .directory-table th:nth-child(7),
    .directory-table td:nth-child(7) {
        width: 15%;
    }


    /* =========================
       CLIENT NAME
    ========================== */

    .client-name-link {
        color: #334155;
        text-decoration: none;
        font-weight: 800;
    }

    .client-name-link:hover {
        color: #2563eb;
    }

    .client-code-small {
        display: block;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 600;
        margin-top: 3px;
    }


    /* =========================
       STATUS
    ========================== */

    .client-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-inactive {
        background: #fef3c7;
        color: #b45309;
    }

    .status-archived {
        background: #e2e8f0;
        color: #475569;
    }


    /* =========================
       ACTIONS
    ========================== */

    .row-actions {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .row-actions form {
        margin: 0;
        padding: 0;
        display: flex;
    }

    .edit-btn,
    .delete-btn {
        width: 52px;
        height: 30px;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
    }

    .edit-btn {
        background: #0d6efd;
        color: white;
        text-decoration: none;
    }

    .edit-btn:hover {
        background: #0b5ed7;
    }

    .delete-btn {
        background: #dc3545;
        color: white;
        border: none;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #bb2d3b;
    }


    /* =========================
       EMPTY
    ========================== */

    .empty-client {
        text-align: center;
        padding: 35px 15px !important;
        color: #64748b !important;
        font-weight: 600 !important;
    }

    .clear-btn {
        height: 40px;
        padding: 0 14px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .clear-btn:hover {
        background: #5c636a;
        color: white;
    }


    /* =========================
       RESPONSIVE
    ========================== */

    @media (max-width: 1100px) {

        .client-kpis {
            grid-template-columns: repeat(2, 1fr);
        }

        .directory-table {
            min-width: 1000px;
        }
    }

    @media (max-width: 750px) {

        .client-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .add-client-btn {
            width: 100%;
        }

        .client-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .client-search,
        .client-select {
            width: 100%;
            min-width: 0;
        }

        .client-kpis {
            grid-template-columns: 1fr;
        }

        .directory-card {
            padding: 15px;
        }

        .directory-head {
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>


<div class="client-page">

    {{-- =========================
         PAGE HEADER
    ========================== --}}

    <div class="client-header">

        <div class="client-title">

            <h1>Client Management</h1>

            <p>
                Company registration, contract, billing terms and assigned fleet
            </p>

        </div>

        <a
            href="{{ route('clients.create') }}"
            class="add-client-btn"
        >
            Add Client
        </a>

    </div>


    {{-- =========================
         KPI CARDS
    ========================== --}}

    <div class="client-kpis">

        {{-- Active Clients --}}

        <div class="client-kpi">

            <div class="client-kpi-label">
                Active Clients
            </div>

            <div class="client-kpi-value">
                {{ $activeClientsCount ?? $clients->where('status', 'Active')->count() }}
            </div>

            <div class="client-kpi-note">
                Companies served
            </div>

        </div>


        {{-- Trucks Assigned --}}

        <div class="client-kpi">

            <div class="client-kpi-label">
                Trucks Assigned
            </div>

            <div class="client-kpi-value">
                {{ $trucksAssignedCount ?? '—' }}
            </div>

            <div class="client-kpi-note">
                Current contracts
            </div>

        </div>


        {{-- Receivables --}}

        <div class="client-kpi">

            <div class="client-kpi-label">
                Receivables
            </div>

            <div class="client-kpi-value">

                @if(isset($receivables))

                    AED {{ number_format($receivables, 0) }}

                @else

                    —

                @endif

            </div>

            <div class="client-kpi-note warning">
                Pending collection
            </div>

        </div>


        {{-- Renewals Due --}}

        <div class="client-kpi">

            <div class="client-kpi-label">
                Renewals Due
            </div>

            <div class="client-kpi-value">
                {{ $renewalsDueCount ?? '—' }}
            </div>

            <div class="client-kpi-note">
                Next 30 days
            </div>

        </div>

    </div>


    {{-- =========================
         FILTERS
    ========================== --}}

    <form
        action="{{ route('clients.index') }}"
        method="GET"
        class="client-filters"
    >

        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            class="client-search"
            placeholder="🔍 Search client, TRN, contract or contact person..."
        >

        <select
            name="billing_type"
            class="client-select"
        >

            <option value="">
                Billing Type
            </option>

            <option value="Per Trip"
                {{ request('billing_type') == 'Per Trip' ? 'selected' : '' }}>
                Per Trip
            </option>

            <option value="Weekly"
                {{ request('billing_type') == 'Weekly' ? 'selected' : '' }}>
                Weekly
            </option>

            <option value="Monthly"
                {{ request('billing_type') == 'Monthly' ? 'selected' : '' }}>
                Monthly
            </option>

            <option value="Fixed Rent"
                {{ request('billing_type') == 'Fixed Rent' ? 'selected' : '' }}>
                Fixed Rent
            </option>

            <option value="Usage"
                {{ request('billing_type') == 'Usage' ? 'selected' : '' }}>
                Usage
            </option>

            <option value="Mixed"
                {{ request('billing_type') == 'Mixed' ? 'selected' : '' }}>
                Mixed
            </option>

            <option value="Custom"
                {{ request('billing_type') == 'Custom' ? 'selected' : '' }}>
                Custom
            </option>

        </select>


        <select
            name="city"
            class="client-select"
        >

            <option value="">
                Emirate
            </option>

            <option value="Dubai"
                {{ request('city') == 'Dubai' ? 'selected' : '' }}>
                Dubai
            </option>

            <option value="Abu Dhabi"
                {{ request('city') == 'Abu Dhabi' ? 'selected' : '' }}>
                Abu Dhabi
            </option>

            <option value="Sharjah"
                {{ request('city') == 'Sharjah' ? 'selected' : '' }}>
                Sharjah
            </option>

            <option value="Ajman"
                {{ request('city') == 'Ajman' ? 'selected' : '' }}>
                Ajman
            </option>

            <option value="Umm Al Quwain"
                {{ request('city') == 'Umm Al Quwain' ? 'selected' : '' }}>
                Umm Al Quwain
            </option>

            <option value="Ras Al Khaimah"
                {{ request('city') == 'Ras Al Khaimah' ? 'selected' : '' }}>
                Ras Al Khaimah
            </option>

            <option value="Fujairah"
                {{ request('city') == 'Fujairah' ? 'selected' : '' }}>
                Fujairah
            </option>

        </select>


        <select
            name="status"
            class="client-select"
        >

            <option value="">
                Contract Status
            </option>

            <option value="Active"
                {{ request('status') == 'Active' ? 'selected' : '' }}>
                Active
            </option>

            <option value="Inactive"
                {{ request('status') == 'Inactive' ? 'selected' : '' }}>
                Inactive
            </option>

            <option value="Archived"
                {{ request('status') == 'Archived' ? 'selected' : '' }}>
                Archived
            </option>

        </select>

        @if(
            $search ||
            request('billing_type') ||
            request('city') ||
            request('status')
        )

            <a
                href="{{ route('clients.index') }}"
                class="clear-btn"
            >
                Clear
            </a>

        @endif

    </form>
    {{-- =========================
         CLIENT DIRECTORY
    ========================== --}}

    <div class="directory-card">

        <div class="directory-head">

            <div class="directory-title">
                Client Directory
            </div>

        </div>


        <div class="directory-table-wrapper">

            <table class="directory-table">

                <thead>

                    <tr>

                        <th>Client</th>

                        <th>Emirate</th>

                        <th>Billing</th>

                        <th>Trucks</th>

                        <th>Monthly Value</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($clients as $client)

                        <tr>

                            {{-- Client --}}

                            <td>

                                <a
                                    href="{{ route('clients.show', $client->id) }}"
                                    class="client-name-link"
                                >
                                    {{ $client->company_name }}
                                </a>

                                <span class="client-code-small">
                                    {{ $client->client_code }}
                                </span>

                            </td>


                            {{-- Emirate --}}

                            <td>
                                {{ $client->city ?: '—' }}
                            </td>


                            {{-- Billing --}}

                            <td>
                                {{ $client->billing_type ?: '—' }}
                            </td>


                            {{-- Trucks --}}

                            <td>
                                {{ $client->trucks_count ?? 0 }} Trucks
                            </td>


                            {{-- Monthly Value --}}

                            <td>

                                @if(isset($client->monthly_value))

                                    AED {{ number_format($client->monthly_value, 0) }}

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Status --}}

                            <td>

                                @if($client->status === 'Active')

                                    <span class="client-status status-active">
                                        Active
                                    </span>

                                @elseif($client->status === 'Inactive')

                                    <span class="client-status status-inactive">
                                        Inactive
                                    </span>

                                @else

                                    <span class="client-status status-archived">
                                        Archived
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}

                            <td>

                                <div class="row-actions">

                                    <a
                                        href="{{ route('clients.edit', $client->id) }}"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('clients.destroy', $client->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this client?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-btn"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-client"
                            >
                                No clients found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
    document.querySelectorAll('.client-select').forEach(function (select) {
        select.addEventListener('change', function () {
            this.form.submit();
        });
    });
</script>

@endsection