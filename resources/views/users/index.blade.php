@extends('layouts.app')

@section('content')

<style>
    .users-page {
        padding: 28px 32px;
    }

    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .users-title {
        margin: 0 0 5px;
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
    }

    .users-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .users-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 15px;
        border-radius: 7px;
        background: linear-gradient(135deg, #101d42, #193b8f);
        color: #fff !important;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #193b8f;
    }

    .users-add-btn:hover {
        background: #101d42;
        color: #fff !important;
    }

    .users-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .users-table th {
        padding: 14px 18px;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .03em;
        border-bottom: 1px solid #e5e7eb;
    }

    .users-table td {
        padding: 16px 18px;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f7;
    }

    .users-table tbody tr:last-child td {
        border-bottom: none;
    }

    .users-table tbody tr:hover {
        background: #f8fafc;
    }

    .users-table th:nth-child(1),
    .users-table td:nth-child(1) {
        width: 23%;
    }

    .users-table th:nth-child(2),
    .users-table td:nth-child(2) {
        width: 27%;
    }

    .users-table th:nth-child(3),
    .users-table td:nth-child(3) {
        width: 25%;
    }

    .users-table th:nth-child(4),
    .users-table td:nth-child(4) {
        width: 25%;
    }

    .user-name {
        font-weight: 700;
        color: #101d42;
    }

    .user-email {
        color: #475569;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        margin: 2px 4px 2px 0;
        border-radius: 6px;
        background: rgba(37, 99, 235, .30);
        color: #193b8f;
        font-size: 12px;
        font-weight: 600;
    }

    .no-role {
        color: #94a3b8;
        font-size: 13px;
    }

    .users-actions {
        text-align: right !important;
        white-space: nowrap;
    }

    .user-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 6px 11px;
        margin-left: 5px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    /* Edit Button */
    .edit-btn {
        background: rgba(37, 99, 235, .85);
        color: white !important;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .edit-btn:hover {
        background: #2563eb;
        color: white !important;
    }

    /* Delete Button */
    .delete-btn {
        background: rgba(220, 53, 69, .85);
        color: white !important;
        border: none;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #dc3545;
        color: white !important;
    }

    .users-empty {
        padding: 55px 20px !important;
        text-align: center !important;
        color: #64748b;
    }

    .users-empty-btn {
        display: inline-block;
        margin-top: 14px;
        padding: 8px 14px;
        border-radius: 7px;
        background: #193b8f;
        color: #fff !important;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .users-empty-btn:hover {
        background: #101d42;
        color: #fff !important;
    }

    @media (max-width: 768px) {

        .users-page {
            padding: 20px 15px;
        }

        .users-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .users-add-btn {
            width: 100%;
        }

        .users-table {
            table-layout: auto;
        }
    }
         


.user-link {
    display: inline-block;
    color: #101d42;
    text-decoration: none;
    font-weight: 700;
}

.user-link:hover {
    color: #193b8f;
    text-decoration: none;
}
</style>

<div class="users-page">

    <div class="users-header">

        <div>
            <h2 class="users-title">
                Users
            </h2>

            <p class="users-subtitle">
                Manage system users and their assigned roles.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="users-add-btn">
            Add User
        </a>

    </div>

    <div class="users-card">

        <div class="table-responsive">

            <table class="users-table">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="users-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>
                                <a href="{{ route('users.show', $user) }}"
   class="user-name user-link">
    {{ $user->name }}
</a>
                            </td>

                            <td>
                                <div class="user-email">
                                    {{ $user->email }}
                                </div>
                            </td>

                            <td>

                                @forelse($user->roles as $role)

                                    <span class="role-badge">
                                        {{ $role->display_name }}
                                    </span>

                                @empty

                                    <span class="no-role">
                                        No role assigned
                                    </span>

                                @endforelse

                            </td>

                            <td class="users-actions">

                                {{-- Edit --}}
                                <a
                                    href="{{ route('users.edit', $user) }}"
                                    class="user-action edit-btn"
                                >
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form
                                    action="{{ route('users.destroy', $user) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="user-action delete-btn"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="users-empty">

                                No users have been created yet.

                                <br>

                                <a
                                    href="{{ route('users.create') }}"
                                    class="users-empty-btn"
                                >
                                    Create First User
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection