<?php declare(strict_types=1);

namespace App\Commands\Services\Article;

use App\Commands\Models\Article\Article;
use App\Commands\Models\Article\ArticleRepository;
use App\Commands\Models\Article\AuthorNotFoundException;
use App\Commands\Models\Article\CheckAuthorExists;

final class PostArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly CheckAuthorExists $checkAuthorExists,
    ) {}

    /**
     * Create and save new article.
     *
     * @param array{'title': string, 'description': string, 'body': string, 'tagList': array<string>} $input
     * @throws AuthorNotFoundException
     */
    public function handle(string $authorId, array $input): Article
    {
        $article = Article::createNewArticle(
            $this->checkAuthorExists,
            $input['title'],
            $input['description'],
            $input['body'],
            $input['tagList'],
            $authorId,
        );

        $this->articleRepository->saveArticle($article);

        return $article;
    }
}
