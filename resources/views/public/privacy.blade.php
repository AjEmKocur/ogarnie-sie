@extends('layouts.public')

@section('title', 'Polityka prywatności - Kocur Serwis Komputerowy')
@section('meta_description', 'Informacja o przetwarzaniu danych osobowych w formularzu kontaktowym, panelu klienta, zgłoszeniach, opiniach i zabezpieczeniach strony.')

@section('content')
    <section class="mx-auto max-w-5xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Polityka prywatności</p>
            <h1 class="mt-3 text-4xl font-black text-white">Jak przetwarzane są dane?</h1>
            <p class="mt-5 text-base leading-8 text-slate-300">
                Ta informacja opisuje, jakie dane są zbierane przez stronę oraz w jakim celu są wykorzystywane.
                Dotyczy formularza kontaktowego, kont użytkowników, zgłoszeń serwisowych, opinii, zabezpieczeń
                antyspamowych i podstawowych funkcji strony.
            </p>
        </div>

        <div class="mt-10 rounded-2xl border border-amber-300/20 bg-slate-950/70 p-6 sm:p-8">
            <ol class="legal-list space-y-8">
                <li>
                    <h2 class="text-xl font-bold text-white">1. Administrator danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Administratorem danych jest Dominik Kocur, wykonujący usługi w ramach działalności nierejestrowanej
                        pod nazwą Kocur Serwis Komputerowy.
                        Kontakt z administratorem jest możliwy przez formularz kontaktowy lub adres e-mail:
                        <a href="mailto:kontakt@kocurserwis.pl" class="text-amber-200 underline underline-offset-4 hover:text-amber-100">kontakt@kocurserwis.pl</a>.
                        Administrator nie wyznaczył inspektora ochrony danych.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">2. Jakie dane mogą być przetwarzane</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        W zależności od sposobu korzystania ze strony mogą być przetwarzane: imię i nazwisko, adres e-mail,
                        numer telefonu, treść wiadomości, informacje o zgłoszeniu serwisowym, dane konta użytkownika,
                        załączniki przekazane w zgłoszeniu, adres IP, identyfikatory sesji oraz dane techniczne potrzebne
                        do zabezpieczenia formularzy i utrzymania konta.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">3. Poprawianie danych konta</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Użytkownik powinien podawać prawdziwe dane potrzebne do kontaktu i obsługi zgłoszeń. Jeżeli dane
                        są nieaktualne albo wpisane z błędem, można je poprawić w ustawieniach profilu. W razie problemu
                        z dostępem do konta można skontaktować się przez adres e-mail wskazany w tej polityce.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">4. Cele przetwarzania</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane są przetwarzane w celu odpowiedzi na wiadomość, ustalenia zakresu usługi, obsługi zgłoszeń,
                        prowadzenia kont użytkowników, zabezpieczenia strony przed spamem, obsługi reklamacji oraz
                        dochodzenia lub obrony ewentualnych roszczeń. Dane mogą być też wykorzystywane do wysyłania
                        technicznych wiadomości e-mail związanych z kontem, zgłoszeniem, zmianą statusu albo odpowiedzią
                        w panelu klienta.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">5. Podstawa przetwarzania</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane są przetwarzane na podstawie art. 6 ust. 1 lit. b RODO, gdy jest to potrzebne do podjęcia
                        działań przed zawarciem umowy albo wykonania uzgodnionej usługi; art. 6 ust. 1 lit. c RODO, gdy
                        wynika to z obowiązków prawnych, w szczególności rozliczeniowych; oraz art. 6 ust. 1 lit. f RODO,
                        gdy uzasadnionym interesem jest obsługa korespondencji, zabezpieczenie strony, prowadzenie panelu,
                        dochodzenie lub obrona roszczeń.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">6. Odbiorcy danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane mogą być przetwarzane przy pomocy dostawców technicznych, takich jak Render (hosting aplikacji),
                        OVH/Zimbra (obsługa poczty e-mail), Cloudflare (Turnstile, zabezpieczenia i R2 do przechowywania
                        załączników), Google Maps oraz dostawca usługi AI wykorzystywanej do moderacji opinii. Dane mogą
                        zostać udostępnione także podmiotom uprawnionym na podstawie przepisów prawa, jeżeli będzie to
                        wymagane.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">7. Cloudflare Turnstile</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Formularz kontaktowy może korzystać z mechanizmu Cloudflare Turnstile. Rozwiązanie to pomaga
                        sprawdzić, czy formularz nie jest wysyłany automatycznie przez boty. W tym celu mogą być
                        przetwarzane dane techniczne przeglądarki i połączenia.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">8. Załączniki i pliki w zgłoszeniach</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Użytkownik może dodać zdjęcia lub inne pliki potrzebne do obsługi zgłoszenia. Pliki są przechowywane
                        w zewnętrznej usłudze storage i są dostępne tylko dla zalogowanego właściciela zgłoszenia oraz
                        uprawnionego administratora lub operatora. Nie należy przesyłać plików zawierających dane, które
                        nie są potrzebne do diagnozy albo obsługi sprawy.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">9. Google Maps</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Na stronie kontaktowej może być osadzona mapa Google Maps. Po wyświetleniu mapy Google może
                        otrzymać dane techniczne użytkownika, takie jak adres IP, informacje o przeglądarce i podstawowe
                        dane o połączeniu.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">10. Opinie i moderacja treści</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Opinie dodawane przez klientów mogą być automatycznie sprawdzane pod kątem spamu, wulgaryzmów,
                        danych kontaktowych publikowanych w treści opinii oraz treści nieodpowiednich. Wynik takiej
                        moderacji może być zapisany razem z opinią. Moderacja ma pomóc w ochronie prywatności i jakości
                        treści publikowanych na stronie; decyzja może zostać zweryfikowana ręcznie.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">11. Czas przechowywania danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane z formularza i zgłoszeń są przechowywane przez czas potrzebny do obsługi sprawy, a następnie
                        przez okres potrzebny do rozliczeń, reklamacji albo zabezpieczenia ewentualnych roszczeń. Dane konta
                        użytkownika są przechowywane do czasu usunięcia konta albo zakończenia korzystania z panelu.
                        Niezatwierdzone i nieaktywne konta mogą być okresowo usuwane.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">12. Prawa osoby, której dane dotyczą</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Osoba, której dane dotyczą, może żądać dostępu do danych, ich sprostowania, usunięcia, ograniczenia
                        przetwarzania, przeniesienia danych albo wnieść sprzeciw wobec przetwarzania, jeżeli pozwalają na to
                        przepisy. Można też wnieść skargę do Prezesa Urzędu Ochrony Danych Osobowych.
                        Aktualne dane kontaktowe UODO są dostępne na stronie
                        <a href="https://uodo.gov.pl/p/kontakt" class="text-amber-200 underline underline-offset-4 hover:text-amber-100" rel="nofollow noopener">uodo.gov.pl</a>.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">13. Dobrowolność podania danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Podanie danych jest dobrowolne, ale potrzebne do odpowiedzi na wiadomość albo obsługi zgłoszenia.
                        Bez podania danych kontaktowych nie będzie możliwe udzielenie odpowiedzi.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">14. Przekazywanie danych poza EOG</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Niektóre narzędzia techniczne, takie jak Google Maps, Cloudflare albo usługi AI, mogą wiązać się
                        z przetwarzaniem danych poza Europejskim Obszarem Gospodarczym. W takim przypadku dostawcy usług
                        powinni stosować odpowiednie mechanizmy ochrony danych, w szczególności standardowe klauzule umowne
                        albo inne rozwiązania przewidziane przez RODO.
                    </p>
                </li>
            </ol>
        </div>
    </section>
@endsection
