<?php

namespace App\Modules\Audits\Observers;

use App\Modules\Audits\Services\AuditLogger;
use App\Modules\Posts\Models\Post;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class PostObserver
{
    private const AUDITABLE_ATTRIBUTES = [
        'id',
        'post_category_id',
        'author_id',
        'title',
        'subtitle',
        'image_url',
        'body',
        'language',
        'like_count',
        'reading_time',
        'visibility',
        'created_at',
        'updated_at',
    ];

    private const DATE_ATTRIBUTES = [
        'created_at',
        'updated_at',
    ];

    public function __construct(private readonly AuditLogger $logger)
    {
    }

    public function created(Post $post): void
    {
        $newValues = $this->extractValues($post->getAttributes());

        $this->logger->record($post, 'posts.created', null, $newValues);
    }

    public function updated(Post $post): void
    {
        $changes = Arr::only($post->getChanges(), self::AUDITABLE_ATTRIBUTES);
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach (array_keys($changes) as $attribute) {
            $oldValues[$attribute] = $post->getOriginal($attribute);
            $newValues[$attribute] = $post->getAttribute($attribute);
        }

        $this->logger->record(
            $post,
            'posts.updated',
            $this->normalizeValues($oldValues),
            $this->normalizeValues($newValues)
        );
    }

    public function deleted(Post $post): void
    {
        $oldValues = $this->extractValues($post->getOriginal());

        $this->logger->record($post, 'posts.deleted', $oldValues);
    }

    private function extractValues(array $attributes): ?array
    {
        $values = Arr::only($attributes, self::AUDITABLE_ATTRIBUTES);

        return $this->normalizeValues($values);
    }

    private function normalizeValues(?array $values): ?array
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

    private function normalizeValue(string $key, mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format(DateTimeInterface::ATOM);
        }

        if ($value === null || $value === '') {
            return $value;
        }

        if (is_string($value) && in_array($key, self::DATE_ATTRIBUTES, true)) {
            return Carbon::parse($value)->format(DateTimeInterface::ATOM);
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return $value;
    }
}
