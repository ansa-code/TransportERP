@extends('layouts.app')

@section('content')

<h2>Fuel Management</h2>

<a href="/fuels/create"
style="background:#0d6efd;color:white;padding:10px 18px;text-decoration:none;border-radius:6px;display:inline-block;margin-bottom:20px;">

+ Add Fuel

</a>



    <form action="{{ route('fuels.index') }}" method="GET" style="margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Search fuel..."
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

<table border="1" cellpadding="10" cellspacing="0" width="100%">

<tr>

<th>ID</th>

<th>Vehicle</th>

<th>Driver</th>

<th>Liters</th>

<th>Total</th>

<th>Date</th>

<th>Action</th>

</tr>

@foreach($fuels as $fuel)

<tr>

<td>{{ $fuel->id }}</td>

<td>{{ $fuel->vehicle->vehicle_number }}</td>

<td>{{ $fuel->driver->driver_name }}</td>

<td>{{ $fuel->liters }}</td>

<td>{{ $fuel->total_amount }}</td>

<td>{{ $fuel->fuel_date }}</td>

<td>

    <a href="{{ route('fuels.edit',$fuel->id) }}"
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

    <form action="{{ route('fuels.destroy',$fuel->id) }}"
          method="POST"
          style="display:inline;">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Delete this fuel record?')"
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

{{ $fuels->links() }}

@endsection