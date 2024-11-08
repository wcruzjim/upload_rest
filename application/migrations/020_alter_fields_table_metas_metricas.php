<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Table_Metas_Metricas extends CI_Migration {

    public function up()
    {                
            $fields = array(
                    'estado' => array(
                            'type' =>'INT', 
                            'constraint' => '11',
                            'default' => '1',
                            'comment' => 'Estado del registro de la meta 1:activado, 0:Desactivado'
                            )
                    );
                    $this->dbforge->add_column('teo_metas_metricas', $fields);               
    }

    public function down()
    {
        return "";
    }
}