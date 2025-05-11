<?php

namespace App\Modules\Healthcheck\Controller;

use App\Models\HealthCheckEndpoint;
use App\Models\Project;
use App\Modules\Healthcheck\Requests\StoreHealthCheckEndpointRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class HealthCheckController
{
    public function store(StoreHealthCheckEndpointRequest $request, Project $project): JsonResponse
    {
        $project->endpoints()->create($request->validated());

        return response()->json(['message' => 'Endpoint created successfully']);
    }

    public function show(HealthCheckEndpoint $healthCheckEndpoint): ResourceCollection
    {
        $results = $healthCheckEndpoint->results()
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc');

        $stats = [
            'avg_response_time' => (clone $results)->avg('response_time') ?? 0,
            'uptime_percentage' =>  (clone $results)->count() > 0
                ? ((clone $results)->where('success', true)->count() / (clone $results)->count()) * 100
                : 100,
            'total_checks' => (clone $results)->count(),
            'success_checks' => (clone $results)->where('success', true)->count(),
            'failure_checks' => (clone $results)->where('success', false)->count(),
        ];

        return JsonResource::collection($results->limit(25)->get())
            ->additional([
                'stats' => $stats,
                'endpoint' => $healthCheckEndpoint,
            ]);
    }

    public function update(
        Request $request,
        HealthCheckEndpoint $healthCheckEndpoint,
    ): JsonResponse {
        $healthCheckEndpoint->update($request->all());

        return response()->json(['message' => 'Endpoint updated successfully']);
    }

    public function destroy(HealthCheckEndpoint $healthCheckEndpoint): JsonResponse
    {
        $healthCheckEndpoint->delete();

        return response()->json(['message' => 'Endpoint deleted successfully']);
    }
}
