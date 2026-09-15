@extends('layouts.app')

@section('content')

<style>

/* =========================================================
   ASSIGNMENT CENTER
========================================================= */

.assignment-page {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
}


/* =========================================================
   HEADER
========================================================= */

.assignment-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 24px;
}

.assignment-title h1 {
    margin: 0;
    color: #14213d;
    font-size: 28px;
    font-weight: 800;
}

.assignment-title p {
    margin: 6px 0 0;
    color: #6c757d;
    font-size: 13px;
}

.create-assignment-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 18px;
    background: #0d6efd;
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(13,110,253,.18);
}

.create-assignment-btn:hover {
    background: #0b5ed7;
    color: #ffffff;
}


/* =========================================================
   FILTER BAR
========================================================= */

.assignment-filters {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.assignment-search {
    height: 40px;
    min-width: 300px;
    flex: 1;
    max-width: 450px;
    padding: 0 14px;
    border: 1px solid #dbe3ef;
    border-radius: 12px;
    background: #f8fafc;
    color: #334155;
    font-size: 13px;
    font-weight: 600;
    outline: none;
    box-sizing: border-box;
}

.assignment-search:focus {
    border-color: #0d6efd;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(13,110,253,.10);
}

.assignment-select {
    height: 40px;
    min-width: 142px;
    padding: 0 12px;
    border: 1px solid #dbe3ef;
    border-radius: 12px;
    background: #ffffff;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
    outline: none;
    cursor: pointer;
}

.assignment-select:focus {
    border-color: #0d6efd;
}

.assignment-filter-btn {
    height: 40px;
    padding: 0 16px;
    border: none;
    border-radius: 8px;
    background: #198754;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.assignment-filter-btn:hover {
    background: #157347;
}

.assignment-clear-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    padding: 0 14px;
    border-radius: 8px;
    background: #6c757d;
    color: #ffffff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
}

.assignment-clear-btn:hover {
    background: #5c636a;
    color: #ffffff;
}


/* =========================================================
   KANBAN BOARD
========================================================= */

.assignment-board {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    width: 100%;
}


/* =========================================================
   BOARD LANE
========================================================= */

.assignment-lane {
    min-width: 0;
    min-height: 520px;
    padding: 14px;
    background: #eef4fb;
    border: 1px solid #dde7f3;
    border-radius: 22px;
    box-sizing: border-box;
}

.assignment-lane-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    padding: 0 2px;
}

.assignment-lane-title {
    color: #334155;
    font-size: 14px;
    font-weight: 800;
}

.assignment-lane-count {
    color: #475569;
    font-size: 14px;
    font-weight: 800;
}


/* =========================================================
   ASSIGNMENT CARD
========================================================= */

.assignment-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 14px;
    margin-bottom: 12px;
    box-shadow: 0 10px 24px rgba(15,23,42,.06);
    transition: transform .15s ease, box-shadow .15s ease;
}

.assignment-card:last-child {
    margin-bottom: 0;
}

.assignment-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 13px 28px rgba(15,23,42,.09);
}

.assignment-card-title {
    margin: 0 0 8px;
    color: #1e293b;
    font-size: 14px;
    font-weight: 800;
    line-height: 1.4;
}

.assignment-card-info {
    margin: 0;
    color: #64748b;
    font-size: 12px;
    line-height: 1.55;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.assignment-empty {
    padding: 35px 10px;
    text-align: center;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
}


/* =========================================================
   STATUS
========================================================= */

.assignment-status {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
    margin-top: 10px;
}

.status-planned {
    background: #fef3c7;
    color: #b45309;
}

.status-active {
    background: #dcfce7;
    color: #15803d;
}

.status-suspended {
    background: #fee2e2;
    color: #b91c1c;
}

.status-completed {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-cancelled {
    background: #e2e3e5;
    color: #41464b;
}


/* =========================================================
   CARD ACTIONS
========================================================= */

.assignment-card-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
}

.assignment-edit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 30px;
    padding: 0 10px;
    background: #0d6efd;
    color: #ffffff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.assignment-edit-btn:hover {
    background: #0b5ed7;
    color: #ffffff;
}

.assignment-delete-btn {
    height: 30px;
    padding: 0 10px;
    background: #dc3545;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    cursor: pointer;
}

.assignment-delete-btn:hover {
    background: #bb2d3b;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .assignment-board {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 700px) {

    .assignment-header {
        flex-direction: column;
    }

    .create-assignment-btn {
        width: 100%;
    }

    .assignment-filters {
        flex-direction: column;
        align-items: stretch;
    }

    .assignment-search {
        width: 100%;
        max-width: none;
        min-width: 0;
    }

    .assignment-select,
    .assignment-filter-btn,
    .assignment-clear-btn {
        width: 100%;
    }

    .assignment-board {
        grid-template-columns: 1fr;
    }

}

</style>


