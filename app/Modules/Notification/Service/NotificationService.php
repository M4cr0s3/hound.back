<?php

namespace App\Modules\Notification\Service;

use App\Models\Event;
use App\Modules\Notification\Channels\NotificationChannelInterface;
use App\Modules\Notification\Channels\TelegramChannel;
use App\Modules\Notification\Triggers\ErrorCountTrigger;
use App\Modules\Notification\Triggers\NotificationTriggerInterface;
use App\Modules\Notification\Triggers\RateLimitTrigger;

final readonly class NotificationService
{
    /** @var array<string, NotificationTriggerInterface> */
    private array $triggers;

    /** @var array<string, NotificationChannelInterface> */
    private array $channels;

    public function __construct()
    {
        $this->triggers = [
            'count' => new ErrorCountTrigger,
            'rate_limit' => new RateLimitTrigger,
        ];

        $this->channels = [
            'telegram' => new TelegramChannel(
                token: \Config::get('notifications.channels.telegram.token'),
                chatId: \Config::get('notifications.channels.telegram.chat_id'),
            ),
        ];
    }

    public function handleEvent(Event $event): void
    {
        $rules = $event->project->notificationRules()
            ->where('event_type', $event->level)
            ->get();

        foreach ($rules as $rule) {
            $trigger = $this->triggers[$rule->trigger_type] ?? null;
            if (! $trigger || ! $trigger->shouldTrigger($event, $rule->trigger_params)) {
                continue;
            }

            $this->sendNotification(
                event: $event,
                message: $trigger->getMessage($event),
                channels: $rule->channels
            );
        }
    }

    private function sendNotification(Event $event, string $message, array $channels): void
    {
        foreach ($channels as $channel) {
            $this->channels[$channel]->send($message, $event->toArray());
        }
    }
}
