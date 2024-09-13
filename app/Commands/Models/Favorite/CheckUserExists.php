<?php

declare(strict_types=1);

namespace App\Commands\Models\Favorite;

interface CheckUserExists
{
    function handle(string $userId): bool;
}
