<?php declare(strict_types=1);

namespace App\Commands\Models\User;

interface CheckUserExistsByUsername
{
    public function handle(string $username): bool;
}
