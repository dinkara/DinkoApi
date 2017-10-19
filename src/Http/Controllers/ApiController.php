<?php

namespace Dinkara\DinkoApi\Http\Controllers;
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
                return ApiResponse::errorNotFound(Lang::get('auth.user_not_found'));
            }
        } catch (JWTException $e) {
            return ApiResponse::errorInternalError(Lang::get('status.500'));
        }
        
        return $user;
    }    
}