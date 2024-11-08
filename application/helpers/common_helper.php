<?php
/**
 * Importamos la librería CodeIgniter Rest Controller
 */
use Restserver\Libraries\REST_Controller;


class COMMON
{

	private static $global_config = [];
	private static $counter_queries_database = 0;
	private static $counter_sessions_database = 0;

	public static $jsonCheck = 'COMMON::json_check';


	public static function increase_counter_queries_database(){
		self::$counter_queries_database++;
	}
	public static function get_counter_queries_database(){
		return self::$counter_queries_database;
	}

	public static function increase_counter_sessions_database(){
		self::$counter_sessions_database++;
	}
	public static function get_counter_sessions_database(){
		return self::$counter_sessions_database;
	}

	/**
	 * show_object() Muestra el contenido formateado de un objeto
	 * @param objeto-array $obj Objeto que se mostrará
	 * @author Fáber Alexánder Gallego
	 * @version 1.0
	 * @copyright 2010
	 */
	public static function show_object($obj)
	{
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
	}

	/**
	 * getQuery(queryName) Recupera una sentencia sql del archivo XML bien formado "config/queries.xml"
	 * @param objeto-array $obj Objeto que se mostrará
	 * @author Fáber Alexánder Gallego
	 * @version 1.1
	 * @copyright 2010
	 */
	public static function getQuery($queryName)
	{
		libxml_disable_entity_loader(false);
		$CI = &get_instance();
		$queriesFile = $CI->config->item('queriesFile');
		$queries = new DOMDocument();
		$queries->validateOnParse = true;
		$queries->load($queriesFile);
		if ($queries->validate()) {
			$query = $queries->getElementById($queryName);
			if ($query != null) {
				return $query->nodeValue;
			} else {
				die('Query no encontrado en queries.xml');
			}
		} else {
			die('XML query mal formado.');
		}
	}

	/**
	 * sort_by_orden() ordena un array multimedencional por la clave valor de mayor a menor
	 * @param objeto-array $obj Objeto que se mostrará
	 * @author Diego Beltran
	 * @version 1.0
	 * @copyright 2018
	 */
	public static function sort_by_orden($a, $b)
	{
		if ($a == $b) {
			return 0;
		}
		return ($a > $b) ? -1 : 1;
	}

	/**
	 * mb_ucfirst() Devuelve una cadena de texto con su primer carácter en mayúscula
	 * @param string $string cadena de texto
	 * @param string $encoding codificación (por defecto es UTF-8)
	 * @author Daniel Cardona Cardona
	 * @version 1.0
	 * @copyright 2019
	 */
	public static function mb_ucfirst($string, $encoding = 'UTF-8')
	{
		$strlen = mb_strlen($string, $encoding);
		$firstChar = mb_substr($string, 0, 1, $encoding);
		$then = mb_substr($string, 1, $strlen - 1, $encoding);
		return mb_strtoupper($firstChar, $encoding) . $then;
	}

	/**
	 * Cambia el formato de la fecha ingresada por el formato suministrado
	 *
	 * @param string $date ('2019-03-04 18:30:00')
	 * @param string $format ('d/m/Y h:i:s A')
	 * @return string ('04/03/2019 06:30:00 PM')
	 */
	public static function dateFormat($date = NULL, $format = NULL)
	{
		if ($date === NULL) {
			$date = time();
		} else {
			$date = strtotime($date);
		}
		if ($format === NULL) {
			$format = 'd/m/Y h:i:s A';
		}
		return date($format, $date);
	}

	/**
	 * Función que devuelve la suma de un array con tiempos en formato 'H:i:s'
	 * @param <array> $array_tiempos array con los tiempos que se desean sumar, Ej: array('04:30:00','00:35:00','01:00:00')
	 * @author Daniel Cardona C.
	 * @version 1.0
	 * @copyright 2018
	 **/
	public static function sumar_tiempos($array_tiempos)
	{

		date_default_timezone_set('UTC');

		$tiempo = '00:00:00';

		foreach ($array_tiempos as $valor) {
			$separador_tiempo = explode(':', $valor);
			$tiempo = strtotime("+$separador_tiempo[0] hour +$separador_tiempo[1] minutes +$separador_tiempo[2] seconds", strtotime($tiempo));
			$tiempo = date('H:i:s', $tiempo);
		}

		return $tiempo;
	}

	/**
	 * Función que devuelve la resta de dos tiempos en formato 'H:i:s'
	 * @author Daniel Cardona C.
	 * @version 1.0
	 * @copyright 2018
	 **/
	public static function restar_tiempos($tiempoMayor, $tiempoMenor)
	{
		date_default_timezone_set('UTC');
		$tiempo = date('H:i:s', strtotime($tiempoMayor) - strtotime($tiempoMenor));
		return $tiempo;
	}

