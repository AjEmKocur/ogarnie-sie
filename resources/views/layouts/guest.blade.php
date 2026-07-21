<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/kocur-logo-amber.png') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-slate-950">
        <div class="app-page-shell flex min-h-screen flex-col bg-gray-100">
            <main class="flex-1 flex flex-col items-center justify-center px-4 py-8">
                <div>
                    <a href="/">
                        <x-application-logo class="h-16 w-auto max-w-[320px] object-contain" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </main>

            <footer class="og-footer border-t border-gray-200">
                <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-3 px-5 py-6 text-sm text-slate-400 sm:flex-row sm:items-center sm:px-6 lg:px-8">
                    <p>&copy; {{ date('Y') }} Kocur. Serwis komputerowy.</p>
                    <p>Godziny pracy: Pn-Pt 8:00-18:00</p>
                </div>
            </footer>
        </div>
    </body>
</html>
