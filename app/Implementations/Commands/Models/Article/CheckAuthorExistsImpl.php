<?php

declare(strict_types=1);

namespace App\Implementations\Commands\Models\Article;

use App\Commands\Models\Article\CheckAuthorExists;
use Illuminate\Support\Facades\DB;

final class CheckAuthorExistsImpl implements CheckAuthorExists
{
    public function handle(string $authorId): bool
    {
        return DB::table('users')->where('id', $authorId)->exists();
    }
}
