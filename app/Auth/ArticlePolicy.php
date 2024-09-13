<?php

declare(strict_types=1);

namespace App\Auth;

use Illuminate\Support\Facades\DB;

final class ArticlePolicy
{
    private function fetchArticle(string $slug): ?object
    {
        return DB::table('articles')->select(['slug', 'author_id'])->where('slug', $slug)->first();
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function canUpdateArticle(string $userId, string $slug): bool
    {
        $article = $this->fetchArticle($slug);

        if (is_null($article)) {
            throw new ResourceNotFoundException("Article {$slug} could not be found.");
        }

        return $article->author_id === $userId;
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function canDeleteArticle(string $userId, string $slug): bool
    {
        $article = $this->fetchArticle($slug);

        if (is_null($article)) {
            throw new ResourceNotFoundException("Article {$slug} could not be found.");
        }

        return $article->author_id === $userId;
    }
}
