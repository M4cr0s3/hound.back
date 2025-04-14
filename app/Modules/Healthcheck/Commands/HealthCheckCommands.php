<?php

namespace App\Modules\Healthcheck\Commands;

final readonly class HealthCheckCommands
{
    public static function commands(): array
    {
        return [
            RunHealthChecksCommand::class,
        ];
    }
}
