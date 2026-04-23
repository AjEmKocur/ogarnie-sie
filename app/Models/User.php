<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERATOR = 'operator';
    public const ROLE_CLIENT = 'client';

    public const ADMIN_PERMISSIONS = [
        'tickets',
        'cms_services',
        'cms_news',
        'contact_messages',
        'testimonials_moderation',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'username',
        'role',
        'is_active',
        'admin_permissions',
        'force_password_change',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'admin_permissions' => 'array',
            'force_password_change' => 'boolean',
            'contact_messages_last_seen_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_OPERATOR], true);
    }

    public function isMainAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function hasAdminPermission(string $permission): bool
    {
        if (! $this->isAdmin()) {
            return false;
        }

        if ($this->isMainAdmin()) {
            return true;
        }

        $permissions = $this->admin_permissions ?? [];

        if (in_array($permission, $permissions, true)) {
            return true;
        }

        return false;
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function hasClosedTicketsWithoutTestimonial(): bool
    {
        return $this->tickets()
            ->where('status', Ticket::STATUS_CLOSED)
            ->whereDoesntHave('testimonial')
            ->exists();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }
}
