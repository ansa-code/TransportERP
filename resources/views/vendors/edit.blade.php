@extends('layouts.app')

@section('content')

<h2>Edit Vendor</h2>

<form action="{{ route('vendors.update',$vendor->id) }}" method="POST">

    @csrf
    @method('PUT')

    <label>Vendor Name</label><br>
    <input type="text" name="vendor_name" value="{{ $vendor->vendor_name }}"><br><br>

    <label>Company Name</label><br>
    <input type="text" name="company_name" value="{{ $vendor->company_name }}"><br><br>

    <label>Phone</label><br>
    <input type="text" name="phone" value="{{ $vendor->phone }}"><br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="{{ $vendor->email }}"><br><br>

    <label>Address</label><br>
    <textarea name="address">{{ $vendor->address }}</textarea><br><br>

    <label>Service Type</label><br>
    <input type="text" name="service_type" value="{{ $vendor->service_type }}"><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="Active" {{ $vendor->status=='Active' ? 'selected' : '' }}>Active</option>
        <option value="Inactive" {{ $vendor->status=='Inactive' ? 'selected' : '' }}>Inactive</option>
    </select>

    <br><br>

    <label>Notes</label><br>
    <textarea name="notes">{{ $vendor->notes }}</textarea><br><br>

    <button type="submit">Update Vendor</button>

</form>

@endsection