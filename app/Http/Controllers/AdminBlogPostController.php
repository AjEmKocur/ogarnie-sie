<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminBlogPostController extends Controller
{
    public function index(): View
    {
        return view('admin.cms.news', [
            'posts' => BlogPost::query()
                ->select('blog_posts.*')
                ->selectSub(function ($q): void {
                    $q->from('news_view_events')
                        ->whereColumn('news_view_events.blog_post_id', 'blog_posts.id')
                        ->selectRaw('COUNT(*)');
                }, 'views_count')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $isPublished = $request->boolean('is_published');
        $disk = (string) config('filesystems.news_cover_disk', 'public');
        $coverPath = $request->hasFile('cover_image') ? $validated['cover_image']->store('news-covers', $disk) : null;

        BlogPost::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::lower(Str::random(5)),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'] ?? null,
            'cover_image_disk' => $coverPath ? $disk : null,
            'cover_image_path' => $coverPath,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? Carbon::now() : null,
        ]);

        return redirect()->route('admin.cms.news.index')->with('status', 'Aktualność została dodana.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.cms.news-edit', [
            'post' => $blogPost,
        ]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $isPublished = $request->boolean('is_published');
        $disk = (string) config('filesystems.news_cover_disk', 'public');
        $coverDisk = $blogPost->cover_image_disk;
        $coverPath = $blogPost->cover_image_path;

        if ($request->boolean('remove_cover_image') && $coverDisk && $coverPath) {
            Storage::disk($coverDisk)->delete($coverPath);
            $coverDisk = null;
            $coverPath = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($coverDisk && $coverPath) {
                Storage::disk($coverDisk)->delete($coverPath);
            }
            $coverPath = $validated['cover_image']->store('news-covers', $disk);
            $coverDisk = $disk;
        }

        $blogPost->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::lower(Str::random(5)),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'] ?? null,
            'cover_image_disk' => $coverDisk,
            'cover_image_path' => $coverPath,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? ($blogPost->published_at ?? Carbon::now()) : null,
        ]);

        return redirect()->route('admin.cms.news.edit', $blogPost)->with('status', 'Aktualność została zaktualizowana.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->cover_image_disk && $blogPost->cover_image_path) {
            Storage::disk($blogPost->cover_image_disk)->delete($blogPost->cover_image_path);
        }

        $blogPost->delete();

        return redirect()->route('admin.cms.news.index')->with('status', 'Aktualność została usunięta.');
    }
}
