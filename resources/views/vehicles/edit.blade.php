@extends('layouts.app')

@section('content')

<h2>Edit Vehicle</h2>

<form action="{{ route('vehicles.update',$vehicle->id) }}" method="POST">

    @csrf

    @method('PUT')

    <label>Vehicle Number</label><br>

    <input type="text"
           name="vehicle_number"
           value="{{ $vehicle->vehicle_number }}"><br><br>

    <label>Plate Number</label><br>

    <input type="text"
           name="plate_number"
           value="{{ $vehicle->plate_number }}"><br><br>

    <label>Vehicle Type</label><br>

    <input type="text"
           name="vehicle_type"
           value="{{ $vehicle->vehicle_type }}"><br><br>

    <label>Brand</label><br>

    <input type="text"
           name="brand"
           value="{{ $vehicle->brand }}"><br><br>

    <label>Model</label><br>

    <input type="text"
           name="model"
           value="{{ $vehicle->model }}"><br><br>
           <label>Manufacture Year</label><br>

<input type="number"
       name="manufacture_year"
       value="{{ $vehicle->manufacture_year }}"><br><br>

<label>Capacity</label><br>

<input type="number"
       name="capacity"
       value="{{ $vehicle->capacity }}"><br><br>

<label>Status</label><br>

<select name="status">

<option
@if($vehicle->status=='Available') selected @endif>
Available
</option>

<option
@if($vehicle->status=='On Trip') selected @endif>
On Trip
</option>

<option
@if($vehicle->status=='Maintenance') selected @endif>
Maintenance
</option>

</select>

<br><br>
 <label>Insurance Expiry</label><br>

<input type="date"
name="insurance_expiry"
value="{{ $vehicle->insurance_expiry }}">

<br><br>

<label>Registration Expiry</label><br>

<input type="date"
name="registration_expiry"
value="{{ $vehicle->registration_expiry }}">

<br><br>

<label>Notes</label><br>

<textarea
name="notes"
rows="4">{{ $vehicle->notes }}</textarea>

<br><br>

<button type="submit">

Update Vehicle

</button>

</form>

@endsection