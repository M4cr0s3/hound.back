<?php

namespace App\Modules\Integration\Github;

use Illuminate\Http\Client\Response;

class GithubApiException extends \RuntimeException
{
    public static function fromResponse(Response $response): self
    {
        $message = $response->json()['message'] ?? 'GitHub API request failed';
        $code = $response->status();

        return new self("GitHub API Error: {$message}", $code);
    }
}
