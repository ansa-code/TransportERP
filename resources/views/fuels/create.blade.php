@extends('layouts.app')

@section('content')

<h2>Add Fuel Record</h2>

<form action="{{ route('fuels.store') }}" method="POST">

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

<label>Driver</label><br>

<select name="driver_id">

@foreach($drivers as $driver)

<option value="{{ $driver->id }}">
{{ $driver->driver_name }}
</option>

@endforeach

</select>

<br><br>

<label>Fuel Date</label><br>

<input type="date" name="fuel_date"><br><br>

<label>Liters</label><br>

<input type="number" step="0.01" name="liters"><br><br>

<label>Price Per Liter</label><br>

<input type="number" step="0.01" name="price_per_liter"><br><br>

<label>Total Amount</label><br>

<input type="number" step="0.01" name="total_amount"><br><br>

<label>Odometer</label><br>

<input type="number" name="odometer"><br><br>

<label>Fuel Station</label><br>

<input type="text" name="fuel_station"><br><br>

<label>Notes</label><br>

<textarea name="notes"></textarea>

<br><br>

<button type="submit">

Save Fuel

</button>

</form>

@endsection