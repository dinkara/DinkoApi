<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiTransformer
 *
 * @author dzale
 */

namespace Dinkara\DinkoApi\Transformers;

use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class ApiTransformer extends TransformerAbstract{
        
    //protected abstract function transformFromModel($data);
    
    protected function item($data, $transformer, $resourceKey = null)
    {
        if($data == null){
            return $this->null();
        }
        
        return new Item($data, $transformer, $resourceKey);
    }
    
    protected function collection($data, $transformer, $resourceKey = null)
    {
        if($data == null){
            return $this->null();
        }
                
        return new Collection($data, $transformer, $resourceKey);
    }     
    
        protected function transformFromModel($item, $pivot = []){
        
        $arr = ['id' => $item->id];
        
        $displayable = $item->getDisplayable();
        
	if($pivot){	    
	    $displayable = array_merge($displayable, array_intersect($pivot, array_keys($item->pivot->getAttributes())));	    
	}
	
        foreach ($displayable as $value) {
	    $result = eval('return $item->'.$value.';');
            	    
            if($result == null && $item->pivot != null){
		$arr["pivot"][$value] = eval('return $item->pivot->'.$value.';');;
		
	    }
	    else{
		$arr[$value] = $result;
	    }
        }
	
        return $arr;
    }
    
}
