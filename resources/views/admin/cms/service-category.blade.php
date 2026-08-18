<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">CMS: {{ $category?->name ?? 'Bez kategorii' }}</h2>
    </x-slot>

    @php
        $categoryServices = $category?->services ?? $services;
    @endphp

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Usługi', 'url' => route('admin.cms.services.index')],
                    ['label' => $category?->name ?? 'Bez kategorii'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('status') }}</div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <a href="{{ route('admin.cms.services.index') }}" class="text-sm font-semibold text-amber-200 hover:text-amber-100">← Wróć do kategorii</a>
                        <h3 class="mt-3 text-2xl font-semibold text-white">{{ $category?->name ?? 'Bez kategorii' }}</h3>
                        <p class="mt-1 max-w-3xl text-sm leading-6 text-gray-500">
                            {{ $category?->description ?? 'Stare albo robocze usługi bez przypisanej kategorii.' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if ($category)
                            <button type="button" onclick="document.getElementById('edit-category-dialog').showModal()" class="rounded-md border border-white/20 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-white/10">
                                Edytuj kategorię
                            </button>
                        @endif
                        <button type="button" onclick="document.getElementById('add-service-dialog').showModal()" class="inline-flex items-center rounded-md border border-amber-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-200 hover:bg-amber-500/10">
                            Dodaj usługę
                        </button>
                    </div>
                </div>
            </section>

            <form id="services-bulk-update-form" method="POST" action="{{ route('admin.cms.services.bulk-update') }}">
                @csrf
                @method('PATCH')
            </form>

            @if ($categoryServices->isEmpty())
                <section class="rounded-xl border border-dashed border-gray-300 bg-white p-7 text-sm text-gray-500">
                    Brak usług w tej kategorii.
                </section>
            @else
                <section class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold">Usługi</h3>
                        <x-primary-button form="services-bulk-update-form">Zapisz zmiany</x-primary-button>
                    </div>

                    @foreach ($categoryServices as $service)
                        @include('admin.cms.partials.service-editor', ['service' => $service, 'categories' => $categories])
                    @endforeach

                    <div class="flex justify-end">
                        <x-primary-button form="services-bulk-update-form">Zapisz zmiany</x-primary-button>
                    </div>
                </section>
            @endif

            @if ($category && $categoryServices->isEmpty())
                <form method="POST" action="{{ route('admin.cms.services.categories.destroy', $category) }}" onsubmit="return confirm('Usunąć kategorię?');">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm font-semibold text-red-500 hover:text-red-400">Usuń pustą kategorię</button>
                </form>
            @endif

            @foreach ($categoryServices as $service)
                <form
                    id="delete-service-{{ $service->id }}"
                    method="POST"
                    action="{{ route('admin.cms.services.destroy', $service) }}"
                    class="hidden"
                    onsubmit="return confirm('Usunąć usługę?');"
                >
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>

    <dialog id="add-service-dialog" class="w-full max-w-3xl rounded-xl border border-amber-300/30 bg-slate-950 p-0 text-slate-100 shadow-2xl shadow-black/60 backdrop:bg-black/70">
        <form method="POST" action="{{ route('admin.cms.services.store') }}">
            @csrf
            <input type="hidden" name="service_category_id" value="{{ $category?->id }}">

            <div class="border-b border-white/10 px-5 py-4">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="text-lg font-semibold text-white">Dodaj usługę</h3>
                    <button type="button" onclick="document.getElementById('add-service-dialog').close()" class="text-2xl leading-none text-slate-400 hover:text-white">&times;</button>
                </div>
            </div>

            <div class="grid gap-4 px-5 py-5 md:grid-cols-2">
                <label class="space-y-1">
                    <span class="text-sm font-semibold text-gray-500">Nazwa usługi</span>
                    <input name="name" placeholder="np. Czyszczenie laptopa" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" required>
                </label>

                <label class="space-y-1">
                    <span class="text-sm font-semibold text-gray-500">Cena od (PLN)</span>
                    <input name="price_from" type="number" step="0.01" placeholder="np. 120" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                </label>

                <label class="space-y-1">
                    <span class="text-sm font-semibold text-gray-500">Kolejność</span>
                    <input name="sort_order" type="number" value="0" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                </label>

                <label class="flex items-center gap-2 self-end text-sm">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Aktywna
                </label>

                <label class="space-y-1 md:col-span-2">
                    <span class="text-sm font-semibold text-gray-500">Krótki opis</span>
                    <textarea name="description" rows="3" placeholder="Widoczny na podstronie szczegółów usługi." class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"></textarea>
                </label>

                <label class="space-y-1 md:col-span-2">
                    <span class="text-sm font-semibold text-gray-500">Opis szczegółowy</span>
                    <textarea name="long_description" rows="5" placeholder="Dłuższy opis na stronie konkretnej usługi." class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"></textarea>
                </label>
            </div>

            <div class="flex justify-end gap-3 border-t border-white/10 px-5 py-4">
                <button type="button" onclick="document.getElementById('add-service-dialog').close()" class="rounded-md border border-white/20 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-white/10">
                    Anuluj
                </button>
                <x-primary-button>Dodaj usługę</x-primary-button>
            </div>
        </form>
    </dialog>

    @if ($category)
        <dialog id="edit-category-dialog" class="w-full max-w-3xl rounded-xl border border-amber-300/30 bg-slate-950 p-0 text-slate-100 shadow-2xl shadow-black/60 backdrop:bg-black/70">
            <form method="POST" action="{{ route('admin.cms.services.categories.update', $category) }}">
                @csrf
                @method('PATCH')

                <div class="border-b border-white/10 px-5 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-white">Edytuj kategorię</h3>
                        <button type="button" onclick="document.getElementById('edit-category-dialog').close()" class="text-2xl leading-none text-slate-400 hover:text-white">&times;</button>
                    </div>
                </div>

                <div class="grid gap-4 px-5 py-5 md:grid-cols-2">
                    <label class="space-y-1 md:col-span-2">
                        <span class="text-sm font-semibold text-gray-500">Nazwa kategorii</span>
                        <input name="name" value="{{ $category->name }}" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" required>
                    </label>

                    <label class="space-y-1">
                        <span class="text-sm font-semibold text-gray-500">Kolejność</span>
                        <input name="sort_order" type="number" value="{{ $category->sort_order }}" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                    </label>

                    <label class="flex items-center gap-2 self-end text-sm">
                        <input type="checkbox" name="is_active" value="1" @checked($category->is_active)>
                        Aktywna
                    </label>

                    <label class="space-y-1 md:col-span-2">
                        <span class="text-sm font-semibold text-gray-500">Opis kategorii</span>
                        <textarea name="description" rows="4" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">{{ $category->description }}</textarea>
                    </label>
                </div>

                <div class="flex justify-end gap-3 border-t border-white/10 px-5 py-4">
                    <button type="button" onclick="document.getElementById('edit-category-dialog').close()" class="rounded-md border border-white/20 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-white/10">
                        Anuluj
                    </button>
                    <x-primary-button>Zapisz kategorię</x-primary-button>
                </div>
            </form>
        </dialog>
    @endif
</x-app-layout>
