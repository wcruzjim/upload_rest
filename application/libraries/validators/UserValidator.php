<?php
defined('BASEPATH') || exit('No direct script access allowed');

class UserValidator
{

    private const DOCUTMENT_MIN_LEN = 3;
    private const DOCUMENT_MAX_LEN = 40;

    private const NAME_MIN_LEN = 1;
    private const NAME_MAX_LEN = 50;

    private const USERNAME_MIN_LEN = 1;
    private const USERNAME_MAX_LEN = 50;


    public static function isValidDocument($chain){

        return (   self::isNotEmpty($chain)   && 
                        self::isMinLen($chain, self::DOCUTMENT_MIN_LEN)  &&
                        self::isMaxLen($chain, self::DOCUMENT_MAX_LEN)  &&
                        self::isValidRegexAlphanumeric($chain)  );
    }
    
    public static function isValidName($chain){

        return (  self::isNotEmpty( $chain )   && 
                        self::isMinLen( $chain, self::NAME_MIN_LEN )  &&
                        self::isMaxLen( $chain, self::NAME_MAX_LEN )  &&
                        self::isValidRegexAlphanumeric( $chain )  );
    }

    public static function isValidUsername($chain){

        return (  self::isNotEmpty( $chain )   && 
                        self::isMinLen( $chain, self::USERNAME_MIN_LEN )  &&
                        self::isMaxLen( $chain, self::USERNAME_MAX_LEN )  &&
                        self::isValidRegexUsername( $chain )  );
    }


    public static function isValidCustomList($rows, $usedValidator){

        if( !isset($usedValidator) || !is_string($usedValidator)  || !method_exists( 'UserValidator', $usedValidator ) ){
            throw new Exception('Validator ' . $usedValidator . ' does not exit in UserValidator class');
        }

        if( self::isValidArray($rows) === FALSE ){
            return FALSE;
        }

        $validRows = array_values( array_filter($rows, array(__CLASS__, $usedValidator)) );

        return count( $validRows  ) === count($rows) ;
    }


    
    private static function isValidArray($array){
        return !(!isset($array) || !is_array($array)  || count($array) < 1);
    }

    private static function isValidRegexAlphanumeric($chain){
        return preg_match("/^[A-Za-z0-9-]+$/", $chain);
    }

    private static function isValidRegexUsername($chain){
        return preg_match("/^[A-Za-z0-9-_.]+$/", $chain);
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
