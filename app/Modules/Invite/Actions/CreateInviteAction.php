<?php

namespace App\Modules\Invite\Actions;

use App\Models\Invite;
use App\Models\User;

final readonly class CreateInviteAction
{
    public function handle(int $id, int $creatorId): Invite
    {
        if (! $user = User::find($id)) {
            throw new \RuntimeException('User not found');
        }

        return Invite::create([
            'email' => $user->email,
            'token' => \Str::random(32),
            'user_id' => $id,
            'inviter_id' => $creatorId,
            'expires_at' => now()->addMinutes((int) \Config::get('invites.default_lifetime')),
        ]);
    }
}
