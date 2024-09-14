<?php declare(strict_types=1);

namespace App\Commands\Services\Follow;

use App\Commands\Models\Follow\CheckUserExists;
use App\Commands\Models\Follow\Follow;
use App\Commands\Models\Follow\FollowRepository;

final class MakeFollowService
{
    public function __construct(
        private readonly FollowRepository $followRepository,
        private readonly CheckUserExists $checkUserExists,
    ) {}

    public function handle(string $followerId, string $followeeId): Follow
    {
        $follow = Follow::makeFollow($this->checkUserExists, $followerId, $followeeId);

        $this->followRepository->save($follow);

        return $follow;
    }
}
