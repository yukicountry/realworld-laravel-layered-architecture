<?php

namespace App\Implementations\Commands\Models\Comment;

use App\Commands\Models\Comment\CheckAuthorExists;
use Illuminate\Support\Facades\DB;

final class CheckAuthorExistsImpl implements CheckAuthorExists
{
    public function handle(string $authorId): bool
    {
        return DB::table('users')->where('id', $authorId)->exists();
    }
}
