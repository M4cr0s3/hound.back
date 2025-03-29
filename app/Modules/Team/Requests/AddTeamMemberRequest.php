<?php

namespace App\Modules\Team\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AddTeamMemberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
