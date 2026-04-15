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
                    <a href="{{ route('admin.tickets.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-500/60">
                        <p class="text-xs uppercase tracking-wider text-gray-500">Obsługa</p>
                        <h3 class="mt-2 text-lg font-semibold">Zgłoszenia serwisowe</h3>
                        <p class="mt-2 text-sm text-slate-300">Statusy, notatki, płatności i komunikacja z klientem.</p>
                    </a>
                @endif

                @if (auth()->user()->hasAdminPermission('cms_services'))
                    <a href="{{ route('admin.cms.services.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-500/60">
                        <p class="text-xs uppercase tracking-wider text-gray-500">Oferta</p>
                        <h3 class="mt-2 text-lg font-semibold">Usługi (z cenami)</h3>
                        <p class="mt-2 text-sm text-slate-300">Dodawanie i edycja usług wraz z ceną od widoczną na stronie.</p>
                    </a>

                    <a href="{{ route('admin.cms.about-gallery.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-500/60">
                        <p class="text-xs uppercase tracking-wider text-gray-500">Treści</p>
                        <h3 class="mt-2 text-lg font-semibold">Galeria O nas</h3>
                        <p class="mt-2 text-sm text-slate-300">Dodawanie zdjęć serwisu do sekcji O nas (slider publiczny).</p>
                    </a>
                @endif

                @if (auth()->user()->hasAdminPermission('cms_blog'))
                    <a href="{{ route('admin.cms.blog.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-500/60">
                        <p class="text-xs uppercase tracking-wider text-gray-500">Treści</p>
                        <h3 class="mt-2 text-lg font-semibold">Aktualności</h3>
                        <p class="mt-2 text-sm text-slate-300">Publikowanie wpisów i nowości technicznych.</p>
                    </a>
                @endif

                @if (auth()->user()->hasAdminPermission('contact_messages'))
                    <a href="{{ route('admin.contact.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-500/60">
                        <p class="text-xs uppercase tracking-wider text-gray-500">Kontakt</p>
                        <h3 class="mt-2 text-lg font-semibold">Wiadomości kontaktowe</h3>
                        <p class="mt-2 text-sm text-slate-300">Obsługa formularza kontaktowego i odpowiedzi.</p>
                    </a>
                @endif

                @if (auth()->user()->hasAdminPermission('testimonials_moderation'))
                    <a href="{{ route('admin.testimonials.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-500/60">
                        <p class="text-xs uppercase tracking-wider text-gray-500">Treści</p>
                        <h3 class="mt-2 text-lg font-semibold">Opinie klientów</h3>
                        <p class="mt-2 text-sm text-slate-300">Moderacja i publikacja opinii po realizacji.</p>
                    </a>
                @endif

                @if (auth()->user()->isMainAdmin())
                    <a href="{{ route('admin.team.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-500/60">
                        <p class="text-xs uppercase tracking-wider text-gray-500">Zarządzanie</p>
                        <h3 class="mt-2 text-lg font-semibold">Operatorzy</h3>
                        <p class="mt-2 text-sm text-slate-300">Tworzenie kont operatorów i przypisywanie uprawnień.</p>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
