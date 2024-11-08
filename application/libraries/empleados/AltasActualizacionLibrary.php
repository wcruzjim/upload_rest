<?php
defined('BASEPATH') || exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

class AltasActualizacionLibrary
{

    public function insertDataDb($data){
        $this->CI =& get_instance();
        $this->CI->load->model("empleados/altasEmpleadosModel");
        $arrDatosGenerales = array();
        $arrDistribucionPersonal = array();
        $arrActualizacionDatos = array();
        foreach ($data as $field) {
            //generamos insert de datos generales 
            array_push($arrDatosGenerales, $this->generateInsertDatosGenerales($field));
            //generamos insert de de distribucion personal
            array_push($arrDistribucionPersonal,  $this->generateInsertDistribucionPersonal($field));
            //generamos insert de actualizacion de datos
            array_push($arrActualizacionDatos,  $this->generateInsertActualizacionDatos($field));
        }
       
        //extraemos los documentos a insertar en los datos generales para extraer el id generado de datos generales
        $documentosAInsertar = array_column($arrDatosGenerales,'documento');
        //insertamos en datos generales
        $insertDatosGenerales = $this->CI->altasEmpleadosModel->insertDatosGenerales($arrDatosGenerales);
        //consultamos el id generado por datos generales
        $consultIdGenerado = $this->CI->altasEmpleadosModel->consultIdDatosGenerales($documentosAInsertar);

        //actualizamos el id datos generales en el insert de distribucion personal
        foreach ($consultIdGenerado as &$idDatosGenerales) {
            foreach ($arrDistribucionPersonal as &$arrDp) {
                if ($idDatosGenerales['documento'] === $arrDp['documento']) {
                    $arrDp['id_dp_datos_generales'] = $idDatosGenerales['id_dp_datos_generales'];
                }
            }
        }
        $insertDpDistribucionPersonal = $this->CI->altasEmpleadosModel->insertDistribucionPersonal($arrDistribucionPersonal);
        $insertActualizacionDatos = $this->CI->altasEmpleadosModel->insertActualizacionDatos($arrActualizacionDatos);

        $arrDataFinal = [
            'quantity_insert_dp_personal' => $insertDpDistribucionPersonal,
            'quantity_insert_dp_datos_generales' => $insertDatosGenerales,
            'quantity_insert_actualizacion_datos' => $insertActualizacionDatos
        ];

        COMMON::sendResponse($arrDataFinal);
    }

    public function insertActualizaciones($data){
        $this->CI =& get_instance();
        $this->CI->load->model("empleados/altasEmpleadosModel"); 

        //insertamos las actualizacion en actualizacion de datos
        $this->actualizacionDatos($data);
        //insertamos las actualizacion en datos generales
        $this->datosGeneralesActualizacion($data);
        //insertamos las actualizacion en dp
        $this->distribucionPersonalActualizacion($data);

        $response = [
            "message" => $this->CI->lang->line("jarvis::apis_generales::actualizacion_ok"),
        ];

        COMMON::sendResponse($response);
    }

