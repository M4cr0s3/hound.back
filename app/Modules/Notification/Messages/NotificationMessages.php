<?php

namespace App\Modules\Notification\Messages;

final readonly class NotificationMessages
{
    public static function rateLimitExceeded(
        string $projectName,
        int $currentCount,
        int $maxEvents,
        int $timeWindow,
        string $lastEventMessage
    ): string {
        return sprintf(
            "🚨 *Превышен лимит ошибок!*\n\n".
            "Проект: *%s*\n".
            "Ошибок: *%d/%d* за последние *%d сек.*\n".
            'Последнее событие: `%s`',
            $projectName,
            $currentCount,
            $maxEvents,
            $timeWindow,
            $lastEventMessage
        );
    }

    public static function errorCountThreshold(
        string $projectName,
        int $errorCount,
        int $threshold,
        int $timeWindow
    ): string {
        return sprintf(
            "⚠️ *Накопилось много ошибок!*\n\n".
            "Проект: *%s*\n".
            "Всего ошибок: *%d* (порог: *%d*)\n".
            'Временное окно: *%d мин.*',
            $projectName,
            $errorCount,
            $threshold,
            $timeWindow
        );
    }
}
