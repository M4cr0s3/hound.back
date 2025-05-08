<?php

namespace App\Modules\Team\Resources;

use App\Models\Team;
use App\Modules\Project\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Team */
final class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'members' => $this->whenNotNull(TeamMemberResource::collection($this->members)),
            'projects' => $this->whenNotNull(ProjectResource::collection($this->projects)),
            'assignments' => $this->whenLoaded('assignments', function () {
                return $this->assignments->map(function ($assignment) {
                    if ($assignment->assignable_type !== Team::class) {
                        return [];

                    }

                    return [
                        'id' => $assignment->id,
                        'issue' => [
                            'id' => $assignment->issue_id,
                            'title' => $assignment->issue->title,
                            'status' => $assignment->issue->status,
                        ],
                    ];
                });
            }),
        ];
    }
}
