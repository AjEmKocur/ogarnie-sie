<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('username')->nullable()->unique()->after('email');
        });

        User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_OPERATOR])
            ->orderBy('id')
            ->get()
            ->each(function (User $user): void {
                if ($user->username) {
                    return;
                }

                $base = preg_replace('/[^a-z0-9_.-]/', '', strtolower((string) strstr((string) $user->email, '@', true)));
                if (! $base) {
                    $base = 'operator';
                }

                $candidate = $base;
                $suffix = 1;
                while (User::query()->where('username', $candidate)->exists()) {
                    $suffix++;
                    $candidate = $base.$suffix;
                }

                $user->username = $candidate;
                $user->save();
            });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};

