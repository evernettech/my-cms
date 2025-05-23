<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create blogs',
            'edit blogs',
            'delete blogs',
            'publish blogs',
            'view blogs',
            'manage versions',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Create roles and assign existing permissions
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $operatorRole = Role::firstOrCreate([
            'name' => 'operator',
            'guard_name' => 'web',
        ]);

        $adminRole->givePermissionTo(Permission::all());

        $operatorRole->givePermissionTo([
            'create blogs',
            'edit blogs',
            'view blogs',
            'delete blogs',
            'view dashboard'
        ]);
    }
}
