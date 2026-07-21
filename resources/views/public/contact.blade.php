@extends('layouts.public')

@section('title', 'Kontakt - Kocur Serwis Komputerowy')
@section('meta_description', 'Kontakt w sprawie składania komputerów, diagnostyki laptopów, modernizacji sprzętu, instalacji systemu i pomocy z siecią domową w Jarosławiu i okolicach.')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-black text-white">Kontakt</h1>
        <p class="mt-4 max-w-2xl text-slate-300">
            Opisz krótko problem, sprzęt albo planowany zestaw. Po wiadomości ustalimy zakres, orientacyjny koszt i możliwy termin.
        </p>
        <p class="mt-2 text-sm text-amber-200">
            Formularz działa dla gości. Konto klienta przyda się dopiero wtedy, gdy chcesz prowadzić pełne zgłoszenie w panelu.
        </p>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-6">
                <h2 class="text-xl font-semibold text-white">Dane kontaktowe</h2>
                <ul class="mt-4 space-y-2 text-sm text-slate-300">
                    <li>Email: kocurserwis@gmail.com</li>
                    <li>Obszar: Jarosław i okolice</li>
                    <li>Dojazd: ustalany indywidualnie przed usługą</li>
                    <li>Godziny kontaktu: Pn-Pt 8:00-18:00</li>
                </ul>

                <div class="mt-6 overflow-hidden rounded-lg border border-amber-300/25 bg-slate-950/40">
                    <iframe
                        title="Mapa obszaru działania"
                        src="https://maps.google.com/maps?hl=pl&q=Jaros%C5%82aw%2C%20Polska&z=13&output=embed"
                        class="w-full"
                        style="display:block;width:100%;height:320px;border:0;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>

            <div class="rounded-xl border border-amber-300/20 bg-slate-950/70 p-6">
                <h2 class="text-xl font-semibold text-white">Opisz problem</h2>
                <form method="POST" action="{{ route('public.contact.store') }}" class="mt-4 space-y-4">
                    @csrf

                    <div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Imię i nazwisko" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Telefon (opcjonalnie)" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Temat, np. składanie PC albo problem z laptopem" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>

                    <div>
                        <textarea name="message" rows="5" placeholder="Opisz sprzęt, objawy, planowany zestaw albo czego potrzebujesz" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>

                    @if (config('services.turnstile.enabled') && config('services.turnstile.site_key'))
                        <div>
                            <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="dark"></div>
                            <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
                            <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2" />
                        </div>
                    @endif

                    <p class="text-xs leading-5 text-slate-400">
                        Administratorem danych jest Dominik Kocur. Wysyłając formularz, przekazujesz dane w celu obsługi wiadomości
                        i ewentualnego ustalenia szczegółów usługi. Szczegóły znajdziesz w
                        <a href="{{ route('public.privacy') }}" class="text-amber-200 underline underline-offset-4 hover:text-amber-100">polityce prywatności</a>.
                    </p>

                    <button type="submit" class="rounded-md bg-amber-400 px-5 py-3 text-sm font-black text-black shadow-[0_18px_40px_rgba(245,158,11,0.22)] transition hover:bg-amber-300">
                        Wyślij wiadomość
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if (config('services.turnstile.enabled') && config('services.turnstile.site_key'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endsection
