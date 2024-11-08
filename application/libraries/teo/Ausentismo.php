<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Ausentismo
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

            $dataAgrupation["dataTable"][$fechaDiaria]["Fecha"] = date('Y-m-d', strtotime($fechaDiaria));
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Inicio Programado"] = $inicio_turno_programado;
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Fin Programado"] = $fin_turno_programado;
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Inicio Real"] =  $inicio_turno_real;
            $dataAgrupation["dataTable"][$fechaDiaria]["Hora Fin Real"] = $fin_turno_real;
            $dataAgrupation["dataTable"][$fechaDiaria]["Ausentismo"] = $this->esDecimal($valorIndicador) ? number_format(round($valorIndicador, 2), 2) : $valorIndicador; 
            $dataAgrupation["dataTable"][$fechaDiaria]["Horas Programadas"] = $horas_programadas;
            $dataAgrupation["dataTable"][$fechaDiaria]["Horas Reales"] = $horas_conexion;
            $dataAgrupation["dataTable"][$fechaDiaria]["Llegadas Tarde"] = strtotime($inicio_turno_programado) - strtotime($inicio_turno_real) < -300 && (!empty($horas_programadas) && (!empty($horas_conexion))) ? 1 : 0;
            $dataAgrupation["dataTable"][$fechaDiaria]["Salidas Temprano"] = strtotime($fin_turno_real) -  strtotime($fin_turno_programado) < -300 && (!empty($horas_programadas) && (!empty($horas_conexion))) ? 1 : 0;
        }

        $dataTable = isset($dataAgrupation["dataTable"]) ? array_values($dataAgrupation["dataTable"]) : []; 

        return $dataTable;
    }

    public function grafica($dataChart, $typeChart){
        foreach($dataChart as $filasIndicador){
            $nombre = $filasIndicador['nombre'];
            $meta = $filasIndicador['meta'];
            $valorIndicador = $filasIndicador['valor_indicador'];
            $fechaDiaria = $filasIndicador['fecha_indicador'];

            if ($typeChart == 1) {
                $dataAgrupation["graphic"]["categories"][] = date('d', strtotime($fechaDiaria));
                $langLabel = $this->CI->lang->line("teo_indicators::label_chart_daily");
            }

            if ($typeChart == 3) {
                if(!isset($counter)){
                    $counter = 1;
                }

                $dataAgrupation["graphic"]["categories"][] = $this->CI->lang->line("teo_indicators::label_chart_week_label") . ' ' . $counter;

                $counter++;

                $langLabel = $this->CI->lang->line("teo_indicators::label_chart_week");
            }
            if ($typeChart == 2) {
                $langLabel = $this->CI->lang->line("teo_indicators::label_chart_month");

                $dataAgrupation["graphic"]["categories"][] = date('M-Y', strtotime($fechaDiaria));

            }

            $valor = $this->esDecimal($valorIndicador) ? floatval(number_format(round($valorIndicador, 2), 2)): (int)$valorIndicador;
            $dataAgrupation["graphic"]["values"][] = $valor;
            $dataAgrupation["graphic"]["meta"][] =  $this->esDecimal($meta) ? floatval(number_format(round($meta, 2), 2)): (int)($meta);
            $dataAgrupation["graphic"]["legendLabel"] = strtoupper($nombre . " ". $langLabel);
            $dataAgrupation["graphic"]["yLabel"] = $nombre . " ". $langLabel;

            $dataAgrupation["graphic"]["min"] = -0.5;
        }

        if(isset($dataAgrupation["graphic"]["series"])){
            $dataAgrupation["graphic"]["series"] = array_values($dataAgrupation["graphic"]["series"]);
        }

        return $dataAgrupation;
    }

    public function graficaLider($dataChart, $typeChart){
        foreach($dataChart as $filasIndicador){
            $nombre = $filasIndicador['nombre'];
            $meta = $filasIndicador['meta'];
            $valorIndicador = $filasIndicador['valor_indicador'];
            $fechaDiaria = $filasIndicador['fecha'];

            if ($typeChart == 1) {
                $dataAgrupation["graphic"]["categories"][] = date('d', strtotime($fechaDiaria));
                $langLabel = $this->CI->lang->line("teo_indicators::label_chart_daily");
            }

            if ($typeChart == 3) {
                if(!isset($counter)){
                    $counter = 1;
                }

                $dataAgrupation["graphic"]["categories"][] = $this->CI->lang->line("teo_indicators::label_chart_week_label") . ' ' . $counter;

                $counter++;

                $langLabel = $this->CI->lang->line("teo_indicators::label_chart_week");
            }
            if ($typeChart == 2) {

                $langLabel = $this->CI->lang->line("teo_indicators::label_chart_month");

                $dataAgrupation["graphic"]["categories"][] = date('M-Y', strtotime($fechaDiaria));

            }

            $valor = $this->esDecimal($valorIndicador) ? floatval(number_format(round($valorIndicador, 2), 2)): (int)$valorIndicador;
            $dataAgrupation["graphic"]["values"][] = $valor;
            $dataAgrupation["graphic"]["legendLabel"] = strtoupper($nombre . " ". $langLabel);
            $dataAgrupation["graphic"]["yLabel"] = $nombre . " ". $langLabel;
            $dataAgrupation["graphic"]["meta"][] =  $this->esDecimal($meta) ? floatval(number_format(round($meta, 2), 2)): (int)($meta);
            $dataAgrupation["graphic"]["min"] = -0.5;
        
        }

        if(isset($dataAgrupation["graphic"]["series"])){
            $dataAgrupation["graphic"]["series"] = array_values($dataAgrupation["graphic"]["series"]);
        }

        return $dataAgrupation;
    }

    private function esDecimal($numero) {
        return strpos($numero, '.') !== false;
    }

}
