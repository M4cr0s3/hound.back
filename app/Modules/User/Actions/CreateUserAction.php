<?php

namespace App\Modules\User\Actions;

use App\Models\User;

final readonly class CreateUserAction
{
    public function handle(array $data): User
    {
        return User::create([
            'email' => $data['email'],
            'role_id' => $data['role_id'],
            'name' => $data['name'],
            'password' => \Hash::make(\Str::random()),
        ]);
    }
}
