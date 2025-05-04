<?php

namespace App\Modules\Invite\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ActivateInviteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
