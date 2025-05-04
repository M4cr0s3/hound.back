<?php

namespace App\Modules\Notification\Triggers;

final class ErrorCountParams
{
    public int $threshold = 10;

    public int $timeWindow = 60;

    public function __construct(array $params)
    {
        $this->threshold = $params['threshold'] ?? $this->threshold;
        $this->timeWindow = $params['time_window'] ?? $this->timeWindow;
    }
}
