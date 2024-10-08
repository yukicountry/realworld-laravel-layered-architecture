<?php declare(strict_types=1);

namespace App\Queries\Models;

use Carbon\CarbonImmutable;

final class ArticleForList
{
    /**
     * @param array<string> $tagList
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly string $description,
        public readonly array $tagList,
        public readonly CarbonImmutable $createdAt,
        public readonly CarbonImmutable $updatedAt,
        public readonly bool $favorited,
        public readonly int $favoritesCount,
        public readonly Profile $author,
    ) {}
}
