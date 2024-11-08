<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Tables_Field_Usuario_Creacion extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'usuario_creacion' => array(
                                'type' =>'VARCHAR',
                                'constraint' => '100',
                                'null' => true,
                                'comment' => 'Nombre del usuario que creo el registro'
                                )
                        );
                        $this->dbforge->add_column('teo_indicadores', $fields);
                        $this->dbforge->add_column('teo_metricas', $fields);
                        $this->dbforge->add_column('teo_medicion_omision', $fields);
                        $this->dbforge->add_column('teo_metas_metricas', $fields);
                        $this->dbforge->add_column('teo_metricas_ceco', $fields);
                        $this->dbforge->add_column('teo_metricas_cliente', $fields);
                        $this->dbforge->add_column('teo_metricas_pcrc', $fields);
        }

        public function down()
        {
                return "";
        }
}