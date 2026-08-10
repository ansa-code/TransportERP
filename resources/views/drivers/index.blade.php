@extends('layouts.app')

@section('content')

<div class="container">

    <h2>Drivers Management</h2>

    <hr>

    <form action="{{ route('drivers.index') }}" method="GET" style="margin-bottom:20px;">

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Search Driver..."
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


    <a href="{{ route('drivers.create') }}"
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
        + Add Driver
    </a>


    <table border="1" cellpadding="10" cellspacing="0" width="100%">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>CNIC</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Action</th>
        </tr>


        @foreach($drivers as $driver)

        <tr>

            <td>{{ $driver->id }}</td>

            <td>{{ $driver->driver_name }}</td>

            <td>{{ $driver->cnic }}</td>

            <td>{{ $driver->phone }}</td>

            <td>{{ $driver->status }}</td>


            <td>

                <a href="{{ route('drivers.edit', $driver->id) }}"
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


                <form action="{{ route('drivers.destroy', $driver->id) }}"
                      method="POST"
                      style="display:inline;">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Delete this driver record?')"
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
        {{ $drivers->links() }}
    </div>

</div>

@endsection