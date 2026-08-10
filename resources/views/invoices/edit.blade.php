@extends('layouts.app')

@section('content')

<h2>Edit Invoice</h2>

<form action="{{ route('invoices.update', $invoice->id) }}" method="POST">

@csrf
@method('PUT')

<label>Invoice Number</label><br>

<input
type="text"
name="invoice_number"
value="{{ $invoice->invoice_number }}"
required>

<br><br>

<label>Client</label><br>

<select name="client_id" required>

@foreach($clients as $client)

<option
value="{{ $client->id }}"
{{ $invoice->client_id == $client->id ? 'selected' : '' }}>

{{ $client->client_name }}

</option>

@endforeach

</select>

<br><br>

<label>Assignment</label><br>

<select name="assignment_id">

<option value="">Select Assignment</option>

@foreach($assignments as $assignment)

<option
value="{{ $assignment->id }}"
{{ $invoice->assignment_id == $assignment->id ? 'selected' : '' }}>

Assignment #{{ $assignment->id }}

@if($assignment->client)

- {{ $assignment->client->client_name }}

@endif

</option>

@endforeach

</select>

<br><br>

<label>Invoice Date</label><br>

<input
type="date"
name="invoice_date"
value="{{ $invoice->invoice_date }}"
required>

<br><br>

<label>Due Date</label><br>

<input
type="date"
name="due_date"
value="{{ $invoice->due_date }}">

<br><br>

<label>Amount</label><br>

<input
type="number"
name="amount"
step="0.01"
min="0"
value="{{ $invoice->amount }}"
required>

<br><br>

<label>Status</label><br>

<select name="status" required>

<option value="Pending"
{{ $invoice->status == 'Pending' ? 'selected' : '' }}>
Pending
</option>

<option value="Paid"
{{ $invoice->status == 'Paid' ? 'selected' : '' }}>
Paid
</option>

<option value="Overdue"
{{ $invoice->status == 'Overdue' ? 'selected' : '' }}>
Overdue
</option>

</select>

<br><br>

<label>Notes</label><br>

<textarea name="notes" rows="4">{{ $invoice->notes }}</textarea>

<br><br>

<button type="submit">

Update Invoice

</button>

</form>

@endsection