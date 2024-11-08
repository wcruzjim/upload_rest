<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Alter_Table_Omision extends CI_Migration {

        public function up()
        {                
                $fields = array(
                        'estado' => array(
                                'type' =>'INT', 
                                'constraint' => '11',
                                'default' => '1',
                                'comment' => 'Estado del registro de la omision 1:activado, 0:Desactivado'
                                )
                        );
                        $this->dbforge->add_column('teo_medicion_omision', $fields);               
        }

        public function down()
        {
                return "";
        }
}