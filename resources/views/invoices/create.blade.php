@extends('layouts.app')

@section('content')

<h2>Add Invoice</h2>

<form action="{{ route('invoices.store') }}" method="POST">

@csrf

<label>Invoice Number</label><br>

<input
type="text"
name="invoice_number"
placeholder="INV-001"
required>

<br><br>

<label>Client</label><br>

<select name="client_id" required>

<option value="">Select Client</option>

@foreach($clients as $client)

<option value="{{ $client->id }}">

{{ $client->client_name }}

</option>

@endforeach

</select>

<br><br>

<label>Assignment</label><br>

<select name="assignment_id">

<option value="">Select Assignment</option>

@foreach($assignments as $assignment)

<option value="{{ $assignment->id }}">

Assignment #{{ $assignment->id }}

@if($assignment->client)

- {{ $assignment->client->client_name }}

@endif

</option>

@endforeach

</select>

<br><br>

<label>Invoice Date</label><br>

<input type="date" name="invoice_date" required>

<br><br>

<label>Due Date</label><br>

<input type="date" name="due_date">

<br><br>

<label>Amount</label><br>

<input
type="number"
name="amount"
step="0.01"
min="0"
required>

<br><br>

<label>Status</label><br>

<select name="status" required>

<option value="Pending">Pending</option>

<option value="Paid">Paid</option>

<option value="Overdue">Overdue</option>

</select>

<br><br>

<label>Notes</label><br>

<textarea name="notes" rows="4"></textarea>

<br><br>

<button type="submit">

Save Invoice

</button>

</form>

@endsection