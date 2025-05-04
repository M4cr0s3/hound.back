<?php

namespace App\Modules\Issue\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AssignToIssueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:user,team'],
            'assignee_ids' => [
                'required',
                'array',
            ],
            'assignee_ids.*' => ['required', Rule::exists(match ($this->type) {
                'user' => 'users',
                'team' => 'teams',
            }, 'id')],
        ];
    }
}
