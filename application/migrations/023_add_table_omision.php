<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Omision extends CI_Migration
{
    public function up()
    {
        /**Tabla tipos de tipo  indicadores */
        $this->dbforge->add_field(array(
                'idomision' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'segmento_omision' => array(
                        'type' => 'JSON',
                        'null' => true,
                        'comment' => 'Nombre y id de pcrc, servicio, ceco o cliente que no se desea medir'
                ),
                'fecha_omision' => array(
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'Fecha en la que no se va a medir'
                    ),              
                'idmetrica' => array(
                                'type' => 'INT',
                                'comment' => 'ID de la metrica'
                        ),
                'observacion_omision' => array(
                                'type' => 'VARCHAR',
                                'null' => true,
                                'constraint' => '400',
                                'comment' => 'Observacion del por que no se va a medir'
                        ),
                        'documento_creacion' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '20',
                                'null' => true,
                                'comment' => 'numero del documento d ela persona que crea la metrica'
                            ),
                        'fecha_creacion' => array(
                                'type' => 'DATETIME',
                                'null' => true,
                                'comment' => 'fecha en la que se crea la metrica'
                            ),
            ));
        $this->dbforge->add_key('idomision', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_medicion_omision', true, $attributes);
        $this->db->query('ALTER TABLE `teo_medicion_omision` CHANGE COLUMN `idmetrica` `idmetrica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_medicion_omision` ADD CONSTRAINT `FK_omision_metrica` FOREIGN KEY (`idmetrica`) REFERENCES `teo_metricas` (`idmetrica`) ON UPDATE CASCADE;');
    }

    public function down()
    {
        return "";
    }
}
