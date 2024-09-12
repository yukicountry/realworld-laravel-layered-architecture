<?php

namespace App\Http\Controllers;

use App\Commands\Models\Article\ArticleNotFoundException;
use App\Commands\Services\Article\DeleteArticleService;
use App\Commands\Services\Article\PostArticleService;
use App\Commands\Services\Article\UpdateArticleService;
use App\Http\Requests\FeedArticlesRequest;
use App\Http\Requests\ListArticlesRequest;
use App\Http\Requests\PostArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Queries\Services\ArticleQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ArticleController extends Controller
{
    public function listArticles(ArticleQueryService $queryService, ListArticlesRequest $request): JsonResponse
    {
        $articles = $queryService->searchArticles(
            null, // TODO: authentication
            tag: $request->query('tag'),
            authorUsername: $request->query('author'),
            favoritedUsername: $request->query('favorited'),
            limit: is_null($request->has('limit')) ? intval($request->query('limit')) : 20,
            offset: is_null($request->has('offset')) ? intval($request->query('offset')) : 0,
        );

        return new JsonResponse([
            'articles' => $articles,
        ]);
    }

    public function feedArticles(ArticleQueryService $queryService, FeedArticlesRequest $request): JsonResponse
    {
        $articles = $queryService->feedArticles(
            $request->user(),
            limit: is_null($request->has('limit')) ? intval($request->query('limit')) : 20,
            offset: is_null($request->has('offset')) ? intval($request->query('offset')) : 0,
        );

        return new JsonResponse([
            'articles' => $articles,
        ]);
    }

    public function getSingleArticle(
        Request $request,
        string $slug,
        ArticleQueryService $queryService,
    ): JsonResponse {
        $readModel = $queryService->getSingleArticle($slug, $request->user());

        if (is_null($readModel)) {
            throw new NotFoundHttpException("article not found");
        }
        return new JsonResponse([
            'article' => $readModel,
        ]);
    }

    public function postArticle(
        PostArticleService $service,
        ArticleQueryService $queryService,
        PostArticleRequest $request,
    ): JsonResponse {
        $input = $request->validated('article');
        $writeModel = $service->handle($request->user(), $input);
        $readModel = $queryService->getSingleArticle($writeModel->slug, $request->user());

        if (is_null($readModel)) {
            throw new RuntimeException(sprintf('$readModel is unexpectedly set to null'));
        }

        return new JsonResponse([
            'article' => $readModel,
        ]);
    }

    public function updateArticle(
        UpdateArticleService $service,
        ArticleQueryService $queryService,
        UpdateArticleRequest $request,
        string $slug,
    ): JsonResponse {
        $input = $request->validated('article');

        try {
            $service->handle($slug, $input);
        } catch (ArticleNotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        }

        $readModel = $queryService->getSingleArticle($slug, $request->user());
        if (is_null($readModel)) {
            throw new RuntimeException(sprintf('$readModel is unexpectedly set to null'));
        }

        return new JsonResponse([
            'article' => $readModel,
        ]);
    }

    public function deleteArticle(DeleteArticleService $service, string $slug): JsonResponse
    {
        $service->handle($slug);
        return new JsonResponse([]);
    }

    public function getTags(ArticleQueryService $queryService): JsonResponse
    {
        return new JsonResponse(['tags' => $queryService->getAllTags()]);
    }
}
