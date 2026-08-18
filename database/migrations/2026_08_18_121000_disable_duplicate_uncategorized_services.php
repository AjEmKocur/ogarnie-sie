<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateNameFragments = [
            'aktualizacja bios',
            'bios/uefi',
            'czyszczenie komputera',
            'diagnostyka komputera',
            'diagnostyka laptopa',
            'diagnostyka sprzętu',
            'dobór podzespołów',
            'instalacja systemu',
            'instalacja windows',
            'klonowanie systemu',
            'konfiguracja routera',
            'konfiguracja sieci',
            'konfiguracja wifi',
            'modernizacja komputera',
            'modernizacja sprzętu',
            'naprawa komputera',
            'naprawa laptopa',
            'podłączenie drukarki',
            'problemy z internetem',
            'problemy z uruchamianiem',
            'przygotowanie komputera',
            'składanie komputera',
            'składanie pc',
            'sprawdzenie dysku',
            'wymiana dysku',
        ];

        DB::table('services')
            ->whereNull('service_category_id')
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id', 'name'])
            ->each(function ($service) use ($duplicateNameFragments): void {
                $name = Str::lower((string) $service->name);

                foreach ($duplicateNameFragments as $fragment) {
                    if (str_contains($name, $fragment)) {
                        DB::table('services')
                            ->where('id', $service->id)
                            ->update([
                                'is_active' => false,
                                'updated_at' => now(),
                            ]);

                        return;
                    }
                }
            });
    }

    public function down(): void
    {
        // Nie przywracamy automatycznie starych duplikatów, żeby rollback nie zaśmiecił oferty.
    }
};
