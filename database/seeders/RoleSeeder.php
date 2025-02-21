<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = Role::create([
            'name'          => 'superadmin',
            'guard_name'    => 'web',
        ]);
        // Get all permissions
        $permissions = Permission::all();

        // Give all permissions to the superadmin role
        $superadmin->syncPermissions($permissions);


        $kasir = Role::create([
            'name'          => 'kasir',
            'guard_name'    => 'web',
        ]);
        $kasir->givePermissionTo([
            'user read',
            'customer read',
            'customer create',
            'customer update',
            'customer delete',
            'product read',
            'product create',
            'product update',
            'product delete',
            'category read',
            'invoice read',
            'payment read',
            'order create',
            'report read',
        ]);
    }
}
