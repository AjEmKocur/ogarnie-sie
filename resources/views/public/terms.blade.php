@extends('layouts.public')

@section('title', 'Zasady współpracy - Kocur Serwis Komputerowy')
@section('meta_description', 'Zasady kontaktu, wyceny, realizacji usług komputerowych, zakupu części, płatności, reklamacji i ochrony danych.')

@section('content')
    <section class="mx-auto max-w-5xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">Zasady współpracy</p>
            <h1 class="mt-3 text-4xl font-black text-white">Jak wygląda realizacja usługi?</h1>
            <p class="mt-5 text-base leading-8 text-slate-300">
                Poniższe zasady opisują kontakt, wycenę, realizację usług, płatność, reklamacje oraz podstawowe
                informacje dotyczące sprzętu przekazywanego do diagnostyki lub naprawy.
            </p>
        </div>

        <div class="mt-10 rounded-2xl border border-amber-300/20 bg-slate-950/70 p-6 sm:p-8">
            <ol class="legal-list space-y-8">
                <li>
                    <h2 class="text-xl font-bold text-white">1. Kto wykonuje usługę</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Usługi wykonuje Dominik Kocur jako osoba fizyczna działająca pod nazwą Kocur Serwis Komputerowy.
                        Nazwa ta służy do oznaczenia usług komputerowych i kontaktu z klientami. W przypadku rozpoczęcia
                        jednoosobowej działalności gospodarczej dane na stronie zostaną uzupełnione o informacje firmowe.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">2. Kontakt i zgłoszenie</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Formularz kontaktowy służy do opisania problemu albo planowanej usługi. Samo wysłanie wiadomości
                        nie oznacza automatycznego przyjęcia zlecenia ani obowiązku wykonania usługi. Po otrzymaniu
                        zgłoszenia kontaktuję się z klientem, dopytuję o szczegóły i dopiero wtedy ustalamy dalsze kroki.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">3. Wycena i zakres pracy</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Koszt usługi jest podawany przed rozpoczęciem pracy. Jeżeli w trakcie diagnozy okaże się, że problem
                        jest szerszy niż wynikało z pierwszego opisu, dodatkowe czynności są uzgadniane z klientem przed ich
                        wykonaniem. Nie wymieniam części ani nie wykonuję dodatkowych prac bez wcześniejszego potwierdzenia.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">4. Części i zakupy sprzętu</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Najbezpieczniejszy model współpracy polega na tym, że klient kupuje części na swoje dane, a ja pomagam
                        w ich doborze, montażu, konfiguracji i testach. Jeżeli zakup części miałby zostać wykonany przeze mnie,
                        cena, źródło zakupu, sposób rozliczenia i odpowiedzialność za gwarancję są ustalane indywidualnie przed
                        zakupem.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">5. Dojazd do klienta</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Pomoc z dojazdem jest możliwa głównie na terenie Jarosławia i okolic. Termin, zakres oraz szczegóły
                        wizyty są ustalane przed przyjazdem. Przy bardziej złożonych problemach może być konieczne
                        przekazanie sprzętu do dokładniejszej diagnostyki.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">6. Sprzęt i dane użytkownika</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Przed przekazaniem komputera lub laptopa warto wykonać kopię ważnych danych. Jeżeli dane są istotne,
                        klient powinien poinformować o tym przed rozpoczęciem pracy. W razie potrzeby można ustalić usługę
                        klonowania dysku albo zabezpieczenia plików przed dalszą diagnostyką.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">7. Przekazanie sprzętu i potwierdzenie ustaleń</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Przy przekazaniu sprzętu można ustalić podstawowe informacje: model urządzenia, opis problemu,
                        widoczne uszkodzenia, przekazane akcesoria oraz orientacyjny zakres prac. Takie ustalenia pomagają
                        uniknąć nieporozumień i mogą zostać potwierdzone wiadomością e-mail, SMS-em albo wpisem w systemie.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">8. Płatność i potwierdzenie sprzedaży</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Sposób płatności jest ustalany indywidualnie z klientem. Przy działalności nierejestrowanej możliwe
                        jest wystawienie potwierdzenia sprzedaży albo rachunku na żądanie klienta, zgodnie z aktualnymi
                        przepisami. Jeżeli działalność zostanie zarejestrowana, dane rozliczeniowe zostaną uzupełnione.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">9. Zakończenie usługi</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Po wykonaniu usługi klient otrzymuje informację, co zostało zrobione oraz czy są potrzebne dalsze
                        działania, np. wymiana części, obserwacja temperatur, aktualizacja systemu albo wykonanie kopii
                        zapasowej.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">10. Reklamacje i uwagi</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Jeżeli po wykonaniu usługi pojawi się problem związany z ustalonym zakresem prac, klient może
                        skontaktować się przez formularz kontaktowy albo adres e-mail wskazany na stronie. Każde zgłoszenie
                        jest analizowane indywidualnie, z uwzględnieniem rodzaju wykonanej usługi oraz stanu sprzętu.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">11. Rezygnacja i odstąpienie</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Jeżeli zakres, termin albo koszt nie odpowiada klientowi, można zrezygnować przed rozpoczęciem prac.
                        W przypadku usług uzgadnianych na odległość szczegóły realizacji oraz ewentualne rozpoczęcie usługi
                        przed upływem terminu na odstąpienie powinny być ustalone z klientem w jasny sposób.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">12. Dane osobowe</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Dane podane w formularzu kontaktowym są wykorzystywane do odpowiedzi na wiadomość i obsługi
                        zgłoszenia. Szczegółowe informacje znajdują się w
                        <a href="{{ route('public.privacy') }}" class="text-amber-200 underline underline-offset-4 hover:text-amber-100">polityce prywatności</a>.
                    </p>
                </li>
            </ol>
        </div>
    </section>
@endsection
