<?php

declare(strict_types=1);

namespace App\Http\Auth;

use App\Auth\ArticlePolicy;
use App\Auth\ResourceNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

function canUpdateArticle(Request $request): void
{
    $userId = $request->user();
    $slug   = $request->route('slug');
    $policy = new ArticlePolicy();

    try {
        if (is_null($userId) || !$policy->canUpdateArticle($userId, $slug)) {
            throw new AccessDeniedHttpException('Operation not permitted.');
        }
    } catch (ResourceNotFoundException $ex) {
        // pass
    }
}

function canDeleteArticle(Request $request): void
{
    $userId = $request->user();
    $slug   = $request->route('slug');
    $policy = new ArticlePolicy();

    try {
        if (is_null($userId) || !$policy->canDeleteArticle($userId, $slug)) {
            throw new AccessDeniedHttpException('Operation not permitted.');
        }
    } catch (ResourceNotFoundException $ex) {
        // pass
    }
}
