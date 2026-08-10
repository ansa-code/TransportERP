@extends('layouts.app')

@section('content')

<h2>Edit Payroll</h2>

<form action="{{ route('payrolls.update', $payroll->id) }}" method="POST">

@csrf
@method('PUT')

<label>Driver</label><br>

<select name="driver_id" required>

@foreach($drivers as $driver)

<option
value="{{ $driver->id }}"
{{ $payroll->driver_id == $driver->id ? 'selected' : '' }}>

{{ $driver->driver_name }}

</option>

@endforeach

</select>

<br><br>

<label>Salary Month</label><br>

<input
type="month"
name="salary_month"
value="{{ $payroll->salary_month }}"
required>

<br><br>

<label>Basic Salary</label><br>

<input
type="number"
name="basic_salary"
step="0.01"
min="0"
value="{{ $payroll->basic_salary }}"
required>

<br><br>

<label>Allowance</label><br>

<input
type="number"
name="allowance"
step="0.01"
min="0"
value="{{ $payroll->allowance }}">

<br><br>

<label>Deduction</label><br>

<input
type="number"
name="deduction"
step="0.01"
min="0"
value="{{ $payroll->deduction }}">

<br><br>

<label>Payment Date</label><br>

<input
type="date"
name="payment_date"
value="{{ $payroll->payment_date }}">

<br><br>

<label>Status</label><br>

<select name="status" required>

<option value="Pending"
{{ $payroll->status == 'Pending' ? 'selected' : '' }}>
Pending
</option>

<option value="Paid"
{{ $payroll->status == 'Paid' ? 'selected' : '' }}>
Paid
</option>

</select>

<br><br>

<label>Notes</label><br>

<textarea name="notes" rows="4">{{ $payroll->notes }}</textarea>

<br><br>

<button type="submit">

Update Payroll

</button>

</form>

@endsection