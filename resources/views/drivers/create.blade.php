@extends('layouts.app')

@section('content')

<h2>Add Driver</h2>

<hr><br>

<form action="{{ route('drivers.store') }}" method="POST">

    @csrf

    <label>Driver Name</label><br>
    <input type="text" name="driver_name" style="width:350px;padding:8px;"><br><br>

    <label>CNIC</label><br>
    <input type="text" name="cnic" style="width:350px;padding:8px;"><br><br>

    <label>License Number</label><br>
    <input type="text" name="license_number" style="width:350px;padding:8px;"><br><br>

    <label>License Expiry</label><br>
    <input type="date" name="license_expiry" style="width:350px;padding:8px;"><br><br>

    <label>Phone</label><br>
    <input type="text" name="phone" style="width:350px;padding:8px;"><br><br>

    <label>Address</label><br>
    <input type="text" name="address" style="width:350px;padding:8px;"><br><br>

    <label>Date of Birth</label><br>
    <input type="date" name="date_of_birth" style="width:350px;padding:8px;"><br><br>

    <label>Joining Date</label><br>
    <input type="date" name="joining_date" style="width:350px;padding:8px;"><br><br>

    <label>Status</label><br>

    <select name="status" style="width:350px;padding:8px;">
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
    </select>

    <br><br>

    <label>Notes</label><br>

    <textarea name="notes" rows="4" style="width:350px;padding:8px;"></textarea>

    <br><br>

    <button
        type="submit"
        style="background:#198754;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;">

        Save Driver

    </button>

</form>

@endsection