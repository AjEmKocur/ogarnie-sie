<details class="rounded-lg border border-gray-200 bg-slate-950/50">
    <summary class="cursor-pointer list-none px-4 py-3">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <p class="font-semibold text-white">{{ $service->name }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $service->category?->name ?? 'Bez kategorii' }}</p>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="text-slate-400">Sort: {{ $service->sort_order }}</span>
                @if ($service->price_from !== null)
                    <span class="text-amber-300">Od {{ number_format($service->price_from, 2, ',', ' ') }} PLN</span>
                @else
                    <span class="text-slate-400">Bez ceny</span>
                @endif
                <span class="{{ $service->is_active ? 'text-green-400' : 'text-amber-300' }}">
                    {{ $service->is_active ? 'Aktywna' : 'Nieaktywna' }}
                </span>
            </div>
        </div>
    </summary>

    <div class="border-t border-gray-200 p-4">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="space-y-1">
                <span class="text-sm font-semibold text-gray-500">Kategoria</span>
                <select
                    name="services[{{ $service->id }}][service_category_id]"
                    form="services-bulk-update-form"
                    class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                >
                    <option value="">Bez kategorii</option>
                    @foreach ($categories as $categoryOption)
                        <option value="{{ $categoryOption->id }}" @selected($service->service_category_id === $categoryOption->id)>
                            {{ $categoryOption->name }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="space-y-1">
                <span class="text-sm font-semibold text-gray-500">Nazwa usługi</span>
                <input
                    name="services[{{ $service->id }}][name]"
                    form="services-bulk-update-form"
                    value="{{ $service->name }}"
                    class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                    required
                >
            </label>

            <label class="space-y-1">
                <span class="text-sm font-semibold text-gray-500">Cena od (PLN)</span>
                <input
                    name="services[{{ $service->id }}][price_from]"
                    form="services-bulk-update-form"
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
                    form="services-bulk-update-form"
                    type="number"
                    value="{{ $service->sort_order }}"
                    class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                >
            </label>

            <label class="space-y-1 md:col-span-2">
                <span class="text-sm font-semibold text-gray-500">Krótki opis</span>
                <textarea
                    name="services[{{ $service->id }}][description]"
                    form="services-bulk-update-form"
                    rows="3"
                    class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                >{{ $service->description }}</textarea>
            </label>

            <label class="space-y-1 md:col-span-2">
                <span class="text-sm font-semibold text-gray-500">Opis szczegółowy</span>
                <textarea
                    name="services[{{ $service->id }}][long_description]"
                    form="services-bulk-update-form"
                    rows="5"
                    class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                >{{ $service->long_description }}</textarea>
            </label>

            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="services[{{ $service->id }}][is_active]" value="0" form="services-bulk-update-form">
                <input type="checkbox" name="services[{{ $service->id }}][is_active]" value="1" form="services-bulk-update-form" @checked($service->is_active)>
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
