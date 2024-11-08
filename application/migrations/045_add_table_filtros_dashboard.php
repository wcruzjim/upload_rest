<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Filtros_Dashboard extends CI_Migration
{
    public function up()
    {

        /**Tabla tipo filtros */
        $this->dbforge->add_field(array(
            'idtipofiltro' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del tipo de filtro'
            ),               
        ));
        $this->dbforge->add_key('idtipofiltro', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_tipo_filtros', true, $attributes);

        /**Tabla filtros */
        $this->dbforge->add_field(array(
            'idfiltro' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del dashboard'
            ),
            'idtipofiltro' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del tipo de filtro'
            ),
            'estado' => array(
                'type' =>'INT', 
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado del registro del dashboard 1:activado, 0:Desactivado'
            ),
            'documento_creacion' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => 'numero del documento de la persona que crea el filtro'
            ),
            'fecha_creacion' => array(
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'fecha en la que se crea el filtro'
            ),
            'usuario_creacion' => array(
                'type' =>'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del usuario que creo el registro'
            )                           
        ));
        $this->dbforge->add_key('idfiltro', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_filtros', true, $attributes);

        $this->db->query('ALTER TABLE `teo_filtros` CHANGE COLUMN `idtipofiltro` `idtipofiltro` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_filtros` ADD CONSTRAINT `FK_tipo_filtro` FOREIGN KEY (`idtipofiltro`) REFERENCES `teo_tipo_filtros` (`idtipofiltro`) ON UPDATE CASCADE;');

        /**Tabla dashboard_filtros */
        $this->dbforge->add_field(array(
            'iddasboardfiltro' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'iddashboardareatrabajo' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID de la asociacion workspace con dasboard'
            ),
            'idfiltro' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID de filtro'
            ),
                
                          
        ));
        $this->dbforge->add_key('iddasboardfiltro', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_dasboard_filtros', true, $attributes);
        
        $this->db->query('ALTER TABLE `teo_dasboard_filtros` CHANGE COLUMN `iddashboardareatrabajo` `iddashboardareatrabajo` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_dasboard_filtros` ADD CONSTRAINT `FK_areatrabajo_filtro` FOREIGN KEY (`iddashboardareatrabajo`) REFERENCES `teo_areas_trabajo_dashboard` (`iddashboardareatrabajo`) ON UPDATE CASCADE;');
        
        $this->db->query('ALTER TABLE `teo_dasboard_filtros` CHANGE COLUMN `idfiltro` `idfiltro` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_dasboard_filtros` ADD CONSTRAINT `FK_tipo_filtro` FOREIGN KEY (`idfiltro`) REFERENCES `teo_filtros` (`idfiltro`) ON UPDATE CASCADE;');
    }

    public function down()
    {        
        return "";
 
    }
}
