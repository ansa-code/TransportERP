@extends('layouts.app')

@section('content')

<h2>Dashboard</h2>

<div style="display:flex;flex-wrap:wrap;gap:20px;">

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
        <h3>⛽ Fuels</h3>
        <h1>{{ $fuels }}</h1>
    </div>

    <div style="background:#20c997;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>🔧 Maintenance</h3>
        <h1>{{ $maintenances }}</h1>
    </div>

    <div style="background:#343a40;color:white;padding:20px;width:220px;border-radius:10px;">
        <h3>💰 Expenses</h3>
        <h1>{{ $expenses }}</h1>
    </div>

</div>

@endsection