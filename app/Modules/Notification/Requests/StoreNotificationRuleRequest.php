<?php

namespace App\Modules\Notification\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreNotificationRuleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_type' => ['required'],
            'trigger_type' => ['required'],
            'trigger_params' => ['required'],
            'channels' => ['required'],
        ];
    }
}
