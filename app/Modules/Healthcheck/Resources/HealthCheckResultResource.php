<?php

namespace App\Modules\Healthcheck\Resources;

use App\Models\HealthCheckResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin HealthCheckResult */
final class HealthCheckResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'response_time' => $this->response_time,
            'status_code' => $this->status_code,
            'success' => $this->success,
            'response_body' => $this->response_body,
            'error_message' => $this->error_message,
            'created_at' => $this->created_at,
        ];
    }
}
