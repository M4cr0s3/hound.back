<?php

namespace App\Modules\Issue\Requests;

use App\Modules\Issue\Enum\IssuePriority;
use App\Modules\Issue\Enum\IssueStatus;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateIssueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_id' => ['nullable', 'exists:events,id'],
            'title' => ['nullable', 'unique:issues,title:'],
            'culprit' => ['nullable'],
            'status' => ['nullable', 'in:'.implode(',', array_column(IssueStatus::cases(), 'value'))],
            'priority' => ['nullable', 'in:'.implode(',', array_column(IssuePriority::cases(), 'value'))],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
