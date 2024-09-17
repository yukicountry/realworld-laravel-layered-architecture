<?php declare(strict_types=1);

namespace App\Queries\Services;

use App\Queries\Models\Profile;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

final class ProfileQueryService
{
    /**
     * @param array<string> $userIds
     * @return array<Profile>
     */
    public function getProfiles(array $userIds, ?string $currentUserId): array
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

    public function getProfileByUsername(string $username, ?string $currentUserId): ?Profile
    {
        $dto = DB::table('users')
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
            ->where('users.username', $username)
            ->first();

        if (is_null($dto)) {
            return null;
        }

        return new Profile($dto->username, $dto->bio, $dto->image, $dto->following);
    }
}
