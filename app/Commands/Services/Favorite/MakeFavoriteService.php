<?php

declare(strict_types=1);

namespace App\Commands\Services\Favorite;

use App\Commands\Models\Favorite\ArticleNotFoundException;
use App\Commands\Models\Favorite\CheckArticleExists;
use App\Commands\Models\Favorite\CheckUserExists;
use App\Commands\Models\Favorite\Favorite;
use App\Commands\Models\Favorite\FavoriteRepository;
use App\Commands\Models\Favorite\UserNotFoundException;

final class MakeFavoriteService
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
    public function handle(string $slug, string $userId): Favorite
    {
        $favorite = Favorite::makeFavorite($this->checkUserExists, $this->checkArticleExists, $slug, $userId);

        $this->favoriteRepository->save($favorite);

        return $favorite;
    }
}
