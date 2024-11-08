<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Calidad
{

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function tablaAsesor($data){
        foreach($data as $filasIndicador){

            $dataAgrupation["dataTable"]["score"] = [
                "Métrica" => "Score",
                "Agente" => $filasIndicador["cantidad_score_agentes"],
                "Proceso" => $filasIndicador["cantidad_score_proceso"], 
                "Calidad del entrenamiento" => $filasIndicador["cantidad_score_entrenamiento"], 
                "Héroes por el cliente" => $filasIndicador["cantidad_score_heroes"], 
                "Ojt" => $filasIndicador["cantidad_score_ojt"],
                "Total" => $filasIndicador["valor_score"]
            ];

            $dataAgrupation["dataTable"]["pec_rac"] = [
                "Métrica" => "Pec rac",
                "Agente" => $filasIndicador["cantidad_pec_rac_agentes"],
                "Proceso" => $filasIndicador["cantidad_pec_rac_proceso"], 
                "Calidad del entrenamiento" => $filasIndicador["cantidad_pec_rac_entrenamiento"], 
                "Héroes por el cliente" => $filasIndicador["cantidad_pec_rac_heroes"], 
                "Ojt" => $filasIndicador["cantidad_pec_rac_ojt"],
                "Total" => $filasIndicador["valor_pec_rac"]
            ];

            $dataAgrupation["dataTable"]["penc"] = [
                "Métrica" => "Penc",
                "Agente" => $filasIndicador["cantidad_penc_agentes"],
                "Proceso" => $filasIndicador["cantidad_penc_proceso"], 
                "Calidad del entrenamiento" => $filasIndicador["cantidad_penc_entrenamiento"], 
                "Héroes por el cliente" => $filasIndicador["cantidad_penc_heroes"], 
                "Ojt" => $filasIndicador["cantidad_penc_ojt"],
                "Total" => $filasIndicador["valor_penc"]
            ];

            $dataAgrupation["dataTable"]["indice_experiencia"] = [
                "Métrica" => "Indice de experiencia",
                "Agente" => $filasIndicador["cantidad_indice_experiencia_agentes"],
                "Proceso" => $filasIndicador["cantidad_indice_experiencia_proceso"], 
                "Calidad del entrenamiento" => $filasIndicador["cantidad_indice_experiencia_entrenamiento"], 
                "Héroes por el cliente" => $filasIndicador["cantidad_indice_experiencia_heroes"], 
                "Ojt" => $filasIndicador["cantidad_indice_experiencia_ojt"],
                "Total" => $filasIndicador["valor_indice_experiencia"]
            ];

            $dataAgrupation["dataTable"]["indice_de_proceso"] = [
                "Métrica" => "Indice de proceso",
                "Agente" => $filasIndicador["cantidad_indice_de_proceso_agentes"],
                "Proceso" => $filasIndicador["cantidad_indice_de_proceso_proceso"], 
                "Calidad del entrenamiento" => $filasIndicador["cantidad_indice_de_proceso_entrenamiento"], 
                "Héroes por el cliente" => $filasIndicador["cantidad_indice_de_proceso_heroes"], 
                "Ojt" => $filasIndicador["cantidad_indice_de_proceso_ojt"],
                "Total" => $filasIndicador["valor_indice_proceso"]
            ];
        }

        $dataTable = isset($dataAgrupation["dataTable"]) ? array_values($dataAgrupation["dataTable"]) : []; 

        return $this->filtarVacios($dataTable);
        
    }

    private function filtarVacios($dataTable){
        $claves = ['Agente', 'Proceso', 'Calidad del entrenamiento', 'Héroes por el cliente', 'Ojt'];
     
        $clavesAEliminar = [];

        foreach($claves  as $clave){
            $resultado = array_sum(array_column($dataTable, $clave));
            
            if($resultado == 0){
                $clavesAEliminar[] = $clave;
            }

        }


        $datosFiltrados = array_map(function($fila) use ($clavesAEliminar) {
            return array_diff_key($fila, array_flip($clavesAEliminar));
        }, $dataTable);
        
        return $datosFiltrados;

    }
}