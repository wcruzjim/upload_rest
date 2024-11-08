<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Dashboard extends CI_Migration {

        public function up()
        {
                /**Modificamos campo en la tabla dashboard */
            $fields = array(
                'idintervalo' => array(
                            'name' => 'id_cargo',
                            'type' => 'INT',                            
                            'null' => true,
                            'comment' => 'Id del cargo al que esta asignado el dashboard'
                ),
               
            );
            $this->dbforge->modify_column('teo_dashboard', $fields);

          /**Creamos la tabla Tabla de teo_dashboard_pcrc*/
        $this->dbforge->add_field(array(
                'iddashboardpcrc' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true
                ),
                'idpcrc' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'comment' => 'PCRC al que pertenece el dashboard'
                ),
                'iddashboard' => array(
                        'type' => 'INT',
                        'comment' => 'id del dashboard'
                ),
                'documento_creacion' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'numero del documento del usuario que asigna el dashboard'
                    ),
                'fecha_creacion' => array(
                        'type' => 'DATETIME',                        
                        'null' => true,
                        'comment' => 'fecha en la que se asigna el dashboard'
                    ),
                    'usuario_creacion' => array(
                        'type' =>'VARCHAR',
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre del usuario que creo el registro'
                    ),
            ));
        $this->dbforge->add_key('iddashboardpcrc', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_dashboard_pcrc', true, $attributes);
        $this->db->query('ALTER TABLE `teo_dashboard_pcrc` CHANGE COLUMN `iddashboard` `iddashboard` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_dashboard_pcrc` ADD CONSTRAINT `FK_dashboard_pcrc` FOREIGN KEY (`iddashboard`) REFERENCES `teo_dashboard` (`iddashboard`) ON UPDATE CASCADE;');
        }

        public function down()
        {
                return "";
        }
}