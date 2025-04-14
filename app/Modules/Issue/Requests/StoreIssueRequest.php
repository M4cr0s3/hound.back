<?php

namespace App\Modules\Issue\Requests;

use App\Modules\Issue\Enum\IssuePriority;
use App\Modules\Issue\Enum\IssueStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'culprit' => ['nullable', 'string', 'max:255'],
            'status' => [
                'nullable',
                'string',
                'in:'.implode(',', array_column(IssueStatus::cases(), 'value')),
            ],
            'priority' => [
                'nullable',
                'string',
                'in:'.implode(',', array_column(IssuePriority::cases(), 'value')),
            ],
            'due_date' => ['nullable', 'date'],
            'assignees' => ['nullable', 'array'],
            'assignees.*' => ['exists:users,id'],
            'teams' => ['nullable', 'array'],
            'teams.*' => ['exists:teams,id'],
        ];
    }
}
