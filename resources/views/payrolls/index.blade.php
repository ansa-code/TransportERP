@extends('layouts.app')

@section('content')

<h2>Payroll Management</h2>

<a href="{{ route('payrolls.create') }}"
style="
background:#0d6efd;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
display:inline-block;
margin-bottom:20px;
">

+ Add Payroll

</a>


<form action="{{ route('payrolls.index') }}"
      method="GET"
      style="margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Search driver or salary month..."
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
    <th>Driver</th>
    <th>Salary Month</th>
    <th>Basic Salary</th>
    <th>Allowance</th>
    <th>Deduction</th>
    <th>Net Salary</th>
    <th>Payment Date</th>
    <th>Status</th>
    <th>Action</th>

</tr>


@forelse($payrolls as $payroll)

<tr>

    <td>{{ $payroll->id }}</td>

    <td>{{ $payroll->driver->driver_name ?? 'N/A' }}</td>

    <td>{{ $payroll->salary_month }}</td>

    <td>{{ number_format($payroll->basic_salary, 2) }}</td>

    <td>{{ number_format($payroll->allowance, 2) }}</td>

    <td>{{ number_format($payroll->deduction, 2) }}</td>

    <td>
        <strong>
            {{ number_format($payroll->net_salary, 2) }}
        </strong>
    </td>

    <td>{{ $payroll->payment_date ?? '—' }}</td>

    <td>{{ $payroll->status }}</td>


    <td>

        <a href="{{ route('payrolls.edit', $payroll->id) }}"
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


        <form action="{{ route('payrolls.destroy', $payroll->id) }}"
              method="POST"
              style="display:inline;">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                onclick="return confirm('Delete this payroll record?')"
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

    <td colspan="10" style="text-align:center;">
        No payroll records found.
    </td>

</tr>

@endforelse

</table>


<br>

{{ $payrolls->links() }}

@endsection