<?php

declare(strict_types=1);

namespace App\Commands\Services\Follow;

use App\Commands\Models\Follow\CheckUserExists;
use App\Commands\Models\Follow\Follow;
use App\Commands\Models\Follow\FollowRepository;
use App\Commands\Models\Follow\UserNotFoundException;

final class UnfollowService
{
    public function __construct(
        private readonly FollowRepository $followRepository,
        private readonly CheckUserExists $checkUserExists,
    ) {}

    /**
     * @throws UserNotFoundException
     */
    public function handle(string $followerId, string $followeeId): void
    {
        Follow::checkForDelete($this->checkUserExists, $followerId, $followeeId);

        $this->followRepository->delete($followerId, $followeeId);
    }
}
