<?php

namespace App\Modules\Search\Controller;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class SearchController
{
    public function globalSearch(Request $request): JsonResponse
    {
        $searchTerm = $request->get('search');

        $results = collect()
            ->concat(
                $this->searchWithType(Issue::class, 'issue', $searchTerm)
            )
            ->concat(
                $this->searchWithType(Team::class, 'team', $searchTerm)
            )
            ->concat(
                $this->searchWithType(Project::class, 'project', $searchTerm)
            )
            ->take(10)
            ->values()
            ->all();

        return response()->json(['data' => $results]);
    }

    /** @param  class-string  $model */
    protected function searchWithType(string $model, string $type, string $searchTerm)
    {
        return $model::search($searchTerm)
            ->get()
            ->map(function ($item) use ($type) {
                return array_merge($item->toArray(), ['type' => $type]);
            });
    }
}
