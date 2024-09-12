<?php

namespace App\Queries\Services;

use App\Queries\Models\Comment;
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
}
