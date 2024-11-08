<?php
defined('BASEPATH') || exit('No direct script access allowed');

class ErrorHandler{


    public static function catch($error){

        $sentryIsEnabled = self::checkIfSentryIsEnabled();
        
        if($sentryIsEnabled === FALSE){
            return;
        }

        self::checkValidExceptionTypeOrThrowError( $error );
        self::sentryRepository( $error );
        return TRUE;
    }

    private static function checkIfSentryIsEnabled(){
        $CI =& get_instance();
        $sentry_autoload = $CI->config->item('sentry_autoload');
        $environment = $CI->config->item('environment');
        return $sentry_autoload && $environment === 'production';
    }

    private static function sentryRepository( $error ){

        if(  gettype($error) === 'object'  ){
            \Sentry\captureException( $error );
        }

        \Sentry\captureMessage( $error );    

        return TRUE;
    }

    private static function checkValidExceptionTypeOrThrowError($error){

        if(!isset($error)){
            throw new Exception( "Error is not defined or is null");
        }

        if(  gettype($error) !== 'string' &&  gettype($error) !== 'object'  ){
            throw new Exception( "Invalid error type. Must be a string or Exception" );
        }

        if( gettype($error) === 'string' && strlen($error) < 1 ){
            throw new Exception("Invalid error type. String can't be null or empty");
        }
        
        return TRUE;
    }

}