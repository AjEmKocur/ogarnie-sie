@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-5xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Cookies</p>
            <h1 class="mt-3 text-4xl font-black text-white">Pliki cookies i dane techniczne</h1>
            <p class="mt-5 text-base leading-8 text-slate-300">
                Strona korzysta z plików cookies i podobnych technologii głównie po to, aby działały formularze,
                logowanie, sesja użytkownika oraz zabezpieczenia antyspamowe.
            </p>
        </div>

        <div class="mt-10 rounded-2xl border border-amber-300/20 bg-slate-950/70 p-6 sm:p-8">
            <ol class="legal-list space-y-8">
                <li>
                    <h2 class="text-xl font-bold text-white">1. Czym są cookies</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Cookies to małe pliki zapisywane w przeglądarce użytkownika. Mogą pomagać stronie zapamiętać sesję,
                        obsłużyć formularz albo utrzymać zalogowanie użytkownika.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">2. Jakie cookies są używane</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Strona wykorzystuje przede wszystkim techniczne pliki cookies niezbędne do działania aplikacji:
                        sesję użytkownika, zabezpieczenie CSRF formularzy, logowanie oraz prawidłowe działanie panelu klienta
                        i administratora.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">3. Cloudflare Turnstile</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Formularz kontaktowy może korzystać z Cloudflare Turnstile, czyli zabezpieczenia antyspamowego.
                        Mechanizm ten może przetwarzać dane techniczne przeglądarki i połączenia w celu odróżnienia
                        prawdziwego użytkownika od automatycznego bota.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">4. Brak reklamowych cookies</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Na stronie nie są obecnie zakładane pliki cookies służące do reklam behawioralnych ani profilowania
                        marketingowego. Jeżeli w przyszłości zostaną dodane narzędzia analityczne lub marketingowe,
                        informacja zostanie zaktualizowana.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">5. Ustawienia przeglądarki</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Użytkownik może ograniczyć albo zablokować obsługę cookies w ustawieniach swojej przeglądarki.
                        Trzeba jednak pamiętać, że wyłączenie technicznych cookies może utrudnić korzystanie z formularzy,
                        logowania i panelu klienta.
                    </p>
                </li>
            </ol>
        </div>
    </section>
@endsection
