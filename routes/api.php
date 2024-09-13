<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthenticateAndGuard;
use Illuminate\Support\Facades\Route;

Route::prefix('articles')->group(function () {
    Route::get('', [ArticleController::class, 'listArticles'])->middleware(Authenticate::class);
    Route::post('', [ArticleController::class, 'postArticle'])->middleware(AuthenticateAndGuard::class);
    Route::get('feed', [ArticleController::class, 'feedArticles'])->middleware(AuthenticateAndGuard::class);
    Route::get('{slug}', [ArticleController::class, 'getSingleArticle'])->middleware(Authenticate::class);
    Route::put('{slug}', [ArticleController::class, 'updateArticle'])->middleware(AuthenticateAndGuard::class);
    Route::delete('{slug}', [ArticleController::class, 'deleteArticle'])->middleware(AuthenticateAndGuard::class);

    Route::post('{slug}/favorite', [FavoriteController::class, 'makeFavorite'])->middleware(AuthenticateAndGuard::class);
    Route::delete('{slug}/favorite', [FavoriteController::class, 'unfavorite'])->middleware(AuthenticateAndGuard::class);

    Route::prefix('{slug}/comments')->group(function () {
        Route::get('', [CommentController::class, 'getComments'])->middleware(Authenticate::class);
        Route::post('', [CommentController::class, 'postComment'])->middleware(AuthenticateAndGuard::class);
        Route::delete('{commentId}', [CommentController::class, 'deleteComment'])->middleware(AuthenticateAndGuard::class);
    });
});

Route::get('tags', [ArticleController::class, 'getTags']);

Route::get('user', [UserController::class, 'getCurrentUser'])->middleware(AuthenticateAndGuard::class);
Route::put('user', [UserController::class, 'updateSettings'])->middleware(AuthenticateAndGuard::class);
Route::post('users', [UserController::class, 'registerUser']);
Route::post('users/login', [UserController::class, 'login']);

Route::prefix('profiles')->group(function () {
    Route::get('{username}', [ProfileController::class, 'getProfile'])->middleware(Authenticate::class);

    Route::post('{username}/follow', [FollowController::class, 'makeFollow'])->middleware(AuthenticateAndGuard::class);
    Route::delete('{username}/follow', [FollowController::class, 'unfollow'])->middleware(AuthenticateAndGuard::class);
});
