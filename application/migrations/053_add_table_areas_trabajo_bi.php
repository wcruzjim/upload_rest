<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Areas_Trabajo_Bi extends CI_Migration
{
    public function up()
    {

        /**Tabla dashboard_powerbi */
        $this->dbforge->add_field(array(
            'iddashboard' => array(
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'iddashboardpowerbi' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del dashboard powerbi'
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
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del usuario que creo el registro'
            ),
            'estado' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado del registro del dashboard 1:activado, 0:Desactivado'
            ),

        ));
        $this->dbforge->add_key('iddashboard', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_dashboard_powerbi', true, $attributes);

        /**Tabla de areas de trabajo - workspace de Power bi*/
        $this->dbforge->add_field(array(
            'idareatrabajo' => array(
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'idareatrabajopowerbi' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del dashboard powerbi'
            ),
            'nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'Nombre del area de trabajo'
            ),
            'estado' => array(
                'type' => 'INT',
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
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del usuario que creo el registro'
            )
        ));
        $this->dbforge->add_key('idareatrabajo', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_areas_trabajo_powerbi', true, $attributes);

        /**Tabla asociacion workspace  con dashboard de Power bi*/
        $this->dbforge->add_field(array(
            'iddashboardareatrabajo' => array(
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'idareatrabajopowerbi' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'id del area de trabajo'
            ),
            'iddashboardpowerbi' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del dashboard powerbi'
            ),
            'estado' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado del registro  1:activado, 0:Desactivado'
            ),
        ));
        $this->dbforge->add_key('iddashboardareatrabajo', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_areas_trabajo_dashboard_powerbi', true, $attributes);
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard_powerbi` CHANGE COLUMN `idareatrabajo` `idareatrabajo` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard_powerbi` ADD CONSTRAINT `FK_areatrabajo_dashboard` FOREIGN KEY (`idareatrabajo`) REFERENCES `teo_areas_trabajo_powerbi` (`idareatrabajo`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard_powerbi` CHANGE COLUMN `iddashboardpowerbi` `iddashboardpowerbi` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard_powerbi` ADD CONSTRAINT `FK__dashboard_powerbi` FOREIGN KEY (`iddashboardpowerbi`) REFERENCES `teo_dashboard_powerbi` (`iddashboardpowerbi`) ON UPDATE CASCADE;');



        /**Filtros  Power Bi */

        $this->dbforge->add_field(array(
            'idfiltro' => array(
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'tabla' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Tabla a Relacionar'
            ),
            'columna' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Columna a Relacionar'
            ),
            'idtipofiltro' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del tipo de filtro'
            ),
            'estado' => array(
                'type' => 'INT',
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
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Nombre del usuario que creo el registro'
            )
        ));
        $this->dbforge->add_key('idfiltro', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_filtros_powerbi', true, $attributes);

        $this->db->query('ALTER TABLE `teo_filtros` CHANGE COLUMN `idtipofiltro` `idtipofiltro` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_filtros` ADD CONSTRAINT `FK_tipo_filtro` FOREIGN KEY (`idtipofiltro`) REFERENCES `teo_tipo_filtros` (`idtipofiltro`) ON UPDATE CASCADE;');

        /**Tabla dashboard_filtros de power bi */
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
        $this->dbforge->create_table('teo_dasboard_filtros_powerbi', true, $attributes);

        $this->db->query('ALTER TABLE `teo_dasboard_filtros_powerbi` CHANGE COLUMN `iddashboardareatrabajo` `iddashboardareatrabajo` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_dasboard_filtros_powerbi` ADD CONSTRAINT `FK_areatrabajo_filtro` FOREIGN KEY (`iddashboardareatrabajo`) REFERENCES `teo_areas_trabajo_dashboard_powerbi` (`iddashboardareatrabajo`) ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `teo_dasboard_filtros_powerbi` CHANGE COLUMN `idfiltro` `idfiltro` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_dasboard_filtros_powerbi` ADD CONSTRAINT `FK_tipo_filtro` FOREIGN KEY (`idfiltro`) REFERENCES `teo_filtros` (`idfiltro`) ON UPDATE CASCADE;');
    }
    public function down()
    {
        return "";
    }
}
