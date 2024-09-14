<?php declare(strict_types=1);

namespace App\Commands\Models\Follow;

final class Follow
{
    private function __construct(public readonly string $followerId, public readonly string $followeeId) {}

    public static function makeFollow(
        CheckUserExists $checkUserExists,
        string $followerId,
        string $followeeId,
    ): Follow {
        if (!$checkUserExists->handle($followerId)) {
            throw new UserNotFoundException("User {$followerId} could not be found.");
        }

        if (!$checkUserExists->handle($followeeId)) {
            throw new UserNotFoundException("User {$followeeId} could not be found.");
        }

        return new Follow($followerId, $followeeId);
    }

    /**
     * @throws UserNotFoundException
     */
    public static function checkForDelete(
        CheckUserExists $checkUserExists,
        string $followerId,
        string $followeeId,
    ): void {
        if (!$checkUserExists->handle($followerId)) {
            throw new UserNotFoundException("User {$followerId} could not be found.");
        }

        if (!$checkUserExists->handle($followeeId)) {
            throw new UserNotFoundException("User {$followeeId} could not be found.");
        }
    }
}
