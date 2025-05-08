<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required'],
        ];
    }
}
