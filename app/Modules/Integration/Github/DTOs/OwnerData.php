<?php

declare(strict_types=1);

namespace App\Modules\Integration\Github\DTOs;

final readonly class OwnerData
{
    public function __construct(
        public int $id,
        public string $login,
        public string $type,
        public string $avatarUrl,
        public string $htmlUrl
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            login: $data['login'],
            type: $data['type'],
            avatarUrl: $data['avatar_url'],
            htmlUrl: $data['html_url']
        );
    }
}
