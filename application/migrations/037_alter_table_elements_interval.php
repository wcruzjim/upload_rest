<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_Table_Elements_Interval extends CI_Migration
{
    public function up()
    {
        /**Agregamos columna de intervalo */
        $fields = array(               
            'idintervalo' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'tipo de intervalo 1=dia, 2=mes, 3=año'
        ),
        );        
        $this->dbforge->add_column('teo_elementos', $fields);   
        /**Eliminamos tablas */     
    }
    public function down()
    {        
        return "";
    }
}
