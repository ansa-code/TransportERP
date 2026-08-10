@extends('layouts.app')

@section('content')

<h2>Add Maintenance</h2>

<form action="{{ route('maintenances.store') }}" method="POST">

@csrf

<label>Vehicle</label><br>

<select name="vehicle_id">

@foreach($vehicles as $vehicle)

<option value="{{ $vehicle->id }}">

{{ $vehicle->vehicle_number }}

</option>

@endforeach

</select>

<br><br>

<label>Maintenance Date</label><br>

<input type="date" name="maintenance_date">

<br><br>

<label>Service Type</label><br>

<input type="text" name="service_type">

<br><br>

<label>Cost</label><br>

<input type="number" step="0.01" name="cost">

<br><br>

<label>Workshop</label><br>

<input type="text" name="workshop">

<br><br>

<label>Next Service Date</label><br>

<input type="date" name="next_service_date">

<br><br>

<label>Notes</label><br>

<textarea name="notes"></textarea>

<br><br>

<button type="submit">

Save Maintenance

</button>

</form>

@endsection