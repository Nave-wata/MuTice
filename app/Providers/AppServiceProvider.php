<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use ReflectionException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(
            \App\Repositories\ApiKey\ApiKeyRepository::class,
            \App\Repositories\ApiKey\ApiKeyEloquent::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @throws ReflectionException
     */
    public function boot(): void
    {
        Arr::mixin(new \App\Mixin\Arr());
    }
}
