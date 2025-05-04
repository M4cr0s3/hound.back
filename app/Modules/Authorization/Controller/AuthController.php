<?php

namespace App\Modules\Authorization\Controller;

use App\Modules\Authorization\Actions\ProcessRefreshTokenAction;
use App\Modules\Authorization\Actions\RefreshAction;
use App\Modules\Authorization\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Cookie;

final class AuthController
{
    public function login(LoginRequest $request, ProcessRefreshTokenAction $action): JsonResponse
    {
        if (! $token = \Auth::attempt($request->validated())) {
            throw new UnauthorizedException('Unauthorized.');
        }

        $refresh = $action->handle(Auth::user(), $request->ip());

        return response()->json([
            'status' => 'success',
            'token' => $token,
        ])->withCookie(new Cookie(
            name: 'refresh_token',
            value: $refresh->token,
            expire: $refresh->active_to
        ));

    }

    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function user(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function refresh(Request $request, RefreshAction $action): JsonResponse
    {
        $user = $request->user('refresh');

        if (! $user) {
            throw new UnauthorizedException('Unauthorized.');
        }

        $refresh = $action->handle(
            user: $user,
            token: $request->cookie('refresh_token'),
            ip: $request->ip(),
        );

        return response()
            ->json([
                'status' => 'success',
                'token' => Auth::refresh(),
            ])
            ->withCookie(new Cookie(
                name: 'refresh_token',
                value: $refresh->token,
                expire: $refresh->active_to
            ));
    }
}
