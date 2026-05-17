<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        @if (!empty($returnTo))
            <input type="hidden" name="return_to" value="{{ $returnTo }}">
        @endif

        <div>
            <x-input-label for="email" :value="'E-mail'" />
            <x-text-input id="email" class="mt-1 block w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="'Hasło'" />
            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-12" type="password" name="password" required autocomplete="current-password" />
                @include('partials.password-visibility-toggle', ['inputId' => 'password'])
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Zapamiętaj mnie</span>
            </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
            @if (Route::has('password.request'))
                <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" href="{{ route('password.request') }}">
                    Nie pamiętasz hasła?
                </a>
            @endif

            <x-primary-button class="ms-3">
                Zaloguj
            </x-primary-button>
        </div>

        @if (Route::has('register'))
            <p class="mt-4 text-right text-sm text-gray-500">
                Nie masz konta?
                <a class="text-gray-300 underline hover:text-white" href="{{ route('register', !empty($returnTo) ? ['return' => $returnTo] : []) }}">
                    Utwórz konto
                </a>
            </p>
        @endif
    </form>

    <div class="mt-6 rounded-lg border border-blue-500/40 bg-blue-950/20 p-4 text-sm">
        <p class="text-slate-200">Nie chcesz zakładać konta?</p>
        <p class="mt-1 text-slate-400">Możesz napisać do nas jako gość.</p>
        <a href="{{ route('public.contact') }}" class="mt-3 inline-flex rounded-md border border-blue-500/60 px-3 py-2 font-semibold text-blue-200 hover:bg-slate-800">
            Szybki kontakt
        </a>
    </div>
</x-guest-layout>



