<?php declare(strict_types=1);

namespace App\Auth;

use Illuminate\Support\Facades\DB;

final class CommentPolicy
{
    private function fetchComment(string $commentId): ?object
    {
        return DB::table('comments')->select(['id', 'author_id'])->where('id', $commentId)->first();
    }

    public function canDeleteComment(string $userId, string $commentId): bool
    {
        $comment = $this->fetchComment($commentId);

        if (is_null($comment)) {
            throw new ResourceNotFoundException("Comment {$commentId} could not be found.");
        }

        return $comment->author_id === $userId;
    }
}
