<?php

namespace App\Modules\Notification\Channels;

use Illuminate\Support\Facades\Http;

final readonly class TelegramChannel implements NotificationChannelInterface
{
    public function __construct(
        private string $token,
        private string $chatId,
    ) {}

    public function send(string $message, array $context = []): void
    {
        Http::withOptions([
            'verify' => false,
        ])->post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'markdown',
        ]);
    }
}
