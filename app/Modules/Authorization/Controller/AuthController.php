<?php

namespace App\Modules\Authorization\Controller;

use App\Modules\Authorization\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

final class AuthController
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! $token = \Auth::attempt($request->validated())) {
            throw new UnauthorizedException('Unauthorized.');
        }

        return response()->json([
            'status' => 'success',
            'token' => $token,
        ]);

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

    public function refresh(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'token' => Auth::refresh(),
        ]);
    }
}
