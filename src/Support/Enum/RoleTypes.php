<?php

namespace Dinkara\DinkoApi\Support\Enum;

class RoleTypes
{
    const ADMIN = 'admin';
    const USER = 'user';  
    
    public static function all(){
        return [
            self::ADMIN,
            self::USER            
        ];
    }
}