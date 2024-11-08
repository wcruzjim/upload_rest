<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Table_Indicadores_Operacion extends CI_Migration {

        public function up()
        {
            /**Tabla tipos de tipo  indicadores */
            $this->dbforge->add_field(array(
                'idindicadoroperacion' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'nombre' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Nombre del intervalo'
                ),               
            ));
            $this->dbforge->add_key('idindicadoroperacion', true);
            $attributes = array('ENGINE' => 'InnoDB');
            $this->dbforge->create_table('teo_indicadores_operacion', true, $attributes);

            $fields = array(               
                'idindicadoroperacion' => array(
                            'type' => 'INT',
                            'comment' => 'ID del intervalo'
                ),
            );
            $this->dbforge->add_column('teo_metricas', $fields);
            $this->db->query('ALTER TABLE `teo_metricas` CHANGE COLUMN `idindicadoroperacion` `idindicadoroperacion` INT(11) UNSIGNED NOT NULL;');        
            $this->db->query('ALTER TABLE `teo_metricas` ADD CONSTRAINT `FK_metrica_indicador_operaciones` FOREIGN KEY (`idindicadoroperacion`) REFERENCES `teo_indicadores_operacion` (`idindicadoroperacion`) ON UPDATE CASCADE;');
           
        }

        public function down()
        {
                return "";
        }
}