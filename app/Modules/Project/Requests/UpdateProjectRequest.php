<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'team_id' => ['exists:teams,id'],
            'name' => [
                'string',
                'max:255',
                Rule::unique('projects')->ignore($this->project),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('projects')->ignore($this->project),
            ],
            'platform' => ['string'],
        ];
    }
}
