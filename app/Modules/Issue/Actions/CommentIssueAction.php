<?php

namespace App\Modules\Issue\Actions;

use App\Models\Issue;

final readonly class CommentIssueAction
{
    public function handle(Issue $issue, array $data): void
    {
        $issue->comments()->create([
            'text' => $data['text'],
            'user_id' => \Auth::id(),
        ]);
    }
}
