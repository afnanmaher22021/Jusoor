<?php

namespace App\Models;

use Database\Factories\OpportunityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organization_id', 'category_id', 'title', 'description', 'location',
    'required_hours', 'max_volunteers', 'status', 'skills_required',
    'image_path', 'starts_at', 'ends_at',
])]
class Opportunity extends Model
{
    /** @use HasFactory<OpportunityFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participation')
            ->withPivot('hours', 'work_date', 'status', 'approved_by', 'approved_at')
            ->withTimestamps();
    }

    public function participation(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function acceptedCount(): int
    {
        return $this->applications()->where('status', 'accepted')->count();
    }

    public function isFull(): bool
    {
        return $this->acceptedCount() >= $this->max_volunteers;
    }
}
