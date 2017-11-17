<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'auth' => [
        'token_not_provided' => 'Token not provided!',
        'token_expired' => 'Token has been expired!',
        'token_blacklisted' => 'Token has been blacklisted!',
        'token_invalid' => 'Invalid token!',
        'failed' => 'These credentials do not match our records.',
        'insufficient_permissions' => 'You do not have sufficient permissions to access this resource',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'confirm_email' => 'Please confirm your email!',
        'banned' => 'You have been banned by administrator. Please contact us to get this sorted out!',
        'user_not_found' => 'User not found!',    
        'invalid_access_token' => 'Invalid access token.',
        'facebook_not_connected' => 'You are still not connected with Facebook.',
        'instagram_not_connected' => 'You are still not connected with Instagram.',
        'success_registration' => 'You are successfully registred! Please confirm your email.',
    ],
    
    'passwords' => [
        'password' => 'Passwords must be at least six characters and match the confirmation.',
        'reset' => 'Your password has been reset!',
        'sent' => 'We have e-mailed your password reset link!',
        'token' => 'This password reset token is invalid.',
        'blacklisted_token' => 'The token has been blacklisted',
        'user' => "We can't find a user with that e-mail address.",
        'failed' => "Failed to save your password!",
        'expired' => "This password reset token has expired.",
        'reset_password_requested' => 'Reset password requested. Please check your email.',
    ],
    
    'status' => [
        '500' => 'Something went wrong!',
    ],
    
    'middleware' => [
        'owner_failed' => "You don't have permission to change this resource.",
    ]

];
