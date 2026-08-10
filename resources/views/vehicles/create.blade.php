@extends('layouts.app')

@section('content')

<h2>Add New Vehicle</h2>

@if ($errors->any())

<div style="background:#f8d7da;color:#842029;padding:10px;border-radius:6px;margin-bottom:15px;">

    <ul>

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif

<form action="{{ route('vehicles.store') }}" method="POST">

    @csrf

    <label>Vehicle Number</label><br>
    <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}"><br><br>

    <label>Plate Number</label><br>
    <input type="text" name="plate_number" value="{{ old('plate_number') }}"><br><br>

    <label>Vehicle Type</label><br>
    <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}"><br><br>

    <label>Brand</label><br>
    <input type="text" name="brand" value="{{ old('brand') }}"><br><br>

    <label>Model</label><br>
    <input type="text" name="model" value="{{ old('model') }}"><br><br>

    <label>Manufacture Year</label><br>
    <input type="number" name="manufacture_year" value="{{ old('manufacture_year') }}"><br><br>

    <label>Capacity</label><br>
    <input type="number" name="capacity" value="{{ old('capacity') }}"><br><br>

    <label>Refrigerated</label>
    <input
        type="checkbox"
        name="refrigerated"
        {{ old('refrigerated') ? 'checked' : '' }}>
    <br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>
            Available
        </option>

        <option value="On Trip" {{ old('status') == 'On Trip' ? 'selected' : '' }}>
            On Trip
        </option>

        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>
            Maintenance
        </option>
    </select>
    <br><br>

    <label>Insurance Expiry</label><br>
    <input
        type="date"
        name="insurance_expiry"
        value="{{ old('insurance_expiry') }}">
    <br><br>

    <label>Registration Expiry</label><br>
    <input
        type="date"
        name="registration_expiry"
        value="{{ old('registration_expiry') }}">
    <br><br>

    <label>Notes</label><br>
    <textarea
        name="notes"
        rows="4">{{ old('notes') }}</textarea>
    <br><br>

    <button type="submit">
        Save Vehicle
    </button>

</form>

@endsection