<?php

namespace App\Commands\Models\Favorite;

interface CheckArticleExists
{
    function handle(string $slug): bool;
}
