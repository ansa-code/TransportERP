@extends('layouts.app')

@section('content')

<h2>Edit Driver</h2>

<form action="{{ route('drivers.update', $driver->id) }}" method="POST">

    @csrf
    @method('PUT')

    <label>Driver Name</label><br>

    <input type="text"
           name="driver_name"
           value="{{ $driver->driver_name }}"><br><br>

    <label>CNIC</label><br>

    <input type="text"
           name="cnic"
           value="{{ $driver->cnic }}"><br><br>

    <label>Phone</label><br>

    <input type="text"
           name="phone"
           value="{{ $driver->phone }}"><br><br>

    <label>License Number</label><br>

    <input type="text"
           name="license_number"
           value="{{ $driver->license_number }}"><br><br>

    <label>License Expiry</label><br>

    <input type="date"
           name="license_expiry"
           value="{{ $driver->license_expiry }}"><br><br>

    <label>Address</label><br>

    <textarea
        name="address"
        rows="4">{{ $driver->address }}</textarea><br><br>

    <label>Status</label><br>

    <select name="status">

        <option value="Active" @if($driver->status == 'Active') selected @endif>
            Active
        </option>

        <option value="Inactive" @if($driver->status == 'Inactive') selected @endif>
            Inactive
        </option>

        <option value="On Leave" @if($driver->status == 'On Leave') selected @endif>
            On Leave
        </option>

    </select>

    <br><br>

    <label>Date of Birth</label><br>

    <input type="date"
           name="date_of_birth"
           value="{{ $driver->date_of_birth }}"><br><br>

    <label>Joining Date</label><br>

    <input type="date"
           name="joining_date"
           value="{{ $driver->joining_date }}"><br><br>

    <label>Notes</label><br>

    <textarea
        name="notes"
        rows="4">{{ $driver->notes }}</textarea><br><br>

    <button type="submit">
        Update Driver
    </button>

</form>

@endsection