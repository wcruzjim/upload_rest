<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Log_Medicion extends CI_Migration {

        public function up()
        {
            $fields = array(
                'fechas_remedicion_inicial' => array(
                            'name' => 'fechas_remedicion',
                            'type' => 'TEXT',
                            'null' => true,
                            'comment' => 'Fechas en la que se hizo la remedicion'
                ),
               
            );
            $this->dbforge->modify_column('teo_log_medicion', $fields);
        }

        public function down()
        {
            return "";
        }
}