@extends('layouts.app')

@section('content')

<h2>Client Management</h2>

  <form action="{{ route('clients.index') }}" method="GET" style="margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Search Client..."
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
<hr>

<a href="/clients/create"
style="
background:#0d6efd;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
font-weight:bold;
display:inline-block;
margin-bottom:20px;
">
+ Add Client
</a>


@if(session('success'))

<p style="color:green;font-weight:bold;">
    {{ session('success') }}
</p>

@endif


<table border="1" cellpadding="10" cellspacing="0" width="100%">

<tr>
    <th>ID</th>
    <th>Client Name</th>
    <th>Company</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Action</th>
</tr>


@foreach($clients as $client)

<tr>

    <td>{{ $client->id }}</td>

    <td>{{ $client->client_name }}</td>

    <td>{{ $client->company_name }}</td>

    <td>{{ $client->phone }}</td>

    <td>{{ $client->status }}</td>


     <td>

    <a href="{{ route('clients.edit', $client->id) }}"
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

    <form action="{{ route('clients.destroy', $client->id) }}"
          method="POST"
          style="display:inline;">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Delete this client record?')"
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


<div style="margin-top:20px;">

    {{ $clients->links() }}

</div>


@endsection