<?php declare(strict_types=1);

namespace App\Commands\Services\Comment;

use App\Commands\Models\Comment\CommentRepository;

final class DeleteCommentService
{
    public function __construct(private readonly CommentRepository $commentRepository) {}

    public function handle(string $id): void
    {
        $this->commentRepository->deleteComments([$id]);
    }
}
