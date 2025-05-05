<?php

namespace App\Modules\Issue\Controller;

use App\Models\Issue;
use App\Models\Team;
use App\Models\User;
use App\Modules\Issue\Actions\AssignToIssueAction;
use App\Modules\Issue\Actions\CommentIssueAction;
use App\Modules\Issue\Actions\CreateIssueAction;
use App\Modules\Issue\Actions\RemoveAssignAction;
use App\Modules\Issue\Filters\IssuePriorityFilter;
use App\Modules\Issue\Filters\IssueSearchFilter;
use App\Modules\Issue\Filters\IssueStatusFilter;
use App\Modules\Issue\Requests\AssignToIssueRequest;
use App\Modules\Issue\Requests\CommentRequest;
use App\Modules\Issue\Requests\RemoveAssignRequest;
use App\Modules\Issue\Requests\StoreIssueRequest;
use App\Modules\Issue\Requests\UpdateIssueRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final readonly class IssueController
{
    public function index(Request $request): JsonResponse
    {
        $filters = [
            IssueSearchFilter::class,
            IssueStatusFilter::class,
            IssuePriorityFilter::class,
        ];

        $issues = Issue::filter($request, $filters)
            ->paginate($request->query('per_page', 10))
            ->toArray();

        return \response()->json([
            'issues' => $issues['data'],
            'pagination' => \Arr::except($issues, 'data'),
        ]);
    }

    public function update(UpdateIssueRequest $request, Issue $issue): JsonResponse
    {
        $issue->update($request->validated());

        return \response()->json([
            'success' => true,
            'message' => 'Issue updated successfully',
        ]);
    }

    public function store(StoreIssueRequest $request, CreateIssueAction $action): JsonResponse
    {
        $action->handle($request->validated(), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Issue created successfully',
        ], Response::HTTP_CREATED);
    }

    public function show(Issue $issue): JsonResponse
    {
        $issue->load(
            'assignments.assignable',
            'event',
            'comments.user',
            'comments.parent.user',
            'comments.replies.user',
            'activities.user'
        );

        $assignees = [
            'users' => [],
            'teams' => [],
        ];

        foreach ($issue->assignments as $assignment) {
            $assignable = $assignment->assignable;

            if ($assignable instanceof User) {
                $assignees['users'][] = $assignable;
            } elseif ($assignable instanceof Team) {
                $assignees['teams'][] = $assignable;
            }
        }

        $responseData = $issue->toArray();
        $responseData['assignees'] = $assignees;
        $responseData['activities'] = $issue->activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'type' => $activity->type,
                'changes' => $activity->changes,
                'user' => $activity->user,
                'created_at' => $activity->created_at,
            ];
        });

        unset($responseData['assignments']);

        return response()->json([
            'issue' => $responseData,
        ]);
    }

    public function destroy(Issue $issue): JsonResponse
    {
        $issue->delete();

        return response()->json([
            'success' => true,
            'message' => 'Issue deleted successfully',
        ]);
    }

    public function comment(Issue $issue, CommentRequest $request, CommentIssueAction $action): JsonResponse
    {
        $action->handle($issue, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
        ], Response::HTTP_CREATED);
    }

    public function assign(
        Issue                $issue,
        AssignToIssueRequest $request,
        AssignToIssueAction  $action
    ): JsonResponse
    {
        $action->handle($issue, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Assigned to issue successfully',
        ], Response::HTTP_CREATED);
    }

    public function removeAssign(
        Issue               $issue,
        int                 $assigneeId,
        RemoveAssignRequest $request,
        RemoveAssignAction  $action
    ): JsonResponse
    {
        $action->handle($issue, [
            'assignee_id' => $assigneeId,
            ...$request->validated(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Removed assignee from issue successfully',
        ]);
    }

    public function dashboard(): Collection
    {
        return Issue::with('event.project')
            ->latest()
            ->limit(3)
            ->get();
    }
}
