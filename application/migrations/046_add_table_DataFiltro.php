<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_DataFiltro extends CI_Migration
{
    public function up()
    {

        /**Tabla tipo filtros */
        $this->dbforge->add_field(array(
            'iddatafiltro' => array(
                    'type' => 'INT',
                    'null' => true,
                    'comment' => 'ID unico dependiendo del regitro'
            ), 
            'datafiltro' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'opciones seleccionadas en la consulta de filtro'
            ),  
            'fecha_creacion' => array(
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'fecha en la que se crea el filtro'
            ),             
        ));
        
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_data_filtro', true, $attributes);        
    }

    public function down()
    {        
        return "";
    }
}
