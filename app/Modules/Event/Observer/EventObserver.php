<?php

namespace App\Modules\Event\Observer;

use App\Models\Event;
use App\Modules\Notification\Jobs\ProcessEventNotification;

final readonly class EventObserver
{
    public function created(Event $event): void
    {
        $this->dispatchNotification($event);
    }

    public function updated(Event $event): void
    {
        if ($event->wasChanged('count')) {
            $this->dispatchNotification($event);
        }
    }

    private function dispatchNotification(Event $event): void
    {
        ProcessEventNotification::dispatch($event)->onQueue('notifications');
    }
}
