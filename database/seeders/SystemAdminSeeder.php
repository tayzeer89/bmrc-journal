<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SystemAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'email' => 'admin@bmrc.gov.bd',
            ],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@123456'),
                'user_type' => 'internal',
            ]
        );

        $role = Role::firstOrCreate([
            'name' => 'system_administrator',
            'guard_name' => 'web',
        ]);

        $user->syncRoles([$role]);

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
