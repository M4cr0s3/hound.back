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
            'code_snippet' => $data['code_snippet'] ?? null,
            'file' => $data['file'] ?? null,
            'line' => $data['line'] ?? null,
        ];
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_object($value)) {
            return json_encode((array) $value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            return json_encode(['raw' => $value], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        throw new InvalidArgumentException('Invalid metadata value type');
    }
}
