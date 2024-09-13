<?php

declare(strict_types=1);

namespace App\Implementations\Commands\Models\Follow;

use App\Commands\Models\Follow\CheckUserExists;
use Illuminate\Support\Facades\DB;

final class CheckUserExistsImpl implements CheckUserExists
{
    public function checkById(string $userId): bool
    {
        return DB::table('users')->where('id', $userId)->exists();
    }

    public function getUserIdByUsername(string $username): ?string
    {
        $dto = DB::table('users')->where('username', $username)->first(['id']);

        return $dto?->id;
    }
}
