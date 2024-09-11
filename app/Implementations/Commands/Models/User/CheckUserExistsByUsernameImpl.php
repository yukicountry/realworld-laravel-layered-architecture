<?php

namespace App\Implementations\Commands\Models\User;

use App\Commands\Models\User\CheckUserExistsByUsername;
use Illuminate\Support\Facades\DB;

final class CheckUserExistsByUsernameImpl implements CheckUserExistsByUsername
{
    public function handle(string $username): bool
    {
        return DB::table('users')->where('username', $username)->exists();
    }
}
