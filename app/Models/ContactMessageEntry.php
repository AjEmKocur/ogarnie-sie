<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessageEntry extends Model
{
    use HasFactory;

    public const SENDER_CLIENT = 'client';
    public const SENDER_ADMIN = 'admin';

    protected $fillable = [
        'contact_message_id',
        'user_id',
        'sender_type',
        'message',
    ];

    public function contactMessage(): BelongsTo
    {
        return $this->belongsTo(ContactMessage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
