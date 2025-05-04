<?php

namespace App\Models;

use App\Modules\Team\Observers\TeamObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;

#[ObservedBy(TeamObserver::class)]
final class Team extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'team_members',
            'team_id',
            'user_id'
        );
    }

    public function assignments(): MorphMany
    {
        return $this->morphMany(IssueAssignment::class, 'assignable');
    }

    public function toSearchableArray(): array
    {
        return ['id' => (string) $this->id, 'name' => $this->name, 'slug' => $this->slug];
    }
}