	/**
	 * Función que devuelve el tiempo en unix de una fecha en formato 'Y-m-d H:i:s'
	 * @author Daniel Cardona C.
	 * @version 1.0
	 * @copyright 2018
	 **/
	public static function obtener_tiempo($tiempo, $unix = TRUE)
	{
		date_default_timezone_set('UTC');

		if ($unix) {
			return strtotime(date('H:i:s', strtotime($tiempo)));
		}

		return date('H:i:s', strtotime($tiempo));
	}

	/**
	 * Función que devuelve el tiempo en formato 'Y-m-d H:i:s'
	 * @author Daniel Cardona C.
	 * @version 1.0
	 * @copyright 2018
	 **/
	public static function fecha_formato_mysql($tiempo)
	{

		if (strpos($tiempo, '/') !== false) {
			$tiempo = str_replace('/', '-', $tiempo);
		}

		return date('Y-m-d H:i:s', strtotime($tiempo));
	}

	/**
	 * Función que devuelve el tiempo en horas fraccionadas (6:30:00 => 6.5)
	 * @author Daniel Cardona C.
	 * @version 1.0
	 * @copyright 2019
	 **/
	public static function tiempo_fraccion($time)
	{
		$hms = explode(":", $time);
		return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
	}

	/**
	 * Envía una respuesta al cliente con los parametros suministrados
	 *
	 * @param array $result respuesta de la consulta
	 * @param int $status status de la consulta
	 * @param string $statusText texto descriptivo del status de la consulta
	 * @param bool $regenerate indica si se regenera o no el JWT
	 * @param int $httpStatus status de la petición http
	 * @return void
	 */
	public static function sendResponse($result = null, $status = 1, $statusText = 'Ok', $regenerate = TRUE, $httpStatus = 200)
	{
		$CI = &get_instance();
		if (empty($statusText)) {
			$statusText = ((int)$status === 0) ? 'Error indefinido' : 'Ok';
		}
		if (empty($httpStatus)) {
			$httpStatus = 200;
		}
		if ($result !== null) {
			$output['result'] = $result;
		}
		if ($regenerate == TRUE) {
			$output['jwt'] = "SECURITY::regenerateJwt()";
		}
		$output['status'] = (int) $status;
		$output['statusText'] = $statusText;
		$CI->response($output, $httpStatus);
	}

	/**
	 * Valida que no este vacio y que sea númerico.
	 *
	 * @param int $value
	 * @param bool $boolean
	 * @return string
	 */
	public static function noEmpty($value = 0)
	{

		$eval = trim($value);

		if (!isset($eval) || strlen($eval) <= 0) {
			$output['boolean'] = FALSE;
			$output['result'] = 'Se encuentra vacio, valor no permitido.';
			return $output;
		}

		if (!is_numeric($eval)) {
			$output['boolean'] = FALSE;
			$output['result'] = 'Debe ser númerico, valor no permitido.';
			return $output;
		}

		return array('boolean' => TRUE);
	}

	/**
	 * Valida el formato fecha.
	 *
	 * @param  <date>  $date  fecha a validar
	 * @param object $output  contenido de la respuesta
	 * @return     FALSE si no encuentra correcto el formato
	 */
	public static function valid_format_date($date)
	{

		$date = trim($date);

		if (!isset($date) || strlen($date) <= 0) {
			$output['boolean'] = FALSE;
			$output['result'] = 'Se encuentra vacio, valor no permitido.';
			return $output;
		}

		try {
			$datetmp = strtotime($date);
			if ($datetmp === FALSE) {
				$output['boolean'] = FALSE;
				$output['result'] = 'Formato de fecha no permitida.';
				return $output;
			} else {
				$datetmp = date('Y-m-d', $datetmp);
			}
		} catch (Exception $err) {
			$output['boolean'] = FALSE;
			$output['result'] = 'Formato de fecha no permitida.';
			return $output;
		}

		return array('boolean' => TRUE, 'result' => $datetmp);
	}

	/**
	 * Elimina registros duplicados, dejando el ultimo registro por documento.
	 *
	 * @param  <date>  $reg  registro a validar por documentos
	 * @param object $output  contenido de la respuesta
	 * @return     FALSE si no encuentra correcto el formato
	 */
	public static function delete_registers_in_array_duplicated($reg = null)
	{

		if (!isset($reg)) {
			return FALSE;
		}

		$arrayDocumentosUnicos = array();

		foreach ($reg as $documentos) {
			$arrayDocumentosUnicos[$documentos['documento']] = $documentos;
		}

		return array_values($arrayDocumentosUnicos);
	}

