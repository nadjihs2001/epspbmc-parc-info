<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'inventory.view',
            'inventory.export',
            'maintenance.view',
            'maintenance.manage',
            'structures.view',
            'structures.create',
            'structures.update',
            'structures.delete',
            'locations.view',
            'locations.create',
            'locations.update',
            'locations.delete',
            'equipments.view',
            'equipments.create',
            'equipments.update',
            'equipments.delete',
            'assignments.view',
            'assignments.create',
            'assignments.update',
            'tickets.view',
            'tickets.create',
            'tickets.update',
            'intervenants.view',
            'intervenants.create',
            'intervenants.update',
            'intervenants.delete',
            'licenses.view',
            'licenses.create',
            'licenses.update',
            'network.view',
            'network.update',
            'dashboard.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $roles = [
            'Super Admin' => $permissions,
            'Admin IT' => $permissions,
            'Technicien IT' => [
                'equipments.view',
                'equipments.update',
                'assignments.view',
                'tickets.view',
                'tickets.update',
                'intervenants.view',
                'inventory.view',
                'maintenance.view',
                'maintenance.manage',
                'dashboard.view',
            ],
            'Responsable structure' => [
                'users.view',
                'inventory.view',
                'maintenance.view',
                'structures.view',
                'locations.view',
                'equipments.view',
                'assignments.view',
                'tickets.view',
                'dashboard.view',
            ],
            'Agent administratif' => [
                'equipments.view',
                'assignments.view',
                'tickets.create',
                'dashboard.view',
            ],
            'Utilisateur simple' => [
                'equipments.view',
                'tickets.create',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }
}
