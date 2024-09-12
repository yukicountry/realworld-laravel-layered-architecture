<?php

namespace App\Implementations\Commands\Models\Article;

use App\Commands\Models\Article\Article;
use App\Commands\Models\Article\ArticleRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class ArticleRepositoryImpl implements ArticleRepository
{
    public function saveArticle(Article $article): void
    {
        [$articleDto, $tagDtos] = $this->mapToDto($article);

        DB::transaction(function () use ($articleDto, $tagDtos) {
            DB::table('tags')->where('slug', $articleDto['slug'])->delete();
            DB::table('articles')->upsert($articleDto, 'slug');
            DB::table('tags')->insert($tagDtos);
        });
    }

    public function findArticleBySlug(string $slug): ?Article
    {
        $articleDto = DB::table('articles')->where('slug', $slug)->first();

        if (is_null($articleDto)) {
            return null;
        }

        $tagDtos = DB::table('tags')->where('slug')->orderBy('sort')->get()->toArray();

        return $this->mapToModel($articleDto, $tagDtos);
    }

    private function mapToDto(Article $article): array
    {
        $articleDto = [
            'slug'        => $article->slug,
            'title'       => $article->title,
            'description' => $article->description,
            'body'        => $article->body,
            'author_id'   => $article->authorId,
            'created_at'  => $article->createdAt,
            'updated_at'  => $article->updatedAt,
        ];
        $tagDtos = array_map(
            function ($key, $tag) use ($article) {
                return [
                    'slug' => $article->slug,
                    'name' => $tag,
                    'sort' => $key,
                ];
            },
            array_keys($article->tagList),
            array_values($article->tagList)
        );

        return [$articleDto, $tagDtos];
    }

    private function mapToModel(object $articleDto, array $tagDtos): Article
    {
        $tags = array_map(fn($dto) => $dto->name, $tagDtos);

        return Article::reconstruct(
            $articleDto->slug,
            $articleDto->title,
            $articleDto->description,
            $articleDto->body,
            $tags,
            CarbonImmutable::parse($articleDto->createdAt),
            CarbonImmutable::parse($articleDto->updatedAt),
            $articleDto->author_id,
        );
    }
}
