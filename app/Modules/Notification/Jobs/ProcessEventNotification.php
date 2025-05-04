<?php

namespace App\Modules\Notification\Jobs;

use App\Models\Event;
use App\Modules\Notification\Service\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEventNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Event $event) {}

    public function handle(NotificationService $notificationService): void
    {
        $notificationService->handleEvent($this->event);
    }
}
