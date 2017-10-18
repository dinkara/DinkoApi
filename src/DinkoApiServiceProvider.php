<?php

namespace Dinkara\DinkoApi;

use Illuminate\Support\ServiceProvider;

class DinkoApiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__."/Routes/api.php");
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}