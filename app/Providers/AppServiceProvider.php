<?php

namespace App\Providers;

use App\{
    Repositories\Auth\AuthRepository,
    Repositories\Cms\Home\HomeRepository,
    Repositories\Cms\About\AboutRepository,
    Repositories\Cms\Client\ClientRepository,
    Repositories\Cms\Service\ServiceRepository
};
use App\{
    Repositories\Auth\AuthRepositoryInterface,
    Repositories\Cms\Home\HomeRepositoryInterface,
    Repositories\Cms\About\AboutRepositoryInterface,
    Repositories\Cms\Client\ClientRepositoryInterface,
    Repositories\Cms\Service\ServiceRepositoryInterface,
};
use App\Repositories\Cms\CountUp\CountUpRepository;
use App\Repositories\Cms\CountUp\CountUpRepositoryInterface;
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
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(CountUpRepositoryInterface::class, CountUpRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
