<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_Add_Fields_Teo_Log_Registros extends CI_Migration
{
    public function up()
    {
        /**Agregamos columna de parametros */
        $fields = array(
            'update_old' => array(
                'type' => 'LONGTEXT',
                'null' => true,
                'comment' => 'Datos que se tenian en el regitro antes d ela modificacion'
            ),               
            'update_new' => array(
                'type' => 'LONGTEXT',
                'null' => true,
                'comment' => 'Datos que se actualizaron'
            ),
            
        );
        $this->dbforge->add_column('teo_log_registros', $fields);       
    }
    public function down()
    {        
        return "";
    }
}
