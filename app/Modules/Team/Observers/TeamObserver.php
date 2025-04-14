<?php

namespace App\Modules\Team\Observers;

use App\Core\Cache\CacheService;
use App\Models\Team;

final readonly class TeamObserver
{
    public function __construct(private CacheService $cache) {}

    public function created(Team $team): void
    {
        $this->cache->forget(Team::class, $team->id);
    }

    public function updated(Team $team): void
    {
        $this->cache->forget(Team::class, $team->id);
    }

    public function saved(Team $team): void
    {
        $this->cache->forget(Team::class, $team->id);
    }

    public function deleted(Team $team): void
    {
        $this->cache->forget(Team::class, $team->id);
    }
}
