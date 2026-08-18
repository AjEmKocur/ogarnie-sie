<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">CMS: Usługi</h2>
    </x-slot>

    @php
        $servicesByCategory = $services->groupBy(fn ($service) => $service->service_category_id ? 'category-'.$service->service_category_id : 'uncategorized');
        $uncategorizedServices = $servicesByCategory->get('uncategorized', collect());
    @endphp

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

            <form id="services-bulk-update-form" method="POST" action="{{ route('admin.cms.services.bulk-update') }}">
                @csrf
                @method('PATCH')
            </form>

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Kategorie usług</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Wejdź w kategorię, żeby dodać, edytować albo usunąć usługę. Zmiany w istniejących usługach zapisujesz jednym przyciskiem.
                        </p>
                    </div>

                    @if ($services->isNotEmpty())
                        <x-primary-button form="services-bulk-update-form">Zapisz wszystkie zmiany</x-primary-button>
                    @endif
                </div>
            </section>

            <div class="space-y-4">
                @foreach ($categories as $category)
                    @php
                        $categoryServices = $servicesByCategory->get('category-'.$category->id, collect());
                        $activeCount = $categoryServices->where('is_active', true)->count();
                    @endphp

                    <details class="rounded-xl border border-gray-200 bg-white shadow-sm">
                        <summary class="cursor-pointer list-none px-5 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
                                        @unless ($category->is_active)
                                            <span class="rounded-full border border-amber-300/40 px-2 py-0.5 text-xs font-semibold text-amber-300">Kategoria nieaktywna</span>
                                        @endunless
                                    </div>
                                    @if ($category->description)
                                        <p class="mt-1 max-w-3xl text-sm leading-6 text-gray-500">{{ $category->description }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-wrap items-center gap-3 text-xs">
                                    <span class="text-slate-400">Sort: {{ $category->sort_order }}</span>
                                    <span class="text-slate-400">Usługi: {{ $categoryServices->count() }}</span>
                                    <span class="text-green-400">Aktywne: {{ $activeCount }}</span>
                                </div>
                            </div>
                        </summary>

                        <div class="space-y-5 border-t border-gray-200 p-5">
                            <details class="rounded-lg border border-amber-300/30 bg-black/25">
                                <summary class="cursor-pointer list-none px-4 py-3">
                                    <span class="inline-flex items-center rounded-md border border-amber-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-200 hover:bg-amber-500/10">
                                        Dodaj usługę w tej kategorii
                                    </span>
                                </summary>

                                <div class="border-t border-gray-200 px-4 py-4">
                                    <form method="POST" action="{{ route('admin.cms.services.store') }}" class="grid gap-4 md:grid-cols-2">
                                        @csrf
                                        <input type="hidden" name="service_category_id" value="{{ $category->id }}">

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

                                        <div class="md:col-span-2 flex justify-end">
                                            <x-primary-button>Dodaj usługę</x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </details>

                            @if ($categoryServices->isEmpty())
                                <div class="rounded-lg border border-dashed border-gray-300 p-5 text-sm text-gray-500">
                                    Brak usług w tej kategorii.
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach ($categoryServices as $service)
                                        @include('admin.cms.partials.service-editor', ['service' => $service, 'categories' => $categories])
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </details>
                @endforeach

                @if ($uncategorizedServices->isNotEmpty())
                    <details class="rounded-xl border border-gray-200 bg-white shadow-sm" open>
                        <summary class="cursor-pointer list-none px-5 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">Bez kategorii</h3>
                                    <p class="mt-1 text-sm text-gray-500">Stare lub robocze usługi. Przenieś je do kategorii albo usuń, jeśli są już niepotrzebne.</p>
                                </div>
                                <span class="text-xs text-slate-400">Usługi: {{ $uncategorizedServices->count() }}</span>
                            </div>
                        </summary>

                        <div class="space-y-3 border-t border-gray-200 p-5">
                            @foreach ($uncategorizedServices as $service)
                                @include('admin.cms.partials.service-editor', ['service' => $service, 'categories' => $categories])
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>

            @if ($services->isNotEmpty())
                <div class="flex justify-end">
                    <x-primary-button form="services-bulk-update-form">Zapisz wszystkie zmiany</x-primary-button>
                </div>
            @endif

            @foreach ($services as $service)
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
</x-app-layout>
