<?php

namespace Amethyst\Common;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AmethystServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->register(\Railken\Lem\Providers\ManagerServiceProvider::class);
        $this->app->register(\Amethyst\Providers\ApiServiceProvider::class);
        $this->app->register(\Railken\EloquentMapper\EloquentMapperServiceProvider::class);

        $this->app->get('eloquent.mapper')->retriever(function () {
            return $this->app->get('amethyst')->getData()->map(function ($data) {
                return Arr::get($data, 'model');
            })->toArray();
        });
    }

    public function boot()
    {
        $this->app->singleton('amethyst', function ($app) {
            return new \Amethyst\Common\Helper();
        });

        $this->loadRoutesFrom(__DIR__.'/../resources/routes.php');
    }
}
