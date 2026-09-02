<?php

namespace App\Console\Commands;

use App\Models\AboutGalleryImage;
use App\Models\NewsPost;
use App\Models\NewsPostImage;
use App\Support\OptimizedImageStorage;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OptimizePublicImages extends Command
{
    protected $signature = 'images:optimize-public {--dry-run : Pokaż, co zostałoby zoptymalizowane bez zapisywania zmian}';

    protected $description = 'Optymalizuje istniejące publiczne zdjęcia CMS do lżejszego formatu.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $optimized = 0;
        $skipped = 0;

        foreach (AboutGalleryImage::query()->cursor() as $image) {
            $this->optimizeModelImage($image, 'disk', 'path', 'about-gallery', $dryRun) ? $optimized++ : $skipped++;
        }

        foreach (NewsPost::query()->whereNotNull('cover_image_path')->cursor() as $post) {
            $this->optimizeModelImage($post, 'cover_image_disk', 'cover_image_path', 'news-covers', $dryRun) ? $optimized++ : $skipped++;
        }

        foreach (NewsPostImage::query()->cursor() as $image) {
            $this->optimizeModelImage($image, 'disk', 'path', 'news-gallery', $dryRun) ? $optimized++ : $skipped++;
        }

        $this->info(($dryRun ? 'Do optymalizacji' : 'Zoptymalizowano').": {$optimized}. Pominięto: {$skipped}.");

        return self::SUCCESS;
    }

    private function optimizeModelImage(Model $model, string $diskColumn, string $pathColumn, string $directory, bool $dryRun): bool
    {
        $diskName = (string) $model->getAttribute($diskColumn);
        $oldPath = (string) $model->getAttribute($pathColumn);

        if ($diskName === '' || $oldPath === '') {
            return false;
        }

        $disk = Storage::disk($diskName);
        if (! $disk->exists($oldPath)) {
            return false;
        }

        if (strtolower(pathinfo($oldPath, PATHINFO_EXTENSION)) === 'webp') {
            return false;
        }

        if ($dryRun) {
            $this->line("{$diskName}:{$oldPath}");

            return true;
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'public-image-');
        if (! $temporaryPath) {
            return false;
        }

        $contents = $disk->get($oldPath);
        file_put_contents($temporaryPath, $contents);

        $newPath = OptimizedImageStorage::storeLocalFile($temporaryPath, $diskName, $directory);
        @unlink($temporaryPath);

        if (! $newPath) {
            return false;
        }

        $model->forceFill([$pathColumn => $newPath])->save();
        $disk->delete($oldPath);

        return true;
    }
}