    private function generateInsertActualizacionDatos($data){
        return [
            "documento" => $data['documento'],
            "telefono1" => isset($data['telefono1']) ? $data['telefono1'] : null,
            "celular1" => isset($data['celular1']) ? $data['celular1'] : null,
            "celular2" => isset($data['celular2']) ? $data['celular2'] : null,
            "email_personal" => isset($data['email_personal']) ? $data['email_personal'] : null,
            "email_corporativo" => isset($data['email_corporativo']) ? $data['email_corporativo'] : null,
            "nivel_academico" => isset($data['nivel_academico']) ? $data['nivel_academico'] : 1,
            "contacto_emergencia" => isset($data['contacto_emergencia']) ? $data['contacto_emergencia'] : null,
            "celular_emergencia" => isset($data['celular_emergencia']) ? $data['celular_emergencia'] : null,
            "id_departamento" => isset($data['id_departamento_origen']) ? $data['id_departamento_origen'] : 1,
            "id_municipio" => isset($data['id_ciudad_origen']) ? $data['id_ciudad_origen'] : 1,
            "id_zona" => isset($data['id_zona']) ? $data['id_zona'] : 21,
            "id_barrio" => isset($data['id_barrio']) ? $data['id_barrio'] : 1,
            "direccion" => isset($data['direccion']) ? $data['direccion'] : null,
            "direccion_temporal" => isset($data['direccion_temporal']) ? $data['direccion_temporal'] : null,
            "estado_ruta" => isset($data['estado_ruta']) ? $data['estado_ruta'] : 0,
            "vehiculo" => isset($data['vehiculo']) ? $data['vehiculo'] : 0,
            "hijos" => isset($data['hijos']) ? $data['hijos'] : 0,
            "latitud" => isset($data['latitud']) ? $data['latitud'] : null,
            "longitud" => isset($data['longitud']) ? $data['longitud'] : null,
            "pais_procedencia" => isset($data['pais_procedencia']) ? $data['pais_procedencia'] : 0,
            "discapacidad" => isset($data['discapacidad']) ? $data['discapacidad'] : 'NINGUNA',
            "grupo_etnicos" => isset($data['grupo_etnicos']) ? $data['grupo_etnicos'] : 0,
            "genero" => isset($data['id_dp_genero']) ? $data['id_dp_genero'] : 0,
            "estrato" => isset($data['estrato']) ? $data['estrato'] : null,
        ];
    }

    

