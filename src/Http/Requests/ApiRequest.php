<?php

namespace Dinkara\DinkoApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use ApiResponse;

abstract class ApiRequest extends FormRequest
{     
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        
        if(method_exists($validator, 'errors')){
            $errors = (new ValidationException($validator->errors()))->validator;
        }else{
            $errors = (new ValidationException($validator))->errors();
        }
        
        throw new HttpResponseException(ApiResponse::UnprocessableEntity($errors));
    }
}
