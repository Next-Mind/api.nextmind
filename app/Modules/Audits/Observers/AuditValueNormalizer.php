<?php

namespace App\Modules\Audits\Observers;

use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

trait AuditValueNormalizer
{
    protected function extractValues(array $attributes): ?array
    {
        $values = Arr::only($attributes, $this->auditableAttributes());

        return $this->normalizeValues($values);
    }

    protected function normalizeValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        $normalized = [];

        foreach ($values as $key => $value) {
            $normalizedValue = $this->normalizeValue($key, $value);

            if ($normalizedValue !== null || array_key_exists($key, $values)) {
                $normalized[$key] = $normalizedValue;
            }
        }

        return empty($normalized) ? null : $normalized;
    }

    protected function normalizeValue(string $key, mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format(DateTimeInterface::ATOM);
        }

        if ($value === null || $value === '') {
            return $value;
        }

        if (is_string($value) && in_array($key, $this->dateAttributes(), true)) {
            return Carbon::parse($value)->format(DateTimeInterface::ATOM);
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return $value;
    }

    /**
     * @return array<int, string>
     */
    private function auditableAttributes(): array
    {
        $constant = static::class . '::AUDITABLE_ATTRIBUTES';

        if (! defined($constant)) {
            return [];
        }

        $attributes = constant($constant);

        return is_array($attributes) ? $attributes : [];
    }

    /**
     * @return array<int, string>
     */
    private function dateAttributes(): array
    {
        $constant = static::class . '::DATE_ATTRIBUTES';

        if (! defined($constant)) {
            return [];
        }

        $attributes = constant($constant);

        return is_array($attributes) ? $attributes : [];
    }
}
