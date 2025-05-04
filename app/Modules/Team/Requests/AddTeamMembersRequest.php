<?php

namespace App\Modules\Team\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AddTeamMembersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|integer|exists:users,id',
        ];
    }
}
