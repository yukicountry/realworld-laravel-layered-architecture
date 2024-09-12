<?php

namespace App\Providers;

use App\Commands\Models\Article\ArticleRepository;
use App\Commands\Models\Article\CheckAuthorExists;
use App\Commands\Models\User\CheckUserExistsByEmail;
use App\Commands\Models\User\CheckUserExistsByUsername;
use App\Commands\Models\User\UserRepository;
use App\Implementations\Commands\Models\Article\ArticleRepositoryImpl;
use App\Implementations\Commands\Models\Article\CheckAuthorExistsImpl;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
