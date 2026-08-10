@extends('layouts.app')

@section('content')

<h2>Edit Fuel Record</h2>

<form action="{{ route('fuels.update',$fuel->id) }}" method="POST">

@csrf
@method('PUT')

<label>Vehicle</label><br>

<select name="vehicle_id">

@foreach($vehicles as $vehicle)

<option value="{{ $vehicle->id }}"
{{ $fuel->vehicle_id == $vehicle->id ? 'selected' : '' }}>

{{ $vehicle->vehicle_number }}

</option>

@endforeach

</select>

<br><br>

<label>Driver</label><br>

<select name="driver_id">

@foreach($drivers as $driver)

<option value="{{ $driver->id }}"
{{ $fuel->driver_id == $driver->id ? 'selected' : '' }}>

{{ $driver->driver_name }}

</option>

@endforeach

</select>

<br><br>

<label>Fuel Date</label><br>

<input type="date"
name="fuel_date"
value="{{ $fuel->fuel_date }}">

<br><br>

<label>Liters</label><br>

<input type="number"
step="0.01"
name="liters"
value="{{ $fuel->liters }}">

<br><br>

<label>Price Per Liter</label><br>

<input type="number"
step="0.01"
name="price_per_liter"
value="{{ $fuel->price_per_liter }}">

<br><br>

<label>Total Amount</label><br>

<input type="number"
step="0.01"
name="total_amount"
value="{{ $fuel->total_amount }}">

<br><br>

<label>Odometer</label><br>

<input type="number"
name="odometer"
value="{{ $fuel->odometer }}">

<br><br>

<label>Fuel Station</label><br>

<input type="text"
name="fuel_station"
value="{{ $fuel->fuel_station }}">

<br><br>

<label>Notes</label><br>

<textarea name="notes">{{ $fuel->notes }}</textarea>

<br><br>

<button type="submit">

Update Fuel

</button>

</form>

@endsection
