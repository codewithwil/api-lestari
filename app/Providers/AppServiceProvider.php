<?php

namespace App\Providers;

use App\{
    Repositories\Auth\AuthRepository,
    Repositories\Cms\Home\HomeRepository,
    Repositories\Cms\About\AboutRepository
};
use App\{
    Repositories\Auth\AuthRepositoryInterface,
    Repositories\Cms\Home\HomeRepositoryInterface,
    Repositories\Cms\About\AboutRepositoryInterface,
};
use App\Repositories\Cms\Client\ClientRepository;
use App\Repositories\Cms\Client\ClientRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(HomeRepositoryInterface::class, HomeRepository::class);
        $this->app->bind(AboutRepositoryInterface::class, AboutRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
