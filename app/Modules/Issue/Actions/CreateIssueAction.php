<?php

namespace App\Modules\Issue\Actions;

use App\Models\Issue;
use App\Models\User;
use App\Modules\Issue\Enum\IssuePriority;
use App\Modules\Issue\Enum\IssueStatus;
use Carbon\Carbon;

final readonly class CreateIssueAction
{
    public function handle(array $data, User $creator): Issue
    {
        return \DB::transaction(function () use ($data, $creator) {
            $issue = Issue::create([
                'event_id' => $data['event_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'culprit' => $data['culprit'] ?? null,
                'status' => $data['status'] ?? IssueStatus::OPEN,
                'priority' => $data['priority'] ?? IssuePriority::MEDIUM,
                'due_date' => isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
            ]);

            if (isset($data['assignees'])) {
                $this->assignUsers($issue, $data['assignees']);
            }

            if (isset($data['teams'])) {
                $this->assignTeams($issue, $data['teams']);
            }

            return $issue;
        });
    }

    private function assignUsers(Issue $issue, array $userIds): void
    {
        $issue->assignees()->sync($userIds);
    }

    private function assignTeams(Issue $issue, array $teamIds): void
    {
        $issue->teams()->sync($teamIds);

        $teamMembers = User::whereHas('teams', function ($query) use ($teamIds) {
            $query->whereIn('teams.id', $teamIds);
        })->pluck('id');

        $this->assignUsers($issue, $teamMembers->toArray());
    }
}
