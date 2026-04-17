<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsPost extends Model
{
    use HasFactory;

    protected $table = 'news_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image_disk',
        'cover_image_path',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function coverImageUrl(): ?string
    {
        if (! $this->cover_image_path || ! $this->cover_image_disk) {
            return null;
        }

        return Storage::disk($this->cover_image_disk)->url($this->cover_image_path);
    }
}
