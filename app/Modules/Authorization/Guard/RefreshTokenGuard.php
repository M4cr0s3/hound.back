<?php

namespace App\Modules\Authorization\Guard;

use App\Models\RefreshToken;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

final class RefreshTokenGuard implements Guard
{
    use GuardHelpers;

    protected Request $request;

    protected $provider;

    protected $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->user = null;
    }

    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->request->cookie('refresh_token');

        if ($token) {
            $user = RefreshToken::where('token', $token)->first()?->user;
        }

        return $this->user = $user;
    }

    public function validate(array $credentials = []): bool
    {
        return ! is_null($this->user());
    }

    public function id()
    {
        return $this->user?->getAuthIdentifier();
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    public function check(): bool
    {
        return ! is_null($this->user());
    }

    public function guest(): bool
    {
        return ! $this->check();
    }

    public function logout(): void
    {
        $this->user = null;
    }

    public function hasUser()
    {
        // TODO: Implement hasUser() method.
    }
}
