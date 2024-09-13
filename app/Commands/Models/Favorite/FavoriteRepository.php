<?php

declare(strict_types=1);

namespace App\Commands\Models\Favorite;

interface FavoriteRepository
{
    function save(Favorite $favorite): void;

    function delete(string $slug, string $userId): void;

    function deleteFavoritesOfArticle(string $slug): void;
}
