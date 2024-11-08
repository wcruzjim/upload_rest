<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Tarea_Proceso extends CI_Migration
{
    public function up()
    {

        /**Tabla tarea_proceso */
        $this->dbforge->add_field(array(
            'idtareaproceso' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),    
            'idtarea' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'ID del workspace'
            ),
            'idproceso' => array(  
                'type' => 'INT',
                'null' => true,
                'comment' => 'id del cargo cargo'
            ),
            'documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'documento del que ejecuta el proceso'
            ),           
            'progreso' => array(
                'type' => 'INT',
                'default' => '0',
                'comment' => 'Progreso de la tarea'
            ),           
            'estado' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => '1',
                'comment' => 'Estado de la tarea proceso 1:activado, 0:Desactivado'
            ),           
                          
        ));
        $this->dbforge->add_key('idtareaproceso', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_tarea_proceso', true, $attributes);
        
        $this->db->query('ALTER TABLE `teo_tarea_proceso` CHANGE COLUMN `idtarea` `idtarea` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_tarea_proceso` ADD CONSTRAINT `FK_teo_tarea` FOREIGN KEY (`idtarea`) REFERENCES `teo_tarea` (`idtarea`) ON UPDATE CASCADE;');
        
        $this->db->query('ALTER TABLE `teo_tarea_proceso` CHANGE COLUMN `idproceso` `idproceso` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_tarea_proceso` ADD CONSTRAINT `FK_teo_proceso` FOREIGN KEY (`idproceso`) REFERENCES `teo_proceso` (`idproceso`) ON UPDATE CASCADE;');
    }
    public function down()
    {        
        return "";
 
    }
}
