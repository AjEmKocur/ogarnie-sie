<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">CMS: Edycja aktualności</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Centrum CMS', 'url' => route('admin.cms.dashboard')],
                    ['label' => 'Aktualności', 'url' => route('admin.cms.news.index')],
                    ['label' => 'Edycja'],
                ],
            ])

            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('status') }}</div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400">Edytujesz wpis</p>
                        <h3 class="text-lg font-semibold">{{ $post->title }}</h3>
                    </div>
                    <a href="{{ route('admin.cms.news.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-200 hover:bg-slate-800">
                        Wróć do listy
                    </a>
                </div>

                <div class="px-5 py-4">
                    <form method="POST" action="{{ route('admin.cms.news.update', $post) }}" enctype="multipart/form-data" class="grid gap-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-200">Tytuł</label>
                            <input name="title" value="{{ old('title', $post->title) }}" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-200">Skrót</label>
                            <textarea name="excerpt" rows="2" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">{{ old('excerpt', $post->excerpt) }}</textarea>
                            <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-200">Treść</label>
                            <textarea name="content" rows="8" class="w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2">{{ old('content', $post->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        @if ($post->coverImageUrl())
                            <div class="flex items-center gap-4">
                                <img src="{{ $post->coverImageUrl() }}" alt="Miniatura wpisu" class="h-24 w-40 rounded-md border border-gray-200 object-cover">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="remove_cover_image" value="1">
                                    Usuń obecne zdjęcie
                                </label>
                            </div>
                        @endif

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-200">Podmień zdjęcie (opcjonalnie)</label>
                            <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp" class="block w-full rounded-md border border-gray-300 bg-slate-900 px-3 py-2 text-sm text-slate-200">
                            <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
                        </div>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))>
                            Opublikowany
                        </label>

                        <div class="flex items-center justify-between gap-3">
                            <button
                                type="submit"
                                form="delete-post-{{ $post->id }}"
                                class="text-sm text-red-600"
                                onclick="return confirm('Usunąć wpis?');"
                            >
                                Usuń
                            </button>
                            <x-primary-button>Zapisz zmiany</x-primary-button>
                        </div>
                    </form>

                    <form
                        id="delete-post-{{ $post->id }}"
                        method="POST"
                        action="{{ route('admin.cms.news.destroy', $post) }}"
                        class="hidden"
                    >
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
