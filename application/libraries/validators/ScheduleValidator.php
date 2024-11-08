<?php
defined('BASEPATH') || exit('No direct script access allowed');

class ScheduleValidator
{

    private const SCHEDULE_ID_MIN_LEN = 1;
    private const SCHEDULE_ID_MAX_LEN = 10;


    public static function isValidScheduleId($chain){

        return (   self::isNotEmpty($chain)   && 
                        self::isMinLen($chain, self::SCHEDULE_ID_MIN_LEN)  &&
                        self::isMaxLen($chain, self::SCHEDULE_ID_MAX_LEN)  &&
                        is_numeric($chain)  );
    }

    public static function isValidRest($chain){

        return (   self::isNotEmpty($chain)   && 
                        is_numeric($chain)  &&
                        ( (int)$chain === 0 || (int)$chain === 1  ));
    }

    private static function isNotEmpty($chain){
        return isset($chain) && strlen($chain) > 0;
    }

    private  static function isMinLen($chain, $minLen){
        return strlen($chain) >= $minLen;
    }
    private static function isMaxLen($chain, $maxLen){
        return strlen($chain) <= $maxLen;
    }

}
