<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Table_Metricas_Cliente extends CI_Migration {

        public function up()
        {
            $fields = array(               
                'nombre_cliente' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '50',
                            'null' => true,                            
                            'comment' => 'Nombre del cliente'
                ),
            );
            $this->dbforge->add_column('teo_metricas_cliente', $fields);
           
        }

        public function down()
        {
            return "";
        }
}