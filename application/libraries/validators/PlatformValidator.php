<?php
defined('BASEPATH') || exit('No direct script access allowed');

class PlatformValidator
{
    // Función que valida desde 3 hasta 40 caracteres y permite alfanuméricos y espacios.
    public static function inValidAlfaWithSpaces($platform){
        return preg_match("/^[A-Za-z0-9- ]{3,40}$/", $platform);
    }
}