    private function generateInsertDatosGenerales($data){
        return [
            "id_dp_solicitudes" => isset($data['id_dp_solicitudes']) ? $data['id_dp_solicitudes'] : 0,
            "id_tipo_solicitud" => isset($data['id_tipo_solicitud']) ? $data['id_tipo_solicitud'] : null,
            "documento" => $data['documento'],
            "nombre_completo" => $data['nombre_completo'],
            "primer_nombre" => $data['primer_nombre'],
            "segundo_nombre" => isset($data['segundo_nombre']) ? $data['segundo_nombre'] : null,
            "primer_apellido" => $data['primer_apellido'],
            "segundo_apellido" => isset($data['segundo_apellido']) ? $data['segundo_apellido'] : null,
            "id_departamento_origen" => $data['id_departamento_origen'],
            "id_ciudad_origen" => $data['id_ciudad_origen'],
            "id_dp_nacionalidad" => $data['id_dp_nacionalidad'],
            "id_dp_pais" => $data['id_dp_pais'],
            "id_dp_idioma_nativo" => $data['id_dp_idioma_nativo'],
            "id_dp_genero" => $data['id_dp_genero'],
            "id_dp_sexo" => $data['id_dp_sexo'],
            "id_gh_eps" => isset($data['id_gh_eps']) ? $data['id_gh_eps'] : 0,
            "id_dp_arl" => isset($data['id_dp_arl']) ? $data['id_dp_arl'] : 0,
            "id_dp_fondo_cesantias" => isset($data['id_dp_fondo_cesantias']) ? $data['id_dp_fondo_cesantias'] : 0,
            "id_dp_caja_compensacion" => isset($data['id_dp_caja_compensacion']) ? $data['id_dp_caja_compensacion'] : 0,
            "fecha_nacimiento" => $data['fecha_nacimiento'],
            "id_dp_sede" => isset($data['id_dp_sede']) ? $data['id_dp_sede'] : 9,
            "id_dp_disponibilidad_lv" => isset($data['id_dp_disponibilidad_lv']) ? $data['id_dp_disponibilidad_lv'] : 0,
            "id_dp_disponibilidad_s" => isset($data['id_dp_disponibilidad_s']) ? $data['id_dp_disponibilidad_s'] : 0,
            "id_dp_disponibilidad_df" => isset($data['id_dp_disponibilidad_df']) ? $data['id_dp_disponibilidad_df'] : 0,
            "id_dp_sub_personal" => isset($data['id_dp_sub_personal']) ? $data['id_dp_sub_personal'] : 0,
            "id_dp_ceco_nomina" => isset($data['id_dp_ceco_nomina']) ? $data['id_dp_ceco_nomina'] : 0,
            "id_dp_ceco_nomina2" => isset($data['id_dp_ceco_nomina2']) ? $data['id_dp_ceco_nomina2'] : 0,
            "id_dp_tipo_vinculacion" => isset($data['id_dp_tipo_vinculacion']) ? $data['id_dp_tipo_vinculacion'] : 0,
            "id_sociedad" => isset($data['id_sociedad']) ? $data['id_sociedad'] : null,
            "id_tipo_rh" => isset($data['id_tipo_rh']) ? $data['id_tipo_rh'] : null,
            "fecha_alta_distribucion" => isset($data['fecha_alta_distribucion']) ? $data['fecha_alta_distribucion'] : null,
            "fecha_alta" => $data['fecha_alta'],
            "fecha_inicio_contrato" => isset($data['fecha_inicio_contrato']) ? $data['fecha_inicio_contrato'] : null,
            "fecha_fin_contrato" => isset($data['fecha_fin_contrato']) ? $data['fecha_fin_contrato'] : null,
            "id_modificador_fecha_ingreso" => isset($data['id_modificador_fecha_ingreso']) ? $data['id_modificador_fecha_ingreso'] : 0,
            "id_dp_tipo_documento" => isset($data['id_dp_tipo_documento']) ? $data['id_dp_tipo_documento'] : 0,
            "aplica_teletrabajo" => isset($data['aplica_teletrabajo']) ? $data['aplica_teletrabajo'] : 0,
            "estado_condicion_medica" => isset($data['estado_condicion_medica']) ? $data['estado_condicion_medica'] : 0,
            "contratacion" => isset($data['contratacion']) ? $data['contratacion'] : NULL,
            "documento_contratacion" => isset($data['documento_contratacion']) ? $data['documento_contratacion'] : null,
            "fecha_contratacion" => isset($data['fecha_contratacion']) ? $data['fecha_contratacion'] : null,
            "compensacion" => isset($data['compensacion']) ? $data['compensacion'] : 0,
            "documento_compensacion" => isset($data['documento_compensacion']) ? $data['documento_compensacion'] : null,
            "fecha_compensacion" => isset($data['fecha_compensacion']) ? $data['fecha_compensacion'] : null,
            "documento_profesional" => isset($data['documento_profesional']) ? $data['documento_profesional'] : null,
            "area_personal" => isset($data['area_personal']) ? $data['area_personal'] : null,
            "id_empleado_meta4" => $data['id_empleado_meta4'],
            "fecha_ingreso_meta4" => $data['fecha_ingreso_meta4'],
            "origen" => $data['origen'],
        ];
    }


    private function generateInsertDistribucionPersonal($data){
        $fechaActual = date('Y-m-01', strtotime($data['fecha_actual']));
        return [
            "documento" => $data['documento'],
            "codigo_sap" => isset($data['codigo_sap']) ? $data['codigo_sap'] : 0,
            "fecha_actual" => $fechaActual,
            "cod_pcrc" => $data['cod_pcrc'],
            "id_dp_centros_costos" => $data['id_dp_centros_costos'],
            "id_dp_centros_costos_adm" => $data['id_dp_centros_costos_adm'],
            "documento_jefe" => $data['documento_jefe'],
            "documento_responsable" => $data['documento_responsable'],
            "tipo_distribucion" => $data['id_fore_tipo_distribucion'],
            "unidad_organizativa" => $data['unidad_organizativa'],
            "fecha_conex_ultimo_pcrc" => isset($data['fecha_conex_ultimo_pcrc']) ? $data['fecha_conex_ultimo_pcrc'] : null,
            "id_dp_estado_pcrc" => isset($data['id_dp_estado_pcrc']) ? $data['id_dp_estado_pcrc'] : 0,
            "id_dp_cargos" => $data['id_dp_cargos'],
            "id_dp_estados" => $data['id_dp_estados'],
            "id_dp_datos_generales" => 0,
            "cargos_encargo" => isset($data['cargos_encargo']) ? $data['cargos_encargo'] : 0,
            "centros_costos_encargo" => isset($data['centros_costos_encargo']) ? $data['centros_costos_encargo'] : 0,
            "unidad_organizativa_antes_encargo" => isset($data['unidad_organizativa_antes_encargo']) ? $data['unidad_organizativa_antes_encargo'] : 0,
            "tipo_distribucion_antes_encargo" => isset($data['tipo_distribucion_antes_encargo']) ? $data['tipo_distribucion_antes_encargo'] : 0,
            "origen" => $data['origen'],
            "horas_semanales" => isset($data['horas_semanales']) ? $data['horas_semanales'] : null,
            "part_time" => isset($data['part_time']) ? $data['part_time'] : 0,
            "trabaja_festivos" => isset($data['trabaja_festivos']) ? $data['trabaja_festivos'] : 0,
        ];
    }

