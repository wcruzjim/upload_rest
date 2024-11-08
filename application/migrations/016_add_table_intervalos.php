<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Table_Intervalos extends CI_Migration {

        public function up()
        {
            /**Tabla tipos de tipo  indicadores */
            $this->dbforge->add_field(array(
                'idintervalo' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'nombre' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Nombre del intervalo'
                ),
                'segundos' => array(
                    'type' => 'BIGINT',                    
                    'null' => true,
                    'comment' => 'Segundos correspondientes al intervalo'
                ),
            ));
            $this->dbforge->add_key('idintervalo', true);
            $attributes = array('ENGINE' => 'InnoDB');
            $this->dbforge->create_table('teo_medicion_intervalo', true, $attributes);
           
        }

        public function down()
        {
                return "";
        }
}