<?php
defined('BASEPATH') || exit('No direct script access allowed');

class AdherenciaDesempeno
{

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function tablaAsesor($data){
        foreach($data as $filasIndicador){
            $valorIndicador = $filasIndicador['valor_indicador'] ?? null;
            $fechaDiaria = $filasIndicador['fecha_indicador'] ?? null;
            $inicio_turno_real = $filasIndicador['hora_inicial_real'];
            $fin_turno_real = $filasIndicador['hora_fin_real'];
            $inicio_turno_programado = $filasIndicador['inicio_programada'];
            $fin_turno_programado = $filasIndicador['fin_programada'];
            $horas_programadas = $filasIndicador['horas_programadas'];
            $horas_conexion = $filasIndicador['horas_conexion_sucesos'];
            $justificacion = $filasIndicador['justificacion'];


            $dataAgrupation["dataTable"][$fechaDiaria]["Fecha"] = date('Y-m-d', strtotime($fechaDiaria));

            $cumplimientoConexion = $horas_programadas == 0 ? 0 : ($horas_conexion/$horas_programadas * 100);

            $dataAgrupation["dataTable"][$fechaDiaria]["Adherencia"] = $valorIndicador;
            $dataAgrupation["dataTable"][$fechaDiaria]["Adherencia Desempeño"] = empty($justificacion) ? $valorIndicador : "--";
            $dataAgrupation["dataTable"][$fechaDiaria]["Justificación"] = empty($justificacion) ? "--" : $justificacion;
            $dataAgrupation["dataTable"][$fechaDiaria]["Cumplimiento Conexion"] = number_format(round($cumplimientoConexion, 2), 2);
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Inicio Programado"] = $inicio_turno_programado;
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Fin Programado"] = $fin_turno_programado;
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Inicio Real"] =  $inicio_turno_real;
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Fin Real"] = $fin_turno_real;
            $dataAgrupation["dataTable"][$fechaDiaria]["Horas Programadas"] = $horas_programadas;
            $dataAgrupation["dataTable"][$fechaDiaria]["Horas Reales"] = $horas_conexion;
            $dataAgrupation["dataTable"][$fechaDiaria]["Llegadas Tarde"] = strtotime($inicio_turno_programado) - strtotime($inicio_turno_real) < -300 && (!empty($horas_programadas) && (!empty($horas_conexion))) ? 1 : 0;
            $dataAgrupation["dataTable"][$fechaDiaria]["Salidas Temprano"] = strtotime($fin_turno_real) -  strtotime($fin_turno_programado) < -300 && (!empty($horas_programadas) && (!empty($horas_conexion))) ? 1 : 0;
        }

        return isset($dataAgrupation["dataTable"]) ? array_values($dataAgrupation["dataTable"]) : [];

    }
}