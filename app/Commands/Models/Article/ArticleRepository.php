<?php declare(strict_types=1);

namespace App\Commands\Models\Article;

interface ArticleRepository
{
    public function saveArticle(Article $article): void;

    public function findArticleBySlug(string $slug): ?Article;

    public function delete(string $slug): void;
}
