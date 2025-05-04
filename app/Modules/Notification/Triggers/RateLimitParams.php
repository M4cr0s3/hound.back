<?php

namespace App\Modules\Notification\Triggers;

final class RateLimitParams
{
    public int $maxEvents = 10;

    public int $timeWindow = 60;

    public function __construct(array $params)
    {
        $this->maxEvents = $params['max_events'] ?? $this->maxEvents;
        $this->timeWindow = $params['time_window'] ?? $this->timeWindow;
    }
}
