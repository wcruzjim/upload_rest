<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Tarea extends CI_Migration
{
    public function up()
    {

        /**Tabla tareas */
        $this->dbforge->add_field(array(
            'idtarea' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'nombre' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del tarea'
            ),
            'estado' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado  de la tarea 1:activado, 0:Desactivado'
            ),
        ));
        $this->dbforge->add_key('idtarea', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_tarea', true, $attributes);
    }
    public function down()
    {        
        return "";
 
    }
}
