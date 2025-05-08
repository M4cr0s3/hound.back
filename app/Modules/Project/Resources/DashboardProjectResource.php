<?php

namespace App\Modules\Project\Resources;

use App\Models\Project;
use App\Modules\Issue\Enum\IssueStatus;
use App\Modules\Team\Resources\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
final class DashboardProjectResource extends JsonResource
{
    /** @var Project */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'platform' => $this->platform,
            'resolved' => $this->getResolvedIssuesCount(),
            'issues' => $this->getTotalIssuesCount(),

            'team' => new TeamResource($this->whenLoaded('team')),
        ];
    }

    // TODO: last day only (24 hours)

    private function getResolvedIssuesCount(): ?int
    {
//        return $this->resource->issues()->lastDay()->where('status', IssueStatus::RESOLVED)->count();
        return $this->resource->issues->where('status', IssueStatus::RESOLVED)->count();
    }

    private function getTotalIssuesCount(): ?int
    {
//        return $this->resource->issues()->lastDay()->count();
        return $this->resource->issues->count();
    }
}
