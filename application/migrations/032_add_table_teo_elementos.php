<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Teo_Elementos extends CI_Migration
{
    public function up()
    {
        /**Tabla decisiones */
        $this->dbforge->add_field(array(
                        'idelemento' => array(
                                'type' => 'INT',
                                'unsigned' => true,
                                'auto_increment' => true,
                                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                        ),
                        'tipo_elemento' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'null' => true,
                                'comment' => 'tipo de elemento que se guarda 1.Tabla, 2.Gafico o 3.CardPanel'
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
        $this->dbforge->add_key('idelemento', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_elementos', true, $attributes);
    }
    public function down()
    {
        return "";
    }
}
