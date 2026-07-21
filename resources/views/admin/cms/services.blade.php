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
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Kategorie usług</h3>
                        <p class="mt-1 text-sm text-gray-500">Kategorie porządkują ofertę na stronie publicznej. Przy dodawaniu usługi wybierz, do której grupy należy.</p>
                    </div>
                    <a href="#lista-uslug" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                        Lista usług
                    </a>
                </div>

                <div class="mt-4 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($categories as $category)
                        <article class="rounded-lg border border-gray-200 bg-slate-950/50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="font-semibold text-white">{{ $category->name }}</h4>
                                <span class="text-xs text-amber-200">Sort: {{ $category->sort_order }}</span>
                            </div>
                            @if ($category->description)
                                <p class="mt-2 text-sm leading-6 text-slate-300">{{ $category->description }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>

            <details class="rounded-xl border border-gray-200 bg-white shadow-sm" @if($services->isEmpty()) open @endif>
                <summary class="cursor-pointer list-none px-5 py-4">
                    <span class="inline-flex items-center rounded-md border border-amber-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-amber-200 hover:bg-amber-500/10">
                        {{ $services->isEmpty() ? 'Dodaj pierwszą usługę' : 'Dodaj kolejną usługę' }}
                    </span>
                </summary>

                <div class="border-t border-gray-200 px-5 py-4">
                    <form method="POST" action="{{ route('admin.cms.services.store') }}" class="grid gap-4 md:grid-cols-2">
                        @csrf

                        <label class="space-y-1">
                            <span class="text-sm font-semibold text-gray-500">Kategoria</span>
                            <select name="service_category_id" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                                <option value="">Bez kategorii</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="space-y-1">
                            <span class="text-sm font-semibold text-gray-500">Nazwa usługi</span>
                            <input
                                name="name"
                                placeholder="np. Składanie komputera z części klienta"
                                class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                required
                            >
                        </label>

                        <label class="space-y-1">
                            <span class="text-sm font-semibold text-gray-500">Cena od (PLN)</span>
                            <input
                                name="price_from"
                                type="number"
                                step="0.01"
                                placeholder="np. 200"
                                class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                            >
                        </label>

                        <label class="space-y-1">
                            <span class="text-sm font-semibold text-gray-500">Kolejność</span>
                            <input
                                name="sort_order"
                                type="number"
                                value="0"
                                class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                            >
                        </label>

                        <label class="space-y-1 md:col-span-2">
                            <span class="text-sm font-semibold text-gray-500">Krótki opis</span>
                            <textarea
                                name="description"
                                rows="3"
                                placeholder="Widoczny na karcie usługi w cenniku."
                                class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                            ></textarea>
                        </label>

                        <label class="space-y-1 md:col-span-2">
                            <span class="text-sm font-semibold text-gray-500">Opis szczegółowy</span>
                            <textarea
                                name="long_description"
                                rows="6"
                                placeholder="Widoczny na podstronie konkretnej usługi."
                                class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                            ></textarea>
                        </label>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Aktywna
                        </label>

                        <div class="md:col-span-2 flex justify-end">
                            <x-primary-button>Dodaj usługę</x-primary-button>
                        </div>
                    </form>
                </div>
            </details>

            @if ($services->isNotEmpty())
                <form id="services-bulk-update-form" method="POST" action="{{ route('admin.cms.services.bulk-update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div id="lista-uslug" class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold">Edytuj usługi</h3>
                        <x-primary-button>Zapisz wszystkie zmiany</x-primary-button>
                    </div>

                    @foreach ($services as $service)
                        <details class="rounded-xl border border-gray-200 bg-white shadow-sm">
                            <summary class="cursor-pointer list-none px-5 py-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="font-semibold">{{ $service->name }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $service->category?->name ?? 'Bez kategorii' }}</p>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs">
                                        <span class="text-slate-400">Sort: {{ $service->sort_order }}</span>
                                        @if ($service->price_from !== null)
                                            <span class="text-blue-300">Od {{ number_format($service->price_from, 2, ',', ' ') }} PLN</span>
                                        @else
                                            <span class="text-slate-400">Bez ceny</span>
                                        @endif
                                        <span class="{{ $service->is_active ? 'text-green-400' : 'text-amber-300' }}">
                                            {{ $service->is_active ? 'Aktywna' : 'Nieaktywna' }}
                                        </span>
                                    </div>
                                </div>
                            </summary>

                            <div class="border-t border-gray-200 p-5">
                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="space-y-1">
                                        <span class="text-sm font-semibold text-gray-500">Kategoria</span>
                                        <select
                                            name="services[{{ $service->id }}][service_category_id]"
                                            class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                        >
                                            <option value="">Bez kategorii</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected($service->service_category_id === $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="space-y-1">
                                        <span class="text-sm font-semibold text-gray-500">Nazwa usługi</span>
                                        <input
                                            name="services[{{ $service->id }}][name]"
                                            value="{{ $service->name }}"
                                            class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                            required
                                        >
                                    </label>

                                    <label class="space-y-1">
                                        <span class="text-sm font-semibold text-gray-500">Cena od (PLN)</span>
                                        <input
                                            name="services[{{ $service->id }}][price_from]"
                                            type="number"
                                            step="0.01"
                                            value="{{ $service->price_from }}"
                                            class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                        >
                                    </label>

                                    <label class="space-y-1">
                                        <span class="text-sm font-semibold text-gray-500">Kolejność</span>
                                        <input
                                            name="services[{{ $service->id }}][sort_order]"
                                            type="number"
                                            value="{{ $service->sort_order }}"
                                            class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                        >
                                    </label>

                                    <label class="space-y-1 md:col-span-2">
                                        <span class="text-sm font-semibold text-gray-500">Krótki opis</span>
                                        <textarea
                                            name="services[{{ $service->id }}][description]"
                                            rows="3"
                                            class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                        >{{ $service->description }}</textarea>
                                    </label>

                                    <label class="space-y-1 md:col-span-2">
                                        <span class="text-sm font-semibold text-gray-500">Opis szczegółowy</span>
                                        <textarea
                                            name="services[{{ $service->id }}][long_description]"
                                            rows="6"
                                            class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                        >{{ $service->long_description }}</textarea>
                                    </label>

                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="hidden" name="services[{{ $service->id }}][is_active]" value="0">
                                        <input type="checkbox" name="services[{{ $service->id }}][is_active]" value="1" @checked($service->is_active)>
                                        Aktywna
                                    </label>
                                </div>

                                <div class="mt-3">
                                    <button
                                        type="submit"
                                        form="delete-service-{{ $service->id }}"
                                        class="text-sm text-red-600"
                                    >
                                        Usuń
                                    </button>
                                </div>
                            </div>
                        </details>
                    @endforeach

                    <div class="flex justify-end">
                        <x-primary-button>Zapisz wszystkie zmiany</x-primary-button>
                    </div>
                </form>

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
            @endif
        </div>
    </div>
</x-app-layout>
