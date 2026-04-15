<x-app-layout>
    @php
        $allTicketsCount = \App\Models\Ticket::count();
        $activeTicketsCount = \App\Models\Ticket::whereIn('status', ['new', 'in_progress', 'waiting_parts'])->count();
        $servicesCount = \App\Models\Service::count();
        $publishedPostsCount = \App\Models\BlogPost::where('is_published', true)->count();
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel administratora
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna'],
                ],
            ])

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Wszystkie zgłoszenia</p>
                    <p class="mt-2 text-3xl font-bold text-gray-100">{{ $allTicketsCount }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Aktywne zgłoszenia</p>
                    <p class="mt-2 text-3xl font-bold text-gray-100">{{ $activeTicketsCount }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Usługi w ofercie</p>
                    <p class="mt-2 text-3xl font-bold text-gray-100">{{ $servicesCount }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Opublikowane wpisy</p>
                    <p class="mt-2 text-3xl font-bold text-gray-100">{{ $publishedPostsCount }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="p-6 text-gray-900 space-y-4">
                    <p class="text-sm uppercase tracking-[0.2em] text-gray-500">Centrum sterowania</p>
                    <p class="text-2xl font-semibold">Witaj, {{ auth()->user()->name }}.</p>
                    <p>To panel operacyjny do zarządzania zgłoszeniami, usługami, cennikiem, aktualnościami i kontaktem.</p>
                    <div class="pt-2 flex flex-wrap gap-3">
                        <a href="{{ route('admin.cms.dashboard') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-blue-500">Centrum CMS</a>
                        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-200 hover:bg-slate-800">Zgłoszenia</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
