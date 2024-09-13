<?php

namespace App\Commands\Services\Favorite;

use App\Commands\Models\Favorite\ArticleNotFoundException;
use App\Commands\Models\Favorite\CheckArticleExists;
use App\Commands\Models\Favorite\CheckUserExists;
use App\Commands\Models\Favorite\FavoriteRepository;
use App\Commands\Models\Favorite\UserNotFoundException;

final class UnfavoriteService
{
    public function __construct(
        private readonly CheckUserExists $checkUserExists,
        private readonly CheckArticleExists $checkArticleExists,
        private readonly FavoriteRepository $favoriteRepository
    ) {}

    /**
     * @throws UserNotFoundException
     * @throws ArticleNotFoundException
     */
    public function handle(string $slug, string $userId): void
    {
        if (!$this->checkUserExists->handle($userId)) {
            throw new UserNotFoundException("User of id {$userId} could not be found.");
        }
        if (!$this->checkArticleExists->handle($slug)) {
            throw new ArticleNotFoundException("Article {$slug} could not be found.");
        }

        $this->favoriteRepository->delete($slug, $userId);
    }
}
