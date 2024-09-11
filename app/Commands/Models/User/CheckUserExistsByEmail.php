<?php

namespace App\Commands\Models\User;

interface CheckUserExistsByEmail
{
    function handle(string $email): bool;
}
