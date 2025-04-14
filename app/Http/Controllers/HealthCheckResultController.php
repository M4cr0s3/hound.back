<?php

namespace App\Http\Controllers;

use App\Models\HealthCheckResult;
use Illuminate\Http\Request;

class HealthCheckResultController
{
    public function index()
    {
        return HealthCheckResult::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'health_check_endpoint_id' => ['required', 'exists:health_check_endpoints'],
            'response_time' => ['required', 'numeric'],
            'status_code' => ['required', 'integer'],
            'success' => ['boolean'],
            'response_body' => ['nullable'],
            'error_message' => ['nullable'],
        ]);

        return HealthCheckResult::create($data);
    }

    public function show(HealthCheckResult $healthCheckResult)
    {
        return $healthCheckResult;
    }

    public function update(Request $request, HealthCheckResult $healthCheckResult)
    {
        $data = $request->validate([
            'health_check_endpoint_id' => ['required', 'exists:health_check_endpoints'],
            'response_time' => ['required', 'numeric'],
            'status_code' => ['required', 'integer'],
            'success' => ['boolean'],
            'response_body' => ['nullable'],
            'error_message' => ['nullable'],
        ]);

        $healthCheckResult->update($data);

        return $healthCheckResult;
    }

    public function destroy(HealthCheckResult $healthCheckResult)
    {
        $healthCheckResult->delete();

        return response()->json();
    }
}
