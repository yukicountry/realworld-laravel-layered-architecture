<?php

declare(strict_types=1);

namespace App\Commands\Models\Follow;

interface CheckUserExists
{
    function handle(string $userId): bool;
}
