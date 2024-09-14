<?php declare(strict_types=1);

namespace App\Commands\Services\Article;

use App\Commands\Models\Article\ArticleRepository;
use App\Commands\Models\Comment\CommentRepository;
use App\Commands\Models\Favorite\FavoriteRepository;

final class DeleteArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly CommentRepository $commentRepository,
        private readonly FavoriteRepository $favoriteRepository,
    ) {}

    public function handle(string $slug): void
    {
        $this->favoriteRepository->deleteFavoritesOfArticle($slug);
        $this->commentRepository->deleteCommentsOfArticle($slug);
        $this->articleRepository->delete($slug);
    }
}
