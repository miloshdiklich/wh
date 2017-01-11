<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\WarehouseRepositoryInterface', 'App\Repositories\WarehouseRepository');
    }
}
