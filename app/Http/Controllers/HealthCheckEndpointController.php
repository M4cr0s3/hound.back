<?php

namespace App\Http\Controllers;

use App\Models\HealthCheckEndpoint;
use Illuminate\Http\Request;

class HealthCheckEndpointController
{
    public function index()
    {
        return HealthCheckEndpoint::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects'],
            'url' => ['required'],
            'method' => ['required'],
            'expected_status' => ['required'],
            'interval' => ['required'],
            'is_active' => ['boolean'],
            'last_checked_at' => ['required'],
        ]);

        return HealthCheckEndpoint::create($data);
    }

    public function show(HealthCheckEndpoint $healthCheckEndpoint)
    {
        return $healthCheckEndpoint;
    }

    public function update(Request $request, HealthCheckEndpoint $healthCheckEndpoint)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects'],
            'url' => ['required'],
            'method' => ['required'],
            'expected_status' => ['required'],
            'interval' => ['required'],
            'is_active' => ['boolean'],
            'last_checked_at' => ['required'],
        ]);

        $healthCheckEndpoint->update($data);

        return $healthCheckEndpoint;
    }

    public function destroy(HealthCheckEndpoint $healthCheckEndpoint)
    {
        $healthCheckEndpoint->delete();

        return response()->json();
    }
}
