<?php

namespace App\Http\Controllers;

use App\Models\AboutGalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminAboutGalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.cms.about-gallery', [
            'images' => AboutGalleryImage::query()
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $disk = (string) config('filesystems.about_gallery_disk', 'public');
        $sortOrder = (int) AboutGalleryImage::max('sort_order');

        foreach ($validated['images'] as $image) {
            $sortOrder += 10;
            $path = $image->store('about-gallery', $disk);

            AboutGalleryImage::create([
                'disk' => $disk,
                'path' => $path,
                'caption' => $validated['caption'] ?? null,
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('admin.cms.about-gallery.index')
            ->with('status', 'Dodano zdjęcia do galerii O nas: '.count($validated['images']).'.');
    }

    public function update(Request $request, AboutGalleryImage $aboutGalleryImage): RedirectResponse
    {
        $validated = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $aboutGalleryImage->update([
            'caption' => $validated['caption'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.cms.about-gallery.index')
            ->with('status', 'Ustawienia zdjęcia zostały zapisane.');
    }

    public function destroy(AboutGalleryImage $aboutGalleryImage): RedirectResponse
    {
        Storage::disk($aboutGalleryImage->disk)->delete($aboutGalleryImage->path);
        $aboutGalleryImage->delete();

        return redirect()
            ->route('admin.cms.about-gallery.index')
            ->with('status', 'Zdjęcie zostało usunięte.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image_ids' => ['required', 'array', 'min:1'],
            'image_ids.*' => ['integer', 'distinct', 'exists:about_gallery_images,id'],
        ]);

        $images = AboutGalleryImage::query()
            ->whereKey($validated['image_ids'])
            ->get();

        foreach ($images as $image) {
            Storage::disk($image->disk)->delete($image->path);
            $image->delete();
        }

        return redirect()
            ->route('admin.cms.about-gallery.index')
            ->with('status', 'Usunięto zaznaczone zdjęcia: '.$images->count().'.');
    }
}
