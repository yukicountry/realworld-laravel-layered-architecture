<?php

namespace App\Commands\Services\Article;

use App\Commands\Models\Article\Article;
use App\Commands\Models\Article\ArticleNotFoundException;
use App\Commands\Models\Article\ArticleRepository;

final class UpdateArticleService
{
    public function __construct(private readonly ArticleRepository $articleRepository) {}

    public function handle(string $slug, array $newAttributes): Article
    {
        $article = $this->articleRepository->findArticleBySlug($slug);

        if (is_null($article)) {
            throw new ArticleNotFoundException("article (slug: {$slug}) does not exist");
        }

        $article->update($newAttributes);

        $this->articleRepository->saveArticle($article);

        return $article;
    }
}
