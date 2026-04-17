<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">CMS: Aktualności</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Aktualności'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('status') }}</div>
            @endif

            <div class="flex flex-wrap items-center gap-2">
                <a href="#lista-aktualnosci" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                    Lista aktualności
                </a>
            </div>

            <details class="rounded-xl border border-gray-200 bg-white shadow-sm" @if($posts->isEmpty()) open @endif>
                <summary class="cursor-pointer list-none px-5 py-4">
                    <span class="inline-flex items-center rounded-md border border-blue-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-200 hover:bg-blue-500/10">
                        {{ $posts->isEmpty() ? 'Dodaj pierwszą aktualność' : 'Dodaj kolejną aktualność' }}
                    </span>
                </summary>

                <div class="border-t border-gray-200 px-5 py-4">
                    <form method="POST" action="{{ route('admin.cms.news.store') }}" enctype="multipart/form-data" class="grid gap-4">
                        @csrf
                        <input name="title" placeholder="Tytuł" class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                        <textarea name="excerpt" rows="2" placeholder="Skrót" class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"></textarea>
                        <textarea name="content" rows="5" placeholder="Treść" class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2"></textarea>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-200">Zdjęcie poglądowe (opcjonalnie)</label>
                            <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp" class="block w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2 text-sm text-slate-200">
                        </div>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1"> Opublikowany</label>
                        <div class="flex justify-end"><x-primary-button>Dodaj</x-primary-button></div>
                    </form>
                </div>
            </details>

            <div id="lista-aktualnosci" class="space-y-4">
                <h3 class="text-lg font-semibold">Edytuj aktualności</h3>
                @foreach ($posts as $post)
                    <article class="rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                            <div class="min-w-0">
                                <p class="truncate text-base font-semibold">{{ $post->title }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
                                    <span class="{{ $post->is_published ? 'text-green-400' : 'text-amber-300' }}">
                                        {{ $post->is_published ? 'Opublikowany' : 'Szkic' }}
                                    </span>
                                    <span class="text-slate-400">Publikacja: {{ $post->published_at?->format('Y-m-d H:i') ?? 'brak' }}</span>
                                    <span class="text-blue-300">Wyświetlenia: {{ (int) ($post->views_count ?? 0) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('admin.cms.news.edit', $post) }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center rounded-md border border-blue-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-blue-200 hover:bg-blue-500/10"
                                >
                                    Edytuj
                                </a>

                                <form method="POST" action="{{ route('admin.cms.news.destroy', $post) }}" onsubmit="return confirm('Usunąć wpis?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-md border border-rose-300/60 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-rose-200 hover:bg-rose-500/10">
                                        Usuń
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
