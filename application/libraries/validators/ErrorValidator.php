<?php

defined('BASEPATH') || exit('No direct script access allowed');

class ErrorValidator
{
	public static function errorCheck($error_handler,$typeError=null){
		$esqueleto = array(
			"linea" => '',
			"columna" => '',
			"codigo_error" => '',
			'texto_error'=> '',
			'texto_solucion' => '',
			'tipo_error' =>  '',
			'dato' =>  '',
			'habilitado' =>  ''
		);
		
		if(!isset($error_handler) || !is_array($error_handler) ) {
			throw new Exception('No es Array o Vacio');
		}

		if(isset($error_handler['mensaje']) && is_numeric($error_handler['mensaje'])){
			$esqueleto['id_maestro_errores'] = $error_handler['mensaje'];
		}else{
			$esqueleto['codigo_error'] = $error_handler['mensaje'];
		}

		if(isset($error_handler['linea'])){
			$esqueleto['linea'] = $error_handler['linea'];
		}
		if(isset($error_handler['columna'])){
			$esqueleto['columna'] = $error_handler['columna'];
		}
		if(isset($error_handler['solucion'])){
			$esqueleto['texto_solucion'] = $error_handler['solucion'];
		}
		if($typeError){
			$mapper = ['warning'=>'1','info'=>'2','debug'=>'3','trace'=>'4','fatal'=>'5'];
			$esqueleto['tipo_error'] = isset($mapper[$typeError]) ? $mapper[$typeError] : '6';
		}else{
			$esqueleto['tipo_error'] = self::errorCheckTipo($error_handler);
		}
			
		if(isset($error_handler['dato'])){
			$esqueleto['dato'] = $error_handler['dato'];
		}

		return $esqueleto;
     }

	public static function errorCheckTipo($error_handler){
		$variable = '';
		$error_handler['level'] = strtolower($error_handler['level']);

		if(isset($error_handler['level'])){
			switch ($error_handler['level']) {
				case 'warning':
					$variable = '1';
					break;
				case 'info':
					$variable = '2';
					break;
				case 'debug':
					$variable = '3';
					break;
				case 'trace':
					$variable = '4';
					break;
				case 'fatal':
					$variable = '5';
					break;
				default:
					$variable = '6';
					break;
			}
			return $variable;
		}
		return $variable;
	}

    public static function checkListErrores($chain=null){
		 $CI =& get_instance();
		 $CI->load->model('ErroresModel');
		 $error_totales=[];
		 $error_handler = $chain;

		 $errotemp= $CI->ErroresModel->postDatosAlmacenadosErrores();
		 
		 $error_totales = array_map(function($error) use ($errotemp){
			$elemento_found = array_filter($errotemp,function($error_search) use ($error){
				return $error_search['codigo_error'] == $error['codigo_error'];
			});
			$elemento_found = array_values($elemento_found);
			if(count($elemento_found)==0){
				$elemento_found = [['error'=>$error['codigo_error'],'habilitado'=>1]];
			}

			$error['texto_solucion'] = isset($error['texto_solucion']) ? $error['texto_solucion'] : "No disponible";

			$resultado = array();
			
			if(!empty($elemento_found)){
				$elemento_found[0]['texto_solucion'] = isset($elemento_found[0]['texto_solucion']) ? $elemento_found[0]['texto_solucion'] : "NA";
				$resultado = array(
					"linea" =>  strlen( $error['linea'])  > 0 ? $error['linea'] : $error['linea'],
					"columna" =>  strlen( $error['columna'])  > 0 ? $error['columna'] : $error['columna'],
					"codigo_error" => $error['codigo_error'],
					"error" => $elemento_found[0]['error'],
					'texto_solucion' => strlen( $error['texto_solucion']) > 0 ? $error['texto_solucion']: $elemento_found[0]['texto_solucion'],
					'tipo_error' =>  strlen( $error['tipo_error']) > 0 ? $error['tipo_error']: $elemento_found[0]['tipo_error'],
					'dato' =>  $error['dato'],
					'habilitado' =>  $elemento_found[0]['habilitado']
				);
			}
			return $resultado;
			
		},$error_handler);
		
		$error_totales = array_filter($error_totales, function($error){
			return isset($error['linea']);  
		});

		$error_totales = array_values($error_totales);
		$data['errores'] = $error_totales;
		
		$temp_comprobacion = array_column($error_totales, 'tipo_error');
		$error_fatales = in_array('3', $temp_comprobacion);
		$error_informativos = in_array('2', $temp_comprobacion);
		$error_correcto = in_array('1', $temp_comprobacion);

		return (array(
			'error_totales'=>$error_totales,
			'error_fatales'=>$error_fatales,
			'error_informativos'=>$error_informativos,
			'error_correcto'=>$error_correcto
		));
    }

	public static function generateFinalErrors($dataError){
		$countError1 = [];
        $countError2 = [];
        $countError3 = [];

        foreach ($dataError as $error) {
            if ($error['tipo_error'] == 1) {
                array_push($countError1, $error);
            }elseif ($error['tipo_error'] == 2) {
                array_push($countError2, $error);
            }else{
                array_push($countError3, $error);
            }
        }

        $arrayResponse = (array(
            'error_totales'=>$dataError,
            'error_fatales'=> isset($countError1) ? count($countError1) : 0,
            'error_informativos'=> isset($countError2) ? count($countError2) : 0,
            'error_correcto'=> isset($countError3) ? count($countError3) : 0
        ));

        return $arrayResponse;
	}

}
