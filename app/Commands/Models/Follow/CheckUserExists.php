<?php

declare(strict_types=1);

namespace App\Commands\Models\Follow;

interface CheckUserExists
{
    function checkById(string $userId): bool;

    function getUserIdByUsername(string $username): ?string;
}
