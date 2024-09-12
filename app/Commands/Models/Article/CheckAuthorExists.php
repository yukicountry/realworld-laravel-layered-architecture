<?php

namespace App\Commands\Models\Article;

interface CheckAuthorExists
{
    function handle(string $authorId): bool;
}
