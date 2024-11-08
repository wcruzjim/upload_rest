<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Medicion_Date_Omision extends CI_Migration {

        public function up()
        {
            $field = array(
                'fechas_no_medidas' => array(
                        'type' =>'TEXT', 
                        'null' => true,                        
                        'comment' => 'fechas que no se midieron'
                        )
                );
                $this->dbforge->add_column('teo_medicion', $field);    
        }

        public function down()
        {
                return "";
        }
}