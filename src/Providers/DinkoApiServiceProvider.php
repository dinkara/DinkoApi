<?php

namespace Dinkara\DinkoApi\Providers;

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
        //$this->loadRoutesFrom(__DIR__."/Routes/api.php");
        //$this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->publishes([
            __DIR__.'/Support/Lang/en' => resource_path('lang/en'),
        ]);
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