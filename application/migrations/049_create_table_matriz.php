<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Table_Matriz extends CI_Migration {

    public function up()
    {  
        $this->dbforge->add_field(array(
            'idmatriz' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'nombre' => array(
                'type' =>'VARCHAR', 
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre de la matriz'
            ),
            'fecha_creacion' => array(
                    'type' => 'DATETIME',                        
                    'null' => true,
                    'comment' => 'Fecha de creacion'
            ),            
            'documento_creacion' => array(
                'type' =>'VARCHAR', 
                'constraint' => '100',
                'null' => true,
                'comment' => 'Documento de quien crea la matriz'
            ),
            'estado' => array(
                'type' => 'INT',
                'unsigned' => true,
                'comment' => 'Estado de la matriz'
            ),
        
        ));
        $this->dbforge->add_key('idmatriz', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_matriz', true, $attributes);
        /**Tabla matriz elementos*/
        $this->dbforge->add_field(array(
            'idmatrizelemento' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'l1' => array(
                    'type' => 'TEXT',
                    'null' => true,
                    'comment' => 'Limite 1'
            ),
            'l2' => array(
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Limite 2'
            ),
            'resultado' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'comment' => 'resultado de los limites'
            ),
            'operador' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'comment' => 'operador para hacer la comparacion de los limites'
            ),            
            'idmatriz' => array(
                'type' => 'INT',
                'comment' => 'ID de la matriz a la que pertenece'
            )
        ));
    $this->dbforge->add_key('idmatrizelemento', true);
    $attributes = array('ENGINE' => 'InnoDB');
    $this->dbforge->create_table('teo_matriz_elementos', true, $attributes);
    $this->db->query('ALTER TABLE `teo_matriz_elementos` CHANGE COLUMN `idmatriz` `idmatriz` INT(11) UNSIGNED NOT NULL;');
    $this->db->query('ALTER TABLE `teo_matriz_elementos` ADD CONSTRAINT `FK_matriz_matrizelemento` FOREIGN KEY (`idmatriz`) REFERENCES `teo_matriz` (`idmatriz`) ON UPDATE CASCADE;');
    
             
    }

    public function down()
    {
        return "";
    }
}