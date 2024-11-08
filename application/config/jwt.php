<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Llave para firmar los tokens
*/
$config['jwt_key'] = EnvLoader::getEnv('SECRET_KEY_ACCESS_JWT');
$config['jwt_client_key'] = EnvLoader::getEnv('SECRET_KEY_ACCESS_CLIENT_JWT');

$config['jwt_external_key'] = EnvLoader::getEnv('SECRET_KEY_EXTERNAL_ACCESS_JWT');

$config['jwt_cookie_name'] = 'jj_s';
$config['jwt_cookie_name_refresh'] = 'jj_sr';
$config['jwt_cookie_actions'] = 'jj_a';

/**
* Tiempo en minutos de la duración de un token
*/
$config['jwt_timeout'] = EnvLoader::getEnv('JWT_TIMEOUT') ? EnvLoader::getEnv('JWT_TIMEOUT') : 300;
$config['jwt_client_timeout'] = EnvLoader::getEnv('JWT_CLIENT_TIMEOUT') ? EnvLoader::getEnv('JWT_CLIENT_TIMEOUT') : 86400;

// Tiempo en minutos de la duración de un token de recuperación
$config['jwt_timeout_refresh'] = EnvLoader::getEnv('JWT_TIMEOUT_REFRESH') ? EnvLoader::getEnv('JWT_TIMEOUT_REFRESH') : 180;

$config['jwt_key_mfa'] = EnvLoader::getEnv('SECRET_KEY_MFA_JWT');
$config['jwt_timeout_mfa'] = EnvLoader::getEnv('JWT_TIMEOUT_MFA') ? EnvLoader::getEnv('JWT_TIMEOUT_MFA') : 10;

/* End of file jwt.php */
