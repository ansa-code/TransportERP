@extends('layouts.app')

@section('content')

<h2>Add Vendor</h2>

<form action="{{ route('vendors.store') }}" method="POST">

@csrf

<label>Vendor Name</label><br>

<input type="text" name="vendor_name"><br><br>

<label>Company Name</label><br>

<input type="text" name="company_name"><br><br>

<label>Phone</label><br>

<input type="text" name="phone"><br><br>

<label>Email</label><br>

<input type="email" name="email"><br><br>

<label>Address</label><br>

<textarea name="address"></textarea><br><br>

<label>Service Type</label><br>

<input type="text" name="service_type"><br><br>

<label>Status</label><br>

<select name="status">

<option>Active</option>

<option>Inactive</option>

</select>

<br><br>

<label>Notes</label><br>

<textarea name="notes"></textarea>

<br><br>

<button type="submit">

Save Vendor

</button>

</form>

@endsection