    private function actualizarDatosComunes($datos, $resultArray, $tabla) {
        $actualizacionArray = [];
        $consultHistorialCambiosCampos = $this->CI->altasEmpleadosModel->consultHistorialCambiosCampos();
        
        $arrHistorialCambio = array();
        foreach ($datos as $dato) {
            $tempArray = [
                'documento' => $dato['documento']
            ];
    
            foreach ($resultArray as $field) {
                if (isset($dato[$field]) && $field != 'documento') {
                    $tempArray[$field] = $dato[$field];
                    if (count($consultHistorialCambiosCampos) > 0) {
                        // Buscar el índice donde se encuentra el campo en el historial de cambios
                        $index = array_search($field, array_column($consultHistorialCambiosCampos, 'campo'));
                        if ($index !== false) {
                            // Obtener el id_dp_historial_tipo_cambios correspondiente al campo
                            $idHistorialTipoCambio = $consultHistorialCambiosCampos[$index]['id_dp_historial_tipo_cambios'];
                            if($idHistorialTipoCambio != null){
                                $datoAnterior = $this->CI->altasEmpleadosModel->consultDatoAnterior($tabla, $field, $dato['documento']);
                                if(count($datoAnterior) > 0){
                                    array_push($arrHistorialCambio,$this->generateArrHistorialCambio($dato['documento'], $idHistorialTipoCambio,$dato[$field], $datoAnterior[0][$field]));
                                }
                            }
                        }
                    }
                }
            }
    
            if (count($tempArray) > 1) {
                $actualizacionArray[] = $tempArray;
            }
        }
    
        if (count($actualizacionArray) > 0) {
            $this->CI->altasEmpleadosModel->actualizacionDatosRecibed($actualizacionArray, $tabla);
            if(count($arrHistorialCambio) > 0){
                $this->CI->altasEmpleadosModel->insertHistorialCambios($arrHistorialCambio);    
            }
        }
    
        return 0;
    }

    private function generateArrHistorialCambio($documento, $idHistorialTipoCambio, $cambioRealizado, $datoAnterior){
        $documentoGestion = SECURITY::getJwtData()->id;
        return [
            'documento' => $documento,
            'id_dp_historial_tipo_cambios' => $idHistorialTipoCambio,
            'cambio_realizado' => $cambioRealizado,
            'dato_anterior' => $datoAnterior,
            'observacion' => 'Actualizacion desde end-point actualizarEmpleados JARVIS',
            'fecha_inicio' => date('Y-m-d'),
            'documento_modificacion' => $documentoGestion
        ];
    }

    
    private function actualizacionDatos($datos) {
        $resultArray = [
            "documento", "telefono1", "celular1", "celular2", "email_personal", "email_corporativo",
            "nivel_academico", "contacto_emergencia", "celular_emergencia", "id_zona",
            "id_barrio", "direccion", "direccion_temporal", "estado_ruta", "vehiculo",
            "hijos", "latitud", "longitud", "pais_procedencia", "discapacidad", "grupo_etnicos",
            "genero", "estrato",
        ];
    
        return $this->actualizarDatosComunes($datos, $resultArray, 'dp_actualizacion_datos');
    }
    
