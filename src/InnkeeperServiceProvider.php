<?php

namespace Shirokovnv\Innkeeper;

use Illuminate\Support\ServiceProvider;
use Shirokovnv\Innkeeper\Contracts\Innkeepable;

class InnkeeperServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the service the package provides.
        $this->app->singleton(Innkeepable::class, function ($app) {
            return new Innkeeper;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [Innkeepable::class];
    }
}
