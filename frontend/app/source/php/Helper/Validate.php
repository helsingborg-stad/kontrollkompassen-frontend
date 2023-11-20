<?php

namespace NavetSearch\Helper;

use \NavetSearch\Helper\Sanitize as Sanitize;

class Validate
{
    public static function pnr($string)
    {   
        if(strlen(Sanitize::number($string)) == 12) {
            return true; 
        }
        return false;
    }

    public static function empty(string $string): bool
    {
        return (bool) !empty($string); 
    }

    public static function email(string $string): bool
    {  
        if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
            return true; 
        }
        return false; 
    }

    public static function username(string $string): bool
    {   
        if(preg_match('/^[A-Za-z]{4}[0-9]{4}$/', $string)){
            return true; 
        }
        return false;
    }

    public static function password(string $string): bool
    {   
        return self::empty($string);
    }

    public static function isErrorResponse(object $response): bool {
        if(isset($response->error)) {
            return true;
        }
        if(isset($response->scalar->errors)) {
            return true;
        }
        return false;
    }
}
