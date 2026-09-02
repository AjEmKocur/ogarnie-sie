<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizedImageStorage
{
    public static function store(UploadedFile $image, string $disk, string $directory, int $maxDimension = 2000): string
    {
        $sourcePath = $image->getRealPath();
        $path = $sourcePath ? self::storeLocalFile($sourcePath, $disk, $directory, $maxDimension) : null;

        return $path ?: $image->store($directory, $disk);
    }

    public static function storeLocalFile(string $sourcePath, string $disk, string $directory, int $maxDimension = 2000): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $size = @getimagesize($sourcePath);

        if (! $size) {
            return null;
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
            return null;
        }

        [$source, $width, $height] = self::applyImageOrientation($source, $sourcePath, $mime, $width, $height);

        $scale = min(1, $maxDimension / max($width, $height));
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        if (! $target) {
            imagedestroy($source);

            return null;
        }

        $background = imagecolorallocate($target, 8, 8, 8);
        imagefill($target, 0, 0, $background);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        $extension = function_exists('imagewebp') ? 'webp' : 'jpg';
        $path = trim($directory, '/').'/'.Str::uuid().'.'.$extension;
        $temporaryPath = tempnam(sys_get_temp_dir(), 'optimized-image-');

        if (! $temporaryPath) {
            imagedestroy($source);
            imagedestroy($target);

            return null;
        }

        $saved = $extension === 'webp'
            ? imagewebp($target, $temporaryPath, 82)
            : imagejpeg($target, $temporaryPath, 84);

        imagedestroy($source);
        imagedestroy($target);

        if (! $saved) {
            @unlink($temporaryPath);

            return null;
        }

        $contents = file_get_contents($temporaryPath);
        if ($contents === false) {
            @unlink($temporaryPath);

            return null;
        }

        Storage::disk($disk)->put($path, $contents);
        @unlink($temporaryPath);

        return $path;
    }

    /**
     * @param resource|\GdImage $source
     * @return array{0: resource|\GdImage, 1: int, 2: int}
     */
    private static function applyImageOrientation($source, string $sourcePath, string $mime, int $width, int $height): array
    {
        if ($mime !== 'image/jpeg' || ! function_exists('exif_read_data')) {
            return [$source, $width, $height];
        }

        $exif = @exif_read_data($sourcePath);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        $rotated = match ($orientation) {
            3 => imagerotate($source, 180, 0),
            6 => imagerotate($source, -90, 0),
            8 => imagerotate($source, 90, 0),
            default => false,
        };

        if (! $rotated) {
            return [$source, $width, $height];
        }

        imagedestroy($source);

        if (in_array($orientation, [6, 8], true)) {
            return [$rotated, $height, $width];
        }

        return [$rotated, $width, $height];
    }
}
