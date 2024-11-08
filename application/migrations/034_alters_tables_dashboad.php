<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alters_Tables_Dashboad extends CI_Migration
{
    public function up()
    {
        /**Agregamos columna d eparametros */
        $fields = array(               
            'parametros' => array(
                'type' => 'JSON',
                'null' => true,
                'comment' => 'parametros nesesarion en el elemento s eguarda en tipo json'
        ),
        );
        $this->dbforge->add_column('teo_dashboard_elementos', $fields);       
    }
    public function down()
    {        
        return "";
    }
}
