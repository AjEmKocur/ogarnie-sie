<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nowe zgłoszenie serwisowe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('client.tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="rounded-lg border border-gray-200 bg-slate-900/30 p-4 text-sm text-slate-300">
                            {{ __('Najprostsza opcja: opisz problem i dodaj zdjęcie. My dobierzemy właściwą usługę po diagnozie.') }}
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Krótki temat zgłoszenia')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Opis problemu (co się dzieje?)')" />
                            <textarea id="description" name="description" rows="7" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="custom_request" :value="__('Dodatkowe informacje (opcjonalnie)')" />
                            <textarea id="custom_request" name="custom_request" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Np. ważne szczegóły, oczekiwany termin, preferowany kontakt">{{ old('custom_request') }}</textarea>
                            <x-input-error :messages="$errors->get('custom_request')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="attachments" :value="__('Załączniki (opcjonalnie)')" />
                            <input id="attachments" name="attachments[]" type="file" multiple class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-semibold" />
                            <p class="mt-2 text-xs text-gray-500">{{ __('Dozwolone: jpg, jpeg, png, pdf, txt, doc, docx. Maks. 10 MB na plik.') }}</p>
                            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                            <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('client.tickets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                {{ __('Anuluj') }}
                            </a>
                            <x-primary-button>{{ __('Wyślij zgłoszenie') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

