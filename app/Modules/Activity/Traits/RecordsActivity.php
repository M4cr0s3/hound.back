<?php

namespace App\Modules\Activity\Traits;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait RecordsActivity
{
    protected static function bootRecordsActivity(): void
    {
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function (Model $model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    protected static function getActivitiesToRecord(): array
    {
        return ['created', 'updated'];
    }

    protected function recordActivity($event): void
    {
        if ($event === 'updated') {
            $changes = $this->getChangedAttributes();
            if (empty($changes['before']) && empty($changes['after'])) {
                return;
            }
        }

        $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
            'changes' => $this->getActivityChanges($event),
        ]);
    }

    protected function getActivityType($event): string
    {
        return $event.'_'.strtolower(class_basename($this));
    }

    protected function getActivityChanges($event): array
    {
        return $event === 'updated'
            ? $this->getChangedAttributes()
            : ['before' => null, 'after' => $this->getAttributes()];
    }

    protected function getChangedAttributes(): array
    {
        $before = Arr::except(array_diff($this->original, $this->attributes), 'updated_at');
        $after = Arr::except($this->getChanges(), 'updated_at');

        return [
            'before' => $before,
            'after' => $after,
        ];
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
