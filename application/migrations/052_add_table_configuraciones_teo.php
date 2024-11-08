<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Configuraciones_Teo extends CI_Migration
{
    public function up()
    {
        /**Tabla dashboard */
        $this->dbforge->add_field(array(
            'idteoconfiguraciongeneral' => array(
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => true,
                'comment' => 'Nombre del Configuracion'
            ),
            'valor' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
                'comment' => 'Nombre del valor de elemento de la variable de configuración'
            ),
            'descripcion' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
                'comment' => 'Descripcion de la variable de la configuracion'
            ),
        ));
        $this->dbforge->add_key('idteoconfiguraciongeneral', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_configuracion_general', true, $attributes);
    }
    public function down()
    {
        return "";
    }
}
