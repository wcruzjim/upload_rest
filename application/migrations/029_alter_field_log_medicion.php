<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Field_Log_Medicion extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'nombre_responsable' => array(
                                'type' =>'VARCHAR',
                                'constraint' => '100',
                                'null' => true,
                                'comment' => 'Nombre del usuario que ejecuto la accion'
                                )
                        );
                        $this->dbforge->add_column('teo_log_medicion', $fields);
        }

        public function down()
        {
                return "";
        }
}