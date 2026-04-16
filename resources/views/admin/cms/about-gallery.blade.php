<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">CMS: Galeria "O nas"</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Galeria O nas'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <p class="font-semibold">Sprawdź formularz i popraw błędy.</p>
                    <ul class="mt-2 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-2">
                <a href="#lista-zdjec" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                    Lista zdjęć
                </a>
            </div>

            <details class="rounded-xl border border-gray-200 bg-white shadow-sm" @if($images->isEmpty()) open @endif>
                <summary class="cursor-pointer list-none px-5 py-4">
                    <span class="inline-flex items-center rounded-md border border-blue-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-200 hover:bg-blue-500/10">
                        {{ $images->isEmpty() ? 'Dodaj pierwsze zdjęcie' : 'Dodaj kolejne zdjęcie' }}
                    </span>
                </summary>

                <div class="border-t border-gray-200 px-5 py-4">
                    <p class="text-sm text-slate-300">Formaty: jpg, png, webp. Maks. 5 MB.</p>

                    <form method="POST" action="{{ route('admin.cms.about-gallery.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-2">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium">Plik zdjęcia</label>
                            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required class="block w-full rounded-md border border-gray-300 bg-white text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-semibold">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Podpis (opcjonalnie)</label>
                            <input type="text" name="caption" value="{{ old('caption') }}" maxlength="255" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                        </div>
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-blue-500">
                                Dodaj zdjęcie
                            </button>
                        </div>
                    </form>
                </div>
            </details>

            <div id="lista-zdjec" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold">Aktualne zdjęcia</h3>

                <div class="mt-4 space-y-4">
                    @forelse ($images as $image)
                        <details class="rounded-lg border border-gray-200">
                            <summary class="cursor-pointer list-none px-4 py-3">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $image->publicUrl() }}" alt="Zdjęcie galerii" class="h-12 w-16 rounded-md object-cover border border-gray-200">
                                        <div>
                                            <p class="text-sm font-semibold">{{ $image->caption ?: 'Bez podpisu' }}</p>
                                            <p class="text-xs text-slate-400">Kolejność: {{ $image->sort_order }} · {{ $image->is_active ? 'Widoczne' : 'Ukryte' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </summary>

                            <div class="border-t border-gray-200 p-4">
                                <div class="grid gap-4 md:grid-cols-[220px,1fr]">
                                    <img src="{{ $image->publicUrl() }}" alt="Zdjęcie galerii" class="h-40 w-full rounded-lg object-cover border border-gray-200">

                                    <div>
                                        <form method="POST" action="{{ route('admin.cms.about-gallery.update', $image) }}" class="grid gap-3 md:grid-cols-2">
                                            @csrf
                                            @method('PATCH')

                                            <div class="md:col-span-2">
                                                <label class="mb-1 block text-sm font-medium">Podpis</label>
                                                <input type="text" name="caption" value="{{ $image->caption }}" maxlength="255" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-sm font-medium">Kolejność</label>
                                                <input type="number" min="0" max="100000" name="sort_order" value="{{ $image->sort_order }}" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                                            </div>

                                            <label class="flex items-center gap-2 self-end pb-2">
                                                <input type="checkbox" name="is_active" value="1" @checked($image->is_active) class="rounded border-gray-300 bg-slate-900 text-blue-500">
                                                <span class="text-sm">Widoczne na stronie</span>
                                            </label>

                                            <div class="md:col-span-2 flex flex-wrap justify-end gap-2">
                                                <button
                                                    type="submit"
                                                    form="delete-about-image-{{ $image->id }}"
                                                    class="rounded-md border border-red-400/50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-red-200 hover:bg-red-900/20"
                                                >
                                                    Usuń
                                                </button>
                                                <button type="submit" class="rounded-md border border-blue-400/50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-100 hover:bg-slate-800">
                                                    Zapisz
                                                </button>
                                            </div>
                                        </form>

                                        <form
                                            id="delete-about-image-{{ $image->id }}"
                                            method="POST"
                                            action="{{ route('admin.cms.about-gallery.destroy', $image) }}"
                                            class="hidden"
                                            onsubmit="return confirm('Usunąć zdjęcie z galerii?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-slate-300">Brak zdjęć w galerii. Dodaj pierwsze zdjęcie powyżej.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
