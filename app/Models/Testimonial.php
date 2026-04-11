<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;

    public const MODERATION_APPROVE = 'approve';
    public const MODERATION_REVIEW = 'review';
    public const MODERATION_REJECT = 'reject';

    protected $fillable = [
        'user_id',
        'ticket_id',
        'rating',
        'content',
        'moderation_status',
        'moderation_score',
        'moderation_reasons',
        'moderated_at',
        'is_approved',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'moderation_score' => 'integer',
            'moderation_reasons' => 'array',
            'moderated_at' => 'datetime',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public static function moderationStatuses(): array
    {
        return [
            self::MODERATION_APPROVE => 'Automatycznie zaakceptowana',
            self::MODERATION_REVIEW => 'Do ręcznej weryfikacji',
            self::MODERATION_REJECT => 'Automatycznie odrzucona',
        ];
    }

    public function moderationStatusLabel(): string
    {
        return self::moderationStatuses()[$this->moderation_status] ?? $this->moderation_status;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
