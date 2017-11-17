<?php

namespace Dinkara\DinkoApi\Http\Middleware;

use Dinkara\RepoBuilder\Repositories\IRepo;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;
use ApiResponse;
use Lang;

/**
 * DinkoApiOwnerMiddleware
 */
class DinkoApiOwnerMiddleware
{
    protected $repo;    
    
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
        $this->repo->find($request->id);
        
        $resource = $this->repo->getModel();
        
        $user = JWTAuth::parseToken()->toUser();                
        
        if($resource->user && $user && $resource->user->id != $user->id){
            return ApiResponse::Unauthorized(Lang::get("dinkoapi.middleware.owner_feild"));
        }
        
	return $next($request);			
    }
}
