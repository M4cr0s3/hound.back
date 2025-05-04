<?php

namespace App\Modules\Authorization\Actions;

use App\Models\RefreshToken;
use App\Models\User;

final readonly class ProcessRefreshTokenAction
{
    public function handle(User $user, string $ip): RefreshToken
    {
        $user->tokens()->delete();

        return $user->tokens()->create([
            'token' => RefreshToken::createToken(
                id: $user->id,
                ip: $ip,
                email: $user->email),
            'ip' => $ip,
            'active_to' => now()->addDays(14),
        ]);
    }
}
