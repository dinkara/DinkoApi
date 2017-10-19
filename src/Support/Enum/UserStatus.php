<?php

namespace Dinkara\DinkoApi\Support\Enum;

class UserStatus
{
    const UNCONFIRMED = 'Unconfirmed';
    const ACTIVE = 'Active';    
    const BANNED = 'Banned';
    const DELETED = 'Deleted';
    
    public static function all(){
        return [
            self::UNCONFIRMED,
            self::ACTIVE,
            self::BANNED,
            self::DELETED
        ];
    }
}