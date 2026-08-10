@extends('layouts.app')

@section('content')

<h2>Expense Management</h2>

<a href="/expenses/create"
style="
background:#0d6efd;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
display:inline-block;
margin-bottom:20px;
">

+ Add Expense

</a>



<form action="/expenses" method="GET">

<input
type="text"
name="search"
value="{{ $search }}"
placeholder="Search Vehicle">

<button type="submit">

Search

</button>

</form>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%">

<tr>

<th>ID</th>

<th>Vehicle</th>

<th>Expense Type</th>

<th>Amount</th>

<th>Date</th>

<th>Action</th>

</tr>

@foreach($expenses as $expense)

<tr>

<td>{{ $expense->id }}</td>

<td>{{ $expense->vehicle->vehicle_number }}</td>

<td>{{ $expense->expense_type }}</td>

<td>{{ $expense->amount }}</td>

<td>{{ $expense->expense_date }}</td>

  <td>

    <a href="{{ route('expenses.edit', $expense->id) }}"
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

    <form action="{{ route('expenses.destroy', $expense->id) }}"
          method="POST"
          style="display:inline;">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Delete this expense record?')"
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

@endforeach

</table>

<br>

{{ $expenses->links() }}

@endsection