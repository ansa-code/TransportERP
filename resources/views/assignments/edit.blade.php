@extends('layouts.app')

@section('content')

<h2>Edit Assignment</h2>

<form action="{{ route('assignments.update', $assignment->id) }}" method="POST">

    @csrf
    @method('PUT')

    <label>Client ID</label><br>
    <input type="number" name="client_id" value="{{ $assignment->client_id }}"><br><br>

    <label>Vehicle ID</label><br>
    <input type="number" name="vehicle_id" value="{{ $assignment->vehicle_id }}"><br><br>

    <label>Driver ID</label><br>
    <input type="number" name="driver_id" value="{{ $assignment->driver_id }}"><br><br>

    <label>Pickup Location</label><br>
    <input type="text" name="pickup_location" value="{{ $assignment->pickup_location }}"><br><br>

    <label>Drop Location</label><br>
    <input type="text" name="drop_location" value="{{ $assignment->drop_location }}"><br><br>

    <label>Loading Date</label><br>
    <input type="date" name="loading_date" value="{{ $assignment->loading_date }}"><br><br>

    <label>Delivery Date</label><br>
    <input type="date" name="delivery_date" value="{{ $assignment->delivery_date }}"><br><br>

    <label>Freight Amount</label><br>
    <input type="number" name="freight_amount" value="{{ $assignment->freight_amount }}"><br><br>

    <label>Status</label><br>
    <input type="text" name="status" value="{{ $assignment->status }}"><br><br>

    <label>Notes</label><br>
    <textarea name="notes">{{ $assignment->notes }}</textarea><br><br>

    <button type="submit">
        Update Assignment
    </button>

</form>

@endsection