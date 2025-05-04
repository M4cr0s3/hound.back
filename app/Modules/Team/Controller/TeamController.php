<?php

namespace App\Modules\Team\Controller;

use App\Models\Team;
use App\Models\User;
use App\Modules\Team\Requests\AddTeamMembersRequest;
use App\Modules\Team\Requests\StoreTeamRequest;
use App\Modules\Team\Requests\UpdateTeamRequest;
use App\Modules\Team\Resources\TeamResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final readonly class TeamController
{
    public function index(): ResourceCollection
    {
        return \Cache::remember('teams', 60, function () {
            return TeamResource::collection(Team::all());
        });
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
            'message' => 'Team created successfully',
        ], Response::HTTP_CREATED);
    }

    public function show(string $slug): TeamResource
    {
        $team = Team::where('slug', $slug)->firstOrFail();

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
            'message' => 'Team updated successfully',
        ]);
    }

    public function destroy(Team $team): JsonResponse
    {
        $team->delete();

        return \response()->json([
            'success' => true,
            'message' => 'Team deleted successfully',
        ]);
    }

    public function availableUsers(Team $team, Request $request): JsonResponse
    {
        $query = User::whereDoesntHave('teams', function ($query) use ($team) {
            $query->where('teams.id', $team->id);
        });

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);

        return response()->json([
            'users' => $users->toArray()['data'],
        ]);
    }

    public function addMembers(Team $team, AddTeamMembersRequest $request): JsonResponse
    {
        $team->members()->syncWithoutDetaching($request->validated()['user_ids']);

        return \response()->json([
            'success' => true,
            'message' => 'Member added successfully',
        ]);
    }

    public function removeMember(Team $team, User $user): JsonResponse
    {
        $team->members()->detach($user->id);

        return \response()->json([
            'success' => true,
            'message' => 'Member removed successfully',
        ]);
    }

    public function availableToAssign(Request $request): JsonResponse
    {
        $query = Team::whereDoesntHave('assignments', function ($query) {
            $query->where(['assignable_type' => Team::class]);
        });

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $teams = $query->paginate(10);

        return response()->json([
            'teams' => $teams->toArray()['data'],
        ]);
    }
}
