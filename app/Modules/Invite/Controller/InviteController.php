<?php

namespace App\Modules\Invite\Controller;

use App\Models\Invite;
use App\Modules\Invite\Actions\ActivateInviteAction;
use App\Modules\Invite\Requests\ActivateInviteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

final readonly class InviteController
{
    public function index(): Collection
    {
        return Invite::all();
    }

    public function show(Invite $invite): JsonResponse
    {
        if ($invite->used) {
            return response()->json([
                'success' => false,
                'message' => 'Invite already used',
            ], Response::HTTP_CONFLICT);
        }

        $invite->load('user');

        return response()->json([
            'success' => true,
            'invite' => $invite,
        ]);
    }

    public function activate(
        ActivateInviteRequest $request,
        Invite $invite,
        ActivateInviteAction $action
    ): JsonResponse {
        return $action->handle($invite, $request->validated()['password']);
    }
}
