<?php

namespace App\Commands\Models\Comment;

interface CheckAuthorExists
{
    function handle(string $authorId): bool;
}
