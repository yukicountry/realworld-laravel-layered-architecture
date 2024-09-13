<?php

declare(strict_types=1);

namespace App\Commands\Models\Favorite;

interface CheckArticleExists
{
    function handle(string $slug): bool;
}