<div class="assignment-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="assignment-header">

        <div class="assignment-title">

            <h1>
                Assignment Center
            </h1>

            <p>
                Allocate trucks and drivers to clients with billing and cost controls
            </p>

        </div>


        <a
            href="{{ route('assignments.create') }}"
            class="create-assignment-btn"
        >
            Create Assignment
        </a>

    </div>


    {{-- =====================================================
         FILTERS
    ====================================================== --}}

    <form
    action="{{ route('assignments.index') }}"
    method="GET"
    class="assignment-filters"
    id="assignmentFilterForm"
>

        {{-- Search --}}

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            class="assignment-search"
            placeholder="🔍 Search assignment, client, truck or driver..."
        >


        {{-- Client --}}

        <select
            name="client"
            class="assignment-select"
            onchange="document.getElementById('assignmentFilterForm').submit()"
>
        >

            <option value="">
                Client 
            </option>

            @foreach($clients as $client)

                <option
                    value="{{ $client->id }}"
                    {{ $clientId == $client->id ? 'selected' : '' }}
                >
                    {{ $client->client_name }}
                </option>

            @endforeach

        </select>


        {{-- Truck Type --}}

        <select
            name="vehicle_type"
            class="assignment-select"
            onchange="document.getElementById('assignmentFilterForm').submit()"
>
        >

            <option value="">
                Truck Type 
            </option>

            @foreach($vehicleTypes as $type)

                <option
                    value="{{ $type }}"
                    {{ $vehicleType == $type ? 'selected' : '' }}
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>


        {{-- Rate Type --}}

        <select
            name="rate_type"
            class="assignment-select"
            onchange="document.getElementById('assignmentFilterForm').submit()"
