<?php

namespace App\Implementations\Commands\Models\Favorite;

use App\Commands\Models\Favorite\CheckUserExists;
use Illuminate\Support\Facades\DB;

final class CheckUserExistsImpl implements CheckUserExists
{
    public function handle(string $userId): bool
    {
        return DB::table('users')->where('id', $userId)->exists();
    }
}
