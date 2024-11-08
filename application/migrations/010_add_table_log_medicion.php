<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Log_Medicion extends CI_Migration
{
    public function up()
    {
        /**Tabla log total mediciones */
        $this->dbforge->add_field(array(
                        'idlogmedicion' => array(
                                'type' => 'INT',
                                'unsigned' => true,
                                'auto_increment' => true,
                                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                        ),
                        'registros_medidos' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'null' => true,
                                'comment' => 'Total de registros medidos'
                        ),
                        'fecha_medicion' => array(
                                'type' => 'DATETIME',
                                'null' => true,
                                'comment' => 'Fecha en la que se realizo la medicion'
                        ),
                        'responsable' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '20',
                                'null' => true,
                                'comment' => 'Actor encargado de la Medicion ETL o SI fue un usuario seria la cedula'
                        ),
                        'ip' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '20',
                                'null' => true,
                                'comment' => 'ip de donde se realizo la medicion'
                        ),
                        'tipo' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '20',
                                'null' => true,
                                'comment' => 'Indica si es Medicion Diaria o una Remedicion'
                        ),
                        'fechas_remedicion_inicial' => array(
                                'type' => 'DATETIME',
                                'null' => true,
                                'comment' => 'Fecha en la que se realizo la medicion'
                        ),
                        'fecha_remedicion_final' => array(
                                'type' => 'DATETIME',
                                'null' => true,
                                'comment' => 'Fecha en la que se realizo la medicion'
                        ),
                        'segmento' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '20',
                                'null' => true,
                                'comment' => 'campo que indica por a que medio s ele hizo la medicion All(Todos), PCRC,CECO, CUSTOMER'
                        ),
                        'idsegmento' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => true,
                                'comment' => 'id sel PCRC, CECO o Cliente'
                        ),
                ));
        $this->dbforge->add_key('idlogmedicion', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_log_medicion', true, $attributes);


    }

    public function down(){
        return "";
    }
}
