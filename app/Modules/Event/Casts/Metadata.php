<?php

namespace App\Modules\Event\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final readonly class Metadata implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): object
    {
        $data = json_decode($value ?? '{}', true) ?: [];

        return (object) [
            'stacktrace' => $data['stacktrace'] ?? null,
            'context' => $data['context'] ?? null,
            'custom_data' => $data['custom_data'] ?? null,
            'fingerprint' => $data['fingerprint'] ?? null,
            'file' => $data['file'] ?? null,
            'line' => $data['line'] ?? null,
        ];
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        if (is_object($value)) {
            return json_encode((array) $value);
        }

        if (is_string($value)) {
            return $value;
        }

        throw new InvalidArgumentException('Invalid metadata value type');
    }
}
