<?php

namespace App\Commands\Models\Comment;

interface CommentRepository
{
    function saveComment(Comment $comment): void;

    function deleteComments(array $ids): void;
}
