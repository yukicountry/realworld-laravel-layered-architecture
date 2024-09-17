<?php declare(strict_types=1);

namespace App\Queries\Services;

use App\Queries\Models\ArticleForList;
use App\Queries\Models\Profile;
use App\Queries\Models\SingleArticle;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ArticleQueryService
{
    public function __construct(private readonly ProfileQueryService $profileQueryService) {}

    public function getSingleArticle(string $slug, ?string $currentUserId): ?SingleArticle
    {
        $articleDto = DB::table('vw_articles')
            ->select([
                'vw_articles.*',
                DB::raw('
                    CASE
                        WHEN favorites.user_id IS NULL THEN FALSE
                        ELSE TRUE
                    END AS favorited
                '),
            ])
            ->leftJoin('favorites', function (JoinClause $join) use ($currentUserId) {
                $join
                    ->on('vw_articles.slug', '=', 'favorites.slug')
                    ->where('favorites.user_id', $currentUserId);
            })
            ->where('vw_articles.slug', $slug)
            ->first();

        if (is_null($articleDto)) {
            return null;
        }

        $tagDtos = DB::table('tags')->where('slug', $slug)->orderBy('sort')->get()->toArray();

        $profiles = $this->profileQueryService->getProfiles([$articleDto->author_id], $currentUserId);

        if (count($profiles) !== 1) {
            throw new RuntimeException(
                sprintf(
                    'profiles count is different from expected value (expected: 1, actual: %d)',
                    count($profiles),
                ),
            );
        }

        return new SingleArticle(
            $articleDto->slug,
            $articleDto->title,
            $articleDto->description,
            $articleDto->body,
            array_map(fn($dto) => $dto->name, $tagDtos),
            CarbonImmutable::parse($articleDto->created_at),
            CarbonImmutable::parse($articleDto->updated_at),
            $articleDto->favorited,
            $articleDto->favorites_count,
            $profiles[0],
        );
    }

    public function getArticleListBySlugs(
        array $slugs,
        ?string $currentUserId,
        int $limit = 20,
        int $offset = 0,
    ): array {
        $articleDtos = DB::table('vw_articles')
            ->select([
                'vw_articles.*',
                DB::raw('
                    CASE
                        WHEN favorites.user_id IS NULL THEN FALSE
                        ELSE TRUE
                    END AS favorited
                '),
                DB::raw('users.username AS author_username'),
            ])
            ->join('users', 'users.id', '=', 'vw_articles.author_id')
            ->leftJoin('favorites', function (JoinClause $join) use ($currentUserId) {
                $join
                    ->on('vw_articles.slug', '=', 'favorites.slug')
                    ->where('favorites.user_id', $currentUserId);
            })
            ->whereIn('vw_articles.slug', $slugs)
            ->orderByDesc('vw_articles.created_at')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->toArray();

        $tagDtos = DB::table('tags')->whereIn('slug', $slugs)->get()->toArray();

        $authorIds = array_map(fn($dto) => $dto->author_id, $articleDtos);
        $profiles = $this->profileQueryService->getProfiles(array_unique($authorIds), $currentUserId);

        return array_map(function ($articleDto) use ($profiles, $tagDtos) {
            $profileOfThisArticle = array_filter(
                $profiles,
                fn(Profile $profile) => $profile->username === $articleDto->author_username
            );

            $tagDtosOfThisArticle = array_filter($tagDtos, fn(object $tagDto) => $tagDto->slug === $articleDto->slug);
            usort($tagDtosOfThisArticle, fn(object $lhs, object $rhs) => $lhs->sort <=> $rhs->sort);
            $tags = array_map(fn($tagDto) => $tagDto->name, $tagDtosOfThisArticle);

            return new ArticleForList(
                $articleDto->slug,
                $articleDto->title,
                $articleDto->description,
                $tags,
                CarbonImmutable::parse($articleDto->created_at),
                CarbonImmutable::parse($articleDto->updated_at),
                $articleDto->favorited,
                $articleDto->favorites_count,
                array_values($profileOfThisArticle)[0],
            );
        }, $articleDtos);
    }

    public function searchArticles(
        ?string $currentUserId,
        ?string $tag = null,
        ?string $authorUsername = null,
        ?string $favoritedUsername = null,
        int $limit = 20,
        int $offset = 0,
    ): array {
        $slugs = DB::table('vw_articles')
            ->select(['vw_articles.slug'])
            ->distinct()
            ->when(isset($authorUsername), function (Builder $query) use ($authorUsername) {
                $query->where('author_username', $authorUsername);
            })
            ->when(isset($favoritedUsername), function (Builder $query) use ($favoritedUsername) {
                $query->join('vw_favorites', function (JoinClause $join) use ($favoritedUsername) {
                    $join
                        ->on('vw_articles.slug', '=', 'vw_favorites.slug')
                        ->where('vw_favorites.username', '=', $favoritedUsername);
                });
            })
            ->when(isset($tag), function (Builder $query) use ($tag) {
                $query->join('tags', function (JoinClause $join) use ($tag) {
                    $join
                        ->on('vw_articles.slug', '=', 'tags.slug')
                        ->where('tags.name', '=', $tag);
                });
            })
            ->orderBy('slug')
            ->pluck('slug')
            ->toArray();

        return [$this->getArticleListBySlugs($slugs, $currentUserId, $limit, $offset), count($slugs)];
    }

    public function feedArticles(
        string $currentUserId,
        int $limit = 20,
        int $offset = 0,
    ): array {
        $slugs = DB::table('vw_articles')
            ->select(['vw_articles.slug'])
            ->distinct()
            ->join('follows', function (JoinClause $join) use ($currentUserId) {
                $join
                    ->on('vw_articles.author_id', '=', 'follows.followee_id')
                    ->where('follows.follower_id', $currentUserId);
            })
            ->orderBy('slug')
            ->pluck('slug')
            ->toArray();

        return [$this->getArticleListBySlugs($slugs, $currentUserId, $limit, $offset), count($slugs)];
    }

    public function getAllTags(): array
    {
        return DB::table('tags')
            ->select(['name'])
            ->distinct()
            ->orderBy('name')
            ->pluck('name')
            ->toArray();
    }
}
