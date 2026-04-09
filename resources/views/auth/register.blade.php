<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        @if (request()->filled('return'))
            <input type="hidden" name="return_to" value="{{ request('return') }}">
        @endif

        <div>
            <x-input-label for="name" :value="'Imię i nazwisko'" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="'Hasło'" />
            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-12" type="password" name="password" required autocomplete="new-password" />
                @include('partials.password-visibility-toggle', ['inputId' => 'password'])
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            @include('partials.password-rules', ['passwordId' => 'password', 'confirmationId' => 'password_confirmation'])
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Potwierdź hasło'" />
            <div class="relative mt-1">
                <x-text-input id="password_confirmation" class="block w-full pr-12" type="password" name="password_confirmation" required autocomplete="new-password" />
                @include('partials.password-visibility-toggle', ['inputId' => 'password_confirmation'])
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" href="{{ route('login', request()->filled('return') ? ['return' => request('return')] : []) }}">
                Masz już konto?
            </a>

            <x-primary-button class="ms-4">
                Zarejestruj
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 rounded-lg border border-blue-500/40 bg-blue-950/20 p-4 text-sm">
        <p class="text-slate-200">Nie chcesz zakładać konta?</p>
        <p class="mt-1 text-slate-400">Możesz napisać do nas jako gość.</p>
        <a href="{{ route('public.contact') }}" class="mt-3 inline-flex rounded-md border border-blue-500/60 px-3 py-2 font-semibold text-blue-200 hover:bg-slate-800">
            Szybki kontakt
        </a>
    </div>
</x-guest-layout>


