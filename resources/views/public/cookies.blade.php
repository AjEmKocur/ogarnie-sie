@extends('layouts.public')

@section('title', 'Cookies - Kocur Serwis Komputerowy')
@section('meta_description', 'Informacja o technicznych plikach cookies, sesji, CSRF, Cloudflare Turnstile i mapie Google Maps na stronie Kocur Serwis Komputerowy.')

@section('content')
    <section class="mx-auto max-w-5xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Cookies</p>
            <h1 class="mt-3 text-4xl font-black text-white">Pliki cookies i dane techniczne</h1>
            <p class="mt-5 text-base leading-8 text-slate-300">
                Strona korzysta głównie z technicznych plików cookies, które są potrzebne do działania formularzy,
                logowania, sesji użytkownika i zabezpieczeń. Obecnie nie używam cookies reklamowych ani narzędzi
                do profilowania marketingowego. Zgoda na cookies nie jest wymagana dla plików niezbędnych do działania
                strony, ale użytkownik może ograniczyć je w ustawieniach przeglądarki.
            </p>
        </div>

        <div class="mt-10 rounded-2xl border border-amber-300/20 bg-slate-950/70 p-6 sm:p-8">
            <ol class="legal-list space-y-8">
                <li>
                    <h2 class="text-xl font-bold text-white">1. Czym są cookies</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Cookies to małe pliki zapisywane w przeglądarce użytkownika. Dzięki nim strona może utrzymać
                        sesję, obsłużyć formularz, zapamiętać zalogowanie i prawidłowo zabezpieczyć przesyłane dane.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">2. Cookies techniczne</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Strona wykorzystuje techniczne cookies związane z sesją użytkownika, logowaniem, panelem klienta,
                        panelem administratora i zabezpieczeniem CSRF. Bez nich część formularzy i funkcji konta może
                        nie działać prawidłowo.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">3. Sesja i bezpieczeństwo formularzy</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Pliki sesyjne pomagają rozpoznać zalogowanego użytkownika, utrzymać bezpieczeństwo formularzy
                        i chronić przed nieuprawnionymi żądaniami. Są używane wyłącznie w celu zapewnienia poprawnego
                        i bezpiecznego działania strony.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">4. Cloudflare Turnstile</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Formularz kontaktowy może korzystać z Cloudflare Turnstile, czyli zabezpieczenia antyspamowego.
                        Mechanizm ten pomaga odróżnić realnego użytkownika od automatycznego bota i może przetwarzać
                        dane techniczne przeglądarki oraz połączenia.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">5. Google Maps</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Na stronie kontaktowej może być osadzona mapa Google Maps. Po jej wyświetleniu Google może
                        otrzymać informacje techniczne, takie jak adres IP, dane przeglądarki i podstawowe informacje
                        o połączeniu.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">6. Brak reklamowych cookies</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Na stronie nie są obecnie używane pliki cookies służące do reklam behawioralnych, remarketingu
                        ani profilowania marketingowego. Jeżeli w przyszłości zostaną dodane narzędzia analityczne
                        lub reklamowe, informacja zostanie zaktualizowana.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">7. Ustawienia przeglądarki</h2>
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