    private function datosGeneralesActualizacion($datos) {
        $resultArray = [
            "id_dp_solicitudes", "id_tipo_solicitud", "documento", "nombre_completo",
            "primer_nombre", "segundo_nombre", "primer_apellido", "segundo_apellido",
            "id_departamento_origen", "id_ciudad_origen", "id_dp_nacionalidad",
            "id_dp_pais", "id_dp_idioma_nativo", "id_dp_genero", "id_dp_sexo",
            "id_gh_eps", "id_dp_arl", "id_dp_fondo_cesantias", "id_dp_caja_compensacion",
            "fecha_nacimiento", "id_dp_sede", "id_dp_disponibilidad_lv", "id_dp_disponibilidad_s",
            "id_dp_disponibilidad_df", "id_dp_sub_personal", "id_dp_ceco_nomina",
            "id_dp_ceco_nomina2", "id_dp_tipo_vinculacion", "id_sociedad", "id_tipo_rh",
            "fecha_alta_distribucion", "fecha_alta", "fecha_inicio_contrato", "fecha_fin_contrato",
            "id_modificador_fecha_ingreso", "id_dp_tipo_documento", "aplica_teletrabajo",
            "estado_condicion_medica", "contratacion", "documento_contratacion", "fecha_contratacion",
            "compensacion", "documento_compensacion", "fecha_compensacion", "documento_profesional",
            "area_personal", "id_empleado_meta4", "fecha_ingreso_meta4", "origen",
        ];
    
        return $this->actualizarDatosComunes($datos, $resultArray, 'dp_datos_generales');
    }
    
    private function distribucionPersonalActualizacion($datos) {
        $resultArray = [
            "documento", "codigo_sap", "fecha_actual", "cod_pcrc", "id_dp_centros_costos",
            "id_dp_centros_costos_adm", "documento_jefe", "documento_responsable",
            "tipo_distribucion", "unidad_organizativa", "fecha_conex_ultimo_pcrc",
            "id_dp_estado_pcrc", "id_dp_cargos", "id_dp_estados", "id_dp_datos_generales",
            "cargos_encargo", "centros_costos_encargo", "unidad_organizativa_antes_encargo",
            "tipo_distribucion_antes_encargo", "origen", "horas_semanales", "part_time",
            "trabaja_festivos",
        ];
    
        return $this->actualizarDatosComunes($datos, $resultArray, 'dp_distribucion_personal');
    }

  

