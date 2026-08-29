<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{

  

    public function run(): void
    {
        $author = Role::findByName('author');

        $author->syncPermissions([
            'dashboard.view',

            'manuscript.view',
            'manuscript.create',
            'manuscript.edit',
            'manuscript.submit',

            'revision.view',
            'revision.submit',

            'payment.view',

            'proofreading.view',
        ]);


        // Other role permissions...

        $systemAdmin = Role::findByName('system_admin');

        $systemAdmin->syncPermissions(
            Permission::pluck('name')->toArray()
        );
    }
}