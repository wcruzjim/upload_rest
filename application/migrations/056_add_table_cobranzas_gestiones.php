<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Table_Cobranzas_Gestiones extends CI_Migration {

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
                        'comment' => 'Fecha que se realiza la gestion'
                ),               
                'fecha' => array(
                        'type' => 'DATE',
                        'null' => true,
                        'comment' => 'Fecha de la gestion'
                ),               
                'hora' => array(
                        'type' => 'INT',
                        'null' => true,
                        'comment' => 'Fecha de la gestion'
                ),
                'grabador' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => true,
                    'comment' => 'Usuario de adminfo'
                ),             
                'codigo_gestion' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => true,
                    'comment' => 'Codigo de la gestión'
                ),             
            ));
            $this->dbforge->add_key('idgestion', true);
            $attributes = array('ENGINE' => 'InnoDB');
            $this->dbforge->create_table('teo_cobranzas_gestiones', true, $attributes);
        }

        public function down()
        {
                return "";
        }
}