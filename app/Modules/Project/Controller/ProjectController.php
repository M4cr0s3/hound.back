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
use App\Modules\Project\Services\ProjectStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final readonly class ProjectController
{
    public function __construct(
        private ProjectStatsService $statsService
    ) {}

    public function index(): ResourceCollection
    {
        return \Cache::remember(
            key: 'projects',
            ttl: 60,
            callback: fn () => ProjectResource::collection(Project::all())
        );
    }

    public function store(StoreProjectRequest $request, CreateProjectAction $action): JsonResponse
    {
        $action->execute(data: $request->validated());

        return response()->json(
            data: [
                'success' => true,
                'message' => 'Project created successfully',
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function show(Project $project): JsonResponse
    {
        $project->load([
            'team',
            'endpoints',
            'notificationRules',
            'events' => fn ($query) => $query->latest()->limit(5),
            'issues' => fn ($query) => $query->with('event')->latest('issues.created_at')->limit(5),
            'key',
        ]);

        return response()->json([
            'project' => $project,
            'stats' => $this->statsService->getSummaryStats(project: $project),
            'daily_stats' => $this->statsService->getDailyStats(project: $project),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project, UpdateProjectAction $action): JsonResponse
    {
        $action->execute(
            project: $project,
            data: $request->validated()
        );

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
            'events',
            'eventHourlyStats' => fn ($q) => $q->whereBetween('created_at', [now()->subHours(5), now()->addHours(5)]),
            'endpoints' => fn ($q) => $q->where('last_checked_at', '>=', now()->subDay())
                ->withAvg('results', 'response_time'),
        ])->get();

        return LastDayStatisticResource::collection($projects);
    }

    public function healthcheck(Project $project): Collection
    {
        return collect($project->endpoints);
    }

    public function events(Request $request, Project $project): LengthAwarePaginator
    {
        return $project->events()
            ->latest()
            ->when($request->get('level'), fn ($q, $level) => $q->ofLevel($level))
            ->paginate(perPage: $request->get('per_page', 10));
    }

    public function dashboard(): ResourceCollection
    {
        return DashboardProjectResource::collection(
            Project::with('team', 'issues')->get()
        );
    }

    public function stats(Project $project): JsonResponse
    {
        return response()->json($this->statsService->getTrendedStats(project: $project));
    }

    public function weeklyStats(Project $project): JsonResponse
    {
        return response()->json($this->statsService->getWeeklyStats(project: $project));
    }
}
