<?php

declare(strict_types=1);

namespace App\Implementations\Commands\Models\Favorite;

use App\Commands\Models\Favorite\CheckArticleExists;
use Illuminate\Support\Facades\DB;

final class CheckArticleExistsImpl implements CheckArticleExists
{
    public function handle(string $slug): bool
    {
        return DB::table('articles')->where('slug', $slug)->exists();
    }
}
