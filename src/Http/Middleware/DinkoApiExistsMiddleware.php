<?php

namespace Dinkara\DinkoApi\Http\Middleware;

use Dinkara\RepoBuilder\Repositories\IRepo;
use Closure;
use ApiResponse;

/**
 * DinkoApiExistsMiddleware
 */
class DinkoApiExistsMiddleware
{
    protected $repo;    
    protected $id;
    
    public function __construct(IRepo $repo) {
        $this->repo = $repo;        
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {         
	$item = $this->repo->find($this->id);
        
        if(!$item){
            return ApiResponse::ItemNotFound($this->repo->getModel());
        }                
        
        return $next($request);
    }
}
