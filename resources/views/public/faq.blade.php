@extends('layouts.public')

@section('title', 'FAQ - Kocur Serwis Komputerowy')
@section('meta_description', 'Najczęstsze pytania o składanie komputerów, diagnostykę, dojazd do klienta, części, wycenę i płatność.')

@section('content')
    <section class="mx-auto max-w-5xl px-5 py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-200">FAQ</p>
            <h1 class="mt-3 text-4xl font-black text-white">Najczęstsze pytania</h1>
            <p class="mt-5 text-base leading-8 text-slate-300">
                Poniżej znajdują się krótkie odpowiedzi na pytania, które mogą pojawić się przed zgłoszeniem usługi.
                Szczegóły konkretnego zlecenia zawsze ustalam indywidualnie przed rozpoczęciem pracy.
            </p>
        </div>

        <div class="mt-10 space-y-4">
            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5" open>
                <summary class="cursor-pointer text-lg font-bold text-white">Czy muszę od razu zakładać konto?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Nie. Możesz najpierw wysłać krótką wiadomość przez formularz kontaktowy. Konto przydaje się wtedy,
                    gdy chcesz prowadzić zgłoszenie w panelu, śledzić status i mieć historię wiadomości.
                </p>
            </details>

            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <summary class="cursor-pointer text-lg font-bold text-white">Czy części kupuje klient, czy serwis?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Najczyściej jest, gdy części kupuje klient na swoje dane. Mogę pomóc dobrać podzespoły, sprawdzić
                    kompatybilność i złożyć zestaw. Zakup części przeze mnie jest możliwy tylko po wcześniejszym,
                    jasnym ustaleniu zakresu, ceny i sposobu rozliczenia.
                </p>
            </details>

            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <summary class="cursor-pointer text-lg font-bold text-white">Czy cena jest znana przed usługą?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Tak, przed rozpoczęciem pracy podaję orientacyjny koszt i zakres. Jeżeli w trakcie diagnostyki wyjdzie,
                    że problem jest większy, dodatkowe prace są ustalane przed ich wykonaniem.
                </p>
            </details>

            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <summary class="cursor-pointer text-lg font-bold text-white">Czy dojeżdżasz do klienta?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Tak, przy prostszych tematach możliwy jest dojazd do klienta w Jarosławiu i okolicach. Dotyczy to
                    między innymi konfiguracji Wi-Fi, routera, drukarki, switcha albo podstawowej diagnostyki.
                </p>
            </details>

            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <summary class="cursor-pointer text-lg font-bold text-white">Czy naprawiasz laptopy?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Tak, w zakresie diagnostyki, sprawdzenia RAM-u, dysku, temperatur, systemu, sterowników i klonowania danych.
                    Nie deklaruję napraw wymagających zaawansowanego lutowania płyt głównych albo napraw po zalaniu bez
                    wcześniejszej weryfikacji.
                </p>
            </details>

            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <summary class="cursor-pointer text-lg font-bold text-white">Co z danymi na dysku?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Przed przekazaniem sprzętu najlepiej wykonać kopię ważnych danych. Jeżeli dane są istotne, powiedz o tym
                    przed diagnostyką. Wtedy można najpierw ustalić klonowanie dysku albo zabezpieczenie plików.
                </p>
            </details>

            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <summary class="cursor-pointer text-lg font-bold text-white">Czy mogę dostać potwierdzenie płatności?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Tak, przy działalności nierejestrowanej możliwe jest przygotowanie prostego potwierdzenia sprzedaży
                    albo rachunku na żądanie klienta. Po rejestracji działalności dane rozliczeniowe na stronie zostaną
                    uzupełnione.
                </p>
            </details>

            <details class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-5">
                <summary class="cursor-pointer text-lg font-bold text-white">Czy mogę zgłosić uwagę po wykonaniu usługi?</summary>
                <p class="mt-4 leading-7 text-slate-300">
                    Tak. Jeżeli problem dotyczy ustalonego zakresu prac, najlepiej napisać przez formularz albo e-mail.
                    Każde zgłoszenie jest sprawdzane indywidualnie, z uwzględnieniem stanu sprzętu i tego, co było ustalone.
                </p>
            </details>
        </div>
    </section>
@endsection
