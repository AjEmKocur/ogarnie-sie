<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = DB::table('service_categories')->pluck('id', 'name');
        $now = now();

        $services = [
            ['Składanie PC', 'Składanie komputera z części klienta', 'Montaż zestawu, uporządkowanie okablowania, pierwsze uruchomienie i podstawowy test stabilności.', 200, 10],
            ['Składanie PC', 'Dobór podzespołów pod budżet', 'Pomoc w dobraniu części do komputera gamingowego, biurowego albo estetycznego zestawu na zamówienie.', 50, 20],
            ['Składanie PC', 'Konfiguracja BIOS/UEFI i test stabilności', 'Ustawienie podstawowych opcji BIOS/UEFI, profilu pamięci RAM oraz sprawdzenie temperatur po złożeniu zestawu.', 80, 30],
            ['Modernizacja sprzętu', 'Wymiana dysku HDD na SSD', 'Montaż szybszego dysku SSD w komputerze lub laptopie oraz podstawowe sprawdzenie działania sprzętu.', 100, 10],
            ['Modernizacja sprzętu', 'Klonowanie systemu na nowy dysk', 'Przeniesienie systemu i danych ze sprawnego dysku na nowy nośnik, jeżeli stan starego dysku na to pozwala.', 120, 20],
            ['Modernizacja sprzętu', 'Czyszczenie komputera i poprawa temperatur', 'Czyszczenie wnętrza komputera, kontrola przepływu powietrza i podstawowa poprawa temperatur.', 120, 30],
            ['Diagnostyka', 'Diagnostyka komputera lub laptopa', 'Sprawdzenie objawów awarii i wskazanie, czy problem dotyczy sprzętu, systemu czy konfiguracji.', 80, 10],
            ['Diagnostyka', 'Sprawdzenie dysku, RAM i temperatur', 'Test podstawowych elementów wpływających na stabilność oraz wydajność komputera.', 60, 20],
            ['Diagnostyka', 'Diagnoza problemów z uruchamianiem', 'Sprawdzenie sytuacji, w której komputer nie startuje, zawiesza się albo wyłącza w trakcie pracy.', 80, 30],
            ['Systemy i oprogramowanie', 'Instalacja Windows i sterowników', 'Instalacja systemu, sterowników oraz podstawowe przygotowanie komputera do pracy.', 120, 10],
            ['Systemy i oprogramowanie', 'Aktualizacja BIOS/UEFI', 'Aktualizacja BIOS/UEFI po wcześniejszym sprawdzeniu modelu płyty głównej lub laptopa.', 80, 20],
            ['Systemy i oprogramowanie', 'Przygotowanie komputera do pracy', 'Instalacja podstawowych programów, aktualizacje i uporządkowanie startowej konfiguracji systemu.', 100, 30],
            ['Sieci domowe', 'Konfiguracja routera lub Wi-Fi', 'Ustawienie routera, nazwy sieci, hasła oraz podstawowych parametrów domowego połączenia.', 100, 10],
            ['Sieci domowe', 'Podłączenie repeatera, switcha albo drukarki', 'Dodanie urządzenia do sieci domowej i sprawdzenie, czy działa w docelowym miejscu.', 80, 20],
            ['Sieci domowe', 'Sprawdzenie problemów z internetem', 'Podstawowa diagnostyka zasięgu Wi-Fi, połączeń kablowych i konfiguracji domowej sieci.', 80, 30],
            ['Dojazd do klienta', 'Pomoc techniczna u klienta', 'Pomoc na miejscu przy prostszych tematach, takich jak internet, drukarka, konfiguracja lub podstawowa diagnostyka.', 100, 10],
            ['Dojazd do klienta', 'Odbiór sprzętu do diagnozy', 'Odbiór komputera lub laptopa, gdy problem wymaga dokładniejszego sprawdzenia poza miejscem zgłoszenia.', null, 20],
        ];

        foreach ($services as [$categoryName, $name, $description, $priceFrom, $sortOrder]) {
            if (! $categories->has($categoryName)) {
                continue;
            }

            $exists = DB::table('services')->where('name', $name)->exists();

            if ($exists) {
                continue;
            }

            DB::table('services')->insert([
                'service_category_id' => $categories->get($categoryName),
                'name' => $name,
                'description' => $description,
                'long_description' => null,
                'price_from' => $priceFrom,
                'is_active' => true,
                'sort_order' => $sortOrder,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('services')->whereIn('name', [
            'Składanie komputera z części klienta',
            'Dobór podzespołów pod budżet',
            'Konfiguracja BIOS/UEFI i test stabilności',
            'Wymiana dysku HDD na SSD',
            'Klonowanie systemu na nowy dysk',
            'Czyszczenie komputera i poprawa temperatur',
            'Diagnostyka komputera lub laptopa',
            'Sprawdzenie dysku, RAM i temperatur',
            'Diagnoza problemów z uruchamianiem',
            'Instalacja Windows i sterowników',
            'Aktualizacja BIOS/UEFI',
            'Przygotowanie komputera do pracy',
            'Konfiguracja routera lub Wi-Fi',
            'Podłączenie repeatera, switcha albo drukarki',
            'Sprawdzenie problemów z internetem',
            'Pomoc techniczna u klienta',
            'Odbiór sprzętu do diagnozy',
        ])->delete();
    }
};
