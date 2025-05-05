<?php

namespace App\Models;

use App\Modules\Project\Observers\ProjectObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

#[ObservedBy(ProjectObserver::class)]
final class Project extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'slug',
        'platform',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function issues(): HasManyThrough
    {
        return $this->hasManyThrough(
            Issue::class,
            Event::class,
            'project_id',
            'event_id',
            'id',
            'id'
        );
    }

    public function endpoints(): HasMany
    {
        return $this->hasMany(HealthCheckEndpoint::class);
    }

    public function eventHourlyStats(): HasMany
    {
        return $this->hasMany(EventHourlyStat::class);
    }

    public function notificationRules(): HasMany
    {
        return $this->hasMany(NotificationRule::class);
    }

    public function toSearchableArray(): array
    {
        return ['id' => (string) $this->id] + $this->toArray();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
