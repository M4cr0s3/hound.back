<?php

namespace App\Modules\Issue\Actions;

use App\Models\Issue;
use App\Models\IssueAssignment;
use App\Models\Team;
use App\Models\User;

final readonly class RemoveAssignAction
{
    public function handle(Issue $issue, array $data): void
    {
        $type = $this->getAssigneeType($data['type']);

        $assignment = IssueAssignment::where([
            'issue_id' => $issue->id,
            'assignable_id' => $data['assignee_id'],
            'assignable_type' => $type,
        ])->first();

        $assignment?->delete();
    }

    private function getAssigneeType(string $type): string
    {
        return match ($type) {
            'user' => User::class,
            'team' => Team::class,
            default => throw new \InvalidArgumentException("Invalid assignee type: {$type}"),
        };
    }
}
