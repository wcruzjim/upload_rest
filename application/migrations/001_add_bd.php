<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_bd extends CI_Migration
{
    public function up()
    {
        /**Tabla tipos de tipo  indicadores */
        $this->dbforge->add_field(array(
                        'idtipoindicador' => array(
                                'type' => 'INT',
                                'unsigned' => true,
                                'auto_increment' => true,
                                'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                        ),
                        'nombre' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => true,
                                'comment' => 'Nombre del tipo de indicador'
                        ),
                ));
        $this->dbforge->add_key('idtipoindicador', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_tipo_indicador', true, $attributes);

        /**Tabla Formatos */
        $this->dbforge->add_field(array(
                    'idformato' => array(
                            'type' => 'INT',
                            'unsigned' => true,
                            'auto_increment' => true,
                            'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                    ),
                    'formato' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '50',
                            'null' => true,
                            'comment' => 'Formato que se le dará a un campo'
                    ),
            ));
        $this->dbforge->add_key('idformato', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_formatos', true, $attributes);

        /**Tabla Unidad de Medición */
        $this->dbforge->add_field(array(
                'idunidadmedicion' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'nombre' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Unidad de medida estandar.'
                ),
        ));
        $this->dbforge->add_key('idunidadmedicion', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_unidad_medicion', true, $attributes);

        /**Tabla Indicadores*/
        $this->dbforge->add_field(array(
            'idindicador' => array(
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                    'comment' => 'ID unico del regitro de la tabla es auto incrementable'
            ),
            'nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                    'comment' => 'Nombre del Indicador.'
            ),
            'descripcion' => array(
                'type' => 'LONGTEXT',
                'null' => true,
                'comment' => 'descripcion del indicador'
            ),
            'idtipoindicador' => array(
                'type' => 'INT',
                'comment' => 'ID tipo de indicador'
            ),
            'fecha_inicio' => array(
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Fecha de inicio del indicador'
            ),
            'fecha_fin' => array(
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Fecha Fin del Indicador'
            ),
            'fecha_creacion' => array(
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Fecha en la que se creo el indicador'
            ),
            'documento_creacion' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => 'Documento del usuario que crea el indicador'
            ),
            'idformato' => array(
                'type' => 'INT',
                'comment' => 'ID del formato'
            ),
            'idunidadmedicion' => array(
                'type' => 'INT',
                'comment' => 'Id de la unidad de medicion'
            ),
        ));
        $this->dbforge->add_key('idindicador', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_indicadores', true, $attributes);
        $this->db->query('ALTER TABLE `teo_indicadores` CHANGE COLUMN `idtipoindicador` `idtipoindicador` INT(11) UNSIGNED NOT NULL;');        
        $this->db->query('ALTER TABLE `teo_indicadores` ADD CONSTRAINT `FK_indicadores_tipoindicador` FOREIGN KEY (`idtipoindicador`) REFERENCES `teo_tipo_indicador` (`idtipoindicador`) ON UPDATE CASCADE;');        
        $this->db->query('ALTER TABLE `teo_indicadores` CHANGE COLUMN `idformato` `idformato` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_indicadores` ADD CONSTRAINT `FK_indicadores_formato` FOREIGN KEY (`idformato`) REFERENCES `teo_formatos` (`idformato`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_indicadores` CHANGE COLUMN `idunidadmedicion` `idunidadmedicion` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_indicadores` ADD CONSTRAINT `FK_indicadores_unidadmedicion` FOREIGN KEY (`idunidadmedicion`) REFERENCES `teo_unidad_medicion` (`idunidadmedicion`) ON UPDATE CASCADE;');
        
        /**Tabla de Plataforma*/
        $this->dbforge->add_field(array(
                'idplataforma' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'nombre' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Nombre de las plataformas de Konecta'
                ),
            ));
        $this->dbforge->add_key('idplataforma', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_plataformas', true, $attributes);

        /**Tabla de tipo de extraccion*/
        $this->dbforge->add_field(array(
                'idextaccion' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'nombre' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Nombre del medio por el cual se hace la extaccion de la informacion ETL, API'
                ),
            ));
        $this->dbforge->add_key('idextaccion', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_tipo_extraccion', true, $attributes);


        /**Tabla Productividad*/
        $this->dbforge->add_field(array(
                'idproductividad' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'fecha' => array(
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'Fecha del registro de la productividad'
                ),
                'documento' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => true,
                    'comment' => 'Documento del asesor '
                ),
                'capa' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '5',
                    'comment' => 'Capa de la cual se extrae la informacion en plataforma.'
                ),
                'idindicador' => array(
                    'type' => 'INT',
                    'comment' => 'ID del indicador'
                ),
                'valor' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                    'comment' => 'Valor del indicador'
                ),
                'fecha_creacion' => array(
                    'type' => 'DATETIME',                    
                    'null' => true,
                    'comment' => 'Fecha en la que se creo la productividad'
                ),
                'idplataforma' => array(
                    'type' => 'INT',
                    'comment' => 'ID de la Plataforma de donde Se trae la informacion'
                ),
                'idextaccion' => array(
                    'type' => 'INT',
                    'comment' => 'ID de la extraccion(Medio por el cual se extrae la información ejemplo: ETL, Archivo plano, API)'
                ),
            ));
        $this->dbforge->add_key('idproductividad', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_productividad', true, $attributes);
        $this->db->query('ALTER TABLE `teo_productividad` CHANGE COLUMN `idindicador` `idindicador` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_productividad` ADD CONSTRAINT `FK_productividad_indicador` FOREIGN KEY (`idindicador`) REFERENCES `teo_indicadores` (`idindicador`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_productividad` CHANGE COLUMN `idplataforma` `idplataforma` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_productividad` ADD CONSTRAINT `FK_productividad_plataforma` FOREIGN KEY (`idplataforma`) REFERENCES `teo_plataformas` (`idplataforma`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_productividad` CHANGE COLUMN `idextaccion` `idextaccion` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_productividad` ADD CONSTRAINT `FK_productividad_extraccion` FOREIGN KEY (`idextaccion`) REFERENCES `teo_tipo_extraccion` (`idextaccion`) ON UPDATE CASCADE;');

        /**Tabla de tipo de Graficos*/
        $this->dbforge->add_field(array(
                'idtipografica' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'tipo' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Tipo de Grafica ejemplo: dona,Tabla,Colum'
                ),
            ));
        $this->dbforge->add_key('idtipografica', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('tipos_graficas', true, $attributes);

        /**Tabla Graficas*/
        $this->dbforge->add_field(array(
                'idgrafica' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'titulo' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Titulo de la grafica'
                ),
                'subtitulo' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                    'comment' => 'Breve descripcion de la grafica'
                ),
                'idtipografica' => array(
                    'type' => 'INT',
                    'comment' => 'ID de tipo de Grafica ejemplo : 1:Dona, 2:Columna, 3:Tabla'
                ),
            ));
        $this->dbforge->add_key('idgrafica', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_graficas', true, $attributes);
        $this->db->query('ALTER TABLE `teo_graficas` CHANGE COLUMN `idtipografica` `idtipografica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_graficas` ADD CONSTRAINT `FK_grafica_tipografica` FOREIGN KEY (`idtipografica`) REFERENCES `tipos_graficas` (`idtipografica`) ON UPDATE CASCADE;');
           
        /**Tabla de Fromulas*/
        $this->dbforge->add_field(array(
                'idformula' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'formula' => array(
                        'type' => 'JSON',
                        'null' => true,
                        'comment' => 'Formula de la Grafica en formato Json ejemplo {"formula":{"1":"Si","2":"(","3":"i-1","4":"+","5":"i-3","6":")"}}'
                ),
            ));
        $this->dbforge->add_key('idformula', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_formula', true, $attributes);

        /**Tabla Metricas*/
        $this->dbforge->add_field(array(
                'idmetrica' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro d ela tabla'
                ),
                'nombre' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '100',
                        'null' => true,
                        'comment' => 'Nombre de la metrica'
                ),
                'descripcion' => array(
                    'type' => 'MEDIUMTEXT',
                    'null' => true,
                    'comment' => 'Descripción de la metrica'
                ),
                'fecha_inicio' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                    'comment' => 'Fecha en la que empiza a ejecutarse la metrica'
                ),
                'fecha_fin' => array(
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'Fecha en la que se debe dejar de ejecutar la metrica'
                    ),
                'idgrafica' => array(
                    'type' => 'INT',
                    'comment' => 'id de la grafica que utilizará la metrica de ser nesesario'
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
                'idformula' => array(
                    'type' => 'INT',
                    'comment' => 'ID de la formula que utilizará la metrica'
                ),
                'idunidadmedicion' => array(
                    'type' => 'INT',
                    'comment' => 'ID de la unidad de Medida ejemplo: Hora,número, porcentaje'
                ),
                'idformato' => array(
                        'type' => 'INT',
                        'comment' => 'id del tipo de formato que utiliza ejemplo : Fecha_hora, Número'
                    ),
            ));
        $this->dbforge->add_key('idmetrica', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_metricas', true, $attributes);
        $this->db->query('ALTER TABLE `teo_metricas` CHANGE COLUMN `idgrafica` `idgrafica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metricas` ADD CONSTRAINT `FK_metrica_grafica` FOREIGN KEY (`idgrafica`) REFERENCES `teo_graficas` (`idgrafica`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_metricas` CHANGE COLUMN `idformula` `idformula` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metricas` ADD CONSTRAINT `FK_metrica_formula` FOREIGN KEY (`idformula`) REFERENCES `teo_formula` (`idformula`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_metricas` CHANGE COLUMN `idunidadmedicion` `idunidadmedicion` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metricas` ADD CONSTRAINT `FK_metrica_medicion` FOREIGN KEY (`idunidadmedicion`) REFERENCES `teo_unidad_medicion` (`idunidadmedicion`) ON UPDATE CASCADE;');
        $this->db->query('ALTER TABLE `teo_metricas` CHANGE COLUMN `idformato` `idformato` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metricas` ADD CONSTRAINT `FK_metrica_formato` FOREIGN KEY (`idformato`) REFERENCES `teo_formatos` (`idformato`) ON UPDATE CASCADE;');

        /**Tabla de metas de las metricas*/
        $this->dbforge->add_field(array(
                'idmeta' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'fecha_inicio' => array(
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'Fecha inicio de la meta'
                ),
                'fecha_fin' => array(
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'fecha final de la meta'
                ),
                'idpcrc' => array(
                    'type' => 'INT',
                    'null' => true,
                    'comment' => 'id del PCRC'
                ),
                'meta' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '10',
                        'null' => true,
                        'comment' => 'Valor de cumplimiento que se le asignará a la metrica'
                    ),
                'idmetrica' => array(
                    'type' => 'INT',
                    'comment' => 'ID de la metrica'
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
            ));
        $this->dbforge->add_key('idmeta', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_metas_metricas', true, $attributes);
        $this->db->query('ALTER TABLE `teo_metas_metricas` CHANGE COLUMN `idmetrica` `idmetrica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metas_metricas` ADD CONSTRAINT `FK_meta_metrica` FOREIGN KEY (`idmetrica`) REFERENCES `teo_metricas` (`idmetrica`) ON UPDATE CASCADE;');

        /**Tabla de metricas_pcrc*/
        $this->dbforge->add_field(array(
                'idmetricapcrc' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true
                ),
                'idpcrc' => array(
                        'type' => 'INT'
                ),
                'idmetrica' => array(
                        'type' => 'INT'
                ),
                'documento_creacion' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'numero del documento d ela persona que asigna la metrica'
                    ),
                'fecha_creacion' => array(
                        'type' => 'DATETIME',                        
                        'null' => true,
                        'comment' => 'fecha en la que se asigna la metrica'
                    ),
            ));
        $this->dbforge->add_key('idmetricapcrc', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_metricas_pcrc', true, $attributes);
        $this->db->query('ALTER TABLE `teo_metricas_pcrc` CHANGE COLUMN `idmetrica` `idmetrica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metricas_pcrc` ADD CONSTRAINT `FK_metrica_pcrc_metrica` FOREIGN KEY (`idmetrica`) REFERENCES `teo_metricas` (`idmetrica`) ON UPDATE CASCADE;');

        /**Tabla de metricas_clientes*/
        $this->dbforge->add_field(array(
                'idmetricacliente' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true
                ),
                'idcliente' => array(
                        'type' => 'INT'
                ),
                'idmetrica' => array(
                        'type' => 'INT'
                ),
                'documento_creacion' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'numero del documento d ela persona que asigna la metrica'
                    ),
                'fecha_creacion' => array(
                        'type' => 'DATETIME',                        
                        'null' => true,
                        'comment' => 'fecha en la que se asigna la metrica'
                    ),
            ));
        $this->dbforge->add_key('idmetricacliente', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_metricas_cliente', true, $attributes);
        $this->db->query('ALTER TABLE `teo_metricas_cliente` CHANGE COLUMN `idmetrica` `idmetrica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metricas_cliente` ADD CONSTRAINT `FK_metrica_cliente_metrica` FOREIGN KEY (`idmetrica`) REFERENCES `teo_metricas` (`idmetrica`) ON UPDATE CASCADE;');
        
        /**Tabla de metricas_CECO*/
        $this->dbforge->add_field(array(
                'idmetricaceco' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true
                ),
                'idceco' => array(
                        'type' => 'INT'
                ),
                'idmetrica' => array(
                        'type' => 'INT'
                ),
                'documento_creacion' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'numero del documento d ela persona que asigna la metrica'
                    ),
                'fecha_creacion' => array(
                        'type' => 'DATETIME',                        
                        'null' => true,
                        'comment' => 'fecha en la que se asigna la metrica'
                    ),
            ));
        $this->dbforge->add_key('idmetricaceco', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_metricas_ceco', true, $attributes);
        $this->db->query('ALTER TABLE `teo_metricas_ceco` CHANGE COLUMN `idmetrica` `idmetrica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_metricas_ceco` ADD CONSTRAINT `FK_metrica_ceco_metrica` FOREIGN KEY (`idmetrica`) REFERENCES `teo_metricas` (`idmetrica`) ON UPDATE CASCADE;');
        
        /**Tabla de medicion */
        $this->dbforge->add_field(array(
                'idmedicion' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'fecha' => array(
                        'type' => 'DATETIME',                        
                        'null' => true,
                        'comment' => 'Fecha a la que corresponde la medicion'
                ),
                'documento' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Documento de la persona que se le hizo la medicion'
                    ),
                'idmetrica' => array(
                        'type' => 'INT'
                ),
                'valor' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Valor correspondiente al resultado de la medición'
                    ),
                'fecha_creacion' => array(
                        'type' => 'DATETIME',                        
                        'null' => true,
                        'comment' => 'Fecha en la que se hace la medicion.'
                    ),
            ));
        $this->dbforge->add_key('idmedicion', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_medicion', true, $attributes);
        $this->db->query('ALTER TABLE `teo_medicion` CHANGE COLUMN `idmetrica` `idmetrica` INT(11) UNSIGNED NOT NULL;');
        $this->db->query('ALTER TABLE `teo_medicion` ADD CONSTRAINT `FK_metrica_medicion_metrica` FOREIGN KEY (`idmetrica`) REFERENCES `teo_metricas` (`idmetrica`) ON UPDATE CASCADE;');
        
        /**Tabla de Logs*/
        $this->dbforge->add_field(array(
                'idlogregistro' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'fecha' => array(
                        'type' => 'DATETIME',                       
                        'null' => true,
                        'comment' => 'fecha en la que se hace el cambio'
                    ),
                'id_registro' => array(
                        'type' => 'INT',
                        'comment' => 'Id del registro que se cambio'
                ),
                'tabla' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Tabla de la que se cambio el Resgistro'
                ),
                'accion' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Tipo de accion  Editar,Eliminar'
                ),
                'documento' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Documento del usuario que ejecuto la accion'
                    ),
                    'ip' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true,
                        'comment' => 'Ip de donde se ejecuto la accion'
                    ),
            ));
        $this->dbforge->add_key('idlogregistro', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_log_registros', true, $attributes);

        /**Tabla de operadoress*/
        $this->dbforge->add_field(array(
                'idoperador' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true,
                        'comment' => 'ID unico del regitro de la tabla es auto incrementable'
                ),
                'nombre' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Nombre del Operador Matametico'
                ),
                'signo' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                        'null' => true,
                        'comment' => 'Nombre del Operador Matametico'
                )
                                
            ));
        $this->dbforge->add_key('idoperador', true);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('operadores', true, $attributes);
            /**Tabla Service log */
        $this->dbforge->add_field(array(
                'id' => array(
                        'type' => 'INT',
                        'unsigned' => true,
                        'auto_increment' => true                      
                ),
                'route' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '100',
                        'null' => true
                        
                ),
                'uri' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '140',
                        'null' => true
                ),
                'method' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '6',
                        'null' => true
                ),
                'params' => array(
                        'type' => 'TEXT',                        
                        'null' => true
                ),
                'jwt' => array(
                        'type' => 'TEXT',                        
                        'null' => true
                ),
                'ip_address' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '45',
                        'null' => true
                ),
                'time' => array(
                        'type' => 'INT' 
                ),
                'rtime' => array(
                        'type' => 'FLOAT' 
                ),
                'document' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '20',
                        'null' => true
                ),
                'simulated' => array(
                        'type' => 'TINYINT' 
                ),
                'date' => array(
                        'type' => 'DATE' 
                ),
                'response_code' => array(
                        'type' => 'SMALLINT' 
                )
                                
            ));
        $this->dbforge->add_key('id', true);       
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('teo_service_logs', true, $attributes);
    }

    public function down(){
        return "";
    }
}
