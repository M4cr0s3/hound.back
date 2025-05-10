<?php

namespace App\Modules\Event\Controller;

use App\Models\Event;
use App\Modules\Event\Actions\GetDashboardStatisticAction;
use App\Modules\Event\Actions\StoreEventAction;
use App\Modules\Event\Requests\StoreEventRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class EventController
{
    public function index() {}

    public function store(StoreEventRequest $request, StoreEventAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
        ], Response::HTTP_CREATED);
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json($event->load('issues', 'project'));
    }

    public function update(Request $request, Event $event) {}

    public function destroy(Event $event) {}

    public function dashboard(GetDashboardStatisticAction $action): JsonResponse
    {
        return response()->json($action->handle(Event::get()));
    }
}
