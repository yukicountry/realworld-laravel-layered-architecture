<?php

namespace App\Commands\Services\Article;

use App\Commands\Models\Article\ArticleRepository;

final class DeleteArticleService
{
    public function __construct(private readonly ArticleRepository $articleRepository) {}

    public function handle(string $slug): void
    {
        $this->articleRepository->delete($slug);
    }
}
