<?php

namespace Dinkara\DinkoApi\Http\Controllers\Auth;

use Dinkara\DinkoApi\Http\Controllers\ApiController;

use Dinkara\DinkoApi\Http\Requests\LoginRequest;
use Dinkara\DinkoApi\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\User\IUserRepo;
use App\Support\Enum\UserStatus;
use Lang;
use ApiResponse;

class AuthController extends ApiController {

    /**
     * @var UserRepository
     */
    private $userRepo;

    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(IUserRepo $user) {
        $this->userRepo = $user;
    }

    /**
     * Returns unique user token
     * @param LoginRequest $request
     * @return type
     */
    public function login(LoginRequest $request) {
        $credentials = $request->only( "email", "password");
        $this->invalidateJWTToken();
        try {
            // Attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiResponse::errorUnauthorized( Lang::get('dinkoapi.auth.failed'), 401);
            }
        } catch (JWTException $e) {
            // Something went wrong whilst attempting to encode the token
            return ApiResponse::errorInternalError( Lang::get('dinkoapi.status.500'));
        }

        $user = JWTAuth::toUser($token);

        if ($user->status == UserStatus::UNCONFIRMED) {
            return ApiResponse::errorUnauthorized( Lang::get('dinkoapi.auth.confirm_email'), 401);
        }

        if ($user->status == UserStatus::BANNED) {
            return ApiResponse::errorForbidden( Lang::get('dinkoapi.auth.banned'), 403);
        }
        
        if($user->password_reset){
            return ApiResponse::errorUnauthorized( Lang::get('dinkoapi.passwords.reset_password_requested'), 401);
        }

        return ApiResponse::respondWithArray(compact('token'))->setStatusCode(200);
    }
    
    /**
     * Refresh token and get back to the client.
     * @return type
     */
    public function getToken() {
        $oldToken = JWTAuth::getToken();
        if (!$oldToken) {
            ApiResponse::errorUnauthorized( Lang::get('dinkoapi.auth.invalid_token'));
        }
        try {         
            $token = JWTAuth::refresh($oldToken);
            $this->invalidateJWTToken();
        } catch (JWTException $e) {
            ApiResponse::errorInternalError( Lang::get('dinkoapi.status.500'));
        }

        return ApiResponse::respondWithArray(compact('token'))->setStatusCode(200);
    }

    /**
     * Create new user
     * @param RegisterRequest $request
     * @return type
     */
    public function register(RegisterRequest $request) {

        $userData = $request->only( "email", "password");

        $this->userRepo->register($userData);
        
        return ApiResponse::respondWithSuccess( Lang::get('dinkoapi.auth.success_registration'));
    }
    
    /**
     * Confirming user. Change status to active.
     * @param type $confirmation_code
     * @return type
     */
    public function confirmEmail($confirmation_code) {

        $user = $this->userRepo->validateEmail($confirmation_code);

        if ($user) {
            return view("messages.email-confirmed");
        } else {
            return view("messages.email-notconfirmed");
        }
    }
    
    /**
     * Invalidate JWT token from request if exist
     */
    private function invalidateJWTToken() {
        $token = JWTAuth::getToken();
        if ($token) {
            JWTAuth::setToken($token)->invalidate();
        }
    }

}
