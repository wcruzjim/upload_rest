<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Dashboard_Metabase extends CI_Migration
{
    public function up()
    {

        /**Tabla dashboard_metabase */
        $this->dbforge->add_field(array(
            'iddashboard' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'iddashboardmetabase' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del dashboard metabase'
            ),
            'nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del dashboard'
            ),                   
            'documento_creacion' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => 'numero del documento d ela persona que crea el dashboard'
            ),
            'fecha_creacion' => array(
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'fecha en la que se crea el dashboard'
            ),
            'usuario_creacion' => array(
                'type' =>'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del usuario que creo el registro'
            ),
            'estado' => array(
                'type' =>'INT', 
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado del registro del dashboard 1:activado, 0:Desactivado'
            ),               
                          
        ));
        $this->dbforge->add_key('iddashboard', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_dashboard_metabase', true, $attributes);
    
        /**Tabla de areas de trabajo - workspace*/
        $this->dbforge->add_field(array(
            'idareatrabajo' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'Nombre del area de trabajo'
            ), 
            'estado' => array(
                'type' =>'INT', 
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado del registro del workspaces 1:activado, 0:Desactivado'
            ),
            'documento_creacion' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => 'numero del documento de la persona que crea el workspaces'
            ),
            'fecha_creacion' => array(
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'fecha en la que se crea el workspaces'
            ),
            'usuario_creacion' => array(
                'type' =>'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del usuario que creo el registro'
            )
        ));
        $this->dbforge->add_key('idareatrabajo', true);   
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_areas_trabajo', true, $attributes); 

        /**Tabla asociacion workspace  con dashboard*/
        $this->dbforge->add_field(array(
            'iddashboardareatrabajo' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'idareatrabajo' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'id del area de trabajo'
            ), 
            'iddashboardmetabase' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del dashboard metabase'
            ),
            'estado' => array(
                'type' =>'INT', 
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado del registro  1:activado, 0:Desactivado'
            ),
        ));
        $this->dbforge->add_key('iddashboardareatrabajo', true);   
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_areas_trabajo_dashboard', true, $attributes); 
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard` CHANGE COLUMN `idareatrabajo` `idareatrabajo` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard` ADD CONSTRAINT `FK_areatrabajo_dashboard` FOREIGN KEY (`idareatrabajo`) REFERENCES `teo_areas_trabajo` (`idareatrabajo`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard` CHANGE COLUMN `iddashboardmetabase` `iddashboardmetabase` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard` ADD CONSTRAINT `FK__dashboard_metabase` FOREIGN KEY (`iddashboardmetabase`) REFERENCES `teo_dashboard_metabase` (`iddashboardmetabase`) ON UPDATE CASCADE;');

    }
    public function down(){        
        // this is intentionally empty
    }
}