	/**
	 * Valida el formato hh:mm y lo convierte de 12hrs to 24hrs or 24hrs to 12hrs
	 * e.g: 1:20:00 pm to 13:20:00  or  13:20:00 to 1:20:00 pm 
	 * regular expression only for 12hr format preg_match("/(1[012]|0[0-9]):[0-5][0-9]/", $time);
	 * regular expression only for 24hr format preg_match("/(2[0-3]|[01][0-9]):[0-5][0-9]/", $time);
	 *
	 * @param int $value
	 * @param bool $boolean
	 * @return string
	 */
	public static function hour_format($value = 0)
	{

		$time = trim($value);

		if (strlen($time) <= 6) {
			$validHour = (preg_match("/(2[0-3]|[01][0-9]):[0-5][0-9]/", $time));
		} else {
			$validHour = (preg_match("/(1[012]|0[0-9]):[0-5][0-9]/", "0" . $time));
		}

		if (!isset($time) || strlen($time) <= 0) {
			$output['boolean'] = FALSE;
			$output['result'] = 'Se encuentra vacio, valor no permitido.';
			return $output;
		}

		if ($validHour == 1) {

			if (strlen($time) <= 6) {
				// 24-hour time to 12-hour time 
				$time_in_hour_format  = date("g:i a", strtotime($time));
			} else {
				// 12-hour time to 24-hour time 
				$time_in_hour_format  = date("H:i:s", strtotime($time));
			}
		} else {
			$output['boolean'] = FALSE;
			$output['result'] = 'No tiene un formato valido de hora.';
			return $output;
		}

		return array('boolean' => TRUE, 'result' => $time_in_hour_format);
	}

	/**
	 * Obtiene un valor de la configuración global de jarvis
	 *
	 * @param      <string>  $config  nombre de la variable a buscar
	 * @return     FALSE si no encuentra la variable o el contenido de la variable
	 */
	public static function get_global_config($config = null)
	{
		if ($config === null) {
			return FALSE;
		}

		// Got from cache if it exists
		if(isset(self::$global_config[$config])){
			return self::$global_config[$config];
		}

		$CI = &get_instance();
		$CI->load->model('JarvisModel');
		$result = $CI->JarvisModel->getGlobalConfig($config);

		// Cache config value if it does not exist
		if(isset($result) && $result !== FALSE){
			self::$global_config[$config] = $result;
		}

		return $result;
	}

	/**
	 * Obtiene un valor de la configuración global de Teo
	 *
	 * @param      <string>  $config  nombre de la variable a buscar
	 * @return     FALSE si no encuentra la variable o el contenido de la variable
	 */
	public static function get_global_config_teo($config = null)
	{
		if ($config === null) {
			return FALSE;
		}
		$CI = &get_instance();
		$CI->load->model('TeoModel');
		return $CI->TeoModel->getGlobalConfig($config);
	}

	/**
	 * Obtiene un valor de la configuración global de Teo
	 *
	 * @param      <string>  $config  nombre de la variable a buscar
	 * @return     FALSE si no encuentra la variable o el contenido de la variable
	 */
	public static function set_global_config_teo($config = null, $value = null)
	{
		if ($config === null) {
			return FALSE;
		}
		$CI = &get_instance();
		$CI->load->model('TeoModel');
		return $CI->Jarvis_model->setGlobalConfig($config, $value);
	}

	public static function set_global_config($config = null, $value = null)
	{
		if ($config === null) {
			return FALSE;
		}
		$CI = &get_instance();
		$CI->load->model('JarvisModel');
		return $CI->JarvisModel->setGlobalConfig($config, $value);
	}

