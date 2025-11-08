<?php

namespace App\Modules\Audits\Observers;

use App\Modules\Audits\Services\AuditLogger;
use App\Modules\Posts\Models\Post;
use Illuminate\Support\Arr;

class PostObserver
{
    use AuditValueNormalizer;

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
}
