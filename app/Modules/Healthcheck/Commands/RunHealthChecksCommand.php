<?php

namespace App\Modules\Healthcheck\Commands;

use App\Models\HealthCheckEndpoint;
use App\Modules\Healthcheck\Services\HealthCheckService;
use Illuminate\Console\Command;

final class RunHealthChecksCommand extends Command
{
    protected $signature = 'health:check';

    protected $description = 'Run all active health checks';

    public function handle(HealthCheckService $service): void
    {
        $endpoints = HealthCheckEndpoint::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('last_checked_at')
                    ->orWhere('last_checked_at', '<', now()->subMinutes());
            })
            ->get();

        if ($endpoints->isEmpty()) {
            $this->info('No endpoints to check');

            return;
        }

        $this->info("Checking {$endpoints->count()} endpoints...");

        if ($endpoints->count() > 5) {
            $service->checkMultipleEndpoints($endpoints);
        } else {
            foreach ($endpoints as $endpoint) {
                $service->checkEndpoint($endpoint);
            }
        }

        $this->info('Health checks completed');
    }
}
