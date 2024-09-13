<?php

declare(strict_types=1);

namespace App\Commands\Models\Follow;

final class Follow
{
    private function __construct(public readonly string $followerId, public readonly string $followeeId) {}

    public static function makeFollow(
        CheckUserExists $checkUserExists,
        string $followerId,
        string $followeeUsername,
    ): Follow {
        if (!$checkUserExists->checkById($followerId)) {
            throw new UserNotFoundException("User {$followerId} could not be found.");
        }

        $followeeId = $checkUserExists->getUserIdByUsername($followeeUsername);
        if (is_null($followeeId)) {
            throw new UserNotFoundException("User (username: {$followeeUsername}) could not be found.");
        }

        return new Follow($followerId, $followeeId);
    }
}
