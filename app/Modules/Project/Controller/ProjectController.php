<?php

namespace App\Modules\Project\Controller;

use App\Models\Project;
use App\Modules\Project\Actions\CreateProjectAction;
use App\Modules\Project\Actions\UpdateProjectAction;
use App\Modules\Project\Requests\StoreProjectRequest;
use App\Modules\Project\Requests\UpdateProjectRequest;
use App\Modules\Project\Resources\ProjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final readonly class ProjectController
{
    public function index(): ResourceCollection
    {
        return ProjectResource::collection(Project::all());
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
    ): JsonResponse
    {
        $action->execute($project, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
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
}
