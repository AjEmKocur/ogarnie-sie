<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->string('moderation_status', 20)->default('review')->after('content');
            $table->unsignedTinyInteger('moderation_score')->nullable()->after('moderation_status');
            $table->json('moderation_reasons')->nullable()->after('moderation_score');
            $table->timestamp('moderated_at')->nullable()->after('moderation_reasons');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropColumn([
                'moderation_status',
                'moderation_score',
                'moderation_reasons',
                'moderated_at',
            ]);
        });
    }
};