	/**
	 * guarda el log de un cambio en BD
	 * @param <int> $idregistro, <string> $tabla, <string> $accion
	 * @author Yeferson Sossa
	 */
	public static function save_log_changes($idregistro = null, $tabla = null, $accion = null, $observations = null, $update_old = null, $update_new = null)
	{
		if ($idregistro === null && $tabla === null && $accion === null) {
			return FALSE;
		}
		$CI = &get_instance();
		$documento = SECURITY::getJwtData()->id;
		if (isset(SECURITY::getJwtData()->lastName) && isset(SECURITY::getJwtData()->name)) {
			$responsible_user = SECURITY::getJwtData()->name . ' ' . SECURITY::getJwtData()->lastName;
		} else {
			$responsible_user = SECURITY::getJwtData()->user;
		}

		$data = array(
			'fecha' => date('Y-m-d H:i:s'),
			'id_registro' => $idregistro,
			'tabla' => $tabla,
			'accion' => $accion,
			'documento' => $documento,
			'ip' => $CI->input->ip_address(),
			'observaciones' => $observations,
			'nombre_responsable' => $responsible_user,
			'update_old' => json_encode($update_old),
			'update_new' => json_encode($update_new)
		);

		$CI->load->model('GeneralModel');
		$CI->GeneralModel->saveLog($data);
	}
	/**
	 * guarda el log de un cambio en BD
	 * @param <int> $idregistro, <string> $tabla, <string> $accion
	 * @author Yeferson Sossa
	 */
	public static function save_log_change($idregistro = null, $tabla = null, $accion = null, $observations = null, $dato_anterior = null, $dato_nuevo = null, $nombre_columna = null)
	{
		if ($idregistro === null && $tabla === null && $accion === null) {
			return FALSE;
		}
		$CI = &get_instance();
		$documento = SECURITY::getJwtData()->id;
		if (isset(SECURITY::getJwtData()->lastName) && isset(SECURITY::getJwtData()->name)) {
			$responsible_user = SECURITY::getJwtData()->name . ' ' . SECURITY::getJwtData()->lastName;
		} else {
			$responsible_user = SECURITY::getJwtData()->user;
		}

		$data = array(
			'fecha' => date('Y-m-d H:i:s'),
			'id_registro' => $idregistro,
			'tabla' => $tabla,
			'accion' => $accion,
			'documento' => $documento,
			'ip' => $CI->input->ip_address(),
			'observaciones' => $observations,
			'nombre_responsable' => $responsible_user,
			'dato_anterior' => $dato_anterior,
			'dato_nuevo' => $dato_nuevo,
			'nombre_columna' => $nombre_columna
		);

		$CI->load->model('GeneralModel');
		$CI->GeneralModel->saveLog($data);
	}
	/**
	 * consume un api que maneje jwt con la misma llave que teo
	 * @param <string> $api, <string> $method, <array()> $data
	 * @return httpclient->getResults() 
	 * @author Yeferson Sossa
	 */
	public static function consume_api($api = null, $method = null, $data = null, $token = null)
	{
		/**Validamos que llegue como paramettro el $api y el $method de lo contrario return false */
		if ($api === null && $method === null) {
			return FALSE;
		}
		/**Si $data es null enviamos una data vacia */
		if ($data === null) {
			$data = array(
				'notdata' => ''
			);
		}
		$CI = &get_instance();
		if (isset($token)) {
			$headerToken = $token;
		} else {
			$headers = $CI->input->request_headers();
			$headerToken = $headers["Authorization"];
		}

		$CI->httpclient->setOptions(
			array(
				'headers' => array(
					'Authorization:' . $headerToken,
					'Content-Type: application/json',
				),
				'data' => $data,
				'url' => $api,
			)
		);
		/**validamos si el metodo es POT o GET */
		switch ($method) {
			case 'POST':
				if ($CI->httpclient->post()) {
					return $CI->httpclient->getResultsArray();
				} else {
					return $CI->httpclient->getErrorMsg();
				}
				break;
			case 'GET':
				if ($CI->httpclient->get()) {
					return $CI->httpclient->getResultsArray();
				} else {
					return $CI->httpclient->getErrorMsg();
				}
				break;
			default:
				break;
		}
	}

	public static function validateDate($date){
		$dateArray = explode('-',$date);
		$year = isset($dateArray[0]) ? $dateArray[0] : -1;
		$month = isset($dateArray[1]) ? $dateArray[1] : -1;
		$day = isset($dateArray[2]) ? $dateArray[2] : -1;
		return checkdate($month, $day, $year);
    }

	/**
	 * Funcion para mostrar lenguaje con una variable
	 * @author Yeferson Sossa
	 */
	public static function line_with_arguments($line, $swap)
	{
		return str_replace('%s', $swap, $line);
	}

	/**
	 * Check if the user has specific actions.
	 * @param array $actions An array of actions to check.
	 * @param array $finishSession. If true. Ends session and response permissions are requiered. if user does not have enough permissions
	 * @return bool Returns true if the user has any of the specified actions, otherwise returns false.
	 */
	public static function hasActions($actions, $finishSession = FALSE)
	{
		$CI = &get_instance();

		if (!is_array($actions)) {
			throw new Error('Invalid actions parameter. Expected an array.');
		}

		$userActions = SECURITY::getJwtData()->actions;

		if (empty($userActions)) {
			return false;
		}

		$adminActions = [100, 438];

		$userHasPermissions = array_intersect($adminActions, $userActions) || array_intersect($actions, $userActions);

		if($finishSession === TRUE && $userHasPermissions === FALSE){
			COMMON::sendResponse(null, '0', $CI->lang->line("text_rest_forbidden"), true, REST_Controller::HTTP_FORBIDDEN);
		}

		return $userHasPermissions;
	}

