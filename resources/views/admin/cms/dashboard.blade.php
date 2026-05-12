<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Centrum sterowania CMS</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS'],
                ],
            ])

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @if (auth()->user()->hasAdminPermission('tickets'))
                    <a href="{{ route('admin.tickets.index') }}" class="group rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:!border-blue-400/80 hover:!bg-slate-800/80 transition-all duration-200 min-h-[220px] flex flex-col">
                        <p class="text-sm uppercase tracking-wider text-gray-500">Obsługa</p>
                        <h3 class="mt-2 text-xl font-semibold">Zgłoszenia serwisowe</h3>
                        <p class="mt-2 text-base text-slate-300">Statusy, notatki, płatności i komunikacja z klientem.</p>
                        <div class="mt-auto pt-6 flex justify-center text-blue-300/90">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/>
                            </svg>
                        </div>
                    </a>
                @endif

                @if (auth()->user()->hasAdminPermission('cms_services'))
                    <a href="{{ route('admin.cms.services.index') }}" class="group rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:!border-blue-400/80 hover:!bg-slate-800/80 transition-all duration-200 min-h-[220px] flex flex-col">
                        <p class="text-sm uppercase tracking-wider text-gray-500">Oferta</p>
                        <h3 class="mt-2 text-xl font-semibold">Usługi (z cenami)</h3>
                        <p class="mt-2 text-base text-slate-300">Dodawanie i edycja usług wraz z ceną od widoczną na stronie.</p>
                        <div class="mt-auto pt-6 flex justify-center text-cyan-300/90">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.7 6.3 3 3m-9.4 9.4 8.9-8.9a2.12 2.12 0 1 0-3-3l-8.9 8.9-.6 3.6 3.6-.6Z"/>
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('admin.cms.about-gallery.index') }}" class="group rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:!border-blue-400/80 hover:!bg-slate-800/80 transition-all duration-200 min-h-[220px] flex flex-col">
                        <p class="text-sm uppercase tracking-wider text-gray-500">Treści</p>
                        <h3 class="mt-2 text-xl font-semibold">Galeria O nas</h3>
                        <p class="mt-2 text-base text-slate-300">Dodawanie zdjęć serwisu do sekcji O nas (slider publiczny).</p>
                        <div class="mt-auto pt-6 flex justify-center text-emerald-300/90">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 15 5-5 4 4 3-3 6 6"/>
                                <circle cx="9" cy="9" r="1.5" />
                            </svg>
                        </div>
                    </a>
                @endif

                @if (auth()->user()->hasAdminPermission('cms_news'))
                    <a href="{{ route('admin.cms.news.index') }}" class="group rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:!border-blue-400/80 hover:!bg-slate-800/80 transition-all duration-200 min-h-[220px] flex flex-col">
                        <p class="text-sm uppercase tracking-wider text-gray-500">Treści</p>
                        <h3 class="mt-2 text-xl font-semibold">Aktualności</h3>
                        <p class="mt-2 text-base text-slate-300">Publikowanie wpisów i nowości technicznych.</p>
                        <div class="mt-auto pt-6 flex justify-center text-indigo-300/90">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12v16H6z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
                            </svg>
                        </div>
                    </a>
                @endif

                @if (auth()->user()->hasAdminPermission('testimonials_moderation'))
                    <a href="{{ route('admin.testimonials.index') }}" class="group rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:!border-blue-400/80 hover:!bg-slate-800/80 transition-all duration-200 min-h-[220px] flex flex-col">
                        <p class="text-sm uppercase tracking-wider text-gray-500">Treści</p>
                        <h3 class="mt-2 text-xl font-semibold">Opinie klientów</h3>
                        <p class="mt-2 text-base text-slate-300">Moderacja i publikacja opinii po realizacji.</p>
                        <div class="mt-auto pt-6 flex justify-center text-amber-300/90">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.7 6.3 3 3m-9.4 9.4 8.9-8.9a2.12 2.12 0 1 0-3-3l-8.9 8.9-.6 3.6 3.6-.6Z"/>
                            </svg>
                        </div>
                    </a>
                @endif

                @if (auth()->user()->isMainAdmin())
                    <a href="{{ route('admin.team.index') }}" class="group rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:!border-blue-400/80 hover:!bg-slate-800/80 transition-all duration-200 min-h-[220px] flex flex-col">
                        <p class="text-sm uppercase tracking-wider text-gray-500">Zarządzanie</p>
                        <h3 class="mt-2 text-xl font-semibold">Operatorzy</h3>
                        <p class="mt-2 text-base text-slate-300">Tworzenie kont operatorów i przypisywanie uprawnień.</p>
                        <div class="mt-auto pt-6 flex justify-center text-violet-300/90">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 19a4 4 0 0 0-8 0"/>
                                <circle cx="12" cy="11" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 19H4a4 4 0 0 1 4-4m9 4h3a4 4 0 0 0-4-4"/>
                            </svg>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


