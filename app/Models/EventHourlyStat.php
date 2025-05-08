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

    public static function getTrendData($projectId, $period = 'day'): array
    {
        $currentPeriod = now()->sub($period);

        $currentCount = self::where('project_id', $projectId)
            ->where('hour', '>=', $currentPeriod)
            ->sum('total_events');

        $previousCount = self::where('project_id', $projectId)
            ->whereBetween('hour', [
                $currentPeriod->copy()->sub($period),
                $currentPeriod,
            ])
            ->sum('total_events');

        return [
            'current' => $currentCount,
            'previous' => $previousCount,
        ];
    }
}
