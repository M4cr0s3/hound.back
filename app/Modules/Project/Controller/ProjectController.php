<?php

namespace App\Modules\Project\Controller;

use App\Models\Project;
use Illuminate\Support\Collection;

class ProjectController
{
    public function index(): Collection
    {
        \Gate::authorize('viewAny', Project::class);

        return Project::all();
    }
}
