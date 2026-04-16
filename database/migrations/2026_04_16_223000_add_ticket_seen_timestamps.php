<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->timestamp('client_last_seen_at')->nullable()->after('paid_at');
            $table->timestamp('admin_last_seen_at')->nullable()->after('client_last_seen_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->dropColumn(['client_last_seen_at', 'admin_last_seen_at']);
        });
    }
};

