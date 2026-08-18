<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $categories = [
            'Składanie PC' => [
                'name' => 'Składanie PC',
                'description' => 'Dobór części, montaż zestawu, konfiguracja BIOS/UEFI i podstawowe testy po złożeniu.',
                'sort_order' => 10,
                'is_active' => true,
            ],
            'Modernizacja sprzętu' => [
                'name' => 'Modernizacja i czyszczenie',
                'description' => 'Usprawnienie komputera lub laptopa: dysk SSD, RAM, czyszczenie i poprawa temperatur.',
                'sort_order' => 20,
                'is_active' => true,
            ],
            'Diagnostyka' => [
                'name' => 'Diagnostyka i naprawa',
                'description' => 'Sprawdzenie objawów, wskazanie przyczyny problemu i ustalenie sensownego zakresu naprawy.',
                'sort_order' => 30,
                'is_active' => true,
            ],
            'Systemy i oprogramowanie' => [
                'name' => 'Systemy i dane',
                'description' => 'Instalacja systemu, sterowników, podstawowych programów oraz przenoszenie danych, gdy jest to możliwe.',
                'sort_order' => 40,
                'is_active' => true,
            ],
            'Sieci domowe' => [
                'name' => 'Sieci i urządzenia',
                'description' => 'Router, WiFi, repeater, switch, drukarka i podstawowa konfiguracja domowego internetu.',
                'sort_order' => 50,
                'is_active' => true,
            ],
            'Dojazd do klienta' => [
                'name' => 'Dojazd do klienta',
                'description' => 'Pomoc techniczna na miejscu przy prostszych problemach.',
                'sort_order' => 60,
                'is_active' => false,
            ],
        ];

        foreach ($categories as $oldName => $data) {
            DB::table('service_categories')
                ->where('name', $oldName)
                ->update([...$data, 'updated_at' => $now]);
        }

        $categoryIds = DB::table('service_categories')->pluck('id', 'name');

        $services = [
            'Składanie komputera z części klienta' => [
                'category' => 'Składanie PC',
                'name' => 'Składanie komputera z części klienta',
                'description' => 'Montaż zestawu, uporządkowanie okablowania, pierwsze uruchomienie i podstawowy test stabilności.',
                'sort_order' => 10,
                'price_from' => 200,
            ],
            'Dobór podzespołów pod budżet' => [
                'category' => 'Składanie PC',
                'name' => 'Dobór podzespołów pod budżet',
                'description' => 'Pomoc w dobraniu części do komputera gamingowego, biurowego albo estetycznego zestawu na zamówienie.',
                'sort_order' => 20,
                'price_from' => 50,
            ],
            'Konfiguracja BIOS/UEFI i test stabilności' => [
                'category' => 'Składanie PC',
                'name' => 'Konfiguracja BIOS/UEFI i testy',
                'description' => 'Ustawienie podstawowych opcji BIOS/UEFI, profilu pamięci RAM oraz sprawdzenie temperatur po złożeniu zestawu.',
                'sort_order' => 30,
                'price_from' => 80,
            ],
            'Wymiana dysku HDD na SSD' => [
                'category' => 'Modernizacja i czyszczenie',
                'name' => 'Wymiana dysku HDD na SSD',
                'description' => 'Montaż szybszego dysku SSD w komputerze lub laptopie oraz podstawowe sprawdzenie działania sprzętu.',
                'sort_order' => 10,
                'price_from' => 100,
            ],
            'Klonowanie systemu na nowy dysk' => [
                'category' => 'Modernizacja i czyszczenie',
                'name' => 'Klonowanie systemu na nowy dysk',
                'description' => 'Przeniesienie systemu i danych ze sprawnego dysku na nowy nośnik, jeżeli stan starego dysku na to pozwala.',
                'sort_order' => 20,
                'price_from' => 120,
            ],
            'Czyszczenie komputera i poprawa temperatur' => [
                'category' => 'Modernizacja i czyszczenie',
                'name' => 'Czyszczenie komputera i poprawa temperatur',
                'description' => 'Czyszczenie wnętrza komputera, kontrola przepływu powietrza i podstawowa poprawa temperatur.',
                'sort_order' => 30,
                'price_from' => 120,
            ],
            'Diagnostyka komputera lub laptopa' => [
                'category' => 'Diagnostyka i naprawa',
                'name' => 'Diagnostyka komputera lub laptopa',
                'description' => 'Sprawdzenie objawów awarii i wskazanie, czy problem dotyczy sprzętu, systemu czy konfiguracji.',
                'sort_order' => 10,
                'price_from' => 80,
            ],
            'Sprawdzenie dysku, RAM i temperatur' => [
                'category' => 'Diagnostyka i naprawa',
                'name' => 'Sprawdzenie dysku, RAM i temperatur',
                'description' => 'Test podstawowych elementów wpływających na stabilność oraz wydajność komputera.',
                'sort_order' => 20,
                'price_from' => 60,
            ],
            'Diagnoza problemów z uruchamianiem' => [
                'category' => 'Diagnostyka i naprawa',
                'name' => 'Problemy z uruchamianiem komputera',
                'description' => 'Sprawdzenie sytuacji, w której komputer nie startuje, zawiesza się albo wyłącza w trakcie pracy.',
                'sort_order' => 30,
                'price_from' => 80,
            ],
            'Pomoc techniczna u klienta' => [
                'category' => 'Diagnostyka i naprawa',
                'name' => 'Pomoc techniczna u klienta',
                'description' => 'Pomoc na miejscu przy prostszych tematach, takich jak internet, drukarka, konfiguracja lub podstawowa diagnostyka.',
                'sort_order' => 40,
                'price_from' => 100,
            ],
            'Odbiór sprzętu do diagnozy' => [
                'category' => 'Diagnostyka i naprawa',
                'name' => 'Odbiór sprzętu do diagnozy',
                'description' => 'Odbiór komputera lub laptopa, gdy problem wymaga dokładniejszego sprawdzenia poza miejscem zgłoszenia.',
                'sort_order' => 50,
                'price_from' => null,
            ],
            'Instalacja Windows i sterowników' => [
                'category' => 'Systemy i dane',
                'name' => 'Instalacja Windows i sterowników',
                'description' => 'Instalacja systemu, sterowników oraz podstawowe przygotowanie komputera do pracy.',
                'sort_order' => 10,
                'price_from' => 120,
            ],
            'Aktualizacja BIOS/UEFI' => [
                'category' => 'Systemy i dane',
                'name' => 'Aktualizacja BIOS/UEFI',
                'description' => 'Aktualizacja BIOS/UEFI po wcześniejszym sprawdzeniu modelu płyty głównej lub laptopa.',
                'sort_order' => 20,
                'price_from' => 80,
            ],
            'Przygotowanie komputera do pracy' => [
                'category' => 'Systemy i dane',
                'name' => 'Przygotowanie komputera do pracy',
                'description' => 'Instalacja podstawowych programów, aktualizacje i uporządkowanie startowej konfiguracji systemu.',
                'sort_order' => 30,
                'price_from' => 100,
            ],
            'Konfiguracja routera lub WiFi' => [
                'category' => 'Sieci i urządzenia',
                'name' => 'Konfiguracja routera lub WiFi',
                'description' => 'Ustawienie routera, nazwy sieci, hasła oraz podstawowych parametrów domowego połączenia.',
                'sort_order' => 10,
                'price_from' => 100,
            ],
            'Podłączenie repeatera, switcha albo drukarki' => [
                'category' => 'Sieci i urządzenia',
                'name' => 'Podłączenie repeatera, switcha albo drukarki',
                'description' => 'Dodanie urządzenia do sieci domowej i sprawdzenie, czy działa w docelowym miejscu.',
                'sort_order' => 20,
                'price_from' => 80,
            ],
            'Sprawdzenie problemów z internetem' => [
                'category' => 'Sieci i urządzenia',
                'name' => 'Sprawdzenie problemów z internetem',
                'description' => 'Podstawowa diagnostyka zasięgu WiFi, połączeń kablowych i konfiguracji domowej sieci.',
                'sort_order' => 30,
                'price_from' => 80,
            ],
        ];

        foreach ($services as $oldName => $data) {
            $categoryId = $categoryIds->get($data['category']);

            if (! $categoryId) {
                continue;
            }

            DB::table('services')
                ->where('name', $oldName)
                ->update([
                    'service_category_id' => $categoryId,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'sort_order' => $data['sort_order'],
                    'price_from' => $data['price_from'],
                    'is_active' => true,
                    'updated_at' => $now,
                ]);
        }
    }

    public function down(): void
    {
        DB::table('service_categories')
            ->where('name', 'Dojazd do klienta')
            ->update(['is_active' => true, 'updated_at' => now()]);
    }
};
