<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_Metas_Metricas extends CI_Migration {

        public function up()
        {
            $fields = array(
                'fecha_inicio' => array(
                            'name' => 'mes',
                            'type' => 'DATE',
                            'comment' => 'Mes al que corresponde la meta'
                ),
                'idpcrc' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '11',
                            'null' => true,
                            'comment' => 'Id del PCRC'
                ),
            );
            $this->dbforge->modify_column('teo_metas_metricas', $fields);
        }

        public function down()
        {
            return "";
        }
}