<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dinkara\DinkoApi\Support;

use Dinkara\DinkoApi\Support\DataArraySerializerAdapter;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
/**
 * Description of ApiResponse
 *
 * @author Dinkic
 */
/**
 * Description of ApiResponse
 *
 * @author Dinkic
 */
class ApiResponse {
    
    protected $statusCode = 200;

    const CODE_WRONG_ARGS = 1;
    const CODE_NOT_FOUND = 2;
    const CODE_INTERNAL_ERROR = 3;
    const CODE_UNAUTHORIZED = 4;
    const CODE_FORBIDDEN = 5;

    protected $fractal;

    /**
     * @return Manager
     */
    protected function fractal(){        
        if ($this->fractal) {
            return $this->fractal;
        }

        $fractal = new Manager();
        $fractal->setSerializer(new DataArraySerializerAdapter);

        
        if ($includes = Input::get('include')) {
            $fractal->parseIncludes($includes);
        }

        return $this->fractal = $fractal;
    }
    
    /**
     * Getter for statusCode
     *
     * @return int
     */
    public function getStatusCode(){
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode){
        $this->statusCode = $statusCode;
        return $this;
    }
    
    /**
     * Returns object transformed over specific transformer
     * @param type $item
     * @param type $callback
     * @return type
     */
    public function respondWithItem($item, $callback){
        $resource = new Item($item, $callback, "data");

        $rootScope = $this->fractal()->createData($resource);

        return $rootScope->toArray();
    }

    /**
     * Returns collection of objects transformed over specific transformer
     * @param type $collection
     * @param type $callback
     * @return type
     */
    public function respondWithCollection($collection, $callback){
        $resource = new Collection($collection, $callback, "data");

        $rootScope = $this->fractal()->createData($resource);

        return $rootScope->toArray();
    }

    /**
     * Returns paginate object with a specific number of objects transformed over specific transformer, also provide redirect urls for next or previous page
     * @param Paginator $paginator
     * @param type $callback
     * @return type
     */
    public function respondWithPagination(Paginator $paginator, $callback){
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);

        $resource = new Collection($paginator, $callback, "data");
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $rootScope = $this->fractal()->createData($resource);

        return $rootScope->toArray();
    }

    /**
     * Returns custom success message otherwise default message
     * @param type $message
     * @param type $statusCode
     * @return type
     */
    public function respondWithSuccess($message = 'Ok', $statusCode = 200){
        return $this->setStatusCode($statusCode)
            ->respondWithArray(['success' => true, 'message' => $message]);
    }
    
    /**
     * Returns custom success message for creating new item
     * @param type $item
     * @param type $statusCode
     * @return type
     */
    public function successfullyCreated($item = 'Item', $statusCode = 201){
        return $this->respondWithSuccess($item . " succesfully created", $statusCode);
    }
    
    /**
     * Returns custom success message for updating existing item
     * @param type $item
     * @param type $statusCode
     * @return type
     */
    public function successfullyUpdated($item = 'Item', $statusCode = 200){
        return $this->respondWithSuccess($item . " succesfully updated", $statusCode);
    }
    
    /**
     * Returns custom success message for deliting existing item
     * @param type $item
     * @param type $statusCode
     * @return type
     */
    public function successfullyDeleted($item = 'Item', $statusCode = 200){
        return $this->respondWithSuccess($item . " succesfully deleted", $statusCode);
    }

    /**
     * Returns array of objects transformed over specific transformer
     * @param array $array
     * @param array $headers
     * @return type
     */
    public function respondWithArray(array $array, array $headers = []){
        $responseData = $this->statusCode > 201 ? ["error" => $array] : ["data" => $array];  
        $response = \Response::json($responseData, $this->statusCode, $headers);

        $response->header('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Returns response with passed error
     * @param type $message
     * @param type $errorCode
     * @return type
     */
    private function respondWithError($message, $errorCode){
        if ($this->statusCode === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'message' => $message, 
            'status_code' => $this->statusCode,
            'errors' => []
        ]);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorForbidden($message = 'Forbidden'){
        return $this->setStatusCode(403)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorInternalError($message = 'Internal Error'){
        return $this->setStatusCode(500)
            ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorNotFound($message = 'Resource Not Found'){
        return $this->setStatusCode(404)
            ->respondWithError($message, self::CODE_NOT_FOUND);
    }
    
    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorItemNotFound($message = 'Resource' ){
        return $this->setStatusCode(404)
            ->respondWithError($message . ' not found', self::CODE_NOT_FOUND);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorUnauthorized($message = 'Unauthorized'){
        return $this->setStatusCode(401)
            ->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorWrongArgs($message = 'Wrong Arguments'){
        return $this->setStatusCode(400)
            ->respondWithError($message, self::CODE_WRONG_ARGS);
    }
}
