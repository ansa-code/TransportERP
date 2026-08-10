@extends('layouts.app')

@section('content')

<h2>Assignment Management</h2>

 <form action="{{ route('assignments.index') }}" method="GET" style="margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Search Assignment..."
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


<a href="/assignments/create"
style="
background:#0d6efd;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
display:inline-block;
margin-bottom:20px;
">

+ Add Assignment

</a>





<table border="1" cellpadding="10" cellspacing="0" width="100%">

<tr>
    <th>ID</th>
    <th>Client</th>
    <th>Vehicle</th>
    <th>Driver</th>
    <th>Pickup</th>
    <th>Drop</th>
    <th>Status</th>
    <th>Action</th>
</tr>


@foreach($assignments as $assignment)

<tr>

    <td>{{ $assignment->id }}</td>

    <td>{{ $assignment->client->client_name }}</td>

    <td>{{ $assignment->vehicle->vehicle_number }}</td>

    <td>{{ $assignment->driver->driver_name }}</td>

    <td>{{ $assignment->pickup_location }}</td>

    <td>{{ $assignment->drop_location }}</td>

    <td>{{ $assignment->status }}</td>

   <td>

    <a href="{{ route('assignments.edit', $assignment->id) }}"
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

    <form action="{{ route('assignments.destroy', $assignment->id) }}"
          method="POST"
          style="display:inline;">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Delete this assignment record?')"
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

{{ $assignments->links() }}


@endsection
