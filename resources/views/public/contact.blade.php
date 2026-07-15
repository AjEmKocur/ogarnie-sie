@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Kontakt</h1>
        <p class="mt-4 text-slate-300">
            Masz pytanie? Skontaktuj się z nami lub od razu załóż zgłoszenie.
        </p>
        <p class="mt-2 text-sm text-blue-200">
            Formularz kontaktowy działa dla gości. Nie musisz mieć konta.
        </p>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-xl font-semibold">Dane kontaktowe</h2>
                <ul class="mt-4 space-y-2 text-sm text-slate-300">
                    <li>Telefon: +48 500 600 700</li>
                    <li>Email: kontakt@ogarniesie.pl</li>
                    <li>Adres: ul. Serwisowa 12, 00-001 Miasto</li>
                    <li>Godziny: Pn-Pt 9:00-18:00</li>
                </ul>

                <div class="mt-6 overflow-hidden rounded-lg border border-blue-400/30 bg-slate-950/40">
                    <iframe
                        title="Mapa lokalizacji firmy"
                        src="https://maps.google.com/maps?hl=pl&q=Rzesz%C3%B3w%2C%20Polska&z=13&output=embed"
                        class="w-full"
                        style="display:block;width:100%;height:320px;border:0;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>

            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-xl font-semibold">Szybki kontakt</h2>
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
                        <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Temat" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>

                    <div>
                        <textarea name="message" rows="5" placeholder="Wiadomość" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">{{ old('message') }}</textarea>
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
                        Wysyłając formularz, przekazujesz dane w celu obsługi wiadomości. Szczegóły znajdziesz w
                        <a href="{{ route('public.privacy') }}" class="text-amber-200 underline underline-offset-4 hover:text-amber-100">polityce prywatności</a>.
                    </p>

                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                        Wyślij
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if (config('services.turnstile.enabled') && config('services.turnstile.site_key'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endsection
