<?php

namespace App\Http\Requests;

class RegisterRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'name' => "required",
//            'username' => "required|max:255|unique:users",
            'email' => "required|email|max:255|unique:users",
            'password' => "required|confirmed|min:6",         
        ];
    }
}
