<?php

namespace Dinkara\DinkoApi\Http\Controllers;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use ApiResponse;
use Lang;

class ApiController extends Controller
{
    /**
     * Check if passed token match with any user and try to get it
     * @return type
     */
    protected function existUser() {
        try {
            $user = JWTAuth::parseToken()->toUser();
            if (!$user) {
                return ApiResponse::NotFound(Lang::get('auth.user_not_found'));
            }
        } catch (JWTException $e) {
            return ApiResponse::InternalError(Lang::get('status.500'));
        }
        
        return $user;
    }    
    
    /**
     * Invalidate JWT token from request if exist
     */
    protected function invalidateJWTToken() {
        $token = JWTAuth::getToken();
        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
            }catch (TokenBlacklistedException  $e) {
                return ApiResponse::Unauthorized(Lang::get('dinkoapi.auth.token_blacklisted'));
            }catch (TokenExpiredException $e) {
                return ApiResponse::Unauthorized( Lang::get('dinkoapi.auth.token_expired'));
            } catch (JWTException $e) {
                return ApiResponse::Unauthorized( Lang::get('dinkoapi.auth.token_invalid'));
            }          
        }
    }
}