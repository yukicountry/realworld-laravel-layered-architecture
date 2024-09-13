<?php

declare(strict_types=1);

namespace App\Commands\Models\Follow;

interface FollowRepository
{
    function save(Follow $follow): void;

    function delete($followerId, $followeeId): void;
}
