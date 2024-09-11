<?php

namespace App\Providers;

use App\Commands\Models\User\CheckUserExistsByEmail;
use App\Commands\Models\User\CheckUserExistsByUsername;
use App\Commands\Models\User\UserRepository;
use App\Implementations\Commands\Models\User\CheckUserExistsByEmailImpl;
use App\Implementations\Commands\Models\User\CheckUserExistsByUsernameImpl;
use App\Implementations\Commands\Models\User\UserRepositoryImpl;
use App\Queries\Services\UserQueryService;
use App\Shared\JwtGenerator;
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
        $this->app->singleton(JwtGenerator::class, function (Application $app) {
            return new JwtGenerator($app['config']['app.key']);
        });
        $this->app->singleton(UserQueryService::class, function (Application $app) {
            return new UserQueryService($app->make(JwtGenerator::class));
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
