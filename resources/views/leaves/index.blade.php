@extends('layouts.app')

@section('title', 'Leave')

@section('content')

<style>
    .leave-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #101d42;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .add-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 9px;
        background: #101d42;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: .2s ease;
    }

    .add-btn:hover {
        background: #193b8f;
        color: #fff;
        transform: translateY(-1px);
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .kpi-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
    }

    .kpi-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .kpi-value {
        color: #101d42;
        font-size: 25px;
        font-weight: 800;
    }

    .filter-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 300px 220px 220px;
        gap: 12px;
    }

    .filter-input,
    .filter-select {
        width: 100%;
        height: 38px;
        padding: 0 12px;
        border: 1px solid #d8dee8;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
    }

    .table-header {
        padding: 20px 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-title {
        margin: 0;
        color: #101d42;
        font-size: 18px;
        font-weight: 800;
    }

    .table-subtitle {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .leave-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .leave-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .03em;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .leave-table td {
        padding: 15px 16px;
        color: #334155;
        font-size: 13px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
        word-break: break-word;
    }

    .leave-table tr:last-child td {
        border-bottom: none;
    }

    .driver-name {
        color: #101d42;
        font-weight: 750;
    }

    .driver-code {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    .leave-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 750;
    }

    .leave-link:hover {
        text-decoration: underline;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 750;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-approved {
        background: #ecfdf5;
        color: #047857;
    }

    .status-rejected {
        background: #fef2f2;
        color: #b91c1c;
    }

    .status-cancelled {
        background: #f1f5f9;
        color: #475569;
    }

    .replacement-name {
        color: #475569;
        font-weight: 600;
    }

    .replacement-empty {
        color: #94a3b8;
    }

    .actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: nowrap;
        white-space: nowrap;
    }
          
.edit-btn,
.delete-btn {
    flex-shrink: 0;
}

.delete-form {
    margin: 0;
}
    .edit-btn,
    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 62px;
        height: 32px;
        padding: 0 10px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .edit-btn {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    .edit-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .delete-btn {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }

    .delete-form {
        margin: 0;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 14px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 24px;
    }

    .empty-title {
        margin: 0 0 6px;
        color: #101d42;
        font-size: 16px;
        font-weight: 800;
    }

    .empty-text {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .pagination-wrap {
        padding: 18px 22px;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 1100px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .leave-table {
            min-width: 1050px;
        }
    }

    @media (max-width: 700px) {
        .page-header {
            flex-direction: column;
        }

        .add-btn {
            width: 100%;
            justify-content: center;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .leave-table {
            min-width: 950px;
        }
    } 
        
    .clear-btn {
    width: 70px !important;
    height: 38px !important;
    padding: 0 !important;

    display: inline-flex !important;
    align-items: center;
    justify-content: center;

    background: #6c757d;
    color: #fff;

    border-radius: 8px;
    text-decoration: none;

    font-size: 13px;
    font-weight: 700;

    white-space: nowrap;
    flex-shrink: 0;
}

.clear-btn:hover {
    background: #5c636a;
    color: #fff;
}


</style>

<div class="leave-page">

    <div class="page-header">

        <div>
            <h1 class="page-title">Leave</h1>

            <p class="page-subtitle">
                Driver leave records, approvals and replacement tracking
            </p>
        </div>

        <a href="{{ route('leaves.create') }}" class="add-btn">
            <span>＋</span>
            Add Leave
        </a>

    </div>

    @php
        $totalLeaves = \App\Models\Leave::count();

        $pendingLeaves = \App\Models\Leave::where(
            'approval_status',
            'Pending'
        )->count();

        $approvedLeaves = \App\Models\Leave::where(
            'approval_status',
            'Approved'
        )->count();

        $rejectedLeaves = \App\Models\Leave::where(
            'approval_status',
            'Rejected'
        )->count();
    @endphp

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">Total Leave</div>
            <div class="kpi-value">{{ $totalLeaves }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Pending</div>
            <div class="kpi-value">{{ $pendingLeaves }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Approved</div>
            <div class="kpi-value">{{ $approvedLeaves }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Rejected</div>
            <div class="kpi-value">{{ $rejectedLeaves }}</div>
        </div>

    </div>

    <div class="filter-card">

        <form
            method="GET"
            action="{{ route('leaves.index') }}"
            class="filter-form"
        >

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                class="filter-input"
                placeholder="Search driver or leave type..."
            >

            <select
                name="approval_status"
                class="filter-select"
                onchange="this.form.submit()"
            >
                <option value="">All Approval Status</option>

                <option
                    value="Pending"
                    {{ $approvalStatus === 'Pending' ? 'selected' : '' }}
                >
                    Pending
                </option>

                <option
                    value="Approved"
                    {{ $approvalStatus === 'Approved' ? 'selected' : '' }}
                >
                    Approved
                </option>

                <option
                    value="Rejected"
                    {{ $approvalStatus === 'Rejected' ? 'selected' : '' }}
                >
                    Rejected
                </option>

                <option
                    value="Cancelled"
                    {{ $approvalStatus === 'Cancelled' ? 'selected' : '' }}
                >
                    Cancelled
                </option>

            </select>

            <select
                name="leave_type"
                class="filter-select"
                onchange="this.form.submit()"
            >
                <option value="">All Leave Types</option>

                <option
                    value="Annual"
                    {{ $leaveType === 'Annual' ? 'selected' : '' }}
                >
                    Annual
                </option>

                <option
                    value="Sick"
                    {{ $leaveType === 'Sick' ? 'selected' : '' }}
                >
                    Sick
                </option>

                <option
                    value="Emergency"
                    {{ $leaveType === 'Emergency' ? 'selected' : '' }}
                >
                    Emergency
                </option>

                <option
                    value="Unpaid"
                    {{ $leaveType === 'Unpaid' ? 'selected' : '' }}
                >
                    Unpaid
                </option>

                <option
                    value="Other"
                    {{ $leaveType === 'Other' ? 'selected' : '' }}
                >
                    Other
                </option>

            </select>
        
            @if($search || $approvalStatus || $leaveType)

    <a
        href="{{ route('leaves.index') }}"
        class="clear-btn"
    >
        Clear
    </a>

@endif

        </form>

    </div>
    <div class="table-card">

        <div class="table-header">

            <h2 class="table-title">
                Leave Records
            </h2>

            <p class="table-subtitle">
                Driver leave history, approval status and replacement details
            </p>

        </div>

        <div class="table-wrapper">

            <table class="leave-table">

                <thead>
                    <tr>
                        <th style="width: 19%;">Driver</th>
                        <th style="width: 12%;">Leave Type</th>
                        <th style="width: 12%;">Start Date</th>
                        <th style="width: 12%;">End Date</th>
                        <th style="width: 14%;">Approval Status</th>
                        <th style="width: 17%;">Replacement Driver</th>
                        <th style="width: 17%;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($leaves as $leave)

                        <tr>

                            <td>
                                <a
                                    href="{{ route('leaves.show', $leave->id) }}"
                                    class="leave-link"
                                >
                                    <div class="driver-name">
                                        {{ $leave->driver->driver_name ?? 'N/A' }}
                                    </div>

                                    @if($leave->driver)
                                        <div class="driver-code">
                                            {{ $leave->driver->driver_code }}
                                        </div>
                                    @endif
                                </a>
                            </td>

                            <td>
                                <span class="type-badge">
                                    {{ $leave->leave_type }}
                                </span>
                            </td>

                            <td>
                                {{ $leave->start_date?->format('d M Y') ?? '-' }}
                            </td>

                            <td>
                                {{ $leave->end_date?->format('d M Y') ?? '-' }}
                            </td>

                            <td>

                                @php
                                    $statusClass = match ($leave->approval_status) {
                                        'Pending' => 'status-pending',
                                        'Approved' => 'status-approved',
                                        'Rejected' => 'status-rejected',
                                        'Cancelled' => 'status-cancelled',
                                        default => 'status-cancelled',
                                    };
                                @endphp

                                <span class="status-badge {{ $statusClass }}">
                                    {{ $leave->approval_status }}
                                </span>

                            </td>

                            <td>

                                @if($leave->replacementDriver)

                                    <div class="replacement-name">
                                        {{ $leave->replacementDriver->driver_name }}
                                    </div>

                                    <div class="driver-code">
                                        {{ $leave->replacementDriver->driver_code }}
                                    </div>

                                @else

                                    <span class="replacement-empty">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        href="{{ route('leaves.edit', $leave->id) }}"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('leaves.destroy', $leave->id) }}"
                                        method="POST"
                                        class="delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this leave record?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-btn"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        📅
                                    </div>

                                    <h3 class="empty-title">
                                        No Leave Records Found
                                    </h3>

                                    <p class="empty-text">
                                        No leave records match the selected filters.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($leaves->hasPages())

            <div class="pagination-wrap">
                {{ $leaves->links() }}
            </div>

        @endif

    </div>

</div>

@endsection