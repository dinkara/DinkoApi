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
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of ApiResponse
 *
 * @author Dinkic
 */
class ApiResponse {
    
    protected $statusCode = 200;

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
     * Generates a Response with a 422 HTTP header and a given message.
     * @return Response
     */
    public function UnprocessableEntity($errors){
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->error($errors, Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY]);
    }
    
    /**
     * Generates a Response with a 404 HTTP header and a given message.
     * @param type $item
     * @return Response
     */
    public function ItemNotFound($item){
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->error(null,  class_basename($item) . " not found");
    }
    
    /**
     * Generates a Response with a 404 HTTP header and a given message.
     * @return Response
     */
    public function NotFound($message = 'Resource Not Found'){
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->error(null,  $message);
    }
       
    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function Unauthorized($message = 'Unauthorized'){
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->error(null, $message);
    }
    
    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function Forbidden($message = 'Forbidden'){
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
            ->error(null, $message);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function InternalError($message = 'Internal Error'){
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->error(null, $message);
    }
    
    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function WrongArgs($message = 'Wrong Arguments'){
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->error(null, $message);
    }
    
    /**
     * Generates a Response with a 200 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function SuccessMessage($message = 'Ok'){
        return $this->setStatusCode(Response::HTTP_OK)
            ->success(null, $message);
    }
    
    /**
     * Generates a Response with a 200 HTTP header and a given message.
     *
     * @param array $data
     * @return Response
     */
    public function Token($data = null){
        return $this->setStatusCode(Response::HTTP_OK)
            ->success($data);
    }
    
    /**
     * Returns object transformed over specific transformer
     * @param type $item
     * @param type $callback
     * @return type
     */
    public function Item($item, $callback, $message = "Ok", $code = Response::HTTP_OK){
        $resource = new Item($item, $callback);

        $rootScope = $this->fractal()->createData($resource);

        return $this->setStatusCode($code)->success($rootScope->toArray(), $message);
    }
    
    /**
     * Returns collection of objects transformed over specific transformer
     * @param type $collection
     * @param type $callback
     * @return type
     */
    public function Collection($collection, $callback, $message = "Ok"){
        $resource = new Collection($collection, $callback);

        $rootScope = $this->fractal()->createData($resource);

        return $this->setStatusCode(Response::HTTP_OK)->success($rootScope->toArray(), $message);
    }
    
    /**
     * Returns paginate object with a specific number of objects transformed over specific transformer, also provide redirect urls for next or previous page
     * @param Paginator $paginator
     * @param type $callback
     * @return type
     */
    public function Pagination(Paginator $paginator, $callback, $message = "Ok"){
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $paginator->appends($queryParams);

        $resource = new Collection($paginator, $callback, "data");
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $rootScope = $this->fractal()->createData($resource);

        return $this->setStatusCode(Response::HTTP_OK)->success($rootScope->toArray(), $message, true);
    }

    /**
     * Returns custom success message and created item
     * @param type $item
     * @return type
     */
    public function ItemCreated($item, $callback){
        return $this->Item($item, $callback, class_basename($item) . " succesfully created", Response::HTTP_CREATED);
    }
    
    /**
     * Returns custom success message and attach item
     * @param type $item
     * @return type
     */
    public function ItemAttached($item, $callback){
        return $this->Item($item, $callback,  "Item successfully attached to " . class_basename($item), Response::HTTP_CREATED);
    }
    
    /**
     * Returns custom success message and updated item
     * @param type $item
     * @return type
     */
    public function ItemUpdated($item, $callback){
        return $this->Item($item, $callback, class_basename($item) . " succesfully updated", Response::HTTP_OK);
    }
    
    /**
     * Returns custom success message for deleted item
     * @param type $item
     * @return type
     */
    public function ItemDeleted($item){
        return $this->setStatusCode(Response::HTTP_OK)->success(null, class_basename($item) . " succesfully deleted");
    }
    
    /**
     * Returns custom success message for detached item
     * @param type $item
     * @return type
     */
    public function ItemDetached($item){
        return $this->setStatusCode(Response::HTTP_OK)->success(null, "Item succesfully deleted from " . class_basename($item));
    }
    
    //PRIVATE SCOPE
    
    /**
     * Returns array of objects transformed over specific transformer
     * @param array $array
     * @param array $headers
     * @return type
     */
    private function respondWithArray(array $array, array $headers = []){
        $response = \Response::json($array, $this->statusCode, $headers);

        $response->header('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * Returns custom success message otherwise default message
     * @param type $data
     * @param type $message
     * @param type $mergeData
     * @return type
     */
    private function success($data = [], $message = 'Ok', $mergeData = false){
        $responseData = ['success' => true, 'message' => $message];   
        $responseData = $mergeData ? array_merge($responseData, $data) : array_add($responseData, 'data', $data);
        return $this->respondWithArray($responseData);
    }
    
    /**
     * Returns response with passed error
     * @param type $errors
     * @param type $message
     * @return type
     */
    private function error($errors = [], $message = "Something went wrong!"){
        if ($this->statusCode === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }
        return $this->respondWithArray([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ]);
    }
}
