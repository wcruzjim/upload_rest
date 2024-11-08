<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Proceso extends CI_Migration
{
    public function up()
    {

        /**Tabla proceso */
        $this->dbforge->add_field(array(
            'idproceso' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'nombre' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del proceso'
            ),
            'estado' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado del proceso 1:activado, 0:Desactivado'
            ),
        ));
        $this->dbforge->add_key('idproceso', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_proceso', true, $attributes);
    }
    public function down()
    {        
        return "";
 
    }
}
