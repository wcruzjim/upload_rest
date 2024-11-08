<?php

class sentryLoader
{
    public function initialize() {

        $CI =& get_instance();
        $sentry_autoload = $CI->config->item('sentry_autoload');
        $sentry_url = $CI->config->item('sentry_url');

        $release_version = $CI->config->item('release_version');
    
        $sentry_config_options = [];
        $sentry_config_options['dsn'] = $sentry_url;

        if(isset($release_version)){
            $sentry_config_options['release'] = $release_version;
        }

        if($sentry_autoload === TRUE){
            Sentry\init( $sentry_config_options );
        }
    }
}