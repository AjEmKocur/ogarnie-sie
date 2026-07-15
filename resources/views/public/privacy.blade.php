@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-5xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Polityka prywatności</p>
            <h1 class="mt-3 text-4xl font-black text-white">Jak przetwarzane są dane?</h1>
            <p class="mt-5 text-base leading-8 text-slate-300">
                Ta informacja opisuje, jakie dane są zbierane przez stronę oraz w jakim celu są wykorzystywane.
                Dokument przygotowano dla strony Kocur Serwis Komputerowy.
            </p>
        </div>

        <div class="mt-10 rounded-2xl border border-amber-300/20 bg-slate-950/70 p-6 sm:p-8">
            <ol class="legal-list space-y-8">
                <li>
                    <h2 class="text-xl font-bold text-white">1. Administrator danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Administratorem danych osobowych jest Dominik Kocur, działający pod nazwą Kocur Serwis Komputerowy.
                        Kontakt z administratorem jest możliwy przez formularz kontaktowy lub adres e-mail wskazany na stronie.
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
                    <h2 class="text-xl font-bold text-white">3. Cele przetwarzania danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane są wykorzystywane do odpowiedzi na wiadomości, obsługi zgłoszeń, umawiania i realizacji usług,
                        prowadzenia kont użytkowników, zabezpieczenia strony przed spamem, obsługi reklamacji oraz dochodzenia
                        lub obrony przed ewentualnymi roszczeniami.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">4. Podstawy prawne</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane są przetwarzane przede wszystkim w celu podjęcia działań przed wykonaniem usługi lub w celu jej
                        realizacji, wypełnienia obowiązków prawnych oraz na podstawie prawnie uzasadnionego interesu, np.
                        ochrony formularzy przed spamem, prowadzenia korespondencji i zabezpieczenia roszczeń.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">5. Odbiorcy danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane mogą być przekazywane dostawcom usług technicznych potrzebnych do działania strony, w tym
                        hostingowi, dostawcy poczty e-mail, dostawcy zabezpieczenia formularzy Cloudflare Turnstile oraz
                        dostawcy zewnętrznego przechowywania plików, jeżeli dane są przesyłane jako załączniki.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">6. Cloudflare Turnstile</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Formularz kontaktowy może korzystać z mechanizmu Cloudflare Turnstile. Rozwiązanie to pomaga sprawdzić,
                        czy formularz nie jest wysyłany automatycznie przez boty. W tym celu mogą być przetwarzane dane
                        techniczne przeglądarki i połączenia.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">7. Okres przechowywania danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane z korespondencji i zgłoszeń są przechowywane przez czas potrzebny do obsługi sprawy, a następnie
                        przez okres potrzebny do rozliczeń, reklamacji albo zabezpieczenia ewentualnych roszczeń. Dane konta
                        użytkownika są przechowywane do czasu usunięcia konta albo zakończenia potrzeby ich przechowywania.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">8. Prawa osoby, której dane dotyczą</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Osoba, której dane dotyczą, może żądać dostępu do danych, ich sprostowania, usunięcia, ograniczenia
                        przetwarzania, przeniesienia danych oraz wnieść sprzeciw wobec przetwarzania. Może także złożyć skargę
                        do Prezesa Urzędu Ochrony Danych Osobowych.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">9. Dobrowolność podania danych</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Podanie danych w formularzu jest dobrowolne, ale niezbędne do odpowiedzi na wiadomość i obsługi
                        zgłoszenia. Bez podania danych kontaktowych nie będzie możliwe udzielenie odpowiedzi.
                    </p>
                </li>
            </ol>
        </div>
    </section>
@endsection
