<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  || define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') || define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   || define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  || define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           || define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     || define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       || define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  || define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   || define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              || define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            || define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       || define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


define('UNIDAD_ORG_AREAS_DE_APOYO', 7210);
define('UNIDAD_ORG_SUPER_UNO', 7215);
define('UNIDAD_ORG_SUPER_DOS', 7220);
define('DIA_FINAL_FORECAST', 25);
/**Constantes de formula */
define('FORMULA_INDICATOR', 'ind');
define('FORMULA_LOGIC', 'dec');
define('FORMULA_OPERADOR', 'op');
define('FORMULA_CONST', 'const');
define('FORMULA_DAY', '-1 day');
define('UNSAFE_FORMULA', 'insecure');
define('FORMULA_METRIC', 'metric');
define('FORMULA_CONSTANTE_PCRC', 'cnp');
/**Apis Jarvis */
/**api para consultar los documentos pertenecientes a un pcrc */
define('API_JARVIS_PERSONAL_PCRC','https://jarvis.grupokonecta.local/jarvis_rest/DistribucionPersonal/personalCargadoPcrc');
/**api para consultar los documentos pertenecientes a un cliente */
define('API_JARVIS_PERSONAL_CUSTOMER','https://jarvis.grupokonecta.local/jarvis_rest/DistribucionPersonal/personalCargadoCliente');
/**api para consultar los documentos pertenecientes a un ceco */
define('API_JARVIS_PERSONAL_CECO','https://jarvis.grupokonecta.local/jarvis_rest/DistribucionPersonal/personalCargadoCeco');
/**api para consultar si un pcrc esta activo */
define('API_JARVIS_PCRC_ACTIVE','https://jarvis.grupokonecta.local/jarvis_rest/pcrc/pcrcExistenteActivo');
/**api para consultar si un asesor esta activo */
define('API_JARVIS_PERSONAL_ACTIVE','https://jarvis.grupokonecta.local/jarvis_rest/DistribucionPersonal/dataPersonalActivoPcrc');
/** api para consultar el equipo de trabajo de un lider*/
define('API_JARVIS_LEADER_WORK_TEAM','https://jarvis.grupokonecta.local/jarvis_rest/distribucionPersonal/dataMiEquipoDeTrabajo');
/** api para consultar la DP de un asesor*/
define('API_JARVIS_DP_AGENT','https://jarvis.grupokonecta.local/jarvis_rest/ServiciosTeo/getDPByDocument');

/**Constantes Intervalos*/
define('FIFTEEN_MINUTE', '900');
define('THIRTY_MINUTE', '1800');
define('ONE_HOUR', '3600');
define('ONE_DAY', '86400');
define('ONE_WEEK', '604800');
define('ONE_MONTH', '2419200');
define('TIME_INITIAL', '00:00:00');
define('TIME_FINISH', '23:59:59');
define('MORE_DAY', '+1 day');
define('MORE_SECOND', '+1 second');
define('MINUS_DAY', '- 1 days');
define('MINUS_WEEK', '- 1 week');
/**Contantes Operadores */
define('SUM', 'Suma');
define('AVG', 'Promedio');
/**Constante Tipo de Medicion */
define('REMEDICION', 'Remedicion');
/**Constantes acciones, permisos */
define('CREATE_METRIC','415');
define('UPDATE_METRIC','416');
define('DESATIVE_METRIC','417');
define('VIEW_METRIC','418');
/**Permisos Indicadores */
define('CREATE_INDICATOR','411');
define('UPDATE_INDICATOR','412');
define('DESACTIVE_INDICATOR','413');
define('VIEW_INDICATOR','414');
/**Permisos Metas */
define('UPLOAD_GOALS','422');
define('VIEWS_GOALS','421');
/**Permisos Productividad */
define('UPLOAD_PRODUCTIVITY','420');
define('VIEWS_PRODUCTIVITY','419');
/**Permisos de remedición */
define('REMEASURE','432');
/**Permisos de omision */
define('CREATE_OMISSION','439');
define('DESACTIVE_OMISSION','440');
define('VIEW_OMISSION','433');
/**Permisos Dashboard */
define('CREATE_DASHBOARD','434');
define('UPDATE_DASHBOARD','425');
define('VIEW_DASHBOARD','424');
/**Permisos Tabla Pivot */
define('CREATE_TABLE_PIVOT','435');
define('UPDATE_TABLE_PIVOT','427');
define('VIEW_TABLE_PIVOT','426');
/**Permisos Tablas */
define('CREATE_TABLE','436');
define('UPDATE_TABLE','429');
define('VIEW_TABLE','428');
/**Permisos Card panel */
define('CREATE_CARD_PANEL','437');
define('UPDATE_CARD_PANEL','431');
define('VIEW_CARD_PANEL','430');
/**Permisos administrador */
define('ADMIN','438');

/**Tipos de elementos */
define('TABLE','1');
define('CHART','2');
define('CARDPANEL','3');
/**Posiciones Jarvis */
define('AGENT','39');
define('LEADER','30');
/**tipo Variable */
define('TYPE_NUMERIC',1);
define('TYPE_STRING',2);
define('TYPE_ARRAY',3);
/*operadores*/
define('MAX','>');
define('MAX_EQUAL','>=');
define('MIN','<');
define('MIN_EQUAL','<=');
define('BETWEEN','Entre');
define('EQUAL','=');
define('DIFFERENT','!=');
define("NOACCESO", "Acceso denegado");

//Correos
define('SALUDO','Hola!, ');
define('FECHA_4000_META4','4000-01-01'); //fecha 4000 para procesos meta4
define('DOCUMENTO_META4',987654321); // documento meta
