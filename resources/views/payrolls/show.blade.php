@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')

<div class="payroll-show-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h1>Payroll Details</h1>
            <p>View complete payroll information</p>
        </div>

        <div class="header-actions">

            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                ← Back
            </a>

            <a href="{{ route('payrolls.edit', $payroll->id) }}" class="btn btn-primary">
                ✏️ Edit
            </a>

            <button
                type="button"
                class="btn btn-print"
                onclick="window.print()"
            >
                🖨️ Print
            </button>

        </div>

    </div>


    @php
        $paymentStatus = $payroll->payment_status
            ?? $payroll->status
            ?? 'Pending';

        $grossSalary =
            (float) $payroll->basic_salary +
            (float) ($payroll->allowance ?? 0) +
            (float) ($payroll->overtime ?? 0);

        $totalDeductions =
            $payroll->total_deductions !== null
                ? (float) $payroll->total_deductions
                : (
                    (float) ($payroll->visa_deduction ?? 0) +
                    (float) ($payroll->fine_deduction ?? 0) +
                    (float) ($payroll->advance_deduction ?? 0) +
                    (float) ($payroll->other_deduction ?? 0)
                );

        $netSalary = (float) ($payroll->net_salary ?? (
            $grossSalary - $totalDeductions
        ));
    @endphp


    {{-- BLUE PAYROLL HERO --}}
    <div class="hero-card">

        {{-- HERO TOP --}}
        <div class="hero-top">

            <div class="hero-main">

                <div class="hero-icon">
                    💰
                </div>

                <div class="hero-info">

                    <div class="hero-label">
                        Payroll Month
                    </div>

                    <h2>
                        {{ $payroll->salary_month ?? 'N/A' }}
                    </h2>

                    <p>
                        {{ $payroll->driver->driver_name ?? 'Driver not available' }}
                    </p>

                </div>

            </div>


            <div class="hero-status">

                <span class="status-badge
                    {{ strtolower($paymentStatus) === 'paid' ? 'status-paid' : '' }}
                    {{ strtolower($paymentStatus) === 'pending' ? 'status-pending' : '' }}
                    {{ strtolower($paymentStatus) === 'cancelled' ? 'status-cancelled' : '' }}
                ">
                    {{ $paymentStatus }}
                </span>

            </div>

        </div>


        {{-- FOUR SUMMARY CARDS INSIDE BLUE BOX --}}
        <div class="summary-grid">

            {{-- BASIC SALARY --}}
            <div class="summary-card">

                <div class="summary-icon">
                    💵
                </div>

                <div class="summary-content">

                    <span>
                        Basic Salary
                    </span>

                    <strong>
                        {{ number_format((float) $payroll->basic_salary, 2) }}
                    </strong>

                </div>

            </div>


            {{-- GROSS SALARY --}}
            <div class="summary-card">

                <div class="summary-icon">
                    📈
                </div>

                <div class="summary-content">

                    <span>
                        Gross Salary
                    </span>

                    <strong>
                        {{ number_format($grossSalary, 2) }}
                    </strong>

                </div>

            </div>


            {{-- TOTAL DEDUCTIONS --}}
            <div class="summary-card">

                <div class="summary-icon">
                    📉
                </div>

                <div class="summary-content">

                    <span>
                        Total Deductions
                    </span>

                    <strong>
                        {{ number_format($totalDeductions, 2) }}
                    </strong>

                </div>

            </div>


            {{-- NET SALARY --}}
            <div class="summary-card net-card">

                <div class="summary-icon">
                    🔥
                </div>

                <div class="summary-content">

                    <span>
                        Net Salary
                    </span>

                    <strong>
                        {{ number_format($netSalary, 2) }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- WHITE DETAILS AREA --}}
    <div class="details-area">


        {{-- EMPLOYEE + PAYMENT --}}
        <div class="content-grid">

            {{-- EMPLOYEE INFORMATION --}}
            <div class="info-card">

                <div class="card-heading">
                    <span>👤</span>
                    Employee Information
                </div>

                <div class="info-list">

                    <div class="info-row">

                        <span>
                            Driver
                        </span>

                        <strong>
                            {{ $payroll->driver->driver_name ?? 'N/A' }}
                        </strong>

                    </div>


                    <div class="info-row">

                        <span>
                            Payroll Month
                        </span>

                        <strong>
                            {{ $payroll->salary_month ?? 'N/A' }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- PAYMENT INFORMATION --}}
            <div class="info-card">

                <div class="card-heading">
                    <span>💳</span>
                    Payment Information
                </div>

                <div class="info-list">

                    <div class="info-row">

                        <span>
                            Payment Status
                        </span>

                        <strong>
                            {{ $paymentStatus }}
                        </strong>

                    </div>


                    <div class="info-row">

                        <span>
                            Paid Date
                        </span>

                        <strong>
                            {{ $payroll->paid_date ?? $payroll->payment_date ?? '—' }}
                        </strong>

                    </div>


                    <div class="info-row">

                        <span>
                            Paid Reference
                        </span>

                        <strong>
                            {{ $payroll->paid_reference ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- EARNINGS + DEDUCTIONS --}}
        <div class="content-grid">

            {{-- EARNINGS BREAKDOWN --}}
            <div class="info-card">

                <div class="card-heading">
                    <span>💰</span>
                    Earnings Breakdown
                </div>

                <div class="money-list">

                    <div class="money-row">

                        <span>
                            Basic Salary
                        </span>

                        <strong>
                            {{ number_format((float) $payroll->basic_salary, 2) }}
                        </strong>

                    </div>


                    <div class="money-row">

                        <span>
                            Allowance
                        </span>

                        <strong>
                            {{ number_format((float) ($payroll->allowance ?? 0), 2) }}
                        </strong>

                    </div>


                    <div class="money-row">

                        <span>
                            Overtime
                        </span>

                        <strong>
                            {{ number_format((float) ($payroll->overtime ?? 0), 2) }}
                        </strong>

                    </div>


                    <div class="money-row total-row">

                        <span>
                            Gross Salary
                        </span>

                        <strong>
                            {{ number_format($grossSalary, 2) }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- DEDUCTIONS BREAKDOWN --}}
            <div class="info-card">

                <div class="card-heading">
                    <span>📉</span>
                    Deductions Breakdown
                </div>

                <div class="money-list">

                    <div class="money-row">

                        <span>
                            Visa Deduction
                        </span>

                        <strong>
                            {{ number_format((float) ($payroll->visa_deduction ?? 0), 2) }}
                        </strong>

                    </div>


                    <div class="money-row">

                        <span>
                            Fine Deduction
                        </span>

                        <strong>
                            {{ number_format((float) ($payroll->fine_deduction ?? 0), 2) }}
                        </strong>

                    </div>


                    <div class="money-row">

                        <span>
                            Advance Deduction
                        </span>

                        <strong>
                            {{ number_format((float) ($payroll->advance_deduction ?? 0), 2) }}
                        </strong>

                    </div>


                    <div class="money-row">

                        <span>
                            Other Deduction
                        </span>

                        <strong>
                            {{ number_format((float) ($payroll->other_deduction ?? 0), 2) }}
                        </strong>

                    </div>


                    @if(!empty($payroll->other_deduction_reason))

                        <div class="reason-row">

                            <span>
                                Other Deduction Reason
                            </span>

                            <p>
                                {{ $payroll->other_deduction_reason }}
                            </p>

                        </div>

                    @endif


                    <div class="money-row total-row deduction-total">

                        <span>
                            Total Deductions
                        </span>

                        <strong>
                            {{ number_format($totalDeductions, 2) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>
        {{-- FINAL NET SALARY --}}
        <div class="net-salary-card">

            <div class="net-salary-info">

                <span>
                    Final Net Salary
                </span>

                <p>
                    Basic Salary + Allowance + Overtime − Total Deductions
                </p>

            </div>

            <strong>
                {{ number_format($netSalary, 2) }}
            </strong>

        </div>


        {{-- REMARKS --}}
        <div class="info-card notes-card">

            <div class="card-heading">
                <span>📝</span>
                Remarks
            </div>

            <div class="notes-content">

                @if(!empty($payroll->notes))

                    {{ $payroll->notes }}

                @else

                    <span class="muted-text">
                        No remarks added.
                    </span>

                @endif

            </div>

        </div>


        {{-- RECORD INFORMATION --}}
        <div class="record-meta">

            <span>
                Created:
                {{ $payroll->created_at ? $payroll->created_at->format('d M Y, h:i A') : '—' }}
            </span>

            <span>
                Updated:
                {{ $payroll->updated_at ? $payroll->updated_at->format('d M Y, h:i A') : '—' }}
            </span>

        </div>


    </div>

</div>


<style>
    * {
        box-sizing: border-box;
    }

    .payroll-show-page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        min-width: 0;
    }


    /* PAGE HEADER */

    .page-header {
        width: 100%;
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

    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }


    /* BUTTONS */

    .btn {
        height: 38px;
        padding: 0 14px;
        border: 0;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background: #173b8f;
        color: #ffffff;
    }

    .btn-secondary {
        background: #eef1f6;
        color: #344054;
    }

    .btn-print {
        background: #eaf4ff;
        color: #1558a6;
    }


    /* BLUE HERO */

    .hero-card {
        width: 100%;
        padding: 25px 28px 24px;
        border-radius: 16px;

        background: linear-gradient(
            135deg,
            #101d42 0%,
            #193b8f 100%
        );

        color: #ffffff;

        box-shadow: 0 8px 24px rgba(16, 29, 66, 0.14);

        overflow: hidden;
    }

    .hero-top {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .hero-main {
        display: flex;
        align-items: center;
        gap: 17px;
        min-width: 0;
    }

    .hero-icon {
        width: 58px;
        height: 58px;
        min-width: 58px;

        border-radius: 14px;
        background: rgba(255, 255, 255, 0.14);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 27px;
    }

    .hero-info {
        min-width: 0;
    }

    .hero-label {
        font-size: 12px;
        opacity: 0.78;
        margin-bottom: 5px;
    }

    .hero-card h2 {
        margin: 0;
        font-size: 24px;
        line-height: 1.2;
    }

    .hero-card p {
        margin: 6px 0 0;
        font-size: 13px;
        opacity: 0.88;
    }

    .hero-status {
        flex-shrink: 0;
    }


    /* STATUS */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 82px;
        height: 32px;
        padding: 0 14px;

        border-radius: 20px;

        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;

        font-size: 12px;
        font-weight: 700;
    }

    .status-paid {
        background: #d1fadf;
        color: #067647;
    }

    .status-pending {
        background: #fff0c2;
        color: #8a5a00;
    }

    .status-cancelled {
        background: #ffe1e1;
        color: #b42318;
    }


    /* FOUR CARDS INSIDE BLUE BOX */

    .summary-grid {
        width: 100%;

        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));

        gap: 14px;

        margin-top: 24px;
    }

    .summary-card {
        width: 100%;
        min-width: 0;

        padding: 15px;

        border-radius: 11px;

        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.08);

        display: flex;
        align-items: center;
        gap: 11px;

        overflow: hidden;
    }

    .summary-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;

        border-radius: 10px;

        background: rgba(255, 255, 255, 0.13);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 18px;
    }

    .summary-content {
        min-width: 0;
        overflow: hidden;
    }

    .summary-content span {
        display: block;

        color: rgba(255, 255, 255, 0.72);

        font-size: 11px;
        margin-bottom: 5px;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .summary-content strong {
        display: block;

        color: #ffffff;

        font-size: 16px;
        line-height: 1.2;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .net-card {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.14);
    }


    /* WHITE DETAILS AREA */

    .details-area {
        width: 100%;
        margin-top: 18px;

        padding: 22px;

        background: #ffffff;

        border: 1px solid #e5e9f2;
        border-radius: 15px;

        box-shadow: 0 5px 18px rgba(16, 29, 66, 0.05);

        min-width: 0;
    }


    /* TWO COLUMN CONTENT */

    .content-grid {
        width: 100%;
        min-width: 0;

        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 18px;

        margin-bottom: 18px;
    }


    /* INFO CARDS */

    .info-card {
        width: 100%;
        min-width: 0;
        max-width: 100%;

        background: #ffffff;

        border: 1px solid #e5e9f2;
        border-radius: 13px;

        padding: 20px;

        overflow: hidden;
    }

    .card-heading {
        display: flex;
        align-items: center;
        gap: 9px;

        color: #101d42;

        font-size: 16px;
        font-weight: 700;

        padding-bottom: 13px;
        margin-bottom: 3px;

        border-bottom: 1px solid #edf0f5;
    }


    /* INFORMATION ROWS */

    .info-list {
        width: 100%;
    }

    .info-row {
        width: 100%;
        min-height: 44px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        border-bottom: 1px solid #f0f2f6;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .info-row span {
        color: #667085;
        font-size: 13px;
    }

    .info-row strong {
        color: #172033;
        font-size: 13px;
        text-align: right;
        overflow-wrap: anywhere;
    }


    /* MONEY ROWS */

    .money-list {
        width: 100%;
    }

    .money-row {
        width: 100%;
        min-height: 43px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        border-bottom: 1px solid #f0f2f6;
    }

    .money-row:last-child {
        border-bottom: 0;
    }

    .money-row span {
        color: #667085;
        font-size: 13px;
    }

    .money-row strong {
        color: #172033;
        font-size: 13px;
        text-align: right;
        white-space: nowrap;
    }

    .total-row {
        margin-top: 5px;
        padding-top: 8px;

        border-top: 1px solid #dfe4ec;
        border-bottom: 0;
    }

    .total-row span,
    .total-row strong {
        color: #101d42;
        font-weight: 700;
    }

    .deduction-total strong {
        color: #b42318;
    }


    /* OTHER DEDUCTION REASON */

    .reason-row {
        padding: 11px 0;

        border-bottom: 1px solid #f0f2f6;
    }

    .reason-row span {
        display: block;

        color: #667085;

        font-size: 12px;

        margin-bottom: 5px;
    }

    .reason-row p {
        margin: 0;

        color: #344054;

        font-size: 13px;
        line-height: 1.5;

        overflow-wrap: anywhere;
    }


    /* FINAL NET SALARY */

    .net-salary-card {
        width: 100%;
        min-width: 0;

        min-height: 88px;

        margin-bottom: 18px;

        padding: 19px 21px;

        background: #eef4ff;

        border: 1px solid #cbdcff;
        border-radius: 13px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        overflow: hidden;
    }

    .net-salary-info {
        min-width: 0;
    }

    .net-salary-card span {
        display: block;

        color: #173b8f;

        font-size: 15px;
        font-weight: 700;
    }

    .net-salary-card p {
        margin: 5px 0 0;

        color: #667085;

        font-size: 11px;
    }

    .net-salary-card > strong {
        color: #173b8f;

        font-size: 24px;

        white-space: nowrap;
        flex-shrink: 0;
    }


    /* NOTES */

    .notes-card {
        margin-bottom: 18px;
    }

    .notes-content {
        padding-top: 12px;

        color: #344054;

        font-size: 13px;
        line-height: 1.7;

        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .muted-text {
        color: #98a2b3;
    }


    /* RECORD META */

    .record-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 2px 3px;

        color: #98a2b3;

        font-size: 11px;
    }


    /* TABLET */

    @media (max-width: 950px) {

        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }


    /* MOBILE */

    @media (max-width: 600px) {

        .payroll-show-page {
            max-width: 100%;
        }

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
            flex-wrap: wrap;
        }

        .header-actions .btn {
            flex: 1;
        }

        .hero-card {
            padding: 20px 16px;
        }

        .hero-top {
            align-items: flex-start;
            flex-direction: column;
        }

        .hero-status {
            align-self: flex-start;
        }

        .summary-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .summary-card {
            width: 100%;
        }

        .details-area {
            padding: 14px;
        }

        .info-card {
            padding: 17px;
        }

        .net-salary-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .net-salary-card > strong {
            font-size: 23px;
        }

        .record-meta {
            align-items: flex-start;
            flex-direction: column;
        }
    }


    /* PRINT */

    @media print {

        .page-header .header-actions {
            display: none !important;
        }

        .payroll-show-page {
            max-width: 100%;
        }

        .hero-card {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .details-area {
            box-shadow: none;
        }

        .info-card,
        .net-salary-card {
            box-shadow: none;
        }

        body {
            background: #ffffff !important;
        }
    }

</style>

@endsection