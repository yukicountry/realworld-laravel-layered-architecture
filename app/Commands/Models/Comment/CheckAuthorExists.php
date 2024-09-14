<?php declare(strict_types=1);

namespace App\Commands\Models\Comment;

interface CheckAuthorExists
{
    public function handle(string $authorId): bool;
}
