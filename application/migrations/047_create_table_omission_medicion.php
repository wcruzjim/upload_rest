<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Table_Omission_Medicion extends CI_Migration {

    public function up()
    {  
        $this->dbforge->add_field(array(
            'idomision' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'fecha' => array(
                    'type' => 'DATETIME',                        
                    'null' => true,
                    'comment' => 'Fecha a la que corresponde la medicion'
            ),            
            'idmetrica' => array(
                    'type' => 'INT'
            ),
            'nombre_metrica' => array(
                'type' =>'VARCHAR', 
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre de la metrica'
            ),
            'documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => 'Documento de la persona que se le hizo la medicion'
            ),
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
        ),
        'fecha_creacion' => array(
                    'type' => 'DATETIME',                        
                    'null' => true,
                    'comment' => 'Fecha en la que se hace la medicion.'
                )
        ));
    $this->dbforge->add_key('idomision', true);
    $attributes = array('ENGINE' => 'InnoDB');
    $this->dbforge->create_table('teo_omisiones', true, $attributes);
    $this->db->query('ALTER TABLE `teo_omisiones` CHANGE COLUMN `idmetrica` `idmetrica` INT(11) UNSIGNED NOT NULL;');
    $this->db->query('ALTER TABLE `teo_omisiones` ADD CONSTRAINT `FK_metrica_omision_metrica` FOREIGN KEY (`idmetrica`) REFERENCES `teo_metricas` (`idmetrica`) ON UPDATE CASCADE;');
             
    }

    public function down()
    {
            $this->dbforge->_table('teo_omisiones');
    }
}