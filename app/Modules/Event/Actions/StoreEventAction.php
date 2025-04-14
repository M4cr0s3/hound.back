<?php

namespace App\Modules\Event\Actions;

use App\Models\Event;

final readonly class StoreEventAction
{
    public function execute(array $data): Event
    {
        $existsEvent = Event::where(['metadata->fingerprint' => $data['metadata']['fingerprint']])->first();

        if ($existsEvent) {
            $existsEvent->count += $data['count'];
            $existsEvent->save();

            return $existsEvent;
        }

        return Event::create($data);
    }
}
