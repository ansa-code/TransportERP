@extends('layouts.app')

@section('content')

<h2>Edit Maintenance</h2>

<form action="{{ route('maintenances.update', $maintenance->id) }}" method="POST">

    @csrf
    @method('PUT')

    <label>Vehicle</label><br>

    <select name="vehicle_id">

        @foreach($vehicles as $vehicle)

            <option value="{{ $vehicle->id }}"
                {{ $maintenance->vehicle_id == $vehicle->id ? 'selected' : '' }}>

                {{ $vehicle->vehicle_number }}

            </option>

        @endforeach

    </select>

    <br><br>

    <label>Maintenance Date</label><br>

    <input type="date"
           name="maintenance_date"
           value="{{ $maintenance->maintenance_date }}">

    <br><br>

    <label>Service Type</label><br>

    <input type="text"
           name="service_type"
           value="{{ $maintenance->service_type }}">

    <br><br>

    <label>Cost</label><br>

    <input type="number"
           step="0.01"
           name="cost"
           value="{{ $maintenance->cost }}">

    <br><br>

    <label>Workshop</label><br>

    <input type="text"
           name="workshop"
           value="{{ $maintenance->workshop }}">

    <br><br>

    <label>Next Service Date</label><br>

    <input type="date"
           name="next_service_date"
           value="{{ $maintenance->next_service_date }}">

    <br><br>

    <label>Notes</label><br>

    <textarea name="notes" rows="4">{{ $maintenance->notes }}</textarea>

    <br><br>

    <button type="submit">

        Update Maintenance

    </button>

</form>

@endsection
