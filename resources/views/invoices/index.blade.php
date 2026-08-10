@extends('layouts.app')

@section('content')

<h2>Invoice Management</h2>

<a href="{{ route('invoices.create') }}"
style="
background:#0d6efd;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
display:inline-block;
margin-bottom:20px;
">

+ Add Invoice

</a>


<form action="{{ route('invoices.index') }}"
      method="GET"
      style="margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Search invoice or client..."
        style="
            padding:10px;
            width:300px;
            border:1px solid #ccc;
            border-radius:5px;
        ">

    <button
        type="submit"
        style="
            padding:10px 20px;
            background:#198754;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
        ">

        Search

    </button>

</form>


<table border="1" cellpadding="10" cellspacing="0" width="100%">

<tr>

    <th>ID</th>
    <th>Invoice No.</th>
    <th>Client</th>
    <th>Assignment</th>
    <th>Invoice Date</th>
    <th>Due Date</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Action</th>

</tr>


@forelse($invoices as $invoice)

<tr>

    <td>{{ $invoice->id }}</td>

    <td>{{ $invoice->invoice_number }}</td>

    <td>{{ $invoice->client->client_name ?? 'N/A' }}</td>

    <td>
        {{ $invoice->assignment ? 'Assignment #'.$invoice->assignment->id : '—' }}
    </td>

    <td>{{ $invoice->invoice_date }}</td>

    <td>{{ $invoice->due_date ?? '—' }}</td>

    <td>{{ number_format($invoice->amount, 2) }}</td>

    <td>{{ $invoice->status }}</td>


    <td>

        <a href="{{ route('invoices.edit', $invoice->id) }}"
           style="
           background:#0d6efd;
           color:white;
           padding:6px 10px;
           text-decoration:none;
           border-radius:5px;
           display:inline-block;
           ">

            Edit

        </a>


        <form action="{{ route('invoices.destroy', $invoice->id) }}"
              method="POST"
              style="display:inline;">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                onclick="return confirm('Delete this invoice record?')"
                style="
                background:#dc3545;
                color:white;
                padding:6px 10px;
                border:none;
                border-radius:5px;
                cursor:pointer;
                ">

                Delete

            </button>

        </form>

    </td>

</tr>

@empty

<tr>

    <td colspan="9" style="text-align:center;">
        No invoices found.
    </td>

</tr>

@endforelse

</table>


<br>

{{ $invoices->links() }}

@endsection
