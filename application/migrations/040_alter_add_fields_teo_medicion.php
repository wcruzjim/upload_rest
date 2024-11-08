<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_Add_Fields_Teo_Medicion extends CI_Migration
{
    public function up()
    {
        /**Agregamos columna de parametros */
        $fields = array(
            'idpcrc' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'Indentificador del pcrc'
            ),               
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
            'nombre_cliente' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'comment' => 'Nombre del cliente al que pertenece la persona que s ele realizo la medición'
            ),
            'idservicio' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
                'comment' => 'Identificador del servicio'
            ),
        );
        $this->dbforge->add_column('teo_medicion', $fields);       
    }
    public function down()
    {        
        return "";
    }
}
