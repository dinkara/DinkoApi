<?php

namespace Dinkara\DinkoApi\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Illuminate\Support\Facades\Lang;
use ApiResponse;

/**
 * Adapted JWTMiddleware to work with ApiResponse
 */
class DinkoApiAuthMiddleware extends BaseMiddleware
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
            return ApiResponse::WrongArgs( Lang::get('dinkoapi.auth.token_not_provided'));
        }  
        try {
            $user = $this->auth->authenticate($token);
        }catch (TokenBlacklistedException  $e) {
            $this->respond('tymon.jwt.blacklisted', Lang::get('dinkoapi.auth.token_blacklisted'), $e->getStatusCode(), [$e]);
            return ApiResponse::Unauthorized(Lang::get('dinkoapi.auth.token_blacklisted'));
        }catch (TokenExpiredException $e) {
            $this->respond('tymon.jwt.expired',  Lang::get('dinkoapi.auth.token_expired'), $e->getStatusCode(), [$e]);
            return ApiResponse::Unauthorized( Lang::get('dinkoapi.auth.token_expired'));
        } catch (JWTException $e) {
            $this->respond('tymon.jwt.invalid',  Lang::get('dinkoapi.auth.token_invalid'), $e->getStatusCode(), [$e]);
            return ApiResponse::Unauthorized( Lang::get('dinkoapi.auth.token_invalid'));
        }

        if (! $user) {
            $this->respond('tymon.jwt.user_not_found',  Lang::get('dinkoapi.auth.user_not_found'), 404);
            return ApiResponse::NotFound( Lang::get('dinkoapi.auth.user_not_found'));
        }
        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
