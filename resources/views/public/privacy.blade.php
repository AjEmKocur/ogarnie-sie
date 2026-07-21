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
                        Administratorem danych jest Dominik Kocur, działający pod nazwą Kocur Serwis Komputerowy.
                        Kontakt z administratorem jest możliwy przez formularz kontaktowy lub adres e-mail:
                        <a href="mailto:kocurserwis@gmail.com" class="text-amber-200 underline underline-offset-4 hover:text-amber-100">kocurserwis@gmail.com</a>.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">2. Jakie dane mogą być przetwarzane</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        W zależności od sposobu korzystania ze strony mogą być przetwarzane: imię i nazwisko, adres e-mail,
                        numer telefonu, treść wiadomości, informacje o zgłoszeniu serwisowym, dane konta użytkownika,
                        adres IP, identyfikatory sesji oraz dane techniczne potrzebne do zabezpieczenia formularzy.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">3. Cele przetwarzania</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane są przetwarzane w celu odpowiedzi na wiadomość, ustalenia zakresu usługi, obsługi zgłoszeń,
                        prowadzenia kont użytkowników, zabezpieczenia strony przed spamem, obsługi reklamacji oraz
                        dochodzenia lub obrony ewentualnych roszczeń.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">4. Podstawa przetwarzania</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Podstawą przetwarzania danych jest podjęcie działań na żądanie osoby, której dane dotyczą,
                        wykonanie uzgodnionej usługi, obowiązki rozliczeniowe oraz prawnie uzasadniony interes polegający
                        na obsłudze korespondencji, zabezpieczeniu strony i obronie przed roszczeniami.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">5. Odbiorcy danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane mogą być przetwarzane przy pomocy dostawców technicznych, takich jak hosting strony,
                        poczta e-mail, Cloudflare Turnstile, Google Maps oraz zewnętrzne przechowywanie plików, jeżeli
                        dane są przesyłane w zgłoszeniach lub załącznikach.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">6. Cloudflare Turnstile</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Formularz kontaktowy może korzystać z mechanizmu Cloudflare Turnstile. Rozwiązanie to pomaga
                        sprawdzić, czy formularz nie jest wysyłany automatycznie przez boty. W tym celu mogą być
                        przetwarzane dane techniczne przeglądarki i połączenia.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">7. Google Maps</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Na stronie kontaktowej może być osadzona mapa Google Maps. Po wyświetleniu mapy Google może
                        otrzymać dane techniczne użytkownika, takie jak adres IP, informacje o przeglądarce i podstawowe
                        dane o połączeniu.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">8. Opinie i moderacja treści</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Opinie dodawane przez klientów mogą być automatycznie sprawdzane pod kątem spamu, wulgaryzmów,
                        danych kontaktowych publikowanych w treści opinii oraz treści nieodpowiednich. Wynik takiej
                        moderacji może być zapisany razem z opinią.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">9. Czas przechowywania danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane z formularza i zgłoszeń są przechowywane przez czas potrzebny do obsługi sprawy, a następnie
                        przez okres potrzebny do rozliczeń, reklamacji albo zabezpieczenia ewentualnych roszczeń. Dane konta
                        użytkownika są przechowywane do czasu usunięcia konta albo zakończenia korzystania z panelu.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">10. Prawa osoby, której dane dotyczą</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Osoba, której dane dotyczą, może żądać dostępu do danych, ich sprostowania, usunięcia, ograniczenia
                        przetwarzania, przeniesienia danych albo wnieść sprzeciw wobec przetwarzania, jeżeli pozwalają na to
                        przepisy. Można też wnieść skargę do Prezesa Urzędu Ochrony Danych Osobowych.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">11. Dobrowolność podania danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Podanie danych jest dobrowolne, ale potrzebne do odpowiedzi na wiadomość albo obsługi zgłoszenia.
                        Bez podania danych kontaktowych nie będzie możliwe udzielenie odpowiedzi.
                    </p>
                </li>
            </ol>
        </div>
    </section>
@endsection
