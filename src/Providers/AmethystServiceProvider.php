<?php

namespace Amethyst\Core\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Amethyst\Core\Map;
use Railken\EloquentMapper\Contracts\Map as MapContract;

class AmethystServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->register(\Railken\Lem\Providers\ManagerServiceProvider::class);
        $this->app->register(\Amethyst\Core\Providers\ApiServiceProvider::class);
        $this->app->register(\Railken\EloquentMapper\EloquentMapperServiceProvider::class);
        $this->app->bind(MapContract::class, Map::class);

        $this->app->get('eloquent.mapper')->retriever(function () {
            return $this->app->get('amethyst')->getData()->map(function ($data) {
                return Arr::get($data, 'model');
            })->toArray();
        });
    }

    public function boot()
    {
        $this->app->singleton('amethyst', function ($app) {
            return new \Amethyst\Core\Helper();
        });

        $this->loadRoutesFrom(__DIR__.'/../resources/routes.php');
    }
}