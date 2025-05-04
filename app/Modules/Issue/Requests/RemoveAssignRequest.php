<?php

namespace App\Modules\Issue\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RemoveAssignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:user,team'],
        ];
    }
}
