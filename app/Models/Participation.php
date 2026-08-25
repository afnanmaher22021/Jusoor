<?php

namespace App\Models;

use Database\Factories\ParticipationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'opportunity_id', 'hours', 'work_date', 'notes', 'status', 'approved_by', 'approved_at'])]
class Participation extends Model
{
    /** @use HasFactory<ParticipationFactory> */
    use HasFactory;

    protected $table = 'participation';

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
