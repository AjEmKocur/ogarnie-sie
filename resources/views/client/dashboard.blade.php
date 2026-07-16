<x-app-layout>
    @php
        $myTicketsCount = auth()->user()->tickets()->count();
        $openTicketsCount = auth()->user()->tickets()->whereIn('status', ['new', 'in_progress', 'waiting_parts'])->count();
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel klienta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">{{ __('Twoje zgłoszenia') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-100">{{ $myTicketsCount }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">{{ __('Otwarte zgłoszenia') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-100">{{ $openTicketsCount }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="p-6 text-gray-900 space-y-3">
                    <p class="text-sm uppercase tracking-[0.2em] text-gray-500">{{ __('Kocur Serwis Komputerowy') }}</p>
                    <p class="text-2xl font-semibold">{{ __('Witaj,') }} {{ auth()->user()->name }}.</p>
                    <p>{{ __('Tutaj zarządzasz zgłoszeniami serwisowymi i załącznikami.') }}</p>
                    <div class="pt-2">
                        <a href="{{ route('client.tickets.index') }}" class="inline-flex items-center rounded-md bg-amber-400 px-4 py-2 text-xs font-black uppercase tracking-widest text-black transition hover:bg-amber-300">
                            {{ __('Moje zgłoszenia') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
