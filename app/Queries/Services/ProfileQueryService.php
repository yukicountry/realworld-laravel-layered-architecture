<?php

namespace App\Queries\Services;

use App\Queries\Models\Profile;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

final class ProfileQueryService
{
    public function getProfiles(array $userIds, string $currentUserId): array
    {
        $dtos = DB::table('users')
            ->select([
                'users.*',
                DB::raw('
                    CASE
                        WHEN follows.followee_id IS NULL THEN FALSE
                        ELSE TRUE
                    END AS following
                '),
            ])
            ->leftJoin('follows', function (JoinClause $join) use ($currentUserId) {
                $join
                    ->on('users.id', '=', 'followee_id')
                    ->where('follows.follower_id', $currentUserId);
            })
            ->whereIn('users.id', $userIds)
            ->get()
            ->toArray();

        $mapToModel = fn(object $dto) => new Profile($dto->username, $dto->bio, $dto->image, $dto->following);

        return array_map($mapToModel, $dtos);
    }
}
