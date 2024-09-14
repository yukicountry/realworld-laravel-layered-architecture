<?php declare(strict_types=1);

namespace App\Commands\Models\Follow;

interface FollowRepository
{
    public function save(Follow $follow): void;

    public function delete($followerId, $followeeId): void;
}
