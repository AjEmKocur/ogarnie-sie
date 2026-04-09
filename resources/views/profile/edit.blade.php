<x-app-layout>
    @if (auth()->user()->force_password_change)
        <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 px-4">
            <div class="rounded-xl border border-blue-400/40 bg-slate-900 p-5 shadow-2xl" style="width:min(92vw,32rem);max-width:32rem;">
                <h3 class="text-lg font-semibold text-white">Zmiana hasła wymagana</h3>
                <p class="mt-2 text-sm text-slate-300">
                    To pierwsze logowanie na koncie operatora. Ustaw teraz własne hasło, aby przejść dalej.
                </p>
                <div class="mt-4 flex justify-end">
                    <button
                        type="button"
                        @click="open = false; document.getElementById('password-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' }); document.getElementById('update_password_password')?.focus();"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500"
                    >
                        Przejdź do zmiany hasła
                    </button>
                </div>
            </div>
        </div>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div id="password-section" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
