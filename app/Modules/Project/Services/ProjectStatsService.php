<?php

namespace App\Modules\Project\Services;

use App\Models\Project;
use App\Modules\Issue\Enum\IssueStatus;
use Illuminate\Support\Collection;

final class ProjectStatsService
{
    public function getSummaryStats(Project $project): array
    {
        $uptime = $this->calculateUptime(project: $project);

        return [
            'events_today' => $project->events()->whereDate('created_at', today())->count(),
            'events_week' => $project->events()
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'active_issues' => $project->issues()
                ->whereIn('status', [IssueStatus::OPEN, IssueStatus::IN_PROGRESS])
                ->count(),
            'uptime_percentage' => $uptime,
        ];
    }

    public function getDailyStats(Project $project): Collection
    {
        return $project->eventHourlyStats()
            ->where('hour', '>=', now()->subDays(7))
            ->orderBy('hour')
            ->get()
            ->groupBy(fn ($item) => $item->hour->format('Y-m-d'))
            ->map(fn ($dayStats) => [
                'date' => $dayStats->first()->hour->format('M j'),
                'total' => $dayStats->sum('total_events'),
                'errors' => $dayStats->sum('error_count'),
                'warnings' => $dayStats->sum('warning_count'),
            ])
            ->values();
    }

    public function getTrendedStats(Project $project): array
    {
        $current = $project->events()->where('created_at', '>=', now()->subDay())->count();
        $previous = $project->events()
            ->whereBetween('created_at', [now()->subDays(2), now()->subDay()])
            ->count();

        return [
            'total' => $project->events()->count(),
            'errors' => $project->events()->ofLevel('error')->count(),
            'warnings' => $project->events()->ofLevel('warning')->count(),
            'trend' => $this->calculateTrend(current: $current, previous: $previous),
            'trend_value' => $this->calculateTrendValue(current: $current, previous: $previous),
        ];
    }

    public function getWeeklyStats(Project $project): array
    {
        $weekStart = now()->startOfWeek();
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();

        $currentCount = $project->events()->where('created_at', '>=', $weekStart)->count();
        $previousCount = $project->events()
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        $currentErrors = $project->events()
            ->ofLevel('error')
            ->where('created_at', '>=', $weekStart)
            ->count();

        $previousErrors = $project->events()
            ->ofLevel('error')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        return [
            'events' => [
                'count' => $currentCount,
                'trend' => $this->calculateTrend(current: $currentCount, previous: $previousCount),
                'trend_value' => $this->calculateTrendValue(current: $currentCount, previous: $previousCount),
            ],
            'errors' => [
                'count' => $currentErrors,
                'trend' => $this->calculateTrend(current: $currentErrors, previous: $previousErrors),
                'trend_value' => $this->calculateTrendValue(current: $currentErrors, previous: $previousErrors),
            ],
        ];
    }

    private function calculateUptime(Project $project): float
    {
        $endpoints = $project->endpoints()->with('results')->get();

        $totalPercentage = $endpoints->sum(function ($endpoint) {
            $results = $endpoint->results;
            if ($results->isEmpty()) {
                return 100;
            }

            return $results->where('success', true)->count() / $results->count() * 100;
        });

        return round($totalPercentage, 2);
    }

    private function calculateTrend(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'up' : 'neutral';
        }

        $percentage = (($current - $previous) / $previous) * 100;

        return match (true) {
            $percentage > 5 => 'up',
            $percentage < -5 => 'down',
            default => 'neutral',
        };
    }

    private function calculateTrendValue(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? '100%' : '0%';
        }

        $percentage = (($current - $previous) / $previous) * 100;

        return round($percentage).'%';
    }
}
