<?php declare(strict_types=1);

namespace App\Commands\Models\Favorite;

interface CheckUserExists
{
    public function handle(string $userId): bool;
}