>
        >

            <option value="">
                Rate Type 
            </option>

            @foreach($rateBases as $rate)

                <option
                    value="{{ $rate }}"
                    {{ $rateBasis == $rate ? 'selected' : '' }}
                >
                    {{ $rate }}
                </option>

            @endforeach

        </select>


       


        {{-- Clear Button --}}

        @if(
            $search ||
            $clientId ||
            $vehicleType ||
            $rateBasis
        )

            <a
                href="{{ route('assignments.index') }}"
                class="assignment-clear-btn"
            >
                Clear
            </a>

        @endif

    </form>


    {{-- =====================================================
         ASSIGNMENT BOARD
    ====================================================== --}}

    <div class="assignment-board">


        {{-- =================================================
             PLANNED
        ================================================== --}}

        <div class="assignment-lane">

            <div class="assignment-lane-header">

                <div class="assignment-lane-title">
                    Planned
                </div>

                <div class="assignment-lane-count">
                    {{ $plannedAssignments->count() }}
                </div>

            </div>


            @forelse($plannedAssignments as $assignment)

                <div class="assignment-card">

                    <h4 class="assignment-card-title">
                        {{ $assignment->assignment_no ?? 'Assignment' }}
                    </h4>


                    <p class="assignment-card-info">

                        Client:
                        {{ $assignment->client?->client_name ?? '-' }}

                        <br>

                        Truck:
                        {{ $assignment->vehicle?->plate_number ?? '-' }}

                        <br>

                        Driver:
                        {{ $assignment->driver?->driver_name ?? '-' }}

                    </p>


                    <span class="assignment-status status-planned">
                        Planned
                    </span>


                    <div class="assignment-card-actions">

                        <a
                            href="{{ route('assignments.edit', $assignment->id) }}"
                            class="assignment-edit-btn"
                        >
                            Edit
                        </a>


                        <form
                            action="{{ route('assignments.destroy', $assignment->id) }}"
                            method="POST"
                            style="margin:0;"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="assignment-delete-btn"
                                onclick="return confirm('Delete this assignment record?')"
                            >
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="assignment-empty">
                    No planned assignments.
                </div>

            @endforelse

        </div>


        {{-- =================================================
             ACTIVE
        ================================================== --}}

        <div class="assignment-lane">

            <div class="assignment-lane-header">

                <div class="assignment-lane-title">
                    Active
                </div>

                <div class="assignment-lane-count">
                    {{ $activeAssignments->count() }}
                </div>

            </div>


            @forelse($activeAssignments as $assignment)

                <div class="assignment-card">

                    <h4 class="assignment-card-title">
                        {{ $assignment->assignment_no ?? 'Assignment' }}
                    </h4>


                    <p class="assignment-card-info">

                        Client:
                        {{ $assignment->client?->client_name ?? '-' }}

                        <br>

                        Truck:
                        {{ $assignment->vehicle?->plate_number ?? '-' }}

                        <br>

                        Driver:
                        {{ $assignment->driver?->driver_name ?? '-' }}

                    </p>


                    <span class="assignment-status status-active">
                        Active
                    </span>


                    <div class="assignment-card-actions">

                        <a
                            href="{{ route('assignments.edit', $assignment->id) }}"
                            class="assignment-edit-btn"
                        >
                            Edit
                        </a>


                        <form
                            action="{{ route('assignments.destroy', $assignment->id) }}"
                            method="POST"
                            style="margin:0;"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="assignment-delete-btn"
                                onclick="return confirm('Delete this assignment record?')"
                            >
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="assignment-empty">
                    No active assignments.
                </div>

            @endforelse

        </div>
        {{-- =================================================
             SUSPENDED
        ================================================== --}}

        <div class="assignment-lane">

            <div class="assignment-lane-header">

                <div class="assignment-lane-title">
                    Suspended
                </div>

                <div class="assignment-lane-count">
                    {{ $suspendedAssignments->count() }}
                </div>

            </div>


            @forelse($suspendedAssignments as $assignment)

                <div class="assignment-card">

                    <h4 class="assignment-card-title">
                        {{ $assignment->assignment_no ?? 'Assignment' }}
                    </h4>


                    <p class="assignment-card-info">

                        Client:
                        {{ $assignment->client?->client_name ?? '-' }}

                        <br>

                        Truck:
                        {{ $assignment->vehicle?->plate_number ?? '-' }}

                        <br>

                        Driver:
                        {{ $assignment->driver?->driver_name ?? '-' }}

                    </p>


                    <span class="assignment-status status-suspended">
                        Suspended
                    </span>


                    <div class="assignment-card-actions">

                        <a
                            href="{{ route('assignments.edit', $assignment->id) }}"
                            class="assignment-edit-btn"
                        >
                            Edit
                        </a>


                        <form
                            action="{{ route('assignments.destroy', $assignment->id) }}"
                            method="POST"
                            style="margin:0;"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="assignment-delete-btn"
                                onclick="return confirm('Delete this assignment record?')"
                            >
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="assignment-empty">
                    No suspended assignments.
                </div>

            @endforelse

        </div>


        {{-- =================================================
             COMPLETED
        ================================================== --}}

        <div class="assignment-lane">

            <div class="assignment-lane-header">

                <div class="assignment-lane-title">
                    Completed
                </div>

                <div class="assignment-lane-count">
                    {{ $completedAssignments->count() }}
                </div>

            </div>


            @forelse($completedAssignments as $assignment)

                <div class="assignment-card">

                    <h4 class="assignment-card-title">
                        {{ $assignment->assignment_no ?? 'Assignment' }}
                    </h4>


                    <p class="assignment-card-info">

                        Client:
                        {{ $assignment->client?->client_name ?? '-' }}

                        <br>

                        Truck:
                        {{ $assignment->vehicle?->plate_number ?? '-' }}

                        <br>

                        Driver:
                        {{ $assignment->driver?->driver_name ?? '-' }}

                    </p>


                    <span class="assignment-status status-completed">
                        Completed
                    </span>


                    <div class="assignment-card-actions">

                        <a
                            href="{{ route('assignments.edit', $assignment->id) }}"
                            class="assignment-edit-btn"
                        >
                            Edit
                        </a>


                        <form
                            action="{{ route('assignments.destroy', $assignment->id) }}"
                            method="POST"
                            style="margin:0;"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="assignment-delete-btn"
                                onclick="return confirm('Delete this assignment record?')"
                            >
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="assignment-empty">
                    No completed assignments.
                </div>

            @endforelse

        </div>


        {{-- =================================================
             CANCELLED
        ================================================== --}}

        <div
            class="assignment-lane"
            style="
                margin-top:16px;
                min-height:auto;
            "
        >

            <div class="assignment-lane-header">

                <div class="assignment-lane-title">
                    Cancelled
                </div>

                <div class="assignment-lane-count">
                    {{ $cancelledAssignments->count() }}
                </div>

            </div>


            @forelse($cancelledAssignments as $assignment)

                <div class="assignment-card">

                    <h4 class="assignment-card-title">
                        {{ $assignment->assignment_no ?? 'Assignment' }}
                    </h4>


                    <p class="assignment-card-info">

                        Client:
                        {{ $assignment->client?->client_name ?? '-' }}

                        <br>

                        Truck:
                        {{ $assignment->vehicle?->plate_number ?? '-' }}

                        <br>

                        Driver:
                        {{ $assignment->driver?->driver_name ?? '-' }}

                    </p>


                    <span class="assignment-status status-cancelled">
                        Cancelled
                    </span>


                    <div class="assignment-card-actions">

                        <a
                            href="{{ route('assignments.edit', $assignment->id) }}"
                            class="assignment-edit-btn"
                        >
                            Edit
                        </a>


                        <form
                            action="{{ route('assignments.destroy', $assignment->id) }}"
                            method="POST"
                            style="margin:0;"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="assignment-delete-btn"
                                onclick="return confirm('Delete this assignment record?')"
                            >
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="assignment-empty">
                    No cancelled assignments.
                </div>

            @endforelse

        </div>

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if(method_exists($assignments, 'links'))

        <div style="margin-top:20px;">
            {{ $assignments->links() }}
        </div>

    @endif


</div>

@endsection