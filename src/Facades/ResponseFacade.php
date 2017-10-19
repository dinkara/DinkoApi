<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResponseFacade
 *
 * @author Dinkic
 */

namespace Dinkara\DinkoApi\Facades;
use Illuminate\Support\Facades\Facade;
 
class ResponseFacade extends Facade{
    protected static function getFacadeAccessor() { return 'ApiResponse'; }
}
