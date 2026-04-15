<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">CMS: Aktualności</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('status') }}</div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold">Dodaj aktualność</h3>
                <form method="POST" action="{{ route('admin.cms.blog.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-4">
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

            <div class="space-y-4">
                @foreach ($posts as $post)
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
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
                            <p class="text-xs text-slate-400">Data publikacji: {{ $post->published_at?->format('Y-m-d H:i') ?? 'brak' }}</p>
                            <div class="flex justify-end"><x-primary-button>Zapisz</x-primary-button></div>
                        </form>
                        <form method="POST" action="{{ route('admin.cms.blog.destroy', $post) }}" class="mt-3" onsubmit="return confirm('Usunąć wpis?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 text-sm">Usuń</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
