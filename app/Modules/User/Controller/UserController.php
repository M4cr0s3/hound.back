<?php

namespace App\Modules\User\Controller;

use App\Models\IssueAssignment;
use App\Models\User;
use App\Modules\Invite\Actions\CreateInviteAction;
use App\Modules\Issue\Enum\IssueStatus;
use App\Modules\User\Actions\CreateUserAction;
use App\Modules\User\Filters\UserSearchFilter;
use App\Modules\User\Requests\ChangePasswordRequest;
use App\Modules\User\Requests\StoreUserRequest;
use Illuminate\Database\Eloquent\Builder;
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
            'user' => \Auth::user()->load('role'),
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
        $query = User::whereDoesntHave('assignments', function (Builder $q) use ($request) {
            $q->where('assignable_type', User::class)
                ->where('issue_id', $request->get('issue_id'));
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

    public function profile(): JsonResponse
    {
        $user = \Auth::user();
        $user->load('assignments.issue.project', 'teams', 'role', 'assignments.issue.event');

        $assignments = IssueAssignment::where([
            'assignable_type' => User::class,
            'assignable_id' => $user->id,
        ]);

        $stats = [
            'teams' => $user->teams->count(),
            'open_issues' => (clone $assignments)->whereHas('issue', function (Builder $q) {
                $q->where('status', IssueStatus::OPEN);
            })->count(),
            'resolved_issues' => (clone $assignments)->whereHas('issue', function (Builder $q) {
                $q->where('status', IssueStatus::RESOLVED);
            })->count(),
        ];

        return response()->json([
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        if (! \Hash::check($request->get('current_password'), \Auth::user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password was incorrect.',
            ], Response::HTTP_FORBIDDEN);
        }

        \Auth::user()->update([
            'password' => $request->get('password'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
