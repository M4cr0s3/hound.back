<?php

namespace App\Modules\Healthcheck\Services;

use App\Models\HealthCheckEndpoint;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

final class HealthCheckService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'connect_timeout' => 5,
            'verify' => false,
        ]);
    }

    public function checkEndpoint(HealthCheckEndpoint $endpoint): void
    {
        $start = microtime(true);

        try {
            $response = $this->client->request(
                $endpoint->method,
                $endpoint->url,
                ['http_errors' => false]
            );

            $responseTime = $this->calculateResponseTime($start);
            $success = $response->getStatusCode() === $endpoint->expected_status;

            $this->saveResult($endpoint, [
                'response_time' => $responseTime,
                'status_code' => $response->getStatusCode(),
                'success' => $success,
                'response_body' => $this->truncateResponse($response),
                'error_message' => $success ? null : 'Unexpected status code',
            ]);

        } catch (GuzzleException $e) {
            $responseTime = $this->calculateResponseTime($start);

            $this->saveResult($endpoint, [
                'response_time' => $responseTime,
                'status_code' => 0,
                'success' => false,
                'response_body' => null,
                'error_message' => $e->getMessage(),
            ]);
        }

        $endpoint->update(['last_checked_at' => now()]);
    }

    public function checkMultipleEndpoints(Collection $endpoints): void
    {
        $promises = [];
        $startTimes = [];

        foreach ($endpoints as $endpoint) {
            $startTimes[$endpoint->id] = microtime(true);
            $promises[$endpoint->id] = $this->client->requestAsync(
                $endpoint->method,
                $endpoint->url,
                ['http_errors' => false]
            )->otherwise(function ($reason) {
                return $reason;
            });
        }

        $results = Utils::settle($promises)->wait();

        foreach ($results as $endpointId => $result) {
            $endpoint = $endpoints->firstWhere('id', $endpointId);
            $responseTime = $this->calculateResponseTime($startTimes[$endpointId]);

            if ($result['state'] === 'fulfilled') {
                $response = $result['value'];
                $success = $response->getStatusCode() === $endpoint->expected_status;

                $this->saveResult($endpoint, [
                    'response_time' => $responseTime,
                    'status_code' => $response->getStatusCode(),
                    'success' => $success,
                    'response_body' => $this->truncateResponse($response),
                    'error_message' => $success ? null : 'Unexpected status code',
                ]);
            } else {
                $this->saveResult($endpoint, [
                    'response_time' => $responseTime,
                    'status_code' => 0,
                    'success' => false,
                    'response_body' => null,
                    'error_message' => $result['reason'] instanceof \Exception
                        ? $result['reason']->getMessage()
                        : 'Unknown error',
                ]);
            }

            $endpoint->update(['last_checked_at' => now()]);
        }
    }

    private function saveResult(HealthCheckEndpoint $endpoint, array $data): void
    {
        $endpoint->results()->create($data);
    }

    private function truncateResponse(ResponseInterface $response): ?string
    {
        $body = (string) $response->getBody();

        return strlen($body) > 1000 ? substr($body, 0, 1000).'...' : $body;
    }

    private function calculateResponseTime(float $startTime): float
    {
        return (microtime(true) - $startTime) * 1000;
    }
}
