<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentStorage
{
    /**
     * @return array{path: string, original_name: string, mime_type: string|null, size: int|null}
     */
    public static function store(UploadedFile $file, int $ticketId, string $disk): array
    {
        $directory = "ticket-attachments/{$ticketId}";
        $mimeType = (string) $file->getMimeType();
        $isOptimizableImage = in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true);

        if ($isOptimizableImage) {
            $path = OptimizedImageStorage::store($file, $disk, $directory);
            $storedExtension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            return [
                'path' => $path,
                'original_name' => self::fileNameWithExtension($file->getClientOriginalName(), $storedExtension),
                'mime_type' => $storedExtension === 'webp' ? 'image/webp' : 'image/jpeg',
                'size' => Storage::disk($disk)->size($path),
            ];
        }

        $path = $file->store($directory, $disk);

        return [
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ];
    }

    private static function fileNameWithExtension(string $name, string $extension): string
    {
        $baseName = pathinfo($name, PATHINFO_FILENAME);
        $baseName = trim($baseName) !== '' ? $baseName : 'zalacznik';

        return $baseName.'.'.$extension;
    }
}
