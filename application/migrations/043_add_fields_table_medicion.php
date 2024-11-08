<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Fields_Table_Medicion extends CI_Migration {

    public function up()
    {                
            $fields = array(
                    'nombre_completo' => array(
                            'type' =>'VARCHAR', 
                            'constraint' => '100',
                            'null' => true,
                            'comment' => 'Nombre completo del asesor'
                    ),
                    'documento_jefe' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Documento del jefe inmediato del asesor'
                    ),
                    'nombre_jefe' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre del jefe inmediato del asesor'
                    ),
                    'documento_coordinador' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Documento del Coordinador'
                    ),
                    'nombre_coordinador' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre del Coordinador'
                    ),
                    'documento_gerente' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Documento del Gerente'
                    ),
                    'nombre_gerente' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre del Gerente'
                    ),
                    'documento_director' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Documento del Director'
                    ),
                    'nombre_director' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre del Director'
                    ),
                    'nombre_metrica' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre de la metrica'
                    ),
                    'nombre_ceco' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre del ceco'
                    ),
                    'nombre_servicio' => array(
                        'type' =>'VARCHAR', 
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre del servicio'
                        )
                    );
                    $this->dbforge->add_column('teo_medicion', $fields);               
    }

    public function down()
    {
        return "";
    }
}