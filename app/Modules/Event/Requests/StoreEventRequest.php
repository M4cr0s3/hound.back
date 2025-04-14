<?php

namespace App\Modules\Event\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_id' => ['required'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'environment' => ['required'],
            'type' => ['required'],
            'level' => ['required'],
            'message' => ['required'],
            'release' => ['required'],
            'metadata' => ['required'],
            'metadata.fingerprint' => ['required'],
            'count' => ['required', 'integer'],
        ];
    }
}
