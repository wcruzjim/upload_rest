<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Productividad extends CI_Migration {

    public function up()
    {
        $fields = array(               
            'fecha_fin' => array(
                        'type' => 'DATETIME',
                        'null' => true,                            
                        'comment' => 'Nombre del cliente',
                        'comment' => 'Fecha final del registro de la productividad'
            ),
        );
        $this->dbforge->add_column('teo_productividad', $fields);

        $fields = array(
            'fecha' => array(
                        'name' => 'fecha_inicio',
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'Fecha inicial del registro de la productividad'
            ),
           
        );
        $this->dbforge->modify_column('teo_productividad', $fields);
       
    }

    public function down()
    {
        return "";
    }
}