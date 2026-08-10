@extends('layouts.app')

@section('content')

<h2>Edit Client</h2>

<form action="{{ route('clients.update', $client->id) }}" method="POST">

    @csrf
    @method('PUT')

    <label>Client Name</label><br>
    <input type="text"
           name="client_name"
           value="{{ $client->client_name }}"><br><br>

    <label>Company Name</label><br>
    <input type="text"
           name="company_name"
           value="{{ $client->company_name }}"><br><br>

    <label>Phone</label><br>
    <input type="text"
           name="phone"
           value="{{ $client->phone }}"><br><br>

    <label>Email</label><br>
    <input type="email"
           name="email"
           value="{{ $client->email }}"><br><br>

    <label>Address</label><br>
    <textarea name="address">{{ $client->address }}</textarea><br><br>

    <label>City</label><br>
    <input type="text"
           name="city"
           value="{{ $client->city }}"><br><br>

    <label>Country</label><br>
    <input type="text"
           name="country"
           value="{{ $client->country }}"><br><br>

    <label>Status</label><br>
    <select name="status">

        <option value="Active" {{ $client->status == 'Active' ? 'selected' : '' }}>
            Active
        </option>

        <option value="Inactive" {{ $client->status == 'Inactive' ? 'selected' : '' }}>
            Inactive
        </option>

    </select>

    <br><br>

    <label>Notes</label><br>
    <textarea name="notes">{{ $client->notes }}</textarea><br><br>

    <button type="submit">
        Update Client
    </button>

</form>

@endsection