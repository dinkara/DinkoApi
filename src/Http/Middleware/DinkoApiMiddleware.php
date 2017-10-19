<?php

namespace Dinkara\DinkoApi\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Illuminate\Support\Facades\Lang;
use ApiResponse;
use Dinkara\DinkoApi\Support\Enum\UserStatus;

/**
 * Adapted JWTMiddleware to work with ApiResponse
 */
class DinkoApiMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {     
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            $this->respond('tymon.jwt.absent', Lang::get('dinkoapi.auth.token_not_provided'), 400);
            return ApiResponse::errorWrongArgs( Lang::get('dinkoapi.auth.token_not_provided'));
        }
        
        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            $this->respond('tymon.jwt.expired',  Lang::get('dinkoapi.auth.token_expired'), $e->getStatusCode(), [$e]);
            return ApiResponse::errorUnauthorized( Lang::get('dinkoapi.auth.token_expired'));
        } catch (JWTException $e) {
            $this->respond('tymon.jwt.invalid',  Lang::get('dinkoapi.auth.token_invalid'), $e->getStatusCode(), [$e]);
            return ApiResponse::errorUnauthorized( Lang::get('dinkoapi.auth.token_invalid'));
        }

        if (! $user) {
            $this->respond('tymon.jwt.user_not_found',  Lang::get('dinkoapi.auth.user_not_found'), 404);
            return ApiResponse::errorNotFound( Lang::get('dinkoapi.auth.user_not_found'));
        }
        if ($user->status == UserStatus::UNCONFIRMED) {
            return ApiResponse::errorUnauthorized( Lang::get('dinkoapi.auth.confirm_email'), 401);
        }

        if ($user->status == UserStatus::BANNED) {
            return ApiResponse::errorForbidden( Lang::get('dinkoapi.auth.banned'), 403);
        }
        
        if($user->password_reset){
            return ApiResponse::errorUnauthorized( Lang::get('dinkoapi.passwords.reset_password_requested'), 401);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
