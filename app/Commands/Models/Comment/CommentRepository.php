<?php declare(strict_types=1);

namespace App\Commands\Models\Comment;

interface CommentRepository
{
    public function saveComment(Comment $comment): void;

    public function deleteComments(array $ids): void;

    public function deleteCommentsOfArticle(string $slug): void;
}
