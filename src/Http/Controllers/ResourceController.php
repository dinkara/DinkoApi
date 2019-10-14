<?php

namespace Dinkara\DinkoApi\Http\Controllers;

use Dinkara\RepoBuilder\Repositories\IRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
     * Get paginated items, included advanced REST querying
     * 
     * Display a listing of the item.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        try{
            return ApiResponse::Pagination( $this->repo->restSearch($request), new $this->transformer );
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        }
    }

    /**
     * Get Single Item
     * 
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
     * Create item
     * 
     * Store a newly created item in storage.
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
     * Update item
     * 
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
                return ApiResponse::ItemUpdated($item->update($data)->getModel(), new $this->transformer);
            }
        } catch (QueryException $e) {
            return ApiResponse::InternalError($e->getMessage());
        }
        return ApiResponse::ItemNotFound($this->repo->getModel());    
    }

    /**
     * Remove item
     * 
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
