<?php

namespace App\Implementations\Commands\Models\Comment;

use App\Commands\Models\Comment\Comment;
use App\Commands\Models\Comment\CommentRepository;
use Illuminate\Support\Facades\DB;

final class CommentRepositoryImpl implements CommentRepository
{
    public function saveComment(Comment $comment): void
    {
        $dto = $this->mapToDto($comment);

        DB::table('comments')->upsert($dto, 'id');
    }

    public function deleteComments(array $ids): void
    {
        //
    }

    private function mapToDto(Comment $model): array
    {
        return [
            'id' => $model->id,
            'body' => $model->body,
            'created_at' => $model->createdAt,
            'updated_at' => $model->updatedAt,
            'slug' => $model->slug,
            'author_id' => $model->authorId,
        ];
    }
}
