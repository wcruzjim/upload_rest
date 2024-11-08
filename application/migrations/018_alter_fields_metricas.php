<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Metricas extends CI_Migration {

    public function up()
    {
        $fields = array(               
            'idintervalo' => array(
                        'type' => 'INT',
                        'comment' => 'ID del intervalo'
            ),
        );
        $this->dbforge->add_column('teo_metricas', $fields);
        $this->db->query('ALTER TABLE `teo_metricas` CHANGE COLUMN `idintervalo` `idintervalo` INT(11) UNSIGNED NOT NULL;');        
        $this->db->query('ALTER TABLE `teo_metricas` ADD CONSTRAINT `FK_metrica_intervalo` FOREIGN KEY (`idintervalo`) REFERENCES `teo_medicion_intervalo` (`idintervalo`) ON UPDATE CASCADE;');
       
    }

    public function down()
    {
        return "";
    }
}