<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AboutGalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'disk',
        'path',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function publicUrl(): ?string
    {
        try {
            return Storage::disk($this->disk)->url($this->path);
        } catch (\Throwable) {
            return null;
        }
    }
}
