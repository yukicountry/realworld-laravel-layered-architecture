<?php

declare(strict_types=1);

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
            htmlspecialchars($body),
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

    public function update(array $newAttributes): void
    {
        if (array_key_exists('title', $newAttributes)) {
            $this->title = $newAttributes['title'];
        }
        if (array_key_exists('description', $newAttributes)) {
            $this->description = $newAttributes['description'];
        }
        if (array_key_exists('body', $newAttributes)) {
            $this->body = htmlspecialchars($newAttributes['body']);
        }
        if (array_key_exists('tagList', $newAttributes)) {
            $this->tagList = $newAttributes['tagList'];
        }
        $this->updatedAt = CarbonImmutable::now();
    }
}
