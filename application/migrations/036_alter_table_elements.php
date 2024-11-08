<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_Table_Elements extends CI_Migration
{
    public function up()
    {
        /**Agregamos columna de parametros */
        $fields = array(               
            'parametros_card' => array(
                'type' => 'JSON',
                'null' => true,
                'comment' => 'parametros principales para guardar el cardpanel'
        ),
        );
        $this->dbforge->add_column('teo_elementos', $fields);       
    }
    public function down()
    {        
        return "";
    }
}
