<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Fields_Table_Log_Registros extends CI_Migration {

    public function up()
    {                
            $fields = array(
                    'dato_anterior' => array(
                            'type' =>'TEXT',
                            'null' => true,
                            'comment' => 'Almacena el dato que existia antes del cambio'
                    ),
                    'dato_nuevo' => array(
                        'type' =>'TEXT',
                        'null' => true,
                        'comment' => 'almacena el cambio realizado'
                    ),
                    'nombre_columna' => array(
                        'type' =>'VARCHAR',
                        'constraint' => '30',
                        'null' => true,
                        'comment' => 'columna en la que se realizo el cambio'
                    )
                    );
                    $this->dbforge->add_column('teo_log_registros', $fields);               
    }

    public function down()
    {
        return "";
    }
}