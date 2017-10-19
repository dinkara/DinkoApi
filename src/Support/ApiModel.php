<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dinkara\DinkoApi\Support;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of ApiModel
 *
 * @author Dinkic
 */
class ApiModel extends Model{
    
    
    protected $displayable = [];
    
    public function getDisplayable() {
        return $this->displayable;
    }
}
