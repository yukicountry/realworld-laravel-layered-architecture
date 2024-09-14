<?php declare(strict_types=1);

namespace App\Commands\Services\Comment;

use App\Commands\Models\Article\ArticleNotFoundException;
use App\Commands\Models\Article\ArticleRepository;
use App\Commands\Models\Comment\CheckAuthorExists;
use App\Commands\Models\Comment\Comment;
use App\Commands\Models\Comment\CommentRepository;

final class PostCommentService
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly ArticleRepository $articleRepository,
        private readonly CheckAuthorExists $checkAuthorExists,
    ) {}

    public function handle(string $slug, string $body, string $authorId): Comment
    {
        $article = $this->articleRepository->findArticleBySlug($slug);

        if (is_null($article)) {
            throw new ArticleNotFoundException("Article {$slug} could not be found.");
        }

        $comment = Comment::createNewComment($this->checkAuthorExists, $body, $authorId, $article);

        $this->commentRepository->saveComment($comment);

        return $comment;
    }
}
