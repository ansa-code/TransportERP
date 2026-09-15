@extends('layouts.app')

@section('content')

<style>
    .roles-page {
        padding: 28px 32px;
    }
    
    .roles-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .roles-title {
        margin: 0 0 5px;
        font-size: 28px;
        font-weight: 700;
        color: #101d42;
    }

    .roles-subtitle {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .roles-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 17px;
        border-radius: 8px;
        background: #193b8f;
        color: #fff !important;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: 1px solid #193b8f;
        transition: .2s;
    }

    .roles-add-btn:hover {
        background: #101d42;
        border-color: #101d42;
    }

    .roles-alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .roles-alert-success {
        background: #ecfdf3;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .roles-alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .roles-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .roles-table-wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    .roles-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        min-width: fixed;
    }

    .roles-table thead {
        background: #f8fafc;
    }

    .roles-table th {
        padding: 14px 18px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .03em;
        border-bottom: 1px solid #e5e7eb;
      
    }

    .roles-table td {
        padding: 16px;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
        word-wrap: break-word;
    }

    .roles-table tbody tr:last-child td {
        border-bottom: none;
    }

    .roles-table tbody tr:hover {
        background: #f8fafc;
    }

    .role-name {
        font-weight: 700;
        color: #101d42;
        margin-bottom: 3px;
    }

    .role-key {
        font-size: 12px;
        color: #94a3b8;
        font-family: monospace;
    }

    .role-description {
        max-width: 380px;
        line-height: 1.5;
    }

    .role-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 27px;
        padding: 0 9px;
        border-radius: 20px;
        background: #eef2ff;
        color: #3730a3;
        font-weight: 700;
        font-size: 12px;
    }

    .permission-count {
        background: #e0f2fe;
        color: #0369a1;
    }

    .role-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .role-status-active {
        background: #dcfce7;
        color: #166534;
    }

    .role-status-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .role-actions {
        text-align: right !important;
        white-space: nowrap;
    }

    .role-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 6px 11px;
        margin-left: 5px;
        border-radius: 6px;
        background: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    it {
        color: #2563eb;
        border: 1px solid #bfdbfe;
        background: #fff;
    }

             .role-edit {
    color: #fff;
    border: 1px solid #193b8f;
    background: linear-gradient(135deg, #101d42, #193b8f);
}

.role-edit:hover {
    color: #fff;
    background: #101d42;
}

.role-toggle {
    color: #fff;
    border: 1px solid #2563eb;
    background: rgba(37, 99, 235, .30);
}

.role-toggle:hover {
    color: #fff;
    background: #2563eb;
}

.role-delete {
    color: #fff;
    border: 1px solid #dc2626;
    background: #dc2626;
}

.role-delete:hover {
    color: #fff;
    background: #b91c1c;
}

    .roles-empty {
        text-align: center !important;
        padding: 55px 20px !important;
        color: #64748b;
    }

    .roles-empty-btn {
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

    @media (max-width: 768px) {

        .roles-page {
            padding: 20px 15px;
        }

        .roles-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .roles-add-btn {
            width: 100%;
            justify-content: center;
        }

        .roles-title {
            font-size: 23px;
        }
    }
       .role-name {
    font-weight: 700;
    color: #101d42;
}

.role-link {
    display: inline-block;
    color: #101d42;
    text-decoration: none;
    font-weight: 700;
}

.role-link:hover {
    color: #193b8f;
    text-decoration: none;
}
</style>

<div class="roles-page">

    <div class="roles-header">

        <div>
            <h2 class="roles-title">
                Roles & Permissions
            </h2>

            <p class="roles-subtitle">
                Manage system roles and their assigned permissions.
            </p>
        </div>

        <a href="{{ route('roles.create') }}"
           class="roles-add-btn">
            <span>+</span>
            Add Role
        </a>

    </div>

    <div class="roles-card">

        <div class="roles-table-wrapper">

            <table class="roles-table">

                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Description</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th class="role-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($roles as $role)

                        <tr>

                            <td>
                                <a href="{{ route('roles.show', $role) }}"
   class="role-name role-link">
    {{ $role->display_name }}
</a>
                                <div class="role-key">
                                    {{ $role->name }}
                                </div>
                            </td>

                            <td>
                                <div class="role-description">
                                    {{ $role->description ?: '—' }}
                                </div>
                            </td>

                            <td>
                                <span class="role-count">
                                    {{ $role->users_count }}
                                </span>
                            </td>

                            <td>
                                <span class="role-count permission-count">
                                    {{ $role->permissions->count() }}
                                </span>
                            </td>

                            <td>

                                @if($role->is_active)

                                    <span class="role-status role-status-active">
                                        Active
                                    </span>

                                @else

                                    <span class="role-status role-status-inactive">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="role-actions">

                                <a href="{{ route('roles.edit', $role) }}"
                                   class="role-action role-edit">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('roles.toggle-status', $role) }}"
                                    method="POST"
                                    style="display:inline;"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="role-action role-toggle"
                                    >
                                        {{ $role->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form
                                    action="{{ route('roles.destroy', $role) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this role?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="role-action role-delete"
                                    >
                                        Delete
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="roles-empty">

                                No roles have been created yet.

                                <br>

                                <a
                                    href="{{ route('roles.create') }}"
                                    class="roles-empty-btn"
                                >
                                    Create First Role
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