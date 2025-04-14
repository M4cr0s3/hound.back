<?php

namespace App\Modules\Integration\Github\DTOs;

final readonly class RepositoryData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $fullName,
        public bool $private,
        public OwnerData $owner,
        public string $htmlUrl,
        public ?string $description,
        public bool $fork,
        public string $createdAt,
        public string $updatedAt,
        public string $pushedAt,
        public string $defaultBranch,
        public int $openIssuesCount,
        public int $forksCount,
        public int $starsCount,
        public int $watchersCount
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            fullName: $data['full_name'],
            private: $data['private'],
            owner: OwnerData::fromArray($data['owner']),
            htmlUrl: $data['html_url'],
            description: $data['description'] ?? null,
            fork: $data['fork'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
            pushedAt: $data['pushed_at'],
            defaultBranch: $data['default_branch'],
            openIssuesCount: $data['open_issues_count'],
            forksCount: $data['forks_count'],
            starsCount: $data['stargazers_count'],
            watchersCount: $data['watchers_count']
        );
    }
}
