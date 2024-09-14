<?php declare(strict_types=1);

namespace App\Commands\Models\Favorite;

interface CheckArticleExists
{
    public function handle(string $slug): bool;
}
