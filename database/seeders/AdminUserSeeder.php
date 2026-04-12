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
        $email = (string) env('ADMIN_EMAIL', 'admin@serwis.local');
        $username = (string) env('ADMIN_USERNAME', 'admin');
        $name = (string) env('ADMIN_NAME', 'Administrator');
        $password = (string) env('ADMIN_PASSWORD', 'Admin123!');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'admin_permissions' => null,
                'force_password_change' => false,
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}
