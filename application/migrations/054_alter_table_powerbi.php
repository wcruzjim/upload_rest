<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_Table_PowerBi extends CI_Migration
{

    public function up()
    {
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard_powerbi` CHANGE COLUMN `idareatrabajopowerbi` `idareatrabajopowerbi` VARCHAR(50);');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_dashboard_powerbi` CHANGE COLUMN `iddashboardpowerbi` `iddashboardpowerbi` VARCHAR(50);');
        $this->db->query('ALTER TABLE `teo_areas_trabajo_powerbi` CHANGE COLUMN `idareatrabajopowerbi` `idareatrabajopowerbi` VARCHAR(50);');
        $this->db->query('ALTER TABLE `teo_dashboard_powerbi` CHANGE COLUMN `iddashboardpowerbi` `iddashboardpowerbi` VARCHAR(50);');
    }

    public function down()
    {
        return "";
    }
}
