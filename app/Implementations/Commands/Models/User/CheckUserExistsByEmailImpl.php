<?php

namespace App\Implementations\Commands\Models\User;

use App\Commands\Models\User\CheckUserExistsByEmail;
use Illuminate\Support\Facades\DB;

final class CheckUserExistsByEmailImpl implements CheckUserExistsByEmail
{
    public function handle(string $email): bool
    {
        return DB::table('users')->where('email', $email)->exists();
    }
}
