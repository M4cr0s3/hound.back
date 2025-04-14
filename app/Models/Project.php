<?php

namespace App\Models;

use App\Modules\Project\Observers\ProjectObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ProjectObserver::class)]
final class Project extends Model
{
    use SoftDeletes;

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

    public function endpoints(): HasMany
    {
        return $this->hasMany(HealthCheckEndpoint::class);
    }

    public function eventHourlyStats(): HasMany
    {
        return $this->hasMany(EventHourlyStat::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
