<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class NewsPostImage extends Model
{
    protected $fillable = [
        'news_post_id',
        'disk',
        'path',
        'caption',
        'sort_order',
    ];

    public function newsPost(): BelongsTo
    {
        return $this->belongsTo(NewsPost::class);
    }

    public function publicUrl(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
