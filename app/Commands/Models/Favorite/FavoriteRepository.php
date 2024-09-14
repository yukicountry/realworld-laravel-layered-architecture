<?php declare(strict_types=1);

namespace App\Commands\Models\Favorite;

interface FavoriteRepository
{
    public function save(Favorite $favorite): void;

    public function delete(string $slug, string $userId): void;

    public function deleteFavoritesOfArticle(string $slug): void;
}
