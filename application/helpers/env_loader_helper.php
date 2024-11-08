<?php

class EnvLoader {
    
    public static function getEnv($environment_var){
        if( !isset($_ENV[ $environment_var ]) ){ //NOSONAR
            return NULL;
        }
        return $_ENV[ $environment_var ]; //NOSONAR
    }
}
