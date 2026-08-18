<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">CMS: Usługi</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Usługi'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('status') }}</div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Kategorie usług</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Kategorie działają jak foldery. Wejdź w kategorię, żeby zarządzać jej usługami.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="document.getElementById('add-category-dialog').showModal()"
                        class="inline-flex items-center rounded-md border border-amber-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-200 hover:bg-amber-500/10"
                    >
                        Dodaj kategorię
                    </button>
                </div>
            </section>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($categories as $category)
                    <a href="{{ route('admin.cms.services.categories.show', $category) }}" class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-amber-300/70 hover:bg-slate-950/60">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-semibold text-white">{{ $category->name }}</h3>
                                    @unless ($category->is_active)
                                        <span class="rounded-full border border-amber-300/40 px-2 py-0.5 text-xs font-semibold text-amber-300">Nieaktywna</span>
                                    @endunless
                                </div>
                                @if ($category->description)
                                    <p class="mt-2 text-sm leading-6 text-slate-300">{{ $category->description }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 text-xs text-amber-200">Sort: {{ $category->sort_order }}</span>
                        </div>

                        <div class="mt-5 flex flex-wrap items-center gap-3 text-xs">
                            <span class="rounded-full border border-white/10 px-3 py-1 text-slate-300">Usługi: {{ $category->services_count }}</span>
                            <span class="rounded-full border border-green-400/20 px-3 py-1 text-green-300">Aktywne: {{ $category->active_services_count }}</span>
                            <span class="ml-auto font-semibold text-amber-200 transition group-hover:translate-x-0.5">Otwórz →</span>
                        </div>
                    </a>
                @endforeach

                @if ($uncategorizedServicesCount > 0)
                    <a href="{{ route('admin.cms.services.uncategorized') }}" class="group rounded-xl border border-amber-300/30 bg-black/40 p-5 shadow-sm transition hover:border-amber-300/70 hover:bg-slate-950/60">
                        <h3 class="text-lg font-semibold text-white">Bez kategorii</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-300">
                            Stare albo robocze usługi. Przenieś je do kategorii lub usuń, jeśli są niepotrzebne.
                        </p>
                        <div class="mt-5 flex flex-wrap items-center gap-3 text-xs">
                            <span class="rounded-full border border-white/10 px-3 py-1 text-slate-300">Usługi: {{ $uncategorizedServicesCount }}</span>
                            <span class="rounded-full border border-green-400/20 px-3 py-1 text-green-300">Aktywne: {{ $uncategorizedActiveServicesCount }}</span>
                            <span class="ml-auto font-semibold text-amber-200 transition group-hover:translate-x-0.5">Otwórz →</span>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <dialog id="add-category-dialog" class="w-full max-w-2xl rounded-xl border border-amber-300/30 bg-slate-950 p-0 text-slate-100 shadow-2xl shadow-black/60 backdrop:bg-black/70">
        <form method="POST" action="{{ route('admin.cms.services.categories.store') }}">
            @csrf

            <div class="border-b border-white/10 px-5 py-4">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="text-lg font-semibold text-white">Dodaj kategorię</h3>
                    <button type="button" onclick="document.getElementById('add-category-dialog').close()" class="text-2xl leading-none text-slate-400 hover:text-white">&times;</button>
                </div>
            </div>

            <div class="grid gap-4 px-5 py-5 md:grid-cols-2">
                <label class="space-y-1 md:col-span-2">
                    <span class="text-sm font-semibold text-gray-500">Nazwa kategorii</span>
                    <input name="name" placeholder="np. Laptopy" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2" required>
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
                    <span class="text-sm font-semibold text-gray-500">Opis kategorii</span>
                    <textarea name="description" rows="4" placeholder="Krótki opis widoczny przy kategorii." class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"></textarea>
                </label>
            </div>

            <div class="flex justify-end gap-3 border-t border-white/10 px-5 py-4">
                <button type="button" onclick="document.getElementById('add-category-dialog').close()" class="rounded-md border border-white/20 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-white/10">
                    Anuluj
                </button>
                <x-primary-button>Dodaj kategorię</x-primary-button>
            </div>
        </form>
    </dialog>
</x-app-layout>
