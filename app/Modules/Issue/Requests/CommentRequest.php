<?php

namespace App\Modules\Issue\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required'],
        ];
    }
}
