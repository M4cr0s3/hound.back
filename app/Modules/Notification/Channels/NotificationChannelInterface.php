<?php

namespace App\Modules\Notification\Channels;

interface NotificationChannelInterface
{
    public function send(string $message, array $context = []): void;
}
