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
                        Jest to działalność nierejestrowana w rozumieniu przepisów prawa. Nazwa ta służy do oznaczenia
                        usług komputerowych i kontaktu z klientami. W przypadku rozpoczęcia jednoosobowej działalności
                        gospodarczej dane na stronie zostaną uzupełnione o informacje firmowe.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">2. Kontakt i zgłoszenie</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Formularz kontaktowy służy do opisania problemu albo planowanej usługi. Samo wysłanie wiadomości
                        nie oznacza automatycznego przyjęcia zlecenia ani obowiązku wykonania usługi. Po otrzymaniu
                        zgłoszenia kontaktuję się z klientem, dopytuję o szczegóły i dopiero wtedy ustalamy dalsze kroki.
                        Zlecenie jest przyjmowane dopiero po uzgodnieniu zakresu, terminu i orientacyjnego kosztu.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">3. Dane klienta i konto</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Przy zakładaniu konta i tworzeniu zgłoszenia klient powinien podać prawdziwe dane kontaktowe.
                        Dzięki temu mogę skontaktować się w sprawie usługi, potwierdzić ustalenia i wysyłać informacje
                        o zgłoszeniu. Jeżeli w danych pojawi się pomyłka, klient może poprawić imię, nazwisko i adres e-mail
                        w ustawieniach profilu albo skontaktować się ze mną przez adres e-mail wskazany na stronie.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">4. Wycena i zakres pracy</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Koszt usługi jest podawany przed rozpoczęciem pracy. Jeżeli w trakcie diagnozy okaże się, że problem
                        jest szerszy niż wynikało z pierwszego opisu, dodatkowe czynności są uzgadniane z klientem przed ich
                        wykonaniem. Nie wymieniam części ani nie wykonuję dodatkowych prac bez wcześniejszego potwierdzenia.
                        Ceny widoczne na stronie mają charakter orientacyjny, chyba że w konkretnej rozmowie ustalimy inaczej.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">5. Części i zakupy sprzętu</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Najbezpieczniejszy model współpracy polega na tym, że klient kupuje części na swoje dane, a ja pomagam
                        w ich doborze, montażu, konfiguracji i testach. Jeżeli zakup części miałby zostać wykonany przeze mnie,
                        cena, źródło zakupu, sposób rozliczenia i odpowiedzialność za gwarancję są ustalane indywidualnie przed
                        zakupem. Gwarancja producenta albo sprzedawcy części jest realizowana zgodnie z zasadami tego sprzedawcy
                        lub producenta.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">6. Dojazd do klienta</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Pomoc z dojazdem jest możliwa głównie na terenie Jarosławia i okolic. Termin, zakres oraz szczegóły
                        wizyty są ustalane przed przyjazdem. Przy bardziej złożonych problemach może być konieczne
                        przekazanie sprzętu do dokładniejszej diagnostyki.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">7. Sprzęt i dane użytkownika</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Przed przekazaniem komputera lub laptopa warto wykonać kopię ważnych danych. Jeżeli dane są istotne,
                        klient powinien poinformować o tym przed rozpoczęciem pracy. W razie potrzeby można ustalić usługę
                        klonowania dysku albo zabezpieczenia plików przed dalszą diagnostyką. Co do zasady nie przeglądam
                        prywatnych plików klienta, chyba że jest to konieczne do wykonania uzgodnionej usługi i klient został
                        o tym poinformowany.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">8. Przekazanie sprzętu i potwierdzenie ustaleń</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Przy przekazaniu sprzętu można ustalić podstawowe informacje: model urządzenia, opis problemu,
                        widoczne uszkodzenia, przekazane akcesoria oraz orientacyjny zakres prac. Takie ustalenia pomagają
                        uniknąć nieporozumień i mogą zostać potwierdzone wiadomością e-mail, SMS-em albo wpisem w systemie.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">9. Płatność i potwierdzenie sprzedaży</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Sposób płatności ustalamy przed wykonaniem usługi. Ceny podawane klientowi są cenami do zapłaty.
                        Sprzedaż w ramach działalności nierejestrowanej zapisuję w uproszczonej ewidencji. Na prośbę klienta
                        mogę wystawić prosty rachunek albo potwierdzenie sprzedaży za wykonaną usługę. Jeżeli działalność
                        zostanie zarejestrowana, dane rozliczeniowe na stronie zostaną uzupełnione.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">10. Zakończenie usługi</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Po wykonaniu usługi klient otrzymuje informację, co zostało zrobione oraz czy są potrzebne dalsze
                        działania, np. wymiana części, obserwacja temperatur, aktualizacja systemu albo wykonanie kopii
                        zapasowej.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">11. Reklamacje i uwagi</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Jeżeli po wykonaniu usługi pojawi się problem związany z ustalonym zakresem prac, klient może
                        skontaktować się przez formularz kontaktowy albo adres e-mail wskazany na stronie. Każde zgłoszenie
                        jest analizowane indywidualnie, z uwzględnieniem rodzaju wykonanej usługi oraz stanu sprzętu.
                        Reklamacja powinna zawierać opis problemu, numer zgłoszenia, jeżeli został nadany, oraz dane kontaktowe.
                        Odpowiedź na reklamację klienta będącego konsumentem jest udzielana w ciągu 14 dni od jej otrzymania.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">12. Rezygnacja i odstąpienie</h2>
                    <p class="mt-3 leading-7 text-slate-300">
                        Jeżeli zakres, termin albo koszt nie odpowiada klientowi, można zrezygnować przed rozpoczęciem prac.
                        Jeżeli usługa jest uzgadniana na odległość, na przykład przez formularz, e-mail, telefon albo wiadomość,
                        klient będący konsumentem może mieć 14 dni na odstąpienie od umowy. Jeżeli klient poprosi o rozpoczęcie
                        usługi przed upływem tego terminu, a usługa zostanie w pełni wykonana, prawo odstąpienia może wygasnąć
                        zgodnie z przepisami. Takie rozpoczęcie usługi powinno być jasno uzgodnione przed pracą.
                    </p>
                </li>

                <li>
                    <h2 class="text-xl font-bold text-white">13. Dane osobowe</h2>
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
