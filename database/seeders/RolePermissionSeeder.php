<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Dashboard
            [
                'name' => 'dashboard.view',
                'display_name' => 'View Dashboard',
                'module' => 'Dashboard',
                'action' => 'view',
            ],

            // Vehicles
            [
                'name' => 'vehicles.view',
                'display_name' => 'View Vehicles',
                'module' => 'Vehicles',
                'action' => 'view',
            ],
            [
                'name' => 'vehicles.create',
                'display_name' => 'Create Vehicles',
                'module' => 'Vehicles',
                'action' => 'create',
            ],
            [
                'name' => 'vehicles.edit',
                'display_name' => 'Edit Vehicles',
                'module' => 'Vehicles',
                'action' => 'edit',
            ],
            [
                'name' => 'vehicles.delete',
                'display_name' => 'Delete Vehicles',
                'module' => 'Vehicles',
                'action' => 'delete',
            ],

            // Drivers
            [
                'name' => 'drivers.view',
                'display_name' => 'View Drivers',
                'module' => 'Drivers',
                'action' => 'view',
            ],
            [
                'name' => 'drivers.create',
                'display_name' => 'Create Drivers',
                'module' => 'Drivers',
                'action' => 'create',
            ],
            [
                'name' => 'drivers.edit',
                'display_name' => 'Edit Drivers',
                'module' => 'Drivers',
                'action' => 'edit',
            ],
            [
                'name' => 'drivers.delete',
                'display_name' => 'Delete Drivers',
                'module' => 'Drivers',
                'action' => 'delete',
            ],

            // Clients
            [
                'name' => 'clients.view',
                'display_name' => 'View Clients',
                'module' => 'Clients',
                'action' => 'view',
            ],
            [
                'name' => 'clients.create',
                'display_name' => 'Create Clients',
                'module' => 'Clients',
                'action' => 'create',
            ],
            [
                'name' => 'clients.edit',
                'display_name' => 'Edit Clients',
                'module' => 'Clients',
                'action' => 'edit',
            ],
            [
                'name' => 'clients.delete',
                'display_name' => 'Delete Clients',
                'module' => 'Clients',
                'action' => 'delete',
            ],

            // Vendors
            [
                'name' => 'vendors.view',
                'display_name' => 'View Vendors',
                'module' => 'Vendors',
                'action' => 'view',
            ],
            [
                'name' => 'vendors.create',
                'display_name' => 'Create Vendors',
                'module' => 'Vendors',
                'action' => 'create',
            ],
            [
                'name' => 'vendors.edit',
                'display_name' => 'Edit Vendors',
                'module' => 'Vendors',
                'action' => 'edit',
            ],
            [
                'name' => 'vendors.delete',
                'display_name' => 'Delete Vendors',
                'module' => 'Vendors',
                'action' => 'delete',
            ],

            // Assignments
            [
                'name' => 'assignments.view',
                'display_name' => 'View Assignments',
                'module' => 'Assignments',
                'action' => 'view',
            ],
            [
                'name' => 'assignments.create',
                'display_name' => 'Create Assignments',
                'module' => 'Assignments',
                'action' => 'create',
            ],
            [
                'name' => 'assignments.edit',
                'display_name' => 'Edit Assignments',
                'module' => 'Assignments',
                'action' => 'edit',
            ],
            [
                'name' => 'assignments.delete',
                'display_name' => 'Delete Assignments',
                'module' => 'Assignments',
                'action' => 'delete',
            ],

            // Trips
            [
                'name' => 'trips.view',
                'display_name' => 'View Trips',
                'module' => 'Trips',
                'action' => 'view',
            ],
            [
                'name' => 'trips.create',
                'display_name' => 'Create Trips',
                'module' => 'Trips',
                'action' => 'create',
            ],
            [
                'name' => 'trips.edit',
                'display_name' => 'Edit Trips',
                'module' => 'Trips',
                'action' => 'edit',
            ],
            [
                'name' => 'trips.delete',
                'display_name' => 'Delete Trips',
                'module' => 'Trips',
                'action' => 'delete',
            ],

            // Documents
            [
                'name' => 'documents.view',
                'display_name' => 'View Documents',
                'module' => 'Documents',
                'action' => 'view',
            ],
            [
                'name' => 'documents.create',
                'display_name' => 'Upload Documents',
                'module' => 'Documents',
                'action' => 'create',
            ],
            [
                'name' => 'documents.edit',
                'display_name' => 'Replace Documents',
                'module' => 'Documents',
                'action' => 'edit',
            ],
            [
                'name' => 'documents.delete',
                'display_name' => 'Delete Documents',
                'module' => 'Documents',
                'action' => 'delete',
            ],

            // Maintenance
            [
                'name' => 'maintenance.view',
                'display_name' => 'View Maintenance',
                'module' => 'Maintenance',
                'action' => 'view',
            ],
            [
                'name' => 'maintenance.create',
                'display_name' => 'Create Maintenance',
                'module' => 'Maintenance',
                'action' => 'create',
            ],
            [
                'name' => 'maintenance.edit',
                'display_name' => 'Edit Maintenance',
                'module' => 'Maintenance',
                'action' => 'edit',
            ],
            [
                'name' => 'maintenance.delete',
                'display_name' => 'Delete Maintenance',
                'module' => 'Maintenance',
                'action' => 'delete',
            ],
            // Fuel
            [
                'name' => 'fuel.view',
                'display_name' => 'View Fuel',
                'module' => 'Fuel',
                'action' => 'view',
            ],
            [
                'name' => 'fuel.create',
                'display_name' => 'Create Fuel',
                'module' => 'Fuel',
                'action' => 'create',
            ],
            [
                'name' => 'fuel.edit',
                'display_name' => 'Edit Fuel',
                'module' => 'Fuel',
                'action' => 'edit',
            ],
            [
                'name' => 'fuel.delete',
                'display_name' => 'Delete Fuel',
                'module' => 'Fuel',
                'action' => 'delete',
            ],

            // Expenses
            [
                'name' => 'expenses.view',
                'display_name' => 'View Expenses',
                'module' => 'Expenses',
                'action' => 'view',
            ],
            [
                'name' => 'expenses.create',
                'display_name' => 'Create Expenses',
                'module' => 'Expenses',
                'action' => 'create',
            ],
            [
                'name' => 'expenses.edit',
                'display_name' => 'Edit Expenses',
                'module' => 'Expenses',
                'action' => 'edit',
            ],
            [
                'name' => 'expenses.delete',
                'display_name' => 'Delete Expenses',
                'module' => 'Expenses',
                'action' => 'delete',
            ],

            // Maintenance
            [
                'name' => 'maintenance.view',
                'display_name' => 'View Maintenance',
                'module' => 'Maintenance',
                'action' => 'view',
            ],
            [
                'name' => 'maintenance.create',
                'display_name' => 'Create Maintenance',
                'module' => 'Maintenance',
                'action' => 'create',
            ],
            [
                'name' => 'maintenance.edit',
                'display_name' => 'Edit Maintenance',
                'module' => 'Maintenance',
                'action' => 'edit',
            ],
            [
                'name' => 'maintenance.delete',
                'display_name' => 'Delete Maintenance',
                'module' => 'Maintenance',
                'action' => 'delete',
            ],

            // Invoices
            [
                'name' => 'invoices.view',
                'display_name' => 'View Invoices',
                'module' => 'Invoices',
                'action' => 'view',
            ],
            [
                'name' => 'invoices.create',
                'display_name' => 'Create Invoices',
                'module' => 'Invoices',
                'action' => 'create',
            ],
            [
                'name' => 'invoices.edit',
                'display_name' => 'Edit Invoices',
                'module' => 'Invoices',
                'action' => 'edit',
            ],
            [
                'name' => 'invoices.delete',
                'display_name' => 'Delete Invoices',
                'module' => 'Invoices',
                'action' => 'delete',
            ],

            // Payments
            [
                'name' => 'payments.view',
                'display_name' => 'View Payments',
                'module' => 'Payments',
                'action' => 'view',
            ],
            [
                'name' => 'payments.create',
                'display_name' => 'Create Payments',
                'module' => 'Payments',
                'action' => 'create',
            ],
            [
                'name' => 'payments.edit',
                'display_name' => 'Edit Payments',
                'module' => 'Payments',
                'action' => 'edit',
            ],
            [
                'name' => 'payments.delete',
                'display_name' => 'Delete Payments',
                'module' => 'Payments',
                'action' => 'delete',
            ],

            // Payroll
            [
                'name' => 'payroll.view',
                'display_name' => 'View Payroll',
                'module' => 'Payroll',
                'action' => 'view',
            ],
            [
                'name' => 'payroll.create',
                'display_name' => 'Create Payroll',
                'module' => 'Payroll',
                'action' => 'create',
            ],
            [
                'name' => 'payroll.edit',
                'display_name' => 'Edit Payroll',
                'module' => 'Payroll',
                'action' => 'edit',
            ],
            [
                'name' => 'payroll.delete',
                'display_name' => 'Delete Payroll',
                'module' => 'Payroll',
                'action' => 'delete',
            ],

            // Traffic Fines
            [
                'name' => 'traffic_fines.view',
                'display_name' => 'View Traffic Fines',
                'module' => 'Traffic Fines',
                'action' => 'view',
            ],
            [
                'name' => 'traffic_fines.create',
                'display_name' => 'Create Traffic Fines',
                'module' => 'Traffic Fines',
                'action' => 'create',
            ],
            [
                'name' => 'traffic_fines.edit',
                'display_name' => 'Edit Traffic Fines',
                'module' => 'Traffic Fines',
                'action' => 'edit',
            ],
            [
                'name' => 'traffic_fines.delete',
                'display_name' => 'Delete Traffic Fines',
                'module' => 'Traffic Fines',
                'action' => 'delete',
            ],

            // Driver Advances
            [
                'name' => 'driver_advances.view',
                'display_name' => 'View Driver Advances',
                'module' => 'Driver Advances',
                'action' => 'view',
            ],
            [
                'name' => 'driver_advances.create',
                'display_name' => 'Create Driver Advances',
                'module' => 'Driver Advances',
                'action' => 'create',
            ],
            [
                'name' => 'driver_advances.edit',
                'display_name' => 'Edit Driver Advances',
                'module' => 'Driver Advances',
                'action' => 'edit',
            ],
            [
                'name' => 'driver_advances.delete',
                'display_name' => 'Delete Driver Advances',
                'module' => 'Driver Advances',
                'action' => 'delete',
            ],

            // Leave
            [
                'name' => 'leaves.view',
                'display_name' => 'View Leave',
                'module' => 'Leave',
                'action' => 'view',
            ],
            [
                'name' => 'leaves.create',
                'display_name' => 'Create Leave',
                'module' => 'Leave',
                'action' => 'create',
            ],
            [
                'name' => 'leaves.edit',
                'display_name' => 'Edit Leave',
                'module' => 'Leave',
                'action' => 'edit',
            ],
            [
                'name' => 'leaves.delete',
                'display_name' => 'Delete Leave',
                'module' => 'Leave',
                'action' => 'delete',
            ],

            // Reports
            [
                'name' => 'reports.view',
                'display_name' => 'View Reports',
                'module' => 'Reports',
                'action' => 'view',
            ],
            [
                'name' => 'reports.export',
                'display_name' => 'Export Reports',
                'module' => 'Reports',
                'action' => 'export',
            ],

            // Alerts
            [
                'name' => 'alerts.view',
                'display_name' => 'View Alerts',
                'module' => 'Alerts',
                'action' => 'view',
            ],
            [
                'name' => 'alerts.manage',
                'display_name' => 'Manage Alerts',
                'module' => 'Alerts',
                'action' => 'manage',
            ],

            // Users
            [
                'name' => 'users.view',
                'display_name' => 'View Users',
                'module' => 'Users',
                'action' => 'view',
            ],
            [
                'name' => 'users.create',
                'display_name' => 'Create Users',
                'module' => 'Users',
                'action' => 'create',
            ],
            [
                'name' => 'users.edit',
                'display_name' => 'Edit Users',
                'module' => 'Users',
                'action' => 'edit',
            ],
            [
                'name' => 'users.delete',
                'display_name' => 'Delete Users',
                'module' => 'Users',
                'action' => 'delete',
            ],

            // Roles & Permissions
            [
                'name' => 'roles.view',
                'display_name' => 'View Roles',
                'module' => 'Roles & Permissions',
                'action' => 'view',
            ],
            [
                'name' => 'roles.create',
                'display_name' => 'Create Roles',
                'module' => 'Roles & Permissions',
                'action' => 'create',
            ],
            [
                'name' => 'roles.edit',
                'display_name' => 'Edit Roles',
                'module' => 'Roles & Permissions',
                'action' => 'edit',
            ],
            [
                'name' => 'roles.delete',
                'display_name' => 'Delete Roles',
                'module' => 'Roles & Permissions',
                'action' => 'delete',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                array_merge($permission, [
                    'is_active' => true,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FRD Roles
        |--------------------------------------------------------------------------
        */

        $roles = [
            'super-admin' => [
                'display_name' => 'Super Admin',
                'description' => 'Full system access including administration and sensitive reports.',
            ],

            'admin' => [
                'display_name' => 'Admin',
                'description' => 'Administrative access to operational system functions.',
            ],

            'operations' => [
                'display_name' => 'Operations',
                'description' => 'Operational access for vehicles, drivers, assignments, trips and maintenance.',
            ],

            'hr' => [
                'display_name' => 'HR',
                'description' => 'Human resources access including drivers, leave and payroll-related functions.',
            ],

            'accounts' => [
                'display_name' => 'Accounts',
                'description' => 'Finance and accounting access including invoices, payments, payroll and reports.',
            ],

            'data-entry' => [
                'display_name' => 'Data Entry',
                'description' => 'Controlled data entry access for approved operational records.',
            ],

            'portal' => [
                'display_name' => 'Portal',
                'description' => 'Read-only customer portal access with restricted client visibility.',
            ],
        ];

        foreach ($roles as $name => $roleData) {
            Role::updateOrCreate(
                ['name' => $name],
                array_merge($roleData, [
                    'is_active' => true,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Role Permission Mapping
        |--------------------------------------------------------------------------
        */

        $allPermissions = Permission::where('is_active', true)
            ->pluck('id', 'name');

        $superAdmin = Role::where('name', 'super-admin')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync($allPermissions->values()->all());
        }

        $adminPermissions = [
            'dashboard.view',

            'vehicles.view',
            'vehicles.create',
            'vehicles.edit',
            'vehicles.delete',

            'drivers.view',
            'drivers.create',
            'drivers.edit',
            'drivers.delete',

            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',

            'vendors.view',
            'vendors.create',
            'vendors.edit',
            'vendors.delete',

            'assignments.view',
            'assignments.create',
            'assignments.edit',
            'assignments.delete',

            'trips.view',
            'trips.create',
            'trips.edit',
            'trips.delete',

            'documents.view',
            'documents.create',
            'documents.edit',
            'documents.delete',

            'maintenance.view',
            'maintenance.create',
            'maintenance.edit',
            'maintenance.delete',

            'fuel.view',
            'fuel.create',
            'fuel.edit',
            'fuel.delete',

            'expenses.view',
            'expenses.create',
            'expenses.edit',
            'expenses.delete',

            'invoices.view',
            'invoices.create',
            'invoices.edit',
            'invoices.delete',

            'payroll.view',
            'payroll.create',
            'payroll.edit',

            'traffic_fines.view',
            'traffic_fines.create',
            'traffic_fines.edit',

            'driver_advances.view',
            'driver_advances.create',
            'driver_advances.edit',

            'leaves.view',
            'leaves.create',
            'leaves.edit',

            'reports.view',
            'alerts.view',
        ];

        Role::where('name', 'admin')
            ->first()
            ?->permissions()
            ->sync($this->permissionIds($allPermissions, $adminPermissions));

        $operationsPermissions = [
            'dashboard.view',

            'vehicles.view',
            'vehicles.create',
            'vehicles.edit',

            'drivers.view',
            'drivers.create',
            'drivers.edit',

            'clients.view',
            'vendors.view',

            'assignments.view',
            'assignments.create',
            'assignments.edit',

            'trips.view',
            'trips.create',
            'trips.edit',

            'documents.view',
            'documents.create',
            'documents.edit',

            'maintenance.view',
            'maintenance.create',
            'maintenance.edit',

            'fuel.view',
            'fuel.create',
            'fuel.edit',

            'traffic_fines.view',
            'traffic_fines.create',
            'traffic_fines.edit',

            'leaves.view',
            'leaves.create',
            'leaves.edit',

            'alerts.view',
        ];

        Role::where('name', 'operations')
            ->first()
            ?->permissions()
            ->sync($this->permissionIds($allPermissions, $operationsPermissions));

        $hrPermissions = [
            'dashboard.view',

            'drivers.view',
            'drivers.create',
            'drivers.edit',

            'leaves.view',
            'leaves.create',
            'leaves.edit',

            'payroll.view',

            'driver_advances.view',
            'driver_advances.create',
            'driver_advances.edit',

            'traffic_fines.view',

            'documents.view',
            'documents.create',
            'documents.edit',

            'alerts.view',
        ];

        Role::where('name', 'hr')
            ->first()
            ?->permissions()
            ->sync($this->permissionIds($allPermissions, $hrPermissions));

        $accountsPermissions = [
            'dashboard.view',

            'clients.view',
            'vendors.view',

            'fuel.view',
            'expenses.view',

            'invoices.view',
            'invoices.create',
            'invoices.edit',

            'payments.view',
            'payments.create',
            'payments.edit',

            'payroll.view',
            'payroll.create',
            'payroll.edit',

            'driver_advances.view',
            'traffic_fines.view',

            'reports.view',
            'reports.export',

            'alerts.view',
        ];

        Role::where('name', 'accounts')
            ->first()
            ?->permissions()
            ->sync($this->permissionIds($allPermissions, $accountsPermissions));

        $dataEntryPermissions = [
            'dashboard.view',

            'vehicles.view',
            'vehicles.create',
            'vehicles.edit',

            'drivers.view',
            'drivers.create',
            'drivers.edit',

            'clients.view',
            'clients.create',
            'clients.edit',

            'vendors.view',
            'vendors.create',
            'vendors.edit',

            'assignments.view',
            'assignments.create',
            'assignments.edit',

            'trips.view',
            'trips.create',
            'trips.edit',

            'fuel.view',
            'fuel.create',
            'fuel.edit',

            'maintenance.view',
            'maintenance.create',
            'maintenance.edit',

            'expenses.view',
            'expenses.create',
            'expenses.edit',

            'documents.view',
            'documents.create',

            'traffic_fines.view',
            'traffic_fines.create',

            'driver_advances.view',
            'driver_advances.create',

            'leaves.view',
            'leaves.create',

            'alerts.view',
        ];

        Role::where('name', 'data-entry')
            ->first()
            ?->permissions()
            ->sync($this->permissionIds($allPermissions, $dataEntryPermissions));

        $portalPermissions = [
            'dashboard.view',
            'documents.view',
            'invoices.view',
            'reports.view',
            'alerts.view',
        ];

        Role::where('name', 'portal')
            ->first()
            ?->permissions()
            ->sync($this->permissionIds($allPermissions, $portalPermissions));
    }

    private function permissionIds($allPermissions, array $names): array
    {
        return collect($names)
            ->map(fn ($name) => $allPermissions[$name] ?? null)
            ->filter()
            ->values()
            ->all();
    }
}