<?php

namespace App\Http\Controllers;

use App\Repositories\IRepo;
use ApiResponse;

class ResourceController extends ApiController
{
    
    protected $repo;
    protected $transformer;
    
    public function __construct(IRepo $repo, $transformer) {
        $this->repo = $repo;
        $this->transformer = $transformer;
    }
    /**
     * Display a listing of the item.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return ApiResponse::respondWithPagination($this->repo->paginateAll(), new $this->transformer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ApiResponse::respondWithError();
    }

    /**
     * Display the specified item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($item = $this->repo->find($id)){
            
            $arr['id'] = $item->getModel()->id;

            $fillable = $this->repo->getModel()->getFillable();

            foreach ($fillable as $value) {
                $arr[$value] = eval('return $item->getModel()->'.$value.';');
            }

            return ApiResponse::respondWithItem($item->getModel(), new $this->transformer);
        }
        else{
            return ApiResponse::errorItemNotFound(class_basename($this->repo->getModel()));
        }
        
    }

    /**
     * Show the item for editing it.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        if($item = $this->repo->find($id)){
            return ApiResponse::respondWithItem($item->getModel(), new $this->transformer);
        }
        else{
            return ApiResponse::errorItemNotFound(class_basename($this->repo->getModel()));
        }
    }

    /**
     * Remove the specified item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($item = $this->repo->find($id)){
            $item->delete($id);
            return ApiResponse::successfullyDeleted(class_basename($this->repo->getModel()));
        }
        else{
            return ApiResponse::errorItemNotFound(class_basename($this->repo->getModel()));
        }
        
    }
}
