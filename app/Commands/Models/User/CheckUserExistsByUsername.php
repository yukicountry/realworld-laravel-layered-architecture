<?php

namespace App\Commands\Models\User;

interface CheckUserExistsByUsername
{
    function handle(string $username): bool;
}
