<?php

namespace App\Modules\Integration\Github;

use App\Modules\Integration\Github\DTOs\RepositoryData;

final readonly class GithubRepositoryProvider
{
    public function __construct(private GitHubApiClient $apiClient) {}

    public function getRepository(string $owner, string $name): RepositoryData
    {
        return $this->apiClient->getRepository($owner, $name);
    }

    public function getRepositoryByUrl(string $url): RepositoryData
    {
        $pattern = '#github\.com/([^/]+)/([^/]+)#';
        if (! preg_match($pattern, $url, $matches)) {
            throw new \InvalidArgumentException('Invalid GitHub repository URL');
        }

        return $this->getRepository($matches[1], $matches[2]);
    }
}
