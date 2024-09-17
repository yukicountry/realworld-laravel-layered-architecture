<?php declare(strict_types=1);

namespace App\Commands\Models\Article;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;

final class Article
{
    /**
     * @param array<string> $tagList
     */
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

    /**
     * @param array<string> $tagList
     */
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

    /**
     * @param array<string> $tagList
     */
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

    /**
     * @param array{'title'?: string, 'description'?: string, 'body'?: string, 'tagList'?: array<string>} $newAttributes
     */
    public function update(array $newAttributes): void
    {
        if (array_key_exists('title', $newAttributes) && $newAttributes['title'] !== $this->title) {
            $this->title = $newAttributes['title'];
            $this->updatedAt = CarbonImmutable::now();
        }
        if (array_key_exists('description', $newAttributes) && $newAttributes['description'] !== $this->description) {
            $this->description = $newAttributes['description'];
            $this->updatedAt = CarbonImmutable::now();
        }
        if (array_key_exists('body', $newAttributes) && $newAttributes['body'] !== $this->body) {
            $this->body = htmlspecialchars($newAttributes['body']);
            $this->updatedAt = CarbonImmutable::now();
        }
        if (array_key_exists('tagList', $newAttributes) && $newAttributes['tagList'] !== $this->tagList) {
            $this->tagList = $newAttributes['tagList'];
            $this->updatedAt = CarbonImmutable::now();
        }
    }
}
