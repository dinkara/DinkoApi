<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dinkara\DinkoApi\Providers;
 
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
 
class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
 
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('ApiResponse', function()
        {
            return new \Dinkara\DinkoApi\Support\ApiResponse;
        });
    }
}