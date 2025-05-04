<?php

namespace App\Modules\Invite\Actions;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class ActivateInviteAction
{
    public function handle(Invite $invite, string $password): JsonResponse
    {
        if ($invite->used) {
            return response()->json([
                'success' => false,
                'message' => 'Invite already used',
            ], Response::HTTP_CONFLICT);
        }

        $user = User::whereEmail($invite->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'password' => \Hash::make($password),
        ]);

        $invite->update(['used' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Invite activated successfully',
        ], Response::HTTP_OK);
    }
}