    public function consultDataBdAComparar(){
        $this->CI =& get_instance();
        $this->CI->load->model("empleados/altasEmpleadosModel");

        $dpPcrc = $this->CI->altasEmpleadosModel->getDpPcrc();
        $arrDataDbOk = array();
        if($dpPcrc === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd1")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpPcrc) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd2")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_pcrc'] = $dpPcrc;

        $dpPcrcAdmin = $this->CI->altasEmpleadosModel->getDpPcrcAdmin();
        if($dpPcrcAdmin === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd3")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpPcrcAdmin) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd4")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_pcrc_admin'] = $dpPcrcAdmin;

        $dpCentrosCostosOper = $this->CI->altasEmpleadosModel->getDpCentrosCostosOper();
        if($dpCentrosCostosOper === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd5")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpCentrosCostosOper) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd6")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_centros_costos'] = $dpCentrosCostosOper;

        $dpCentrosCostosAdmin = $this->CI->altasEmpleadosModel->getDpCentrosCostosAdmin();
        if($dpCentrosCostosAdmin === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd7")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpCentrosCostosAdmin) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd8")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_centros_costos_adm'] = $dpCentrosCostosAdmin;

        $foreTipoDistribucion = $this->CI->altasEmpleadosModel->getForeTipoDistribucion();
        if($foreTipoDistribucion === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd9")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($foreTipoDistribucion) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd10")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['fore_tipo_distribucion'] = $foreTipoDistribucion;

        $foreTipoDistribucionEncargo = $this->CI->altasEmpleadosModel->getForeTipoDistribucionEncargo();
        if($foreTipoDistribucionEncargo === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd9")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($foreTipoDistribucionEncargo) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd10")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['fore_tipo_distribucion_encargo'] = $foreTipoDistribucionEncargo;
        
        $dpUnidadesOrganizativas = $this->CI->altasEmpleadosModel->getDpUnidadesOrganizativas();
        if($dpUnidadesOrganizativas === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd11")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpUnidadesOrganizativas) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd12")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_unidades_organizativas'] = $dpUnidadesOrganizativas;

        $dpUnidadesOrganizativasEncargo = $this->CI->altasEmpleadosModel->getDpUnidadesOrganizativasEncargo();
        if($dpUnidadesOrganizativasEncargo === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd11")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpUnidadesOrganizativasEncargo) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd12")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_unidades_organizativas_encargo'] = $dpUnidadesOrganizativasEncargo;

        $dpCargos = $this->CI->altasEmpleadosModel->getDpCargos();
        if($dpCargos === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd13")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpCargos) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd14")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_cargos'] = $dpCargos;

        $dpCargosEncargo = $this->CI->altasEmpleadosModel->getDpCargosEncargo();
        if($dpCargosEncargo === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd13")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpCargosEncargo) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd14")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_cargos_encargo'] = $dpCargosEncargo;

        $plataformasOrigenDatos = $this->CI->altasEmpleadosModel->getPlataformasOrigenDatos();
        if($plataformasOrigenDatos === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd15")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($plataformasOrigenDatos) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd16")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['plataformas_origen_datos'] = $plataformasOrigenDatos;

        $dpDepartamentos = $this->CI->altasEmpleadosModel->getDpDepartamentos();
        if($dpDepartamentos === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd17")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpDepartamentos) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd18")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_departamentos'] = $dpDepartamentos;

        $dpMunicipios = $this->CI->altasEmpleadosModel->getDpMunicipios();
        if($dpMunicipios === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd19")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpMunicipios) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd20")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_municipios'] = $dpMunicipios;

        $dpNacionalidad = $this->CI->altasEmpleadosModel->getDpNacionalidad();
        if($dpNacionalidad === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd21")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpNacionalidad) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd22")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_nacionalidades'] = $dpNacionalidad;

        $dpPaises = $this->CI->altasEmpleadosModel->getDpPaises();
        if($dpPaises === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd23")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpPaises) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd24")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_paises'] = $dpPaises;

        $dpPaises = $this->CI->altasEmpleadosModel->getDpPaisesProcedencia();
        if($dpPaises === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd23")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpPaises) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd24")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_paises_procedencia'] = $dpPaises;

        $dpIdiomaNativo = $this->CI->altasEmpleadosModel->getDpIdiomaNativo();
        if($dpIdiomaNativo === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd25")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpIdiomaNativo) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd26")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_idioma_nativo'] = $dpIdiomaNativo;

        $dpGenero = $this->CI->altasEmpleadosModel->getDpGenero();
        if($dpGenero === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd27")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpGenero) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd28")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_genero'] = $dpGenero;

        $dpSexo = $this->CI->altasEmpleadosModel->getDpSexo();
        if($dpSexo === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd29")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpSexo) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd30")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_sexo'] = $dpSexo;

        $dpNivelesAcademicos = $this->CI->altasEmpleadosModel->getDpNivelesAcademicos();
        if($dpNivelesAcademicos === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd31")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpNivelesAcademicos) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd32")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_niveles_academicos'] = $dpNivelesAcademicos;

        $dpDiscapacidad = $this->CI->altasEmpleadosModel->getDpDiscapacidad();
        if($dpDiscapacidad === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd33")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpDiscapacidad) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd34")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_discapacidad'] = $dpDiscapacidad;

        $dpEstados = $this->CI->altasEmpleadosModel->getDpEstados();
        if($dpEstados === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd35")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpEstados) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd36")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_estados'] = $dpEstados;

        $ghEps = $this->CI->altasEmpleadosModel->ghEps();
        if($ghEps === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd37")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($ghEps) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd38")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['gh_eps'] = $ghEps;

        $dpArl = $this->CI->altasEmpleadosModel->dpArl();
        if($dpArl === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd39")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpArl) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd40")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_arls'] = $dpArl;

        $dpFondoCensatias = $this->CI->altasEmpleadosModel->dpFondoCensatias();
        if($dpFondoCensatias === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd41")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpFondoCensatias) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd42")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_fondo_cesantias'] = $dpFondoCensatias;

        $dpCajaCompensacion = $this->CI->altasEmpleadosModel->dpCajaCompensacion();
        if($dpCajaCompensacion === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd43")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpCajaCompensacion) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd44")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_caja_compensacion'] = $dpCajaCompensacion;

        $dpSedes = $this->CI->altasEmpleadosModel->dpSedes();
        if($dpSedes === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd45")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpSedes) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd46")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_sedes'] = $dpSedes;

        $dpSubdivisionPersonal = $this->CI->altasEmpleadosModel->dpSubdivisionPersonal();
        if($dpSubdivisionPersonal === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd47")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpSubdivisionPersonal) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd48")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_subdivision_personal'] = $dpSubdivisionPersonal;

        $dpTipoVinculacion = $this->CI->altasEmpleadosModel->dpTipoVinculacion();
        if($dpTipoVinculacion === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd49")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpTipoVinculacion) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd50")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_tipo_vinculacion'] = $dpTipoVinculacion;

        $meta4CodigoSociedad = $this->CI->altasEmpleadosModel->meta4CodigoSociedad();
        if($meta4CodigoSociedad === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd51")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($meta4CodigoSociedad) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd52")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['meta4_codigo_sociedad'] = $meta4CodigoSociedad;

        $dpTipoDocumento = $this->CI->altasEmpleadosModel->dpTipoDocumento();
        if($dpTipoDocumento === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd53")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpTipoDocumento) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd54")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_tipo_documento'] = $dpTipoDocumento;

        $dpEstadoModalidadTrabajo = $this->CI->altasEmpleadosModel->dpEstadoModalidadTrabajo();
        if($dpEstadoModalidadTrabajo === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd55")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpEstadoModalidadTrabajo) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd56")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_estado_modalidad_trabajo'] = $dpEstadoModalidadTrabajo;

        $dpEstadoCondicionMedica = $this->CI->altasEmpleadosModel->dpEstadoCondicionMedica();
        if($dpEstadoCondicionMedica === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd57")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($dpEstadoCondicionMedica) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd58")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['dp_estado_condicion_medica'] = $dpEstadoCondicionMedica;

        $meta4TipoRh = $this->CI->altasEmpleadosModel->meta4TipoRh();
        if($meta4TipoRh === FALSE){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd57")), TRUE, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(count($meta4TipoRh) == 0){
            COMMON::sendResponse(null, 0, array("status" => '0',"message" => $this->CI->lang->line("jarvis::apis_generales::error_bd58")), TRUE, REST_Controller::HTTP_BAD_REQUEST);
        }
        $arrDataDbOk['meta4_tipo_rh'] = $meta4TipoRh;

        return $arrDataDbOk;
    }

