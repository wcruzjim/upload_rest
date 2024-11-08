<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Aht
{

    public function __construct() {
        $this->CI =& get_instance();
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

                
            if(!isset($dataAgrupation["graphic"]["series"])){
                
                $dataAgrupation["graphic"]["series"]["conversacion"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::AHT::ConvProm"),
                    "type" => 'column',
                    "data" => []
                ];

                $dataAgrupation["graphic"]["series"]["hold"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::AHT::HoldProm"),
                    "type" => 'column',
                    "data" => []
                ];

                $dataAgrupation["graphic"]["series"]["acw"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::AHT::acwProm"),
                    "type" => 'column',
                    "data" => []
                ];

                $dataAgrupation["graphic"]["series"]["meta"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::Goal"),
                    "type" => 'line',
                    "data" => [],
                    "color" => 'green'
                ];
            }

            $dataAgrupation["graphic"]["series"]["conversacion"]["data"][] = (float)$filasIndicador["prom_conv"];
            $dataAgrupation["graphic"]["series"]["hold"]["data"][] = (float)$filasIndicador["prom_hold"];
            $dataAgrupation["graphic"]["series"]["acw"]["data"][] = (float)$filasIndicador["prom_acw"];
            $dataAgrupation["graphic"]["series"]["meta"]["data"][] =  $this->esDecimal($meta) ? floatval(number_format(round($meta, 2), 2)): (int)($meta);
            
        }

        $dataAgrupation["graphic"]["typeColumn"] = 1;
        if(isset($dataAgrupation["graphic"]["series"])){
            $dataAgrupation["graphic"]["series"] = array_values($dataAgrupation["graphic"]["series"]);
        }

        return $dataAgrupation;

        
    }

    public function tablaAsesor($data){
        foreach($data as $filasIndicador){
            $valorIndicador = $filasIndicador['valor_indicador'] ?? null;
            $fechaDiaria = $filasIndicador['fecha_indicador'] ?? null;
            $nombreFormula = $nombreFormula['fecha_indicador'] ?? null;

            $tiempo_hold = $filasIndicador['tiempo_hold'];
            $llamadas_in = $filasIndicador['llamadas_in'];
            $llamadas_out = $filasIndicador['llamadas_out'];
            $tiempo_acw = $filasIndicador['tiempo_acw'];
            $tiempo_ringing = $filasIndicador['tiempo_ringing'];
            $tiempo_dialing = $filasIndicador['tiempo_dialing'];
            $tiempo_in = $filasIndicador['tiempo_in'];
            $tiempo_salida = $filasIndicador['tiempo_salida'];

            if(!isset($dataAgrupation["dataTable"][$fechaDiaria])){
                $dataAgrupation["dataTable"][$fechaDiaria] = [
                    "Fecha" => "",
                    "AHT" => "",
                    "AHT Entrada" => "",
                    "AHT Salida" => ""
                ];
            }

            $valorIndicador = $this->esDecimal($valorIndicador) ? number_format(round($valorIndicador, 2), 2): $valorIndicador;

            $dataAgrupation["dataTable"][$fechaDiaria]["Fecha"] = date('Y-m-d', strtotime($fechaDiaria));
            $dataAgrupation["dataTable"][$fechaDiaria]["AHT"] = $valorIndicador;

            $promNolisto = ($llamadas_in + $llamadas_out) == 0 ? 0 : $tiempo_acw/($llamadas_in + $llamadas_out);
            $promNolisto = $this->esDecimal($promNolisto) ? floatval(number_format(round($promNolisto, 2), 2)): $promNolisto;

            $promHold = ($llamadas_in + $llamadas_out) == 0 ? 0 : $tiempo_hold/($llamadas_in + $llamadas_out);
            $promHold = $this->esDecimal($promHold) ? floatval(number_format(round($promHold, 2), 2)): $promHold;

            $promConvEnt = $llamadas_in == 0 ? 0 :($tiempo_in + $tiempo_ringing)/$llamadas_in;
            $promConvEnt = $this->esDecimal($promConvEnt) ? number_format(round($promConvEnt, 2), 2): $promConvEnt;

            $promConvSal = $llamadas_out == 0 ? 0 : ($tiempo_salida + $tiempo_dialing)/$llamadas_out;
            $promConvSal = $this->esDecimal($promConvSal) ? number_format(round($promConvSal, 2), 2): $promConvSal;

            $promConv = ($llamadas_out + $llamadas_in) == 0 ? 0 : ($tiempo_salida + $tiempo_in + $tiempo_ringing + $tiempo_dialing)/($llamadas_out + $llamadas_in);
            $promConv = $this->esDecimal($promConv) ? floatval(number_format(round($promConv, 2), 2)): $promConv;

            if(!empty($promHold)){
                $dataAgrupation["dataTable"][$fechaDiaria]["Prom Hold"] = $promHold;
            }    
            
            if(!empty($promNolisto)){
                $dataAgrupation["dataTable"][$fechaDiaria]["Prom No listo"] = $promNolisto;
            }  

            if(!empty($promConvEnt)){
                $dataAgrupation["dataTable"][$fechaDiaria]["Prom Conversacion Entrada"] = $promConvEnt;
            } 
            
            if(!empty($llamadas_in)){
                $dataAgrupation["dataTable"][$fechaDiaria]["Llamadas Entrada"] = $llamadas_in;
            } 

            if(!empty($promConvSal)){
                $dataAgrupation["dataTable"][$fechaDiaria]["Prom Conversacion Salida"] = $promConvSal;
            } 

            if(!empty($llamadas_out)){
                $dataAgrupation["dataTable"][$fechaDiaria]["Llamadas Salida"] = $llamadas_out;
            }     

            $dataAgrupation["dataTable"][$fechaDiaria]["AHT Salida"] = $valorIndicador;

            $dataAgrupation["dataTable"][$fechaDiaria]["AHT Entrada"] = $valorIndicador;
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

                
            if(!isset($dataAgrupation["graphic"]["series"])){
                
                $dataAgrupation["graphic"]["series"]["conversacion"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::AHT::ConvProm"),
                    "type" => 'column',
                    "data" => []
                ];

                $dataAgrupation["graphic"]["series"]["hold"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::AHT::HoldProm"),
                    "type" => 'column',
                    "data" => []
                ];

                $dataAgrupation["graphic"]["series"]["acw"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::AHT::acwProm"),
                    "type" => 'column',
                    "data" => []
                ];

                $dataAgrupation["graphic"]["series"]["meta"] = [
                    "name" => $this->CI->lang->line("Teo::Asesor::Goal"),
                    "type" => 'line',
                    "data" => [],
                    "color" => 'green'
                ];

            }

            $dataAgrupation["graphic"]["series"]["conversacion"]["data"][] = (float)$filasIndicador["prom_conv"];
            $dataAgrupation["graphic"]["series"]["hold"]["data"][] = (float)$filasIndicador["prom_hold"];
            $dataAgrupation["graphic"]["series"]["acw"]["data"][] = (float)$filasIndicador["prom_acw"];
            $dataAgrupation["graphic"]["series"]["meta"]["data"][] =  $this->esDecimal($meta) ? floatval(number_format(round($meta, 2), 2)): (int)($meta);
        }

        $dataAgrupation["graphic"]["typeColumn"] = 1;
        if(isset($dataAgrupation["graphic"]["series"])){
            $dataAgrupation["graphic"]["series"] = array_values($dataAgrupation["graphic"]["series"]);
        }

        return $dataAgrupation;
    }

    private function esDecimal($numero) {
        return strpos($numero, '.') !== false;
    }

}
