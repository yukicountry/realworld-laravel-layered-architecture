<?php

namespace App\Commands\Models\Favorite;

interface CheckUserExists
{
    function handle(string $userId): bool;
}
