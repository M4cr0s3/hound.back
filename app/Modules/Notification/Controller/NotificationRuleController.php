<?php

namespace App\Modules\Notification\Controller;

use App\Models\NotificationRule;
use App\Models\Project;
use App\Modules\Notification\Requests\StoreNotificationRuleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final readonly class NotificationRuleController
{
    public function index(Project $project): Collection
    {
        return $project->notificationRules;
    }

    public function store(StoreNotificationRuleRequest $request, Project $project): JsonResponse
    {
        $project->notificationRules()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Notification rule created successfully',
        ], Response::HTTP_CREATED);
    }

    public function show(NotificationRule $notificationRule)
    {
        return $notificationRule;
    }

    public function update(StoreNotificationRuleRequest $request, NotificationRule $notificationRule)
    {
        $notificationRule->update($request->validated());

        return $notificationRule;
    }

    public function destroy(NotificationRule $notificationRule): JsonResponse
    {
        $notificationRule->delete();

        return response()->json([
            'message' => 'Notification rule deleted successfully',
        ]);
    }
}
