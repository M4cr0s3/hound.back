<?php

namespace App\Modules\Project\Resources;

use App\Models\Project;
use App\Modules\Team\Resources\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
final class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'platform' => $this->platform,

            'team' => new TeamResource($this->whenLoaded('team')),
        ];
    }
}
