<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_Table_Cjn_Prediccion_Intervalo extends CI_Migration
{
    public function up()
    {
        /**Tabla decisiones */
        $this->dbforge->add_field(array(
                        'id_cjn_prediccion_intervalo' => array(
                            'type' => 'INT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                        ),
                        'id_dp_clientes' => array(
                            'type' => 'INT',
                            'constraint' => '5',
                            'null' => true,
                            'comment' => 'Id del cliente'
                        ),
                        'pcrc' => array(
                            'type' => 'VARCHAR',
                            'null' => true,
                            'comment' => 'PCRC'
                        ),
                        'cola' => array(
                            'type' => 'VARCHAR',
                            'null' => true,
                            'comment' => 'Cola'
                        ),
                        'fecha_intervalo' => array(
                            'type' => 'DATETIME',
                            'comment' => 'Fecha del intervalo'
                        ),
                        'md1_combinaciones' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 1 para  predicciones'
                        ),
                        'md2_tipo_dia' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 2 para  predicciones'
                        ),
                        'md3_serie_tiempo' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 3 para  predicciones'
                        ),
                        'md4_funciones_densidad' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 4 para  predicciones'
                        ),
                        'md5_funciones_densidad_td' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 5 para  predicciones'
                        ),
                        'md6_holt_winter' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 6 para  predicciones'
                        ),
                        'md7_hw_combinacion' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 7 para  predicciones'
                        ),
                        'md8_hw_combinacion_2' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 8 para  predicciones'
                        ),
                        'md9_suavizacion_exponencial' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 9 para  predicciones'
                        ),
                        'md10_suavizacion_exponencial_c' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 10 para  predicciones'
                        ),
                        'md11_suavizacion_exponencial_c2' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 11 para  predicciones'
                        ),
                        'md12_regresion_lineal' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 12 para  predicciones'
                        ),
                        'md13_loess' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 13 para  predicciones'
                        ),
                        'md14_redes_neuronales' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 14 para  predicciones'
                        ),
                        'md15_combinacion_modelos' => array(
                            'type' => 'INT',
                            'comment' => 'Modelo 15 para  predicciones'
                        ),
                ));
        $this->dbforge->add_key('id_cjn_prediccion_intervalo', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('cjn_prediccion_intervalo', true, $attributes);
    }
    public function down(){
        return "";
    }
}
