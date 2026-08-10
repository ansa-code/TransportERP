@extends('layouts.app')

@section('content')

<h2>Add Assignment</h2>

<form action="{{ route('assignments.store') }}" method="POST">

    @csrf

    <label>Client</label><br>

    <select name="client_id">

        @foreach($clients as $client)

            <option value="{{ $client->id }}">
                {{ $client->client_name }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Vehicle</label><br>

    <select name="vehicle_id">

        @foreach($vehicles as $vehicle)

            <option value="{{ $vehicle->id }}">
                {{ $vehicle->vehicle_number }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Driver</label><br>

    <select name="driver_id">

        @foreach($drivers as $driver)

            <option value="{{ $driver->id }}">
                {{ $driver->driver_name }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Pickup Location</label><br>

    <input type="text" name="pickup_location">

    <br><br>

    <label>Drop Location</label><br>

    <input type="text" name="drop_location">

    <br><br>

    <label>Loading Date</label><br>

    <input type="date" name="loading_date">

    <br><br>

    <label>Delivery Date</label><br>

    <input type="date" name="delivery_date">

    <br><br>

    <label>Freight Amount</label><br>

    <input type="number" step="0.01" name="freight_amount">

    <br><br>

    <label>Status</label><br>

    <select name="status">

        <option>Pending</option>

        <option>On Trip</option>

        <option>Delivered</option>

    </select>

    <br><br>

    <label>Notes</label><br>

    <textarea name="notes"></textarea>

    <br><br>

    <button type="submit">

        Save Assignment

    </button>

</form>

@endsection