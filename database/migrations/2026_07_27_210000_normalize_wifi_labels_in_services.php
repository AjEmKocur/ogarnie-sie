<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('service_categories')
            ->where('description', 'like', '%Wi-Fi%')
            ->update([
                'description' => DB::raw("replace(description, 'Wi-Fi', 'WiFi')"),
                'updated_at' => now(),
            ]);

        DB::table('services')
            ->where('name', 'like', '%Wi-Fi%')
            ->update([
                'name' => DB::raw("replace(name, 'Wi-Fi', 'WiFi')"),
                'updated_at' => now(),
            ]);

        DB::table('services')
            ->where('description', 'like', '%Wi-Fi%')
            ->update([
                'description' => DB::raw("replace(description, 'Wi-Fi', 'WiFi')"),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('service_categories')
            ->where('description', 'like', '%WiFi%')
            ->update([
                'description' => DB::raw("replace(description, 'WiFi', 'Wi-Fi')"),
                'updated_at' => now(),
            ]);

        DB::table('services')
            ->where('name', 'like', '%WiFi%')
            ->update([
                'name' => DB::raw("replace(name, 'WiFi', 'Wi-Fi')"),
                'updated_at' => now(),
            ]);

        DB::table('services')
            ->where('description', 'like', '%WiFi%')
            ->update([
                'description' => DB::raw("replace(description, 'WiFi', 'Wi-Fi')"),
                'updated_at' => now(),
            ]);
    }
};
