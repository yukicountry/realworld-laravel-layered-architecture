<?php

namespace App\Commands\Models\Article;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;

final class Article
{
    private function __construct(
        public readonly string $slug,
        public string $title,
        public string $description,
        public string $body,
        public array $tagList,
        public CarbonImmutable $createdAt,
        public CarbonImmutable $updatedAt,
        public readonly string $authorId,
    ) {}

    public static function createNewArticle(
        CheckAuthorExists $checkAuthorExists,
        string $title,
        string $description,
        string $body,
        array $tagList,
        string $authorId,
    ): Article {
        if (!$checkAuthorExists->handle($authorId)) {
            throw new AuthorNotFoundException("author not found (id: {$authorId})");
        }

        return new self(
            Uuid::uuid7()->toString(),
            $title,
            $description,
            $body,
            $tagList,
            CarbonImmutable::now(),
            CarbonImmutable::now(),
            $authorId,
        );
    }

    public static function reconstruct(
        string $slug,
        string $title,
        string $description,
        string $body,
        array $tagList,
        CarbonImmutable $createdAt,
        CarbonImmutable $updatedAt,
        string $authorId,
    ): Article {
        return new self(
            $slug,
            $title,
            $description,
            $body,
            $tagList,
            $createdAt,
            $updatedAt,
            $authorId,
        );
    }
}
