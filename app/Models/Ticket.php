<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_WAITING_PARTS = 'waiting_parts';
    public const STATUS_READY = 'ready';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELLED = 'cancelled';
    public const PAYMENT_MODE_NONE = 'none';
    public const PAYMENT_MODE_UPFRONT = 'upfront';
    public const PAYMENT_MODE_ON_PICKUP = 'on_pickup';
    public const PAYMENT_MODE_TRANSFER = 'transfer';
    public const PAYMENT_STATUS_NOT_REQUIRED = 'not_required';
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_PAID = 'paid';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'custom_request',
        'estimated_price_from',
        'status',
        'admin_note',
        'payment_mode',
        'payment_amount',
        'payment_status',
        'payment_note',
        'payment_requested_at',
        'paid_at',
        'client_last_seen_at',
        'admin_last_seen_at',
    ];

    protected $casts = [
        'estimated_price_from' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'payment_requested_at' => 'datetime',
        'paid_at' => 'datetime',
        'client_last_seen_at' => 'datetime',
        'admin_last_seen_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket): void {
            if (empty($ticket->status)) {
                $ticket->status = self::STATUS_NEW;
            }

            if (empty($ticket->payment_mode)) {
                $ticket->payment_mode = self::PAYMENT_MODE_NONE;
            }

            if (empty($ticket->payment_status)) {
                $ticket->payment_status = self::PAYMENT_STATUS_NOT_REQUIRED;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class)->latest();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->oldest();
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class)->latest();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withTimestamps();
    }

    public function testimonial(): HasOne
    {
        return $this->hasOne(Testimonial::class);
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'Nowe',
            self::STATUS_IN_PROGRESS => 'W trakcie',
            self::STATUS_WAITING_PARTS => 'Czeka na części',
            self::STATUS_READY => 'Gotowe do odbioru',
            self::STATUS_CLOSED => 'Zamknięte',
            self::STATUS_CANCELLED => 'Anulowane',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function paymentModes(): array
    {
        return [
            self::PAYMENT_MODE_NONE => 'Brak płatności',
            self::PAYMENT_MODE_UPFRONT => 'Przed realizacją (online)',
            self::PAYMENT_MODE_ON_PICKUP => 'Przy odbiorze',
            self::PAYMENT_MODE_TRANSFER => 'Przelew tradycyjny',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function paymentStatuses(): array
    {
        return [
            self::PAYMENT_STATUS_NOT_REQUIRED => 'Nie wymagane',
            self::PAYMENT_STATUS_PENDING => 'Oczekuje na płatność',
            self::PAYMENT_STATUS_PAID => 'Opłacone',
        ];
    }
}
