<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_Fields_Teo_Productividad extends CI_Migration
{
    public function up()
    {
        /**Agregamos columna de parametros */
        $fields = array(               
            'idceco' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
                'comment' => 'Identificador del ceco'
            ),
            'idcliente' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
                'comment' => 'Identificador del cliente'
            ),
            'idservicio' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
                'comment' => 'Identificador del servicio'
            ),
        );
        $this->dbforge->add_column('teo_productividad', $fields);       
    }
    public function down()
    {        
        return "";
    }
}
