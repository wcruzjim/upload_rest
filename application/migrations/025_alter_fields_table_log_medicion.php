<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Table_Log_Medicion extends CI_Migration {

        public function up()
        {
            $fields = array(
                'idsegmento' => array(
                            'type' => 'TEXT',
                            'null' => true,
                            'comment' => 'id del PCRC, CECO, Cliente o documento del asesor'
                ),
               
            );
            $this->dbforge->modify_column('teo_log_medicion', $fields);


        }

        public function down()
        {
            return "";
                
        }
}