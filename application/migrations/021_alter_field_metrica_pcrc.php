<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Field_Metrica_Pcrc extends CI_Migration {

        public function up()
        {
            $fields = array(
                'idpcrc' => array(
                            'name' => 'idpcrc',
                            'type' => 'VARCHAR',
                            'constraint' => '20',
                            'null' => true,
                            'comment' => 'PCR al que perteneca la metrica'
                ),
               
            );
            $this->dbforge->modify_column('teo_metricas_pcrc', $fields);
        }

        public function down()
        {
            return "";
        }
}