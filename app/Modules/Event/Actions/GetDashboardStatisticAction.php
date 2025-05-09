<?php

namespace App\Modules\Event\Actions;

use App\Models\Event;
use App\Models\Issue;
use App\Modules\Issue\Enum\IssueStatus;
use Illuminate\Support\Collection;

final class GetDashboardStatisticAction
{
    /** @var Collection<Event> */
    private Collection $resource;

    public function handle(Collection $events): array
    {
        $this->resource = $events;

        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'total_events' => [
                'value' => $this->getTotalEvents(),
                'change_percentage' => $this->calculateChangePercentage(
                    $this->getTotalEvents(),
                    $this->getTotalEventsYesterday()
                ),
            ],
            'unresolved_issues' => [
                'value' => $this->getUnresolvedIssues(),
                'change_percentage' => $this->calculateChangePercentage(
                    $this->getUnresolvedIssues(),
                    $this->getUnresolvedIssuesYesterday()
                ),
            ],
            'resolved_issues' => [
                'value' => $this->getResolvedIssues(),
                'change_percentage' => $this->calculateChangePercentage(
                    $this->getResolvedIssues(),
                    $this->getResolvedIssuesYesterday()
                ),
            ],
            'closed_issues' => [
                'value' => $this->getClosedIssues(),
                'change_percentage' => $this->calculateChangePercentage(
                    $this->getClosedIssues(),
                    $this->getClosedIssuesYesterday()
                ),
            ],
        ];
    }

    private function getTotalEvents(): int
    {
        return $this->resource->count();
    }

    private function getUnresolvedIssues(): int
    {
        return Issue::whereIn('status', [IssueStatus::OPEN, IssueStatus::IN_PROGRESS])->count();
    }

    private function getResolvedIssues(): int
    {
        return Issue::where('status', IssueStatus::RESOLVED)->count();
    }

    private function getClosedIssues(): int
    {
        return Issue::where('status', IssueStatus::CLOSED)->count();
    }

    private function getTotalEventsYesterday(): int
    {
        return $this->resource->where('created_at', now()->subDay())->count();
    }

    private function getUnresolvedIssuesYesterday(): int
    {
        return Issue::where('status', [IssueStatus::OPEN, IssueStatus::IN_PROGRESS])
            ->whereDate('created_at', now()->subDay())
            ->count();
    }

    private function getResolvedIssuesYesterday(): int
    {
        return Issue::where('status', IssueStatus::RESOLVED)
            ->whereDate('created_at', now()->subDay())
            ->count();
    }

    private function getClosedIssuesYesterday(): int
    {
        return Issue::where('status', IssueStatus::CLOSED)
            ->whereDate('created_at', now()->subDay())
            ->count();
    }

    private function calculateChangePercentage(int $today, int $yesterday): ?float
    {
        if ($yesterday === 0) {
            return $today > 0 ? 100.0 : null;
        }

        return round((($today - $yesterday) / $yesterday) * 100, 2);
    }
}