	public static function setInitValues($valuesKeys){
		ini_set($valuesKeys['key'], $valuesKeys['value']); //NOSONAR
	}

	public static function uploadFile($fileName){
		$CI = &get_instance();
		$uploadPath = sys_get_temp_dir();
		$config['upload_path'] = $uploadPath;
		$fileTypes = COMMON::get_global_config('general::extensiones_permitidas');
		$config['allowed_types'] = $fileTypes;
		$config['file_name'] = time();
		$CI->load->library('upload', $config);
		if(!$CI->upload->do_upload($fileName)){
			return [];
		}else{
			return $CI->upload->data(); 
		}
	}

	public static function getPathGlobalCARoot(){
		$path = preg_replace('/\\\\/','/', APPPATH ) . 'certificates/cacert.pem';
		return file_exists($path) ? $path: FALSE;
	}

	// Obtener la fecha de inicio y fin de la semana en la que se encuentra una fecha
	public static function obtenerFechaInicioFinDeSemana($fecha){
  
		$dateParam = $fecha;
		$week = date('w', (strtotime($dateParam) - 86400));
		$date = new DateTime($dateParam);
		$firstWeek = $date->modify("-".$week." day")->format("Y-m-d H:i:s");
		$endWeek = $date->modify("+6 day")->format("Y-m-d H:i:s");
		return array("inicio" => $firstWeek, "fin" => $endWeek);
	}

	public static function validateQueryResult($queryResult, $customMessage=""){
		if ($queryResult === false && !is_array($queryResult)){
			$CI = &get_instance();
		    $message = $customMessage != "" ? $customMessage : $CI->lang->line('error_aplication');
		    COMMON::sendResponse(null, 0, array("status" => '0', "message" => $message), TRUE, $CI::HTTP_INTERNAL_SERVER_ERROR);
	    }
		return true;
	}

	public static function activateErrorDetection(){
		
		$respuesta = self::get_global_config('jarvis::debug_errores');

		if($respuesta == 1){
			self::setInitValues(["key" => "display_errors", "value" => 1]);
			self::setInitValues(["key" => "display_startup_errors", "value" => 1]);
			error_reporting(E_ALL); //NOSONAR
		}

	}

	/**
	 * Reemplazar string que contenga letras con tildes
	 *
	 * @param  <string> con tildes
	 * @return  <string> sin tildes
	 */
	public static function replaceLetters( $str ) {
		if($str == "-"){
			$str = str_replace("-","",$str);
		}
		$str = str_replace("N/A","",$str);
		$str = str_replace("n/a","",$str);
		$str = str_replace("Ñ","ñ",$str);
		$str = str_replace("Ó","o",$str);
		$str = str_replace("Ò","o",$str);
		$str = str_replace("ó","o",$str);
		$str = str_replace("ò","o",$str);
		$str = str_replace("Í","i",$str);
		$str = str_replace("Ì","i",$str);
		$str = str_replace("í","i",$str);
		$str = str_replace("ì","i",$str);
		$str = str_replace("Á","a",$str);
		$str = str_replace("â","a",$str);
		$str = str_replace("À","a",$str);
		$str = str_replace("á","a",$str);
		$str = str_replace("à","a",$str);
		$str = str_replace("Ü","u",$str);
		$str = str_replace("Ú","u",$str);
		$str = str_replace("Ù","u",$str);
		$str = str_replace("ú","u",$str);
		$str = str_replace("ù","u",$str);
		$str = str_replace("É","e",$str);
		$str = str_replace("È","e",$str);
		$str = str_replace("é","e",$str);
		$str = str_replace("è","e",$str);
		$str = str_replace("  "," ",$str);
		$str = str_replace("\t"," ",$str);
		$str = str_replace(" "," ",$str);
		$str = str_replace(array("\n\r", "\n", "\r"), " ", $str);
		return $str;
	}

	public static function get_application_url(){
		return filter_input(INPUT_SERVER,'REQUEST_SCHEME') . "://" . filter_input(INPUT_SERVER,'HTTP_HOST');
	}

	public function json_check($data){
        json_decode($data);
        if(json_last_error() === JSON_ERROR_NONE || !$data){
            return true;
        }else {
			$CI = &get_instance();
            $CI->form_validation->set_message('json_check', '{field} '. $CI->lang->line('field::json'));
            return false;
        }
    }

}
