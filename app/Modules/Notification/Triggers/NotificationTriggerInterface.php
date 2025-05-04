<?php

namespace App\Modules\Notification\Triggers;

use App\Models\Event;

interface NotificationTriggerInterface
{
    public function shouldTrigger(Event $event, array $params): bool;

    public function getMessage(Event $event): string;
}
