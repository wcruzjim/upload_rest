<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Cargos_Dashboard extends CI_Migration
{
    public function up()
    {

        /**Tabla dashboard_metabase */
        $this->dbforge->add_field(array(
            'idcargo' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'idareatrabajo' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del workspace'
            ),
            'cargo' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'id del cargo cargo'
            )            
                          
        ));
        $this->dbforge->add_key('idcargo', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_cargo_area_trabajo', true, $attributes);
        
        $this->db->query('ALTER TABLE `teo_cargo_area_trabajo` CHANGE COLUMN `idareatrabajo` `idareatrabajo` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_cargo_area_trabajo` ADD CONSTRAINT `FK_areatrabajo_cargo` FOREIGN KEY (`idareatrabajo`) REFERENCES `teo_areas_trabajo` (`idareatrabajo`) ON UPDATE CASCADE;');
    }
    public function down()
    {        
        return "";
 
    }
}
