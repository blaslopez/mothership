<?php

namespace Modules\Cockpit\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    // All permissions owned by this module
    public const PERMISSIONS = [
        'cockpit.users.view',
        'cockpit.users.create',
        'cockpit.users.edit',
        'cockpit.users.delete',
        'cockpit.roles.view',
        'cockpit.roles.create',
        'cockpit.roles.edit',
        'cockpit.roles.delete',
        'cockpit.permissions.view',
        'cockpit.permissions.assign',
    ];

    public function run(): void
    {
        foreach (self::PERMISSIONS as $name) {
            Permission::firstOrCreate([
                'name'       => $name,
                'guard_name' => 'web',
            ], [
                'module' => 'cockpit',
            ]);
        }

        // Create default admin role with all cockpit permissions
        $admin = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions(self::PERMISSIONS);
    }
}
