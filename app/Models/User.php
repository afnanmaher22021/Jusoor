<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'role', 'phone', 'city', 'skills',
    'bio', 'avatar_path', 'birth_date', 'monthly_hours_goal',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_VOLUNTEER = 'volunteer';
    public const ROLE_ORGANIZATION = 'organization';
    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_VOLUNTEER,
        self::ROLE_ORGANIZATION,
        self::ROLE_ADMIN,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function isVolunteer(): bool
    {
        return $this->role === self::ROLE_VOLUNTEER;
    }

    public function isOrganization(): bool
    {
        return $this->role === self::ROLE_ORGANIZATION;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Organization::class);
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function participation(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function totalApprovedHours(): float
    {
        return (float) $this->participation()->where('status', 'approved')->sum('hours');
    }

    public function currentMonthHours(): float
    {
        return (float) $this->participation()
            ->where('status', 'approved')
            ->whereYear('work_date', now()->year)
            ->whereMonth('work_date', now()->month)
            ->sum('hours');
    }
}
