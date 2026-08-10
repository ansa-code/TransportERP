@extends('layouts.app')

@section('content')

<h2>Add Client</h2>

<form action="{{ route('clients.store') }}" method="POST">

    @csrf

    <label>Client Name</label><br>
    <input type="text" name="client_name"><br><br>

    <label>Company Name</label><br>
    <input type="text" name="company_name"><br><br>

    <label>Phone</label><br>
    <input type="text" name="phone"><br><br>

    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Address</label><br>
    <textarea name="address"></textarea><br><br>

    <label>City</label><br>
    <input type="text" name="city"><br><br>

    <label>Country</label><br>
    <input type="text" name="country"><br><br>

    <label>Status</label><br>
    <select name="status">
        <option>Active</option>
        <option>Inactive</option>
    </select>

    <br><br>

    <label>Notes</label><br>
    <textarea name="notes"></textarea><br><br>

    <button type="submit">
        Save Client
    </button>

</form>

@endsection