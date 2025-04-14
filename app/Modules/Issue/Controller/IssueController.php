<?php

namespace App\Modules\Issue\Controller;

use App\Modules\Issue\Actions\CreateIssueAction;
use App\Modules\Issue\Requests\StoreIssueRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class IssueController
{
    public function store(StoreIssueRequest $request, CreateIssueAction $action): JsonResponse
    {
        $action->handle($request->validated(), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Issue created successfully'
        ], Response::HTTP_CREATED);
    }
}
