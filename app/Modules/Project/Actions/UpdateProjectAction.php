<?php

namespace App\Modules\Project\Actions;

use App\Models\Project;

final readonly class UpdateProjectAction
{
    public function execute(Project $project, array $data): void
    {
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = \Str::slug($data['name']);
        }

        $project->slug = $data['slug'] ?? $project->slug;
        $project->team_id = $data['team_id'] ?? $project->team_id;
        $project->name = $data['name'] ?? $project->name;
        $project->platform = $data['platform'] ?? $project->platform;

        $project->save();
    }
}
