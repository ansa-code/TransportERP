@extends('layouts.app')

@section('content')

<h2>Add Expense</h2>

<form action="{{ route('expenses.store') }}" method="POST">

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

<label>Expense Date</label><br>

<input type="date" name="expense_date">

<br><br>

<label>Expense Type</label><br>

<input type="text" name="expense_type">

<br><br>

<label>Amount</label><br>

<input type="number" step="0.01" name="amount">

<br><br>

<label>Vendor</label><br>

<input type="text" name="vendor">

<br><br>

<label>Notes</label><br>

<textarea name="notes"></textarea>

<br><br>

<button type="submit">

Save Expense

</button>

</form>

@endsection