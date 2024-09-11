<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('articles')->group(function () {
    Route::get('', [ArticleController::class, 'getArticles']);
    Route::post('', [ArticleController::class, 'postArticle']);
    Route::get('feed', [ArticleController::class, 'feedArticles']);
    Route::get('tags', [ArticleController::class, 'getTags']);
    Route::get('{slug}', [ArticleController::class, 'getArticle']);
    Route::put('{slug}', [ArticleController::class, 'updateArticle']);
    Route::delete('{slug}', [ArticleController::class, 'deleteArticle']);
    Route::post('{slug}/favorite', [FavoriteController::class, 'makeFavorite']);
    Route::delete('{slug}/favorite', [FavoriteController::class, 'unfavorite']);
    Route::prefix('{slug}/comments')->group(function () {
        Route::get('', [CommentController::class, 'getComments']);
        Route::post('', [CommentController::class, 'postComment']);
        Route::delete('{commentId}', [CommentController::class, 'deleteComment']);
    });
});

Route::get('/user', [UserController::class, 'getCurrentUser'])->middleware(VerifyJwtToken::class);
Route::put('/user', [UserController::class, 'updateSettings']);
Route::post('/users', [UserController::class, 'registerUser']);
Route::post('/users/login', [UserController::class, 'login']);

Route::prefix('profiles')->group(function () {
    Route::get('/{username}', [ProfileController::class, 'getProfile']);
    Route::post('/{username}/follow', [FollowController::class, 'makeFollow']);
    Route::delete('/{username}/follow', [FollowController::class, 'unfollow']);
});
