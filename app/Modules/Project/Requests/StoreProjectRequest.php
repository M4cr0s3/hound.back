<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'team_id' => ['required', 'exists:teams,id'],
            'name' => ['required', 'string', 'max:255', 'unique:projects,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:projects,slug'],
            'platform' => ['required', 'string'],
        ];
    }
}
