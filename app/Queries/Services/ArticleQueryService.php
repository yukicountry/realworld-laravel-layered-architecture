<?php

namespace App\Queries\Services;

use App\Queries\Models\SingleArticle;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ArticleQueryService
{
    public function __construct(private readonly ProfileQueryService $profileQueryService) {}

    public function getSingleArticle(string $slug, string $currentUserId): ?SingleArticle
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

        $profileDtos = $this->profileQueryService->getProfiles([$articleDto->author_id], $currentUserId);

        if (count($profileDtos) !== 1) {
            throw new RuntimeException(
                sprintf(
                    'profiles count is different from expected value (expected: 1, actual: %d)',
                    count($profileDtos),
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
            $profileDtos[0],
        );
    }
}
