<?php

class MEUCCIHORARIOS{

    public static function getEmpIdEmisorReceptorMeucci($datosIntercambio){
      
      $CI =& get_instance();
      /**
       * Otengo informacion de MEUCCI para actualizar
      */
      $CI->load->model('EmpleadoHorarioMeucci_model');
      $emp_id_emisor = $CI->EmpleadoHorarioMeucci_model->get_emp_id_empleado($datosIntercambio['identificacion_emisor']);
      $emp_id_receptor = $CI->EmpleadoHorarioMeucci_model->get_emp_id_empleado($datosIntercambio['identificacion_receptor']);
      
      return [
        "emp_id_emisor" => $emp_id_emisor,
        "emp_id_receptor" => $emp_id_receptor
      ];
      
    }

    public static function intercambioDescansoTurnoProgramadoEntreRepresentantesMeucci($turnoProgramadoEmisor,$turnoProgramadoReceptor,$emp_id_emisor,$emp_id_receptor){

      $CI =& get_instance();
      $CI->load->model('EmpleadoHorarioMeucci_model');
      
      $id_turno_emisor_meucci = $CI->EmpleadoHorarioMeucci_model->get_id_empleadohorario($turnoProgramadoEmisor['cedula'],date('Y-m-d',strtotime($turnoProgramadoEmisor['inicio'])));
      $id_turno_receptor_meucci = $CI->EmpleadoHorarioMeucci_model->get_id_empleadohorario($turnoProgramadoReceptor['cedula'],date('Y-m-d',strtotime($turnoProgramadoReceptor['inicio'])));
      
      if(count($id_turno_emisor_meucci) > 0 && count($id_turno_receptor_meucci) > 0){
        //actualizo emp_id del turno del receptor que está en meucci por el emp_id del emisor
        $CI->EmpleadoHorarioMeucci_model->update_empleadoHorario($emp_id_emisor[0]['emp_id'],$id_turno_receptor_meucci[0]['eho_id']);

        //actualizo emp_id del turno del receptor que está en meucci por el emp_id del emisor
        $CI->EmpleadoHorarioMeucci_model->update_empleadoHorario($emp_id_receptor[0]['emp_id'],$id_turno_emisor_meucci[0]['eho_id']);
      }

    }

    public static function intercambioDescansoTurnoProgramadoExcepcionalMeucci($datosIntercambio,$fechaFinActualizarTurnoProgramado){

      $CI =& get_instance();
      $CI->load->model('EmpleadoHorarioMeucci_model');
      
      $id_turno_programado_emisor_meucci = $CI->EmpleadoHorarioMeucci_model->get_id_empleadohorario($datosIntercambio['identificacion_emisor'],$datosIntercambio['turno_inicio_receptor']);	
      $id_turno_descanso_emisor_meucci = $CI->EmpleadoHorarioMeucci_model->get_id_empleadohorario($datosIntercambio['identificacion_emisor'],$datosIntercambio['turno_inicio_emisor']);
      if(count($id_turno_programado_emisor_meucci) > 0 && count($id_turno_descanso_emisor_meucci) > 0){
        //se intercambian las fechas, el que estaba con turno ahora tiene la fecha de descanso y, y la que tenía el descanso ahora tiene el turno
        $CI->EmpleadoHorarioMeucci_model->update_solo_fecha($datosIntercambio['turno_inicio_emisor'],$fechaFinActualizarTurnoProgramado,$id_turno_programado_emisor_meucci[0]['eho_id']);
        $CI->EmpleadoHorarioMeucci_model->update_solo_fecha($datosIntercambio['turno_inicio_receptor'],$datosIntercambio['turno_inicio_receptor'],$id_turno_descanso_emisor_meucci[0]['eho_id']);
			}

    }

    public static function intercambioEntreRepresentantes($datosIntercambio,$emp_id_emisor,$emp_id_receptor){

      $CI =& get_instance();
      $CI->load->model('EmpleadoHorarioMeucci_model');
      
      $id_turno_emisor_meucci = $CI->EmpleadoHorarioMeucci_model->get_id_empleadohorario($datosIntercambio['identificacion_emisor'],$datosIntercambio['turno_inicio_emisor']);
			$id_turno_receptor_meucci = $CI->EmpleadoHorarioMeucci_model->get_id_empleadohorario($datosIntercambio['identificacion_receptor'],$datosIntercambio['turno_inicio_receptor']);
			if(count($id_turno_emisor_meucci) > 0 && count($id_turno_receptor_meucci) > 0){
				//actualizo emp_id del turno del receptor que está en meucci por el emp_id del emisor
				$CI->EmpleadoHorarioMeucci_model->update_empleadoHorario($emp_id_emisor[0]['emp_id'],$id_turno_receptor_meucci[0]['eho_id']);
				
				//actualizo emp_id del turno del receptor que está en meucci por el emp_id del emisor
				$CI->EmpleadoHorarioMeucci_model->update_empleadoHorario($emp_id_receptor[0]['emp_id'],$id_turno_emisor_meucci[0]['eho_id']);
			}

    }

    public static function intercambioExcepcional($datosIntercambio){

      $CI =& get_instance();
      $CI->load->model('EmpleadoHorarioMeucci_model');
      
      $id_turno_emisor_meucci = $CI->EmpleadoHorarioMeucci_model->get_id_empleadohorario($datosIntercambio['identificacion_emisor'],$datosIntercambio['turno_inicio_emisor']);
				
				//Como es un turno excepcional, hay que actualizar las fechas.
				$actualizar_fecha_meucci['inicio'] = $datosIntercambio['turno_inicio_receptor_a_meucci'];
				$actualizar_fecha_meucci['fin'] = $datosIntercambio['turno_fin_receptor_a_meucci'];
				$actualizar_fecha_meucci['descanso'] = $datosIntercambio['descanso_receptor'];
				if(count($id_turno_emisor_meucci) > 0 ){
					$CI->EmpleadoHorarioMeucci_model->update_fecha_empleadoHorario($actualizar_fecha_meucci,$id_turno_emisor_meucci[0]['eho_id']);
				}

    }
    
}
?>
