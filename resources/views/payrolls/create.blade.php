@extends('layouts.app')

@section('content')

<h2>Add Payroll</h2>

<form action="{{ route('payrolls.store') }}" method="POST">

@csrf

<label>Driver</label><br>

<select name="driver_id" required>

<option value="">Select Driver</option>

@foreach($drivers as $driver)

<option value="{{ $driver->id }}">
{{ $driver->driver_name }}
</option>

@endforeach

</select>

<br><br>

<label>Salary Month</label><br>

<input
type="month"
name="salary_month"
required>

<br><br>

<label>Basic Salary</label><br>

<input
type="number"
name="basic_salary"
step="0.01"
min="0"
required>

<br><br>

<label>Allowance</label><br>

<input
type="number"
name="allowance"
step="0.01"
min="0"
value="0">

<br><br>

<label>Deduction</label><br>

<input
type="number"
name="deduction"
step="0.01"
min="0"
value="0">

<br><br>

<label>Payment Date</label><br>

<input type="date" name="payment_date">

<br><br>

<label>Status</label><br>

<select name="status" required>

<option value="Pending">Pending</option>

<option value="Paid">Paid</option>

</select>

<br><br>

<label>Notes</label><br>

<textarea name="notes" rows="4"></textarea>

<br><br>

<button type="submit">

Save Payroll

</button>

</form>

@endsection