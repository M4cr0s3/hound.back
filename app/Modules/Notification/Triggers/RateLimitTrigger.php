<?php

namespace App\Modules\Notification\Triggers;

use App\Models\Event;
use App\Modules\Notification\Messages\NotificationMessages;

final class RateLimitTrigger implements NotificationTriggerInterface
{
    private ?int $currentCount = null;

    private ?int $newCount = null;

    private ?string $cacheKey = null;

    private RateLimitParams $params;

    public function shouldTrigger(Event $event, array $params): bool
    {
        $this->params = new RateLimitParams($params);
        $this->cacheKey = $this->getCacheKey($event, $this->params);
        $this->currentCount = \Cache::get($this->cacheKey, 0);
        $this->newCount = $this->currentCount + $event->count;

        \Cache::put($this->cacheKey, $this->newCount, $this->params->timeWindow);

        return $this->newCount >= $this->params->maxEvents;
    }

    public function getMessage(Event $event): string
    {
        return NotificationMessages::rateLimitExceeded(
            projectName: $event->project->name,
            currentCount: $this->newCount ?? $this->currentCount + $event->count,
            maxEvents: $this->params->maxEvents,
            timeWindow: $this->params->timeWindow,
            lastEventMessage: $event->message
        );
    }

    private function getCacheKey(Event $event, RateLimitParams $params): string
    {
        $roundedTime = now()->timestamp - (now()->timestamp % $params->timeWindow);

        return sprintf('event_rate_limit:%s:%s:%s', $event->project_id, $event->level, $roundedTime);
    }
}
