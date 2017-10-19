# DinkoAPI Helper ~ Test version

## Requirements
* Laravel ~5.5 or higher

## Composer Install

``` bash
composer require dinkara/dinkoapi
```

## Publish service

If it is not automaticly published, add the service provider in `config/app.php`:

```php
        Dinkara\RepoBuilder\RepositoryBuilderServiceProvider::class,
        Dinkara\DinkoApi\Providers\DinkoApiServiceProvider::class,
        Dinkara\DinkoApi\Providers\ApiResponseServiceProvider::class,
        Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class
```

Also you need to add new aliases for available facades in `config/app.php`:

```php
        'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory' => Tymon\JWTAuth\Facades\JWTAuth::class,
        'ApiResponse' => Dinkara\DinkoApi\Facades\ResponseFacade::class,
```

For publishing new services you need to execute the following line

``` bash
php artisan vendor:publish --all
```

To register new middleware you need to add following line in your `app\Http\Kernel.php`

```php

    'dinkoapi.auth' => \Dinkara\DinkoApi\Http\Middleware\DinkoApiMiddleware::class,

```

And now you can protect your routes with JWT authenticate, simply adding `dinkoapi.auth` as middleware in your route.

### __All suggestions and advices are welcome! So please send us your feedback and we will try to improve this library__



