<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Fields_bd extends CI_Migration {

        public function up()
        {
            

            /** Tabla de log_medicion */
            $fields1 = array(
                'idlogmedicion' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
               
            );
            $this->dbforge->modify_column('teo_log_medicion', $fields1);


            /** Tabla de log_registros */
            $fields2 = array(
                'idlogregistro' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
               
            );
            $this->dbforge->modify_column('teo_log_registros', $fields2);


            /** Tabla de medicion */
            $fields3 = array(
                'idmedicion' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
               
            );
            $this->dbforge->modify_column('teo_medicion', $fields3);

            /** Tabla de metas_metricas */
            $fields4 = array(
                'idmeta' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
               
            );
            $this->dbforge->modify_column('teo_metas_metricas', $fields4);

            /** Tabla de metricas_ceco */
            $fields5 = array(
                'idmetricaceco' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla'
                ),
               
            );
            $this->dbforge->modify_column('teo_metricas_ceco', $fields5);


            /** Tabla de metricas_ceco */
            $fields6 = array(
                'idmetricacliente' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla'
                ),
               
            );
            $this->dbforge->modify_column('teo_metricas_cliente', $fields6);

            /** Tabla de metricas_pcrc */
            $fields7 = array(
                'idmetricapcrc' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla'
                ),
               
            );
            $this->dbforge->modify_column('teo_metricas_pcrc', $fields7);


            /** Tabla de productividad */
            $fields8 = array(
                'idproductividad' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla'
                ),
               
            );
            $this->dbforge->modify_column('teo_productividad', $fields8);


            /** Tabla de service logs */
            $fields9 = array(
                'id' => array(
                            'type' => 'BIGINT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla'
                ),
               
            );
            $this->dbforge->modify_column('teo_service_logs', $fields9);
        }

        public function down()
        {
            return "";
        }
}