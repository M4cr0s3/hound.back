<?php

namespace App\Modules\Team\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTeamRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:teams,name'],
            'slug' => ['string', 'max:255', 'unique:teams,slug'],
        ];
    }
}
