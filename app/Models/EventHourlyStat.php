<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EventHourlyStat extends Model
{
    protected $fillable = [
        'project_id',
        'hour',
        'total_events',
        'error_count',
        'warning_count',
    ];

    protected function casts(): array
    {
        return [
            'hour' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeLastDay(Builder $query, ?string $timezone = null): Builder
    {
        return $query->where('created_at', '>=', now($timezone)->subDay());
    }
}
