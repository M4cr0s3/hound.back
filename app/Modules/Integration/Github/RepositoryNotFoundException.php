<?php

namespace App\Modules\Integration\Github;

class RepositoryNotFoundException extends \RuntimeException
{
    public static function forRepository(string $owner, string $repo): self
    {
        return new self("Repository {$owner}/{$repo} not found on GitHub");
    }
}
