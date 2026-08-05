<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', '');
        $password = (string) env('ADMIN_PASSWORD', '');

        if ($email === '' || $password === '') {
            $this->command?->warn('Skipping admin user seeding. Set ADMIN_EMAIL and ADMIN_PASSWORD to create an admin account.');

            return;
        }

        Validator::make(
            [
                'email' => $email,
                'password' => $password,
            ],
            [
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
            ]
        )->validate();

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) env('ADMIN_NAME', 'Administrator'),
                'username' => (string) env('ADMIN_USERNAME', Str::before($email, '@')),
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'admin_permissions' => null,
                'force_password_change' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        $this->command?->info('Admin user seeded for '.$email.'.');
    }
}
