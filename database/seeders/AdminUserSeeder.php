<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@serwis.local'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('Admin123!'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'admin_permissions' => null,
                'force_password_change' => false,
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}
