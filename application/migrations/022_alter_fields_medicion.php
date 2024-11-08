<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Medicion extends CI_Migration {

        public function up()
        {
            $fields = array(
                'fecha' => array(
                            'name' => 'fecha_inicio',
                            'type' => 'DATETIME',                            
                            'null' => true,
                            'comment' => 'fecha inicial del rango en el que se mide'
                ),
               
            );
            $this->dbforge->modify_column('teo_medicion', $fields);

            $field = array(
                'fecha_fin' => array(
                        'type' =>'DATETIME', 
                        'null' => true,                        
                        'comment' => 'fecha final del rango en el que se mide'
                        )
                );
                $this->dbforge->add_column('teo_medicion', $field);    
        }

        public function down()
        {
                return "";
        }
}