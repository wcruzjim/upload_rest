<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Indicators extends CI_Migration {

        public function up()
        {
                
                $fields = array(
                        'estado' => array(
                                'type' =>'INT', 
                                'constraint' => '11',
                                'default' => '1',
                                'comment' => 'Estado del indicador 1:activado, 0:Desactivado'
                                )
                        );
                        $this->dbforge->add_column('teo_indicadores', $fields);
               
        }

        public function down()
        {
                return "";
        }
}