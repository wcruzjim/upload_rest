<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Metricas extends CI_Migration {

        public function up()
        {                
                $fields = array(
                        'estado' => array(
                                'type' =>'INT', 
                                'constraint' => '11',
                                'default' => '1',
                                'comment' => 'Estado de la metrica 1:activado, 0:Desactivado'
                                )
                        );
                        $this->dbforge->add_column('teo_metricas', $fields);               
        }

        public function down()
        {
                return "";
        }
}