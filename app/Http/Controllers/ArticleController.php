<?php

namespace App\Http\Controllers;

use App\Commands\Services\Article\PostArticleService;
use App\Http\Requests\PostArticleRequest;
use App\Queries\Services\ArticleQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ArticleController extends Controller
{
    public function getArticles(): JsonResponse
    {
        return new JsonResponse();
    }

    public function feedArticles(): JsonResponse
    {
        return new JsonResponse();
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

    public function updateArticle(): JsonResponse
    {
        return new JsonResponse();
    }

    public function deleteArticle(): JsonResponse
    {
        return new JsonResponse();
    }

    public function getTags(): JsonResponse
    {
        return new JsonResponse();
    }
}
