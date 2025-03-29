<?php

namespace App\Modules\Integration\Github;


use App\Modules\Integration\Github\DTOs\RepositoryData;
use Firebase\JWT\JWT;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Factory as HttpClient;

final readonly class GithubApiClient
{
    private const BASE_URL = 'https://api.github.com';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private HttpClient $httpClient,
        private string $appId,
        private string $privateKey,
    ) {
    }

    public function getRepository(string $owner, string $repo): RepositoryData
    {
        $url = sprintf('%s/repos/%s/%s', self::BASE_URL, $owner, $repo);
        $response = $this->authenticatedRequest('get', $url);

        if ($response->status() === 404) {
            throw RepositoryNotFoundException::forRepository($owner, $repo);
        }

        if (!$response->successful()) {
            throw GitHubApiException::fromResponse($response);
        }

        return RepositoryData::fromArray($response->json());
    }

    private function authenticatedRequest(string $method, string $url): Response
    {
        return Cache::remember(
            key: 'github_token_'.$this->appId,
            ttl: self::CACHE_TTL - 300,
            callback: fn() => $this->httpClient->withHeaders([
                'Accept' => 'application/vnd.github+json',
                'Authorization' => 'Bearer '.$this->generateJwtToken(),
                'X-GitHub-Api-Version' => '2022-11-28',
            ])->{$method}($url)
        );
    }

    private function generateJwtToken(): string
    {
        $now = time();
        $payload = [
            'iat' => $now,
            'exp' => $now + self::CACHE_TTL,
            'iss' => $this->appId,
        ];

        $privateKey = openssl_pkey_get_private($this->privateKey);
        if ($privateKey === false) {
            throw new \RuntimeException('Invalid private key for GitHub App');
        }

        $jwt = JWT::encode($payload, $privateKey, 'RS256');
        openssl_free_key($privateKey);

        return $jwt;
    }
}
