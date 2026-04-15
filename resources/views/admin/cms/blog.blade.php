<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">CMS: Aktualności</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
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
                    <form method="POST" action="{{ route('admin.cms.blog.store') }}" enctype="multipart/form-data" class="grid gap-4">
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
                    <details class="rounded-xl border border-gray-200 bg-white shadow-sm">
                        <summary class="cursor-pointer list-none px-5 py-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-semibold">{{ $post->title }}</p>
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="{{ $post->is_published ? 'text-green-400' : 'text-amber-300' }}">
                                        {{ $post->is_published ? 'Opublikowany' : 'Szkic' }}
                                    </span>
                                    <span class="text-slate-400">Publikacja: {{ $post->published_at?->format('Y-m-d H:i') ?? 'brak' }}</span>
                                </div>
                            </div>
                        </summary>

                        <div class="border-t border-gray-200 px-5 py-4">
                            <form method="POST" action="{{ route('admin.cms.blog.update', $post) }}" enctype="multipart/form-data" class="grid gap-4">
                                @csrf
                                @method('PATCH')
                                <input name="title" value="{{ $post->title }}" class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                                <textarea name="excerpt" rows="2" class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2">{{ $post->excerpt }}</textarea>
                                <textarea name="content" rows="5" class="rounded-md border border-gray-300 bg-slate-900 px-3 py-2">{{ $post->content }}</textarea>
                                @if ($post->coverImageUrl())
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $post->coverImageUrl() }}" alt="Miniatura wpisu" class="h-20 w-32 rounded-md border border-gray-200 object-cover">
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox" name="remove_cover_image" value="1">
                                            Usuń obecne zdjęcie
                                        </label>
                                    </div>
                                @endif
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-200">Podmień zdjęcie (opcjonalnie)</label>
                                    <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp" class="block w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2 text-sm text-slate-200">
                                </div>
                                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked($post->is_published)> Opublikowany</label>
                                <div class="flex items-center justify-between gap-3">
                                    <button
                                        type="submit"
                                        form="delete-post-{{ $post->id }}"
                                        class="text-sm text-red-600"
                                    >
                                        Usuń
                                    </button>
                                    <x-primary-button>Zapisz</x-primary-button>
                                </div>
                            </form>
                            <form
                                id="delete-post-{{ $post->id }}"
                                method="POST"
                                action="{{ route('admin.cms.blog.destroy', $post) }}"
                                class="hidden"
                                onsubmit="return confirm('Usunąć wpis?');"
                            >
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
