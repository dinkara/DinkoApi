<?php

namespace Dinkara\DinkoApi\Http\Controllers;

use Dinkara\RepoBuilder\Repositories\IRepo;
use Illuminate\Database\QueryException;
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
        try{
            return ApiResponse::Collection($this->repo->all(), new $this->transformer);
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        } 
    }
    
    /**
     * Display a listing of the item.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginate()
    {   
        try{
            return ApiResponse::Pagination($this->repo->paginateAll(), new $this->transformer);
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        } 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ApiResponse::NotFound();
    }

    /**
     * Display the specified item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if($item = $this->repo->find($id)){

                $arr['id'] = $item->getModel()->id;

                $fillable = $this->repo->getModel()->getFillable();

                foreach ($fillable as $value) {
                    $arr[$value] = eval('return $item->getModel()->'.$value.';');
                }

                return ApiResponse::Item($item->getModel(), new $this->transformer);
            }
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        }    
        
        return ApiResponse::ItemNotFound($this->repo->getModel());
        
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  $data
     * @return \Illuminate\Http\Response
     */
    public function storeItem($data)
    {       
        try {
            return ApiResponse::ItemCreated($this->repo->create($data)->getModel(), $this->transformer);
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $data
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItem($data, $id)
    {
        try {
            if( $item = $this->repo->find($id)){
                return ApiResponse::ItemUpdated($item->update($data)->getModel(), new $this->transformer, class_basename($this->repo->getModel()));
            }
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        }
        return ApiResponse::ItemNotFound($this->repo->getModel());    
    }
    /**
     * Show the item for editing it.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        try{
            if($item = $this->repo->find($id)){
                return ApiResponse::Item($item->getModel(), new $this->transformer);
            }
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        }  
        return ApiResponse::ItemNotFound($this->repo->getModel());
    }

    /**
     * Remove the specified item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            if($item = $this->repo->find($id)){
                $item->delete($id);
                return ApiResponse::ItemDeleted($this->repo->getModel());
            }
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        } 
        
        return ApiResponse::ItemNotFound($this->repo->getModel());       
    }
}
