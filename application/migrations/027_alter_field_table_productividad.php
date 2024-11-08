<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Field_Table_productividad extends CI_Migration {

        public function up()
        {
            $fields = array(
                'idpcrc' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '20',
                            'null' => true,
                            'comment' => 'PCRC al que pertenece el documento'
                ),
               
            );
            $this->dbforge->add_column('teo_productividad', $fields);
        }

        public function down()
        {
            return "";
        }
}