<?php
defined('BASEPATH') || exit('No direct script access allowed');

class TypeValidator
{

    public static function isValidBoolean($chain){
        return ( isset($chain) && 
                    ( (  is_string($chain) && $chain === 'true' )  || (is_bool($chain) && $chain === TRUE)  )  );
    }

   
    
}
