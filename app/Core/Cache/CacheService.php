<?php

namespace App\Core\Cache;

final class CacheService
{
    /**
     * @param  class-string  $model
     */
    public function forget(string $model, string|int|null $id = null, ForgetMode $mode = ForgetMode::ALL): void
    {
        $key = $this->getKeyFromModelName($model);

        match ($mode) {
            ForgetMode::ID => \Cache::forget("$key:$id"),
            ForgetMode::ALL => $this->forgetAll($key),
        };
    }

    private function forgetAll(string $key): void
    {
        \Cache::forget($this->getPlural($key));
        \Cache::forget("$key:*");
    }

    private function getKeyFromModelName(string $key): string
    {
        return \Str::of($key)
            ->afterLast('\\')
            ->lower()
            ->value();
    }

    private function getPlural(string $key): string
    {
        return \Str::plural($key);
    }
}
