<?php declare(strict_types=1);

namespace App\Http\Auth;

use App\Auth\CommentPolicy;
use App\Auth\ResourceNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

function canDeleteComment(Request $request): void
{
    $userId    = $request->user();
    $commentId = $request->route('commentId');
    $policy    = new CommentPolicy();

    try {
        if (is_null($userId) || !$policy->canDeleteComment($userId, $commentId)) {
            throw new AccessDeniedHttpException('Operation not permitted.');
        }
    } catch (ResourceNotFoundException $ex) {
        // pass
    }
}
