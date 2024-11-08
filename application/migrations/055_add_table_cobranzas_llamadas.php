<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Table_Cobranzas_Llamadas extends CI_Migration {

        public function up()
        {
            /**Tabla tipos de tipo  indicadores */
            $this->dbforge->add_field(array(
                'idgestion' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'consecutivo_obligacion' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Codigo de la Obligacion'
                ),               
                'nit' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Nit del Cliente'
                ),               
                'fecha_gestion' => array(
                        'type' => 'DATETIME',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Fecha que se realiza la llamada'
                ),               
                'fecha' => array(
                        'type' => 'DATE',
                        'null' => true,
                        'comment' => 'Fecha de la gestion'
                ),               
            ));
            $this->dbforge->add_key('idgestion', true);
            $attributes = array('ENGINE' => 'InnoDB');
            $this->dbforge->create_table('teo_cobranzas_llamadas', true, $attributes);
        }

        public function down()
        {
                return "";
        }
}