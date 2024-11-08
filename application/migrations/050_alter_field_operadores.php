<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Field_Operadores extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'aplicable_matriz' => array(
                                'type' =>'int',
                                'constraint' => '11',
                                'default' => '0',
                                'comment' => 'Indica si el operador esta aplicable a la matriz 1:activado, 0:Desactivado'
                                )
                        );
                $this->dbforge->add_column('operadores', $fields);
        }

        public function down()
        {
                return "";
        }
}