<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_REPLIED = 'replied';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'reply_subject',
        'reply_message',
        'replied_at',
        'replied_by_user_id',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'Nowa',
            self::STATUS_REPLIED => 'Odpowiedziano',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function badgeClasses(): array
    {
        return [
            self::STATUS_NEW => 'bg-blue-500/20 text-blue-300 border border-blue-400/30',
            self::STATUS_REPLIED => 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30',
        ];
    }

    public function repliedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by_user_id');
    }
}
