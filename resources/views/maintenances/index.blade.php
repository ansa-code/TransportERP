@extends('layouts.app')

@section('content')

<h2>Maintenance Management</h2>

<a href="/maintenances/create"
style="
background:#0d6efd;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
display:inline-block;
margin-bottom:20px;
">

+ Add Maintenance

</a>

   <form action="{{ route('maintenances.index') }}" method="GET" style="margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Search Maintenances..."
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
<table
border="1"
cellpadding="10"
cellspacing="0"
width="100%">

<tr>

<th>ID</th>

<th>Vehicle</th>

<th>Service</th>

<th>Cost</th>

<th>Date</th>

<th>Action</th>

</tr>

@foreach($maintenances as $maintenance)

<tr>

<td>{{ $maintenance->id }}</td>

<td>{{ $maintenance->vehicle->vehicle_number }}</td>

<td>{{ $maintenance->service_type }}</td>

<td>{{ $maintenance->cost }}</td>

<td>{{ $maintenance->maintenance_date }}</td>

   <td>

    <a href="{{ route('maintenances.edit', $maintenance->id) }}"
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

    <form action="{{ route('maintenances.destroy', $maintenance->id) }}"
          method="POST"
          style="display:inline;">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Delete this maintenance record?')"
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

{{ $maintenances->links() }}

@endsection