<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Intervalo_Consolidado extends CI_Migration
{
    public function up()
    {
        /**Tabla dashboard */
        $this->dbforge->add_field(array(
            'iddashboardintervalo' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'nombre' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'Nombre del intervalo'
        ),
        'intervalo' => array(
            'type' => 'VARCHAR',
            'constraint' => '20',
            'null' => true,
            'comment' => 'valor que corresponde el intervalo ejemplo -1 day'
        ),
        'tipo' => array(
            'type' => 'INT',
            'null' => true,
            'comment' => 'tipo de intervalo 1=dia, 2=mes, 3=año'
        ),
    ));
        $this->dbforge->add_key('iddashboardintervalo', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_dashboard_intervalos', true, $attributes);
    /**Agregamos columna de intervalo en la tabla de dashboard */
          $fields = array(               
            'idintervalo' => array(
                'type' => 'INT',
                'null' => true,
                'comment' => 'tipo de intervalo 1=dia, 2=mes, 3=año'
        ),
        );
        $this->dbforge->add_column('teo_dashboard', $fields);     
    }
    public function down()
    {
        return "";
    }
}
