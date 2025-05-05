<?php

namespace App\Modules\Project\Controller;

use App\Models\Project;
use App\Modules\Project\Actions\CreateProjectAction;
use App\Modules\Project\Actions\UpdateProjectAction;
use App\Modules\Project\Requests\StoreProjectRequest;
use App\Modules\Project\Requests\UpdateProjectRequest;
use App\Modules\Project\Resources\DashboardProjectResource;
use App\Modules\Project\Resources\LastDayStatisticResource;
use App\Modules\Project\Resources\ProjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final readonly class ProjectController
{
    public function index(): ResourceCollection
    {
        return \Cache::remember('projects', 60, function () {
            return ProjectResource::collection(Project::all());
        });
    }

    public function store(StoreProjectRequest $request, CreateProjectAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
        ], Response::HTTP_CREATED);
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    public function update(
        UpdateProjectRequest $request,
        Project $project,
        UpdateProjectAction $action
    ): JsonResponse {
        $action->execute($project, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => new ProjectResource($project),
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }

    public function getStatsForLastDay(): ResourceCollection
    {
        $projects = Project::with([
            'events' => fn ($q) => $q->lastDay(),
            'eventHourlyStats' => fn ($q) => $q->whereBetween('created_at', [
                now()->subHours(5),
                now()->addHours(5),
            ]),
            'endpoints' => fn ($q) => $q->where('last_checked_at', '>=', now()->subDay())
                ->withAvg('results', 'response_time'),
        ])->get();

        return LastDayStatisticResource::collection($projects);
    }

    public function healthcheck(Project $project): Collection
    {
        return $project->endpoints;
    }

    public function events(Request $request, Project $project): LengthAwarePaginator
    {
        return $project->events()
            ->latest()
            ->when($request->get('level'), fn ($q, $level) => $q->ofLevel($level))
            ->paginate($request->get('per_page', 10));
    }

    public function dashboard(): ResourceCollection
    {
        return DashboardProjectResource::collection(
            Project::with('team', 'issues')->get()
        );
    }
}
