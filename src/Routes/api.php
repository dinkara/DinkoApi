<?php

use Illuminate\Http\Request;

//Default auth routes
Route::post('api/login', 'Dinkara\DinkoApi\Controllers\Auth\AuthController@login');
Route::post('api/register', 'Dinkara\DinkoApi\Controllers\Auth\AuthController@register');
Route::post('api/forgot/password', 'Dinkara\DinkoApi\Controllers\Auth\ForgotPasswordController@forgot');


Route::middleware(['api.auth'])->group(function (){
    Route::get('api/token/refresh', 'Dinkara\DinkoApi\Controllers\Auth\AuthController@getToken');
});