    public function erroresCampos() {
        $errores = array();
        $errores['documento'] = array();
        $errores['codigo_sap'] = array();
        $errores['fecha_actual'] = array();
        $errores['cod_pcrc'] = array();
        $errores['id_dp_centros_costos'] = array();
        $errores['id_dp_centros_costos_adm'] = array();
        $errores['documento_jefe'] = array();
        $errores['documento_responsable'] = array();
        $errores['id_fore_tipo_distribucion'] = array();
        $errores['unidad_organizativa'] = array();
        $errores['fecha_conex_ultimo_pcrc'] = array();
        $errores['id_dp_estado_pcrc'] = array();
        $errores['id_dp_cargos'] = array();
        $errores['id_dp_estados'] = array();
        $errores['cargos_encargo'] = array();
        $errores['centros_costos_encargo'] = array();
        $errores['unidad_organizativa_antes_encargo'] = array();
        $errores['tipo_distribucion_antes_encargo'] = array();
        $errores['origen'] = array();
        $errores['horas_semanales'] = array();
        $errores['part_time'] = array();
        $errores['trabaja_festivos'] = array();
        $errores['id_dp_solicitudes'] = array();
        $errores['id_tipo_solicitud'] = array();
        $errores['nombre_completo'] = array();
        $errores['primer_nombre'] = array();
        $errores['segundo_nombre'] = array();
        $errores['primer_apellido'] = array();
        $errores['segundo_apellido'] = array();
        $errores['id_departamento_origen'] = array();
        $errores['id_ciudad_origen'] = array();
        $errores['id_dp_nacionalidad'] = array();
        $errores['id_dp_pais'] = array();
        $errores['id_dp_idioma_nativo'] = array();
        $errores['id_dp_genero'] = array();
        $errores['id_dp_sexo'] = array();
        $errores['id_gh_eps'] = array();
        $errores['id_dp_arl'] = array();
        $errores['id_dp_fondo_cesantias'] = array();
        $errores['id_dp_caja_compensacion'] = array();
        $errores['fecha_nacimiento'] = array();
        $errores['id_dp_sede'] = array();
        $errores['id_dp_disponibilidad_lv'] = array();
        $errores['id_dp_disponibilidad_s'] = array();
        $errores['id_dp_disponibilidad_df'] = array();
        $errores['id_dp_sub_personal'] = array();
        $errores['id_dp_ceco_nomina'] = array();
        $errores['id_dp_ceco_nomina2'] = array();
        $errores['id_dp_tipo_vinculacion'] = array();
        $errores['id_sociedad'] = array();
        $errores['id_tipo_rh'] = array();
        $errores['fecha_alta_distribucion'] = array();
        $errores['fecha_alta'] = array();
        $errores['fecha_inicio_contrato'] = array();
        $errores['fecha_fin_contrato'] = array();
        $errores['id_modificador_fecha_ingreso'] = array();
        $errores['id_dp_tipo_documento'] = array();
        $errores['aplica_teletrabajo'] = array();
        $errores['estado_condicion_medica'] = array();
        $errores['contratacion'] = array();
        $errores['documento_contratacion'] = array();
        $errores['fecha_contratacion'] = array();
        $errores['compensacion'] = array();
        $errores['documento_compensacion'] = array();
        $errores['fecha_compensacion'] = array();
        $errores['documento_profesional'] = array();
        $errores['area_personal'] = array();
        $errores['id_empleado_meta4'] = array();
        $errores['fecha_ingreso_meta4'] = array();
        $errores['telefono1'] = array();
        $errores['celular1'] = array();
        $errores['celular2'] = array();
        $errores['email_personal'] = array();
        $errores['email_corporativo'] = array();
        $errores['nivel_academico'] = array();
        $errores['contacto_emergencia'] = array();
        $errores['celular_emergencia'] = array();
        $errores['id_zona'] = array();
        $errores['id_barrio'] = array();
        $errores['direccion'] = array();
        $errores['direccion_temporal'] = array();
        $errores['estado_ruta'] = array();
        $errores['vehiculo'] = array();
        $errores['hijos'] = array();
        $errores['latitud'] = array();
        $errores['longitud'] = array();
        $errores['pais_procedencia'] = array();
        $errores['discapacidad'] = array();
        $errores['grupo_etnicos'] = array();
        $errores['estrato'] = array();
        return $errores;
    }
}
