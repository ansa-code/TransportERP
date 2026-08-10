@extends('layouts.app')

@section('content')

<h2>Reports</h2>

<p style="color:#666;">
Transport ERP summary report
</p>

<div style="display:flex;flex-wrap:wrap;gap:20px;margin-top:20px;">

    <div style="background:#0d6efd;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>🚚 Vehicles</h3>
        <h1>{{ $vehicles }}</h1>
    </div>

    <div style="background:#198754;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>👨 Drivers</h3>
        <h1>{{ $drivers }}</h1>
    </div>

    <div style="background:#ffc107;color:black;padding:20px;width:220px;border-radius:10px;">
        <h3>👥 Clients</h3>
        <h1>{{ $clients }}</h1>
    </div>

    <div style="background:#dc3545;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>📦 Assignments</h3>
        <h1>{{ $assignments }}</h1>
    </div>

    <div style="background:#6f42c1;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>🏢 Vendors</h3>
        <h1>{{ $vendors }}</h1>
    </div>

    <div style="background:#fd7e14;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>⛽ Fuel Records</h3>
        <h1>{{ $fuels }}</h1>
    </div>

    <div style="background:#20c997;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>🔧 Maintenance</h3>
        <h1>{{ $maintenances }}</h1>
    </div>

</div>

<br><br>

<h2>Financial Summary</h2>

<div style="display:flex;flex-wrap:wrap;gap:20px;margin-top:20px;">

    <div style="background:#343a40;color:white;padding:20px;width:250px;border-radius:10px;">
        <h3>💰 Total Expenses</h3>
        <h2>{{ number_format($expenses, 2) }}</h2>
    </div>

    <div style="background:#198754;color:white;padding:20px;width:250px;border-radius:10px;">
        <h3>🧾 Total Invoices</h3>
        <h2>{{ number_format($invoices, 2) }}</h2>
    </div>

    <div style="background:#6c757d;color:white;padding:20px;width:250px;border-radius:10px;">
        <h3>💵 Total Payroll</h3>
        <h2>{{ number_format($payrolls, 2) }}</h2>
    </div>

</div>

@endsection
