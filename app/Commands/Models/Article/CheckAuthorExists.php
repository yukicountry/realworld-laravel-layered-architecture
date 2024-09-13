<?php

declare(strict_types=1);

namespace App\Commands\Models\Article;

interface CheckAuthorExists
{
    function handle(string $authorId): bool;
}
