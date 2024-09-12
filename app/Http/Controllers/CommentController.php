<?php

namespace App\Http\Controllers;

use App\Commands\Services\Comment\DeleteCommentService;
use App\Commands\Services\Comment\PostCommentService;
use App\Http\Requests\PostCommentRequest;
use App\Queries\Services\CommentQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

final class CommentController extends Controller
{
    public function getComments(CommentQueryService $queryService, Request $request, string $slug): JsonResponse
    {
        $comments = $queryService->getCommentsOfArticle($slug, $request->user());
        return new JsonResponse([
            'comments' => $comments,
        ]);
    }

    public function postComment(
        PostCommentService $service,
        CommentQueryService $queryService,
        PostCommentRequest $request,
        string $slug,
    ): JsonResponse {
        $input = $request->validated('comment');
        $currentUserId = $request->user();
        $writeModel = $service->handle($slug, $input['body'], $currentUserId);
        $readModel = $queryService->getSingleComment($writeModel->id, $currentUserId);

        if (is_null($readModel)) {
            throw new RuntimeException(sprintf('$readModel is unexpectedly set to null'));
        }

        return new JsonResponse([
            'comment' => $readModel,
        ]);
    }

    public function deleteComment(DeleteCommentService $service, string $slug, string $id): JsonResponse
    {
        $service->handle($id);
        return new JsonResponse([]);
    }
}
