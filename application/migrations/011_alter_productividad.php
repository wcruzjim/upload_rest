<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Alter_Productividad extends CI_Migration {

        public function up()
        {                
                $fields = array(
                        'estado' => array(
                                'type' =>'INT', 
                                'constraint' => '11',
                                'default' => '1',
                                'comment' => 'Estado del registro de productividad 1:activado, 0:Desactivado'
                                )
                        );
                        $this->dbforge->add_column('teo_productividad', $fields);               
        }

        public function down()
        {
                return "";
        }
}