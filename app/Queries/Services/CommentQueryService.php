<?php declare(strict_types=1);

namespace App\Queries\Services;

use App\Queries\Models\Comment;
use App\Queries\Models\Profile;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class CommentQueryService
{
    public function __construct(private readonly ProfileQueryService $profileQueryService) {}

    public function getSingleComment(string $id, ?string $currentUserId): ?Comment
    {
        $commentDto = DB::table('comments')->where('id', $id)->first();

        if (is_null($commentDto)) {
            return null;
        }

        $profiles = $this->profileQueryService->getProfiles([$commentDto->author_id], $currentUserId);

        if (count($profiles) !== 1) {
            throw new RuntimeException(
                sprintf(
                    'profiles count is different from expected value (expected: 1, actual: %d)',
                    count($profiles),
                ),
            );
        }

        return new Comment(
            $commentDto->id,
            $commentDto->body,
            CarbonImmutable::parse($commentDto->created_at),
            CarbonImmutable::parse($commentDto->updated_at),
            $profiles[0],
        );
    }

    public function getCommentsOfArticle(string $slug, ?string $currentUserId): array
    {
        $commentDtos = DB::table('comments')
            ->select(['comments.*', DB::raw('users.username AS author_username')])
            ->join('users', 'comments.author_id', '=', 'users.id')
            ->where('comments.slug', $slug)
            ->orderBy('comments.created_at')
            ->get()
            ->toArray();

        $authorIds = array_map(fn($dto) => $dto->author_id, $commentDtos);
        $profiles = $this->profileQueryService->getProfiles(array_unique($authorIds), $currentUserId);

        return array_map(function ($commentDto) use ($profiles) {
            $profileOfThisArticle = array_filter(
                $profiles,
                fn(Profile $profile) => $profile->username === $commentDto->author_username
            );

            return new Comment(
                $commentDto->id,
                $commentDto->body,
                CarbonImmutable::parse($commentDto->created_at),
                CarbonImmutable::parse($commentDto->updated_at),
                array_values($profileOfThisArticle)[0],
            );
        }, $commentDtos);
    }
}
