<?php

namespace Dinkara\DinkoApi\Support;

use League\Fractal\Serializer\DataArraySerializer;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataArraySerializerAdapter
 *
 * @author Dinkic
 */
class DataArraySerializerAdapter extends DataArraySerializer{
    
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    
    public function collection($resourceKey, array $data)
    {
        if($data == null){
            return $this->null();
        }
        
        if(!$resourceKey){
            return $data;
        }
        
        return [$resourceKey => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        if($data == null){
            return $this->null();
        }
        
        if(!$resourceKey){
            return $data;
        }
        
        return [$resourceKey => $data];
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null()    
    {
        return null;
    }
}
