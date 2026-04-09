<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">CMS: Usługi</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('status') }}</div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold">Dodaj usługę</h3>
                <form method="POST" action="{{ route('admin.cms.services.store') }}" class="mt-4 grid gap-4 md:grid-cols-2">
                    @csrf
                    <input
                        name="name"
                        placeholder="Nazwa usługi"
                        class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                        required
                    >
                    <input
                        name="price_from"
                        type="number"
                        step="0.01"
                        placeholder="Cena od (PLN)"
                        class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                    >
                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Krótki opis (karta usługi)"
                        class="md:col-span-2 rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                    ></textarea>
                    <textarea
                        name="long_description"
                        rows="6"
                        placeholder="Opis szczegółowy (podstrona O usłudze)"
                        class="md:col-span-2 rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                    ></textarea>
                    <input
                        name="sort_order"
                        type="number"
                        value="0"
                        class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                    >
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Aktywna
                    </label>
                    <div class="md:col-span-2 flex justify-end">
                        <x-primary-button>Dodaj</x-primary-button>
                    </div>
                </form>
            </div>

            @if ($services->isNotEmpty())
                <form method="POST" action="{{ route('admin.cms.services.bulk-update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="flex justify-end">
                        <x-primary-button>Zapisz wszystkie zmiany</x-primary-button>
                    </div>

                    @foreach ($services as $service)
                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="grid gap-4 md:grid-cols-2">
                                <input
                                    name="services[{{ $service->id }}][name]"
                                    value="{{ $service->name }}"
                                    class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                    required
                                >
                                <input
                                    name="services[{{ $service->id }}][price_from]"
                                    type="number"
                                    step="0.01"
                                    value="{{ $service->price_from }}"
                                    class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                >
                                <textarea
                                    name="services[{{ $service->id }}][description]"
                                    rows="3"
                                    class="md:col-span-2 rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                >{{ $service->description }}</textarea>
                                <textarea
                                    name="services[{{ $service->id }}][long_description]"
                                    rows="6"
                                    class="md:col-span-2 rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                >{{ $service->long_description }}</textarea>
                                <input
                                    name="services[{{ $service->id }}][sort_order]"
                                    type="number"
                                    value="{{ $service->sort_order }}"
                                    class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"
                                >
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

