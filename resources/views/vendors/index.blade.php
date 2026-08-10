@extends('layouts.app')

@section('content')

<h2>Vendor Management</h2>

<form action="{{ route('vendors.index') }}" method="GET" style="margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Search Vendor..."
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

<br>

<a href="/vendors/create"
   style="
   background:#0d6efd;
   color:white;
   padding:10px 18px;
   text-decoration:none;
   border-radius:6px;
   display:inline-block;
   margin-bottom:20px;
   ">

    + Add Vendor

</a>

<table border="1" cellpadding="10" cellspacing="0" width="100%">

<tr>

    <th>ID</th>
    <th>Vendor Name</th>
    <th>Company</th>
    <th>Phone</th>
    <th>Service</th>
    <th>Status</th>
    <th>Action</th>

</tr>

@foreach($vendors as $vendor)

<tr>

    <td>{{ $vendor->id }}</td>

    <td>{{ $vendor->vendor_name }}</td>

    <td>{{ $vendor->company_name }}</td>

    <td>{{ $vendor->phone }}</td>

    <td>{{ $vendor->service_type }}</td>

    <td>{{ $vendor->status }}</td>

    <td>

        <a href="{{ route('vendors.edit', $vendor->id) }}"
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

        <form action="{{ route('vendors.destroy', $vendor->id) }}"
              method="POST"
              style="display:inline;">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                onclick="return confirm('Delete this vendor?')"
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

{{ $vendors->links() }}

@endsection