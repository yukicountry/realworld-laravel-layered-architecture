<?php

namespace App\Commands\Models\Article;

interface ArticleRepository
{
    function saveArticle(Article $article): void;

    function findArticleBySlug(string $slug): ?Article;

    function delete(string $slug): void;
}
