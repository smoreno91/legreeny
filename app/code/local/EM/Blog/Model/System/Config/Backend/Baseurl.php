<?php
class EM_Blog_Model_System_Config_Backend_Baseurl extends Mage_Core_Model_Config_Data
{
    /**
     * Valid base url before saving config value
     *
     * @return Mage_Adminhtml_Model_System_Config_Backend_File
     */
    protected function _beforeSave(){
        $value = $this->getValue();
        if(!$value)
            $value = 'blog';
        $this->setValue(Mage::helper('blog/post')->friendlyURL($value));
        return $this;
    }
}