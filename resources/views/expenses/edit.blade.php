@extends('layouts.app')

@section('content')

<h2>Edit Expense</h2>

<form action="{{ route('expenses.update',$expense->id) }}" method="POST">

@csrf
@method('PUT')

<label>Vehicle</label><br>

<select name="vehicle_id">

@foreach($vehicles as $vehicle)

<option value="{{ $vehicle->id }}"
{{ $expense->vehicle_id==$vehicle->id ? 'selected' : '' }}>

{{ $vehicle->vehicle_number }}

</option>

@endforeach

</select>

<br><br>

<label>Expense Date</label><br>

<input
type="date"
name="expense_date"
value="{{ $expense->expense_date }}">

<br><br>

<label>Expense Type</label><br>

<input
type="text"
name="expense_type"
value="{{ $expense->expense_type }}">

<br><br>

<label>Amount</label><br>

<input
type="number"
step="0.01"
name="amount"
value="{{ $expense->amount }}">

<br><br>

<label>Vendor</label><br>

<input
type="text"
name="vendor"
value="{{ $expense->vendor }}">

<br><br>

<label>Notes</label><br>

<textarea name="notes">{{ $expense->notes }}</textarea>

<br><br>

<button type="submit">

Update Expense

</button>

</form>

@endsection