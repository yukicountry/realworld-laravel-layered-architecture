<?php declare(strict_types=1);

namespace App\Commands\Services\Article;

use App\Commands\Models\Article\Article;
use App\Commands\Models\Article\ArticleNotFoundException;
use App\Commands\Models\Article\ArticleRepository;

final class UpdateArticleService
{
    public function __construct(private readonly ArticleRepository $articleRepository) {}

    /**
     * @param array{'title'?: string, 'description'?: string, 'body'?: string, 'tagList'?: array<string>} $newAttributes
     */
    public function handle(string $slug, array $newAttributes): Article
    {
        $article = $this->articleRepository->findArticleBySlug($slug);

        if (is_null($article)) {
            throw new ArticleNotFoundException("article (slug: {$slug}) does not exist");
        }

        $article->update($newAttributes);

        $this->articleRepository->saveArticle($article);

        return $article;
    }
}
