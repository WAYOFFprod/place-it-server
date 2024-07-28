<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        $adminRole = Role::create(['name' => 'admin']);
        $editAllCanvaPerission = Permission::create(['name' => 'edit-all-canvas']);

        $adminRole->syncPermissions([
            $editAllCanvaPerission
        ]);

        // user
        $userRole = Role::create(['name' => 'user']);
        $createCanvaPerission = Permission::create(['name' => 'create-canvas']);
        $editOwnCanvaPerission = Permission::create(['name' => 'edit-own-canvas']);

        $userRole->syncPermissions([
            $createCanvaPerission,
            $editOwnCanvaPerission
        ]);



    }
}
