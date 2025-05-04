<?php

namespace App\Modules\User\Controller;

use App\Models\User;
use App\Modules\Invite\Actions\CreateInviteAction;
use App\Modules\User\Actions\CreateUserAction;
use App\Modules\User\Filters\UserSearchFilter;
use App\Modules\User\Requests\StoreUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final class UserController
{
    public function index(Request $request): JsonResponse
    {
        $filters = [
            UserSearchFilter::class,
        ];

        $users = User::filter($request, $filters)
            ->with(['role', 'invitations' => fn ($query) => $query->notUsed()])
            ->paginate($request->query('per_page', 10))
            ->toArray();

        return response()->json([
            'users' => $users['data'],
            'pagination' => \Arr::except($users, 'data'),
        ]);
    }

    public function store(
        StoreUserRequest $request,
        CreateUserAction $action,
        CreateInviteAction $inviteAction,
    ): JsonResponse {
        $user = $action->handle($request->validated());
        $inviteAction->handle($user->id, \Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
        ], Response::HTTP_CREATED);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'user' => \Auth::user(),
        ]);
    }

    public function search(Request $request): Collection
    {
        return User::search($request->get('q'))->get()->map(fn (User $user) => [
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }

    public function availableToAssign(Request $request): JsonResponse
    {
        $query = User::whereDoesntHave('assignments', function ($query) {
            $query->where(['assignable_type' => User::class]);
        });

        if ($request->has('q')) {
            $search = $request->input('q');
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
}
