<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'email' => 'admin@bmrc.gov.bd',
            ],
            [
                'name' => 'BMRC Administrator',
                'password' => Hash::make('ChangeThisPassword123!'),
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles(['system_admin']);
    }
}