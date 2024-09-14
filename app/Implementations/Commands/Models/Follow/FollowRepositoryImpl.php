<?php declare(strict_types=1);

namespace App\Implementations\Commands\Models\Follow;

use App\Commands\Models\Follow\Follow;
use App\Commands\Models\Follow\FollowRepository;
use Illuminate\Support\Facades\DB;

final class FollowRepositoryImpl implements FollowRepository
{
    public function save(Follow $follow): void
    {
        $dto = $this->mapToDto($follow);

        DB::table('follows')->upsert($dto, ['follower_id', 'followee_id']);
    }

    public function delete($followerId, $followeeId): void
    {
        DB::table('follows')->where('follower_id', $followerId)->where('followee_id', $followeeId)->delete();
    }

    private function mapToDto(Follow $model): array
    {
        return [
            'follower_id' => $model->followerId,
            'followee_id' => $model->followeeId,
        ];
    }
}
