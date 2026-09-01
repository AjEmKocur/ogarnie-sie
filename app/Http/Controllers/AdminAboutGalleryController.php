<?php

namespace App\Http\Controllers;

use App\Models\AboutGalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'caption' => ['nullable', 'string', 'max:255'],
        ], [
            'images.required' => 'Wybierz co najmniej jedno zdjęcie.',
            'images.max' => 'Możesz dodać maksymalnie 20 zdjęć naraz.',
            'images.*.image' => 'Każdy wybrany plik musi być zdjęciem.',
            'images.*.mimes' => 'Zdjęcia muszą być w formacie JPG, PNG albo WebP.',
            'images.*.max' => 'Każde zdjęcie może mieć maksymalnie 20 MB.',
        ]);

        $disk = (string) config('filesystems.about_gallery_disk', 'public');
        $sortOrder = (int) AboutGalleryImage::max('sort_order');

        foreach ($validated['images'] as $image) {
            $sortOrder += 10;
            $path = $this->storeOptimizedImage($image, $disk);

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

    private function storeOptimizedImage(UploadedFile $image, string $disk): string
    {
        if (! extension_loaded('gd')) {
            return $image->store('about-gallery', $disk);
        }

        $sourcePath = $image->getRealPath();
        $size = $sourcePath ? @getimagesize($sourcePath) : false;

        if (! $sourcePath || ! $size) {
            return $image->store('about-gallery', $disk);
        }

        [$width, $height] = $size;
        $mime = $size['mime'] ?? '';

        $source = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (! $source) {
            return $image->store('about-gallery', $disk);
        }

        $maxDimension = 2000;
        $scale = min(1, $maxDimension / max($width, $height));
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        if (! $target) {
            imagedestroy($source);

            return $image->store('about-gallery', $disk);
        }
        $background = imagecolorallocate($target, 8, 8, 8);
        imagefill($target, 0, 0, $background);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        $extension = function_exists('imagewebp') ? 'webp' : 'jpg';
        $path = 'about-gallery/'.Str::uuid().'.'.$extension;
        $temporaryPath = tempnam(sys_get_temp_dir(), 'about-gallery-');
        if (! $temporaryPath) {
            imagedestroy($source);
            imagedestroy($target);

            return $image->store('about-gallery', $disk);
        }

        $saved = $extension === 'webp'
            ? imagewebp($target, $temporaryPath, 82)
            : imagejpeg($target, $temporaryPath, 84);

        imagedestroy($source);
        imagedestroy($target);

        if (! $saved) {
            @unlink($temporaryPath);

            return $image->store('about-gallery', $disk);
        }

        $contents = file_get_contents($temporaryPath);
        if ($contents === false) {
            @unlink($temporaryPath);

            return $image->store('about-gallery', $disk);
        }

        Storage::disk($disk)->put($path, $contents);
        @unlink($temporaryPath);

        return $path;
    }
}
