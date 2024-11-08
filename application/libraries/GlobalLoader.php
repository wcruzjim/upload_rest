<?php
class GlobalLoader{

    public static function &getGlobalServer(){
        return $_SERVER; // NOSONAR
    }

    public static function &getGlobalPost(){
        return $_POST; // NOSONAR
    }

    public static function &getGlobalGet(){
        return $_GET; // NOSONAR
    }

    public static function &getGlobalCookie(){
        return $_COOKIE; // NOSONAR
    }

    public static function &getGlobalRequest(){
        return $_REQUEST; // NOSONAR
    }

    public static function &getGlobalFiles(){
        return $_FILES; // NOSONAR
    }

    public static function setGlobalInitValues($valuesKeys){
		ini_set($valuesKeys['key'], $valuesKeys['value']); //NOSONAR
	}

}

?>