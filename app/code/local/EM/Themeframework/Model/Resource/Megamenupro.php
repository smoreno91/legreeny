<?php

class EM_Themeframework_Model_Resource_Megamenupro extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {    
        // Note that the megamenupro_id refers to the key field in your database table.
        $this->_init('themeframework/megamenupro', 'megamenupro_id');
    }
}