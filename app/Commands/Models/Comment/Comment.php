<?php

namespace App\Commands\Models\Comment;

use App\Commands\Models\Article\Article;
use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;

final class Comment
{
    private function __construct(
        public readonly string $id,
        public string $body,
        public readonly CarbonImmutable $createdAt,
        public CarbonImmutable $updatedAt,
        public readonly string $slug,
        public readonly string $authorId,
    ) {}

    public static function createNewComment(
        CheckAuthorExists $checkAuthorExists,
        string $body,
        string $authorId,
        Article $article,
    ): self {
        if (!$checkAuthorExists->handle($authorId)) {
            throw new AuthorNotFoundException("Author {$authorId} could not be found.");
        }

        return new self(
            Uuid::uuid7()->toString(),
            $body,
            CarbonImmutable::now(),
            CarbonImmutable::now(),
            $article->slug,
            $authorId,
        );
    }

    public static function reconstruct(
        string $id,
        string $body,
        CarbonImmutable $createdAt,
        CarbonImmutable $updatedAt,
        string $slug,
        string $authorId,
    ): Comment {
        return new Comment($id, $body, $createdAt, $updatedAt, $slug, $authorId);
    }
}
