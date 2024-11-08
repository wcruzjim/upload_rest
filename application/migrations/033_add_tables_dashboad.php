<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Tables_Dashboad extends CI_Migration
{
    public function up()
    {
        /**Tabla dashboard */
        $this->dbforge->add_field(array(
                        'iddashboard' => array(
                                'type' => 'INT',
                                'unsigned' => true,
                                'auto_increment' => true,
                                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                        ),                      
                        'nombre' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '50',
                            'null' => true,
                            'comment' => 'Nombre del elemento'
                    ),
                        'parametros' => array(
                            'type' => 'JSON',
                            'null' => true,
                            'comment' => 'parametros nesesarion en el elemento s eguarda en tipo json'
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
                        'comment' => 'Estado del registro del elemento 1:activado, 0:Desactivado'
                        )               
                ));
        $this->dbforge->add_key('iddashboard', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_dashboard', true, $attributes);

         /**Tabla de dashboard elementos*/
         $this->dbforge->add_field(array(
            'iddashboardelemento' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'idelemento' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'id del elemento'
            ),
            'iddashboard' => array(
                'type' => 'INT',
                'comment' => 'ID del dashboard'
            ),          
        ));
    $this->dbforge->add_key('iddashboardelemento', true);
    $attributes = array('ENGINE' => 'InnoDB');
    $this->dbforge->create_table('teo_dashboard_elementos', true, $attributes);
    $this->db->query('ALTER TABLE `teo_dashboard_elementos` CHANGE COLUMN `idelemento` `idelemento` INT(11) UNSIGNED NOT NULL;');
    $this->db->query('ALTER TABLE `teo_dashboard_elementos` ADD CONSTRAINT `FK_dashboard_elemento` FOREIGN KEY (`idelemento`) REFERENCES `teo_elementos` (`idelemento`) ON UPDATE CASCADE;');
    $this->db->query('ALTER TABLE `teo_dashboard_elementos` CHANGE COLUMN `iddashboard` `iddashboard` INT(11) UNSIGNED NOT NULL;');
    $this->db->query('ALTER TABLE `teo_dashboard_elementos` ADD CONSTRAINT `FK__dashboard_dashboard_elemento` FOREIGN KEY (`iddashboard`) REFERENCES `teo_dashboard` (`iddashboard`) ON UPDATE CASCADE;');
    }
    public function down()
    {
        return "";
    }
}
