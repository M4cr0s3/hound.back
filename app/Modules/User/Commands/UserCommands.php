<?php

namespace App\Modules\User\Commands;

final readonly class UserCommands
{
    public static function commands(): array
    {
        return [
            CreateMaintainerCommand::class,
        ];
    }
}
