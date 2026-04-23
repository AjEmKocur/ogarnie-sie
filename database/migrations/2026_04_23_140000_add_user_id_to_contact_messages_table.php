<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('email')->constrained('users')->nullOnDelete();
        });

        DB::table('contact_messages')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $userId = DB::table('users')
                        ->whereRaw('lower(email) = ?', [mb_strtolower((string) $row->email)])
                        ->value('id');

                    if ($userId !== null) {
                        DB::table('contact_messages')
                            ->where('id', $row->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
