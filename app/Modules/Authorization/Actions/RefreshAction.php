<?php

namespace App\Modules\Authorization\Actions;

use App\Models\RefreshToken;
use App\Models\User;

final readonly class RefreshAction
{
    public function __construct(private ProcessRefreshTokenAction $action) {}

    public function handle(User $user, string $token, string $ip): RefreshToken
    {
        $isExistsToken = RefreshToken::where('token', $token)->exists();

        if (! $isExistsToken) {
            throw new \HttpException('Refresh token not found.', 403);
        }

        $user->tokens()->where('token', $token)->delete();

        return $this->action->handle($user, $ip);
    }
}
