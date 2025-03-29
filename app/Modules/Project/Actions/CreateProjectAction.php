<?php

namespace App\Modules\Project\Actions;

use App\Models\Project;

final readonly class CreateProjectAction
{
    public function execute(array $data): Project
    {
        return Project::create([
            'team_id' => $data['team_id'],
            'name' => $data['name'],
            'slug' => $data['slug'] ?? \Str::slug($data['name']),
            'platform' => $data['platform'],
        ]);
    }
}
