<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Field_Table_Formula extends CI_Migration {

    public function up()
    {                
            $fields = array(
                    'stringformula' => array(
                            'type' =>'TEXT',
                            'null' => true,
                            'comment' => 'formula en formato string'
                    )
                    );
                    $this->dbforge->add_column('teo_formula', $fields);               
    }

    public function down()
    {
        return "";
    }
}