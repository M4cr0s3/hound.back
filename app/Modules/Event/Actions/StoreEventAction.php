<?php

namespace App\Modules\Event\Actions;

use App\Models\Event;

final readonly class StoreEventAction
{
    public function execute(array $data): Event
    {
        $existsEvent = Event::where([
            'project_id' => $data['project_id'],
            'environment' => $data['environment'],
            'type' => $data['type'],
            'level' => $data['level'],
            'message' => $data['message'],
            'release' => $data['release'],
        ])->first();

        if ($existsEvent) {
            $existsEvent->count += $data['count'];
            $existsEvent->save();
            return $existsEvent;
        }

        return Event::create($data);
    }
}
