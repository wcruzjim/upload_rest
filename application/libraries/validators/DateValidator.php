<?php
defined('BASEPATH') || exit('No direct script access allowed');

class DateValidator
{

    public static function isValidDate($chain){
        return ( isset($chain) && 
                       strlen($chain) > 0 &&  
                       is_string($chain)  && 
                       strtotime($chain)  &&
                       (preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", $chain) || preg_match("/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/", $chain)  )  ); //NOSONAR
    }

    
    public static function isValidDateYyyyMnDd($chain){
        if (preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", $chain) === 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function isValidDatedmY($chain) {
        if (preg_match("/^(\d{2}-\d{2}-\d{4})$/", $chain) === 1) {
            return true;
        } else {
            return false;
        }
    }
    
}
