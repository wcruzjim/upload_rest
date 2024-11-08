<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Medicion_Desciciones_Operadores_Indicadores extends CI_Migration {

        public function up()
        {
            /**Cambios en la tabla medicion */
            $fields = array(               
                'estado' => array(
                            'type' => 'INT',
                            'constraint' => '11',
                            'null' => true,
                            'default'=>'1',
                            'comment' => 'estado en el que se encuentra la medicion'
                ),
            );
            $this->dbforge->add_column('teo_medicion', $fields);
             /**Cambios en la tabla indicadores */
             $fields = array(               
                'color' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '20',
                            'null' => true,
                            'default'=>'#000000',
                            'comment' => 'Color con el que se identificara el indicador'
                ),
            );
            $this->dbforge->add_column('teo_indicadores', $fields);
              /**Cambios en la tabla desiciones */
              $fields = array(               
                'color' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '20',
                            'null' => true,
                            'default'=>'#023E94',
                            'comment' => 'Color con el que se identificara la desiciones'
                ),
            );
            $this->dbforge->add_column('decisiones', $fields);
             /**Cambios en la tabla operadores */
             $fields = array(               
                'color' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '20',
                            'null' => true,
                            'default'=>'#309402',
                            'comment' => 'Color con el que se identificara el operador'
                ),
            );
            $this->dbforge->add_column('operadores', $fields);
        }

        public function down()
        {
            return "";
        }
}