<?php

namespace App\Modules\Issue\Actions;

use App\Models\Issue;
use App\Models\Team;
use App\Models\User;

final readonly class AssignToIssueAction
{
    public function handle(Issue $issue, array $data): void
    {
        $type = $this->getAssignableType($data['type']);

        \DB::transaction(function () use ($data, $issue, $type) {

            foreach ($data['assignee_ids'] as $id) {
                $issue->assignments()->create([
                    'assignable_type' => $type,
                    'assignable_id' => $id,
                ]);
            }
        });
    }

    private function getAssignableType(string $type): string
    {
        return match ($type) {
            'user' => User::class,
            'team' => Team::class,
            default => throw new \InvalidArgumentException("Invalid assignable type: {$type}"),
        };
    }
}
