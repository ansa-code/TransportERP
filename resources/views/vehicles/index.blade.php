@extends('layouts.app')

@section('content')

<div class="container">

    <h2>Fleet Management</h2>

    <hr>

    <form action="{{ route('vehicles.index') }}" method="GET" style="margin-bottom:20px;">

        <input
            type="text"
            name="search"
            placeholder="Search Vehicle..."
            value="{{ $search }}"
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


    <a href="{{ route('vehicles.create') }}"
       style="
       background:#0d6efd;
       color:white;
       padding:10px 18px;
       text-decoration:none;
       border-radius:6px;
       font-weight:bold;
       margin-bottom:20px;
       display:inline-block;
       ">
        + Add Vehicle
    </a>


    <table border="1" cellpadding="10" cellspacing="0" width="100%">

        <tr>
            <th>ID</th>
            <th>Vehicle No</th>
            <th>Plate</th>
            <th>Type</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Status</th>
            <th>Action</th>
        </tr>


        @foreach($vehicles as $vehicle)

        <tr>

            <td>{{ $vehicle->id }}</td>

            <td>{{ $vehicle->vehicle_number }}</td>

            <td>{{ $vehicle->plate_number }}</td>

            <td>{{ $vehicle->vehicle_type }}</td>

            <td>{{ $vehicle->brand }}</td>

            <td>{{ $vehicle->model }}</td>

            <td>{{ $vehicle->status }}</td>

            <td>

                <a href="{{ route('vehicles.edit', $vehicle->id) }}"
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


                <form action="{{ route('vehicles.destroy', $vehicle->id) }}"
                      method="POST"
                      style="display:inline;">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Delete this vehicle record?')"
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

    {{ $vehicles->links() }}

</div>

@endsection