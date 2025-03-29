<?php

namespace App\Modules\Team\Controller;

use App\Models\Team;
use App\Modules\Team\Requests\AddTeamMemberRequest;
use App\Modules\Team\Requests\StoreTeamRequest;
use App\Modules\Team\Requests\UpdateTeamRequest;
use App\Modules\Team\Resources\TeamResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final readonly class TeamController
{
    public function index(): ResourceCollection
    {
        return TeamResource::collection(Team::all());
    }

    public function store(StoreTeamRequest $request): JsonResponse
    {
        $data = $request->validated();

        Team::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? \Str::slug($data['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Team created successfully'
        ], Response::HTTP_CREATED);
    }

    public function show(Team $team): TeamResource
    {
        return new TeamResource($team);
    }

    public function update(Team $team, UpdateTeamRequest $request): JsonResponse
    {
        $data = $request->validated();

        $team->update([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? \Str::slug($data['name']),
        ]);

        return \response()->json([
            'success' => true,
            'message' => 'Team updated successfully'
        ]);
    }

    public function destroy(Team $team): JsonResponse
    {
        $team->delete();

        return \response()->json([
            'success' => true,
            'message' => 'Team deleted successfully'
        ]);
    }

    public function addMember(Team $team, AddTeamMemberRequest $request): JsonResponse
    {
        $team->members()->attach($request->validated()['user_id']);

        return \response()->json([
            'success' => true,
            'message' => 'Member added successfully'
        ]);
    }
}
