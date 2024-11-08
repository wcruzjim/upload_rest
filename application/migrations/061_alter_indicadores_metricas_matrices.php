<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Alter_Indicadores_Metricas_Matrices extends CI_Migration
{
    public function up()
    {
        /**Agregamos columna de parametros */
        $fieldIndicadores = array(               
            'idvisibilidad' => array(
                'type' => 'INT',
                'constraint' => '5',
                'null' => true,
                'comment' => 'Identificador visibilidad'
            )
        );
        $this->dbforge->add_column('teo_indicadores', $fieldIndicadores);       
        /**Agregamos columna de parametros */
        $fieldMetricas = array(               
            'idvisibilidad' => array(
                'type' => 'INT',
                'constraint' => '5',
                'null' => true,
                'comment' => 'Identificador visibilidad'
            )
        );
        $this->dbforge->add_column('teo_metricas', $fieldMetricas);       
        /**Agregamos columna de parametros */
        $fieldMatrices = array(               
            'idvisibilidad' => array(
                'type' => 'INT',
                'constraint' => '5',
                'null' => true,
                'comment' => 'Identificador visibilidad'
            )
        );
        $this->dbforge->add_column('teo_matriz', $fieldMatrices);       
    }

    public function down()
    {        
        return "";      
    }
}
