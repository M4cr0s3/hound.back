<?php

namespace App\Modules\Project\Resources;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/** @mixin Project */
final class LastDayStatisticResource extends JsonResource
{
    /** @var Project */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'platform' => $this->platform,

            'stats' => [
                'total_events' => $this->getTotalEvents(),
                'errors_last_day' => $this->getErrorCount(),
                'error_rate_change' => $this->getErrorRateChange(),
                'avg_response_time' => $this->getAvgResponseTime(),
            ],
            'events_last_day' => $this->getEventsLastDay(),
        ];
    }

    protected function getTotalEvents(): int
    {
        return $this->resource->events()->count();
    }

    protected function getErrorCount(): int
    {
        return $this->resource->events()->lastDay()->where('level', 'error')->count();
    }

    protected function getAvgResponseTime(): float
    {
        return $this->resource->endpoints->first()->results_avg_response_time ?? 0;
    }

    protected function getEventsLastDay(): Collection
    {
        return $this->resource->eventHourlyStats
            ->map(fn ($item) => [
                'hour' => Carbon::parse($item->hour)->format('H:00'),
                'count' => $item->total_events,
                'errors' => $item->error_count,
                'warnings' => $item->warning_count,
            ]);
    }

    protected function getErrorRateChange(): float
    {
        $statsLast48h = $this->resource->eventHourlyStats()
            ->where('hour', '>=', now()->subHours(48))
            ->orderBy('hour')
            ->get();

        if ($statsLast48h->isEmpty()) {
            return 0;
        }

        $currentPeriodEnd = now();
        $currentPeriodStart = now()->subDay();

        $currentPeriod = $statsLast48h->filter(function ($item) use ($currentPeriodStart, $currentPeriodEnd) {
            return $item->hour >= $currentPeriodStart && $item->hour <= $currentPeriodEnd;
        });

        $previousPeriod = $statsLast48h->filter(function ($item) use ($currentPeriodStart) {
            return $item->hour < $currentPeriodStart;
        });

        $currentErrorRate = $this->calculateErrorRate($currentPeriod);
        $previousErrorRate = $this->calculateErrorRate($previousPeriod);

        if ($previousErrorRate == 0) {
            return $currentErrorRate > 0 ? 100 : 0;
        }

        return round((($currentErrorRate - $previousErrorRate) / $previousErrorRate) * 100, 1);
    }

    protected function calculateErrorRate(Collection $periodStats): float
    {
        $totalEvents = $periodStats->sum('total_events');
        $totalErrors = $periodStats->sum('error_count');

        return $totalEvents > 0 ? ($totalErrors / $totalEvents) * 100 : 0;
    }
}
