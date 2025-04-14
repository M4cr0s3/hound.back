<?php

namespace App\Modules\Project\Observers;

use App\Core\Cache\CacheService;
use App\Models\Project;

final readonly class ProjectObserver
{
    public function __construct(private CacheService $cache) {}

    public function created(Project $project): void
    {
        $this->cache->forget(Project::class, $project->id);
    }

    public function updated(Project $project): void
    {
        $this->cache->forget(Project::class, $project->id);
    }

    public function saved(Project $project): void
    {
        $this->cache->forget(Project::class, $project->id);
    }

    public function deleted(Project $project): void
    {
        $this->cache->forget(Project::class, $project->id);
    }
}
