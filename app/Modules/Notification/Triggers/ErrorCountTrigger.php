<?php

namespace App\Modules\Notification\Triggers;

use App\Models\Event;
use App\Modules\Notification\Messages\NotificationMessages;
use Illuminate\Database\Eloquent\Builder;

final readonly class ErrorCountTrigger implements NotificationTriggerInterface
{
    private ErrorCountParams $params;

    private int $errorCount;

    public function shouldTrigger(Event $event, array $params): bool
    {
        $this->params = new ErrorCountParams($params);
        $this->errorCount = $event->project->events()
            ->ofLevel('error')
            ->where(function (Builder $query) {
                $query->where('created_at', '>=', now()->subMinutes($this->params->timeWindow))
                    ->orWhere('updated_at', '>=', now()->subMinutes($this->params->timeWindow));
            })
            ->sum('count');

        return $this->errorCount >= $this->params->threshold;
    }

    public function getMessage(Event $event): string
    {
        return NotificationMessages::errorCountThreshold(
            projectName: $event->project->name,
            errorCount: $this->errorCount,
            threshold: $this->params->threshold,
            timeWindow: $this->params->timeWindow
        );
    }
}
