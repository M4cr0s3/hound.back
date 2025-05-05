<?php

namespace App\Models;

use App\Modules\Event\Casts\Metadata;
use App\Modules\Event\Observer\EventObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(EventObserver::class)]
final class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'environment',
        'type',
        'level',
        'event_id',
        'message',
        'release',
        'metadata',
        'count',
    ];

    protected function casts(): array
    {
        return [
            'event_id' => 'string',
            'metadata' => Metadata::class,
        ];
    }

    // TODO: оставить поддержку sqlite?
    protected static function booted(): void
    {
        self::created(function (Event $event) {
            $hour = $event->created_at->format('Y-m-d H:00:00');

            $stats = EventHourlyStat::firstOrNew([
                'project_id' => $event->project_id,
                'hour' => $hour,
            ]);

            $stats->total_events += $event->count;
            $stats->error_count += ($event->level === 'error' ? $event->count : 0);
            $stats->warning_count += ($event->level === 'warning' ? $event->count : 0);
            $stats->updated_at = now();

            if (! $stats->exists) {
                $stats->created_at = now();
            }

            $stats->save();
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    public function scopeLastDay(Builder $query, ?string $timezone = null): Builder
    {
        return $query->where('created_at', '>=', now($timezone)->subDay());
    }

    public function scopeLastHours(Builder $query, int $hours, ?string $timezone = null): Builder
    {
        return $query->where('created_at', '>=', now($timezone)->subHours($hours));
    }

    public function scopeOfLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', $level);
    }

    public function scopeForProject(Builder $query, int|Project $project): Builder
    {
        $projectId = $project instanceof Project ? $project->id : $project;

        return $query->where('project_id', $projectId);
    }
}
