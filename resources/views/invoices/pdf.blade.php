<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        Invoice {{ $invoice->invoice_no ?? $invoice->invoice_number }}
    </title>

    <style>
        @page {
            margin: 28px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 11px;
            background: #ffffff;
        }

        .invoice-container {
            width: 100%;
        }

        /* HEADER */

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .header-left {
            width: 60%;
            vertical-align: top;
        }

        .header-right {
            width: 40%;
            text-align: right;
            vertical-align: top;
        }

        .company-name {
            margin: 0;
            color: #101d42;
            font-size: 22px;
            font-weight: bold;
        }

        .company-subtitle {
            margin-top: 5px;
            color: #64748b;
            font-size: 10px;
        }

        .company-logo {
            width: 65px;
            height: auto;
        }

        .logo-cell {
            width: 75px;
            vertical-align: middle;
            padding-right: 10px;
        }

        .company-info-cell {
            vertical-align: middle;
        }

        .invoice-title {
            margin: 0;
            color: #101d42;
            font-size: 24px;
            font-weight: bold;
        }

        .invoice-number {
            margin-top: 6px;
            color: #2563eb;
            font-size: 11px;
            font-weight: bold;
        }

        /* STATUS */

        .status {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 9px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 9px;
            font-weight: bold;
        }

        /* INFO */

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .info-box {
            width: 50%;
            padding: 13px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .info-label {
            margin-bottom: 6px;
            color: #64748b;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-value {
            color: #1e293b;
            font-size: 11px;
            font-weight: bold;
        }

        .info-small {
            margin-top: 4px;
            color: #64748b;
            font-size: 10px;
        }

        /* BILLING */

        .section-title {
            margin-bottom: 8px;
            color: #101d42;
            font-size: 12px;
            font-weight: bold;
        }

        .billing-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .billing-table th {
            padding: 9px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-size: 9px;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
        }

        .billing-table td {
            padding: 10px;
            color: #334155;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        /* TOTALS */

        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .notes-cell {
            width: 58%;
            vertical-align: top;
            padding-right: 20px;
        }

        .totals-cell {
            width: 42%;
            vertical-align: top;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        .totals-label {
            color: #64748b;
            text-align: left;
        }

        .totals-value {
            color: #1e293b;
            font-weight: bold;
            text-align: right;
            white-space: nowrap;
        }

        .grand-total td {
            padding-top: 10px;
            padding-bottom: 10px;
            background: #101d42;
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
        }

        .paid-row .totals-value {
            color: #047857;
        }

        .balance-row .totals-value {
            color: #b91c1c;
        }

        /* NOTES */

        .notes-title {
            margin-bottom: 6px;
            color: #101d42;
            font-size: 11px;
            font-weight: bold;
        }

        .notes {
            color: #64748b;
            font-size: 10px;
            line-height: 1.6;
        }

        /* FOOTER */

        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            color: #94a3b8;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="invoice-container">

    {{-- HEADER --}}

    <table class="header-table">

        <tr>

            <td class="header-left">

                <table style="border-collapse: collapse;">

                    <tr>

                        {{-- COMPANY LOGO --}}

                        <td class="logo-cell">

                            <img
                                src="{{ public_path('images/logo.jpeg') }}"
                                alt="Al Shaqra Transport"
                                class="company-logo"
                            >

                        </td>

                        {{-- COMPANY NAME --}}

                        <td class="company-info-cell">

                            <h1 class="company-name">
                                Al Shaqra Transport
                            </h1>

                            <div class="company-subtitle">
                                Transport &amp; Logistics Services
                            </div>

                        </td>

                    </tr>

                </table>

            </td>


            {{-- INVOICE INFORMATION --}}

            <td class="header-right">

                <h2 class="invoice-title">
                    INVOICE
                </h2>

                <div class="invoice-number">
                    {{ $invoice->invoice_no ?? $invoice->invoice_number }}
                </div>

                <div class="status">
                    {{ $invoice->status }}
                </div>

            </td>

        </tr>

    </table>


    {{-- CLIENT / INVOICE INFO --}}

    <table class="info-table">

        <tr>

            {{-- BILL TO --}}

            <td class="info-box">

                <div class="info-label">
                    Bill To
                </div>

                <div class="info-value">
                    {{ $invoice->client->client_name ?? 'N/A' }}
                </div>

            </td>


            {{-- INVOICE DATE --}}

            <td class="info-box">

                <div class="info-label">
                    Invoice Date
                </div>

                <div class="info-value">
                    {{ $invoice->invoice_date?->format('d M Y') ?? '—' }}
                </div>

                <div class="info-small">
                    Due Date:
                    {{ $invoice->due_date?->format('d M Y') ?? '—' }}
                </div>

            </td>

        </tr>

    </table>


    {{-- BILLING PERIOD --}}

    <div class="section-title">
        Billing Details
    </div>

    <table class="billing-table">

        <thead>

            <tr>

                <th>
                    Billing Period
                </th>

                <th>
                    Source
                </th>

                <th>
                    Assignment
                </th>

                <th class="text-right">
                    Subtotal
                </th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>
                    {{ $invoice->billing_start?->format('d M Y') ?? '—' }}
                    -
                    {{ $invoice->billing_end?->format('d M Y') ?? '—' }}
                </td>

                <td>
                    {{ $invoice->source ?? 'Manual' }}
                </td>

                <td>
                    {{ $invoice->assignment->id ?? '—' }}
                </td>

                <td class="text-right">
                    AED {{ number_format((float) $invoice->subtotal, 2) }}
                </td>

            </tr>

        </tbody>

    </table>


    {{-- AMOUNT SUMMARY --}}

    <table class="bottom-table">

        <tr>

            {{-- NOTES --}}

            <td class="notes-cell">

                @if($invoice->notes)

                    <div class="notes-title">
                        Notes
                    </div>

                    <div class="notes">
                        {{ $invoice->notes }}
                    </div>

                @endif

            </td>


            {{-- TOTALS --}}

            <td class="totals-cell">

                <table class="totals-table">

                    {{-- SUBTOTAL --}}

                    <tr>

                        <td class="totals-label">
                            Subtotal
                        </td>

                        <td class="totals-value">
                            AED {{ number_format((float) $invoice->subtotal, 2) }}
                        </td>

                    </tr>


                    {{-- VAT --}}

                    <tr>

                        <td class="totals-label">
                            VAT
                            ({{ number_format((float) $invoice->vat_percent, 2) }}%)
                        </td>

                        <td class="totals-value">
                            AED {{ number_format((float) $invoice->vat_amount, 2) }}
                        </td>

                    </tr>


                    {{-- FUEL REIMBURSEMENT --}}

                    <tr>

                        <td class="totals-label">
                            Fuel Reimbursement
                        </td>

                        <td class="totals-value">
                            AED {{ number_format((float) $invoice->fuel_reimbursement, 2) }}
                        </td>

                    </tr>


                    {{-- OTHER REIMBURSEMENT --}}

                    <tr>

                        <td class="totals-label">
                            Other Reimbursement
                        </td>

                        <td class="totals-value">
                            AED {{ number_format((float) $invoice->other_reimbursement, 2) }}
                        </td>

                    </tr>


                    {{-- GRAND TOTAL --}}

                    <tr class="grand-total">

                        <td>
                            TOTAL
                        </td>

                        <td class="text-right">
                            AED {{ number_format((float) $invoice->total_amount, 2) }}
                        </td>

                    </tr>


                    {{-- PAID --}}

                    <tr class="paid-row">

                        <td class="totals-label">
                            Paid
                        </td>

                        <td class="totals-value">
                            AED {{ number_format((float) $invoice->paid_amount, 2) }}
                        </td>

                    </tr>


                    {{-- OUTSTANDING BALANCE --}}

                    <tr class="balance-row">

                        <td class="totals-label">
                            Outstanding Balance
                        </td>

                        <td class="totals-value">
                            AED {{ number_format((float) $invoice->balance, 2) }}
                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>


    {{-- FOOTER --}}

    <div class="footer">

        Thank you for your business.

        <br>

        Al Shaqra Transport — Transport &amp; Logistics Services

    </div>

</div>

</body>
</html>