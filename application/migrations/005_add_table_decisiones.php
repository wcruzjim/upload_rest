<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Decisiones extends CI_Migration
{
    public function up()
    {
        /**Tabla decisiones */
        $this->dbforge->add_field(array(
                        'iddecision' => array(
                                'type' => 'INT',
                                'unsigned' => true,
                                'auto_increment' => true,
                                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                        ),
                        'nombre' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => true,
                                'comment' => 'Nombre de la decisiones'
                        ),
                ));
        $this->dbforge->add_key('iddecision', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('decisiones', true, $attributes);
    }
    public function down(){
        return "";
    }
}
