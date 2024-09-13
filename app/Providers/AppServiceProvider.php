<?php

namespace App\Providers;

use App\Commands\Models\Article\ArticleRepository;
use App\Commands\Models\Article\CheckAuthorExists;
use App\Commands\Models\Comment\CheckAuthorExists as CommentCheckAuthorExists;
use App\Commands\Models\Comment\CommentRepository;
use App\Commands\Models\Favorite\CheckArticleExists;
use App\Commands\Models\Favorite\CheckUserExists;
use App\Commands\Models\Favorite\FavoriteRepository;
use App\Commands\Models\User\CheckUserExistsByEmail;
use App\Commands\Models\User\CheckUserExistsByUsername;
use App\Commands\Models\User\UserRepository;
use App\Implementations\Commands\Models\Article\ArticleRepositoryImpl;
use App\Implementations\Commands\Models\Article\CheckAuthorExistsImpl;
use App\Implementations\Commands\Models\Comment\CheckAuthorExistsImpl as CommentCheckAuthorExistsImpl;
use App\Implementations\Commands\Models\Comment\CommentRepositoryImpl;
use App\Implementations\Commands\Models\Favorite\CheckArticleExistsImpl;
use App\Implementations\Commands\Models\Favorite\CheckUserExistsImpl;
use App\Implementations\Commands\Models\Favorite\FavoriteRepositoryImpl;
use App\Implementations\Commands\Models\User\CheckUserExistsByEmailImpl;
use App\Implementations\Commands\Models\User\CheckUserExistsByUsernameImpl;
use App\Implementations\Commands\Models\User\UserRepositoryImpl;
use App\Shared\Jwt\JwtManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, function () {
            return new UserRepositoryImpl();
        });
        $this->app->singleton(CheckUserExistsByEmail::class, function () {
            return new CheckUserExistsByEmailImpl();
        });
        $this->app->singleton(CheckUserExistsByUsername::class, function () {
            return new CheckUserExistsByUsernameImpl();
        });
        $this->app->singleton(JwtManager::class, function (Application $app) {
            return new JwtManager($app['config']['app.key']);
        });
        $this->app->singleton(ArticleRepository::class, function () {
            return new ArticleRepositoryImpl();
        });
        $this->app->singleton(CheckAuthorExists::class, function () {
            return new CheckAuthorExistsImpl();
        });
        $this->app->singleton(CommentRepository::class, function () {
            return new CommentRepositoryImpl();
        });
        $this->app->singleton(CommentCheckAuthorExists::class, function () {
            return new CommentCheckAuthorExistsImpl();
        });
        $this->app->singleton(FavoriteRepository::class, function () {
            return new FavoriteRepositoryImpl();
        });
        $this->app->singleton(CheckUserExists::class, function () {
            return new CheckUserExistsImpl();
        });
        $this->app->singleton(CheckArticleExists::class, function () {
            return new CheckArticleExistsImpl();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
