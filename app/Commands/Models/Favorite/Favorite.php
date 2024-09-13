<?php

namespace App\Commands\Models\Favorite;

final class Favorite
{
    private function __construct(public readonly string $slug, public readonly string $userId) {}

    /**
     * @throws UserNotFoundException
     * @throws ArticleNotFoundException
     */
    public static function makeFavorite(
        CheckUserExists $checkUserExists,
        CheckArticleExists $checkArticleExists,
        string $slug,
        string $userId,
    ): Favorite {
        if (!$checkUserExists->handle($userId)) {
            throw new UserNotFoundException("User of id {$userId} could not be found.");
        }
        if (!$checkArticleExists->handle($slug)) {
            throw new ArticleNotFoundException("Article {$slug} could not be found.");
        }

        return new Favorite($slug, $userId);
    }
}
