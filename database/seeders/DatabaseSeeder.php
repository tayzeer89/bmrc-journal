<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Clear Spatie Permission Cache
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | Internal Roles
        |--------------------------------------------------------------------------
        */

        $roles = [

            'system_administrator',

            'editorial_officer',

            'editor_in_chief',

            'associate_editor',

            'accounts_officer',

            'copy_editor',

            'proofreader',

            'production_web_admin',

            'journal_manager',
        ];


        /*
        |--------------------------------------------------------------------------
        | Create Roles
        |--------------------------------------------------------------------------
        */

        foreach ($roles as $roleName) {

            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Permissions
        |--------------------------------------------------------------------------
        |
        | PermissionSeeder contains all BMRC Journal permissions.
        |
        */

        $this->call([
            PermissionSeeder::class,
            ArticleTypeSeeder::class,
            JournalSeeder::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create System Administrator
        |--------------------------------------------------------------------------
        */



            $admin = User::updateOrCreate(
                [
                    'email' => 'admin@bmrc.gov.bd',
                ],
                [
                    'name' => 'System Administrator',
                    'password' => Hash::make('Admin@123456'),
                    'user_type' => 'internal',
                ]
            );

            $admin->assignRole('system_administrator');









        /*
        |--------------------------------------------------------------------------
        | Assign System Administrator Role
        |--------------------------------------------------------------------------
        */

        $admin->syncRoles([
            'system_administrator',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Give All Permissions to System Administrator
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions(
            Permission::all()
        );


        /*
        |--------------------------------------------------------------------------
        | Success Message
        |--------------------------------------------------------------------------
        */

        $this->command->info(
            'BMRC Journal roles created successfully.'
        );

        $this->command->info(
            'System Administrator created successfully.'
        );

        $this->command->info(
            'Email: admin@bmrc.gov.bd'
        );

        $this->command->info(
            'Password: Admin@123456'
        );
    }
}
