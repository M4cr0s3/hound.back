<?php

namespace App\Modules\Project\Policy;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final readonly class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool {}

    public function create(User $user): bool {}

    public function update(User $user, Project $project): bool {}

    public function delete(User $user, Project $project): bool {}

    public function restore(User $user, Project $project): bool {}

    public function forceDelete(User $user, Project $project): bool {}
}
