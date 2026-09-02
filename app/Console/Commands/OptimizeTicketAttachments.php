<?php

namespace App\Console\Commands;

use App\Models\TicketAttachment;
use App\Support\OptimizedImageStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeTicketAttachments extends Command
{
    protected $signature = 'images:optimize-ticket-attachments {--dry-run : Pokaż, co zostałoby zoptymalizowane bez zapisywania zmian}';

    protected $description = 'Optymalizuje istniejące zdjęcia dodane jako załączniki zgłoszeń.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $optimized = 0;
        $skipped = 0;

        foreach (TicketAttachment::query()->cursor() as $attachment) {
            $this->optimizeAttachment($attachment, $dryRun) ? $optimized++ : $skipped++;
        }

        $this->info(($dryRun ? 'Do optymalizacji' : 'Zoptymalizowano').": {$optimized}. Pominięto: {$skipped}.");

        return self::SUCCESS;
    }

    private function optimizeAttachment(TicketAttachment $attachment, bool $dryRun): bool
    {
        $diskName = (string) $attachment->disk;
        $oldPath = (string) $attachment->path;

        if ($diskName === '' || $oldPath === '' || strtolower(pathinfo($oldPath, PATHINFO_EXTENSION)) === 'webp') {
            return false;
        }

        if (! $this->looksLikeImage($attachment)) {
            return false;
        }

        $disk = Storage::disk($diskName);
        if (! $disk->exists($oldPath)) {
            return false;
        }

        if ($dryRun) {
            $this->line("{$diskName}:{$oldPath}");

            return true;
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'ticket-image-');
        if (! $temporaryPath) {
            return false;
        }

        file_put_contents($temporaryPath, $disk->get($oldPath));

        $directory = trim(pathinfo($oldPath, PATHINFO_DIRNAME), '.\\/');
        $newPath = OptimizedImageStorage::storeLocalFile($temporaryPath, $diskName, $directory);
        @unlink($temporaryPath);

        if (! $newPath) {
            return false;
        }

        $extension = strtolower(pathinfo($newPath, PATHINFO_EXTENSION));

        $attachment->forceFill([
            'path' => $newPath,
            'original_name' => $this->fileNameWithExtension($attachment->original_name, $extension),
            'mime_type' => $extension === 'webp' ? 'image/webp' : 'image/jpeg',
            'size' => $disk->size($newPath),
        ])->save();

        $disk->delete($oldPath);

        return true;
    }

    private function looksLikeImage(TicketAttachment $attachment): bool
    {
        $mimeType = strtolower((string) $attachment->mime_type);
        $extension = strtolower(pathinfo((string) $attachment->path, PATHINFO_EXTENSION));

        return str_starts_with($mimeType, 'image/')
            || in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);
    }

    private function fileNameWithExtension(string $name, string $extension): string
    {
        $baseName = pathinfo($name, PATHINFO_FILENAME);
        $baseName = trim($baseName) !== '' ? $baseName : 'zalacznik';

        return $baseName.'.'.$extension;
    }
}
