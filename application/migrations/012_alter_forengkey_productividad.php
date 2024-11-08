<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_Forengkey_Productividad extends CI_Migration {

        public function up()
        {
            /**Cambiar de nombre el campo idextaccion que es una llave foranea */
            $this->db->query('ALTER TABLE `teo_productividad` CHANGE COLUMN `idextaccion` `idextraccion` INT(11) UNSIGNED NOT NULL;');
           
        }
        public function down()
        {
                return "";
        }
}