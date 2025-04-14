<?php

namespace App\Modules\Healthcheck\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthCheckEndpointRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'string', 'url'],
            'method' => ['required', 'string', 'in:GET,POST,PUT,PATCH,DELETE,OPTIONS'],
            'expected_status' => ['required', 'integer'],
            'interval' => ['required', 'integer'],
            'is_active' => ['boolean'],
        ];
    }
}
