<?php

namespace App\Http\Controllers;

use App\Commands\Models\Favorite\ArticleNotFoundException;
use App\Commands\Services\Favorite\MakeFavoriteService;
use App\Queries\Services\ArticleQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class FavoriteController extends Controller
{
    public function makeFavorite(
        MakeFavoriteService $service,
        ArticleQueryService $queryService,
        Request $request,
        string $slug,
    ): JsonResponse {
        $currentUserId = $request->user();

        try {
            $service->handle($slug, $currentUserId);
        } catch (ArticleNotFoundException $ex) {
            throw new NotFoundHttpException("Article {$slug} could not be found.");
        }

        $article = $queryService->getSingleArticle($slug, $currentUserId);
        if (is_null($article)) {
            throw new RuntimeException(sprintf('$readModel is unexpectedly set to null'));
        }

        return new JsonResponse(['article' => $article]);
    }

    public function unfavorite(): JsonResponse
    {
        return new JsonResponse();
    }
}
