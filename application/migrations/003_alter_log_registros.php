<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Log_Registros extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'observaciones' => array(
                                'type' =>'TEXT', 
                                'null' => true,
                                'comment' => 'Justificacion de la accion'
                                )
                        );
                        $this->dbforge->add_column('teo_log_registros', $fields);
        }

        public function down()
        {
                return "";
        }
}