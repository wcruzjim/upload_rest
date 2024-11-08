<?php
defined('BASEPATH') || exit('No direct script access allowed');

class RetirosLibrary
{

  public function retirarEmpleados($data) {
    $this->CI =& get_instance();
    $this->CI->load->model('DescargarFormatoBaseModel');
    $fechaSolicitud = date("Y-m-d");
    $arrayRetiro = array();
    $arrayDistribucionPersonal = array();
    $arrayHistorialCambios = array();
    $arrayErrorInfo = array();
    foreach ($data as $value) {
        $fechaBajaDp = date("Y-m-01", strtotime($value['fecha_baja']));

        // Generamos la plantilla de error
        $plantillaError = array(
            'data' => json_encode($value),
            'documento' => $value['documento'],
            'endpoint' => 'retirarEmpleados_post',
            'fecha_proceso' => $fechaSolicitud,
        );

        // Generamos el arr de gh_retiros
        $this->arrGhRetiros($value, $arrayRetiro, $fechaSolicitud);

        // Validamos que exista en dp
        $this->validateExistsDp($value, $fechaBajaDp, $arrayErrorInfo, $plantillaError);

        // Generamos arr dp
        $this->arrDistribucionPersonal($value, $fechaBajaDp, $arrayDistribucionPersonal);

        // Generamos arr historial de cambios
        $this->arrHistorialCambios($value, $arrayHistorialCambios);
    }
    // Insert retiros
    $this->insertRetiros($arrayRetiro);
    
    // Insert log errores informacion empleados
    if (!empty($arrayErrorInfo)) {
        $this->generateLogsErrorInfo($arrayErrorInfo, $data);
    }
    // Actualizacion y eliminacion distribucion_personal
    $this->updateEliminateDp($arrayDistribucionPersonal, $arrayHistorialCambios);
    $datosCountFinal = array(
        "bajas_entrantes" => count($arrayRetiro),
        "bajas_con_errores" => count(array_unique(array_column($arrayErrorInfo, 'documento'))),
        "bajas_exitosas" => count($arrayDistribucionPersonal),
    );
    COMMON::sendResponse($datosCountFinal);
  }

  private function generateLogsErrorInfo($data,$parametrosEntrada){
    $documento_gestion = SECURITY::getJwtData()->id;
        $arrLogsErrores = array();
        array_push($arrLogsErrores, [
            'id_identificador' => $documento_gestion.'RETIROS',
            'ruta' => 'empleados/retiros/retirarEmpleados_post',
            'model' => 'retirosEmpleadosModel',
            'numero_error' => 1,
            'mensaje' => json_encode($data),
            'documento' => $documento_gestion,
            'fecha' => date('Y-m-d H:i:s'),
            'parametros' => json_encode($parametrosEntrada),
            'dato' => 'RETIROS'
        ]);
        $this->CI->retirosEmpleadosModel->insertLogsErroresJarvis($arrLogsErrores);
    }

  private function validateExistsDp($value, $fechaBajaDp, &$arrayErrorInfo, $plantillaError) {
      $distribucionPersonal = $this->CI->retirosEmpleadosModel->getIdDpDatos($value['documento'], $fechaBajaDp);
      if (count($distribucionPersonal) == 0) {
          $consultUltimaDistribucion = $this->CI->retirosEmpleadosModel->getUltimaDistribucion($value['documento']);
          if (count($consultUltimaDistribucion) > 0) {
            $this->CI->retirosEmpleadosModel->updateUltimaDistribucion($consultUltimaDistribucion, $fechaBajaDp);
          } else {
            $plantillaError['motivo'] = 'No se encontró distribución de personal en el mes de la baja y tampoco en meses posteriores';
            array_push($arrayErrorInfo, $plantillaError);
          }
      }
  }

  private function arrGhRetiros($value, &$arrayRetiro, $fechaSolicitud) {
    $documento_gestion = SECURITY::getJwtData()->id;
    $element = array(
        'documento' => $value['documento'],
        'id_gh_tipo_medida' => isset($value['id_gh_tipo_medida']) ? $value['id_gh_tipo_medida'] : null,
        'id_gh_motivo_medida' => isset($value['id_gh_motivo_medida']) ? $value['id_gh_motivo_medida'] : null,
        'id_gh_aspecto_medida' => isset($value['id_gh_aspecto_medida']) ? $value['id_gh_aspecto_medida'] : null,
        'fecha_alta' => isset($value['fecha_alta']) ? $value['fecha_alta'] : null,
        'fecha_baja' => $value['fecha_baja'],
        'observacion' => $this->CI->lang->line("jarvis::apis_generales::retiros_message"),
        'documento_gestion' => $documento_gestion,
        'fecha_solicitud' => $fechaSolicitud
    );

    $arrayRetiro[] = $element;
  }

  private function arrDistribucionPersonal($value, $fechaBajaDp, &$arrayDistribucionPersonal) {
    $distribucionPersonal = $this->CI->retirosEmpleadosModel->getIdDpDatos($value['documento'], $fechaBajaDp);
    array_push($arrayDistribucionPersonal, array(
        'id_dp_distribucion_personal' => $distribucionPersonal[0]['id_dp_distribucion_personal'],
        'documento' => $value['documento'],
        'id_dp_estados' => 305,
        'fecha_actual' => $fechaBajaDp,
        'documento_modificacion' => '123456789',
        'observacion_modificacion' => $this->CI->lang->line("jarvis::apis_generales::retiros_message2")
    ));
  }

  private function arrHistorialCambios($value, &$arrayHistorialCambios) {
    $mesActivoDp = COMMON::get_global_config('mes_activo_dp');
    array_push($arrayHistorialCambios, [
        'documento' => $value['documento'],
        'dato_nuevo' => 305,
        'tipo_historial' => 4,
        'campo' => 'id_dp_estados',
        'tabla' => 'dp_distribucion_personal',
        'mes_activo' => $mesActivoDp,
        'observacion' => $this->CI->lang->line("jarvis::apis_generales::retiros_message2"),
        'documento_mod' => '123456789'
    ]);
  }

  private function insertRetiros($arrayRetiro) {
    
    if (count($arrayRetiro) > 0) {
        $this->CI->retirosEmpleadosModel->insertBajaEmpleados($arrayRetiro);
        $arrayDocumentos = array_column($arrayRetiro, 'documento');
        $this->CI->retirosEmpleadosModel->deletePermisosUsuarios($arrayDocumentos);
        $this->deleteProcesosPendientes($arrayDocumentos);
        $this->exonerarProcesosDisciplinarios($arrayDocumentos);
    }
  }

  private function updateEliminateDp($arrayDistribucionPersonal, $arrayHistorialCambios) {
    if (count($arrayHistorialCambios) > 0) {
        $getHistorialCambios = historial_cambios::get_historial_cambios($arrayHistorialCambios);
    }

    if (count($arrayDistribucionPersonal) > 0) {
        $updateDistribucion = $this->CI->retirosEmpleadosModel->updateDistribution($arrayDistribucionPersonal);
        if ($updateDistribucion === FALSE) {
            COMMON::sendResponse(null, 0, $this->lang->line("baja_empleados::bot::params_info_9"), true, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }

        foreach ($arrayDistribucionPersonal as $value_actual) {
            $this->CI->retirosEmpleadosModel->deleteDistribution($value_actual['documento'], $value_actual['fecha_actual']);
        }

        if (count($getHistorialCambios['insert']) > 0) {
            historial_cambios::insert_historial_cambio($getHistorialCambios['insert']);
        }
    }
  }

  private function deleteProcesosPendientes($documentos) {
    $this->deleteMeta4CambioPasado($documentos);
    $this->deleteMeta4CambioFuturo($documentos);
    $this->deleteMeta4BajaEncargos($documentos);
    $this->deleteMeta4ReubicacionPendiente($documentos);
    $this->deleteMeta4DevolucionReubicacion($documentos);
  }

  private function deleteMeta4CambioPasado($documentos) {
    $arrUpdateEstadoAplicacion = array(
        "estado_aplicacion" => 1
    );
    $this->CI->retirosEmpleadosModel->deleteMeta4CambioPasado($arrUpdateEstadoAplicacion, $documentos);
  }

  private function deleteMeta4CambioFuturo($documentos) {
    $arrUpdateEstadoAplicacion = array(
        "estado_aplicacion" => 1
    );
    $this->CI->retirosEmpleadosModel->deleteMeta4CambioFuturo($arrUpdateEstadoAplicacion, $documentos);
  }

  private function deleteMeta4BajaEncargos($documentos) {
    $arrUpdateBajaEncargos = array(
        "baja_procesada" => 1
    );
    $this->CI->retirosEmpleadosModel->deleteMeta4BajaEncargos($arrUpdateBajaEncargos, $documentos);
  }

  private function deleteMeta4ReubicacionPendiente($documentos) {
    $arrUpdateReubicacionPendiente = array(
        "estado" => 1
    );
    $this->CI->retirosEmpleadosModel->deleteMeta4ReubicacionPendiente($arrUpdateReubicacionPendiente, $documentos);
  }

  private function deleteMeta4DevolucionReubicacion($documentos) {
    $arrUpdateDevolucionReubicacion = array(
        "devolucion" => 1
    );
    $this->CI->retirosEmpleadosModel->deleteMeta4DevolucionReubicacion($arrUpdateDevolucionReubicacion, $documentos);
  }

  private function exonerarProcesosDisciplinarios($documentos) {
    $fechaFin = COMMON::get_global_config('mes_activo_dp');
    $fechaInicio = date('Y-m-d', strtotime($fechaFin . "- 7 month"));

    $procesosExonerados = $this->CI->retirosEmpleadosModel->exonerarProcesosDisciplinarios($documentos, $fechaInicio, $fechaFin);
    if ($procesosExonerados === false) {
        COMMON::sendResponse(null, '0', $this->lang->line("Oper::model::desempeno::error_exonerar_procesos_disciplinarios"), true, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
  }  
}
