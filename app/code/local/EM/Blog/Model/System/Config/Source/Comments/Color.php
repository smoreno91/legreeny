<?php
class EM_Blog_Model_System_Config_Source_Comments_Color
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'light', 'label'=>Mage::helper('adminhtml')->__('Light')),
            array('value' => 'dark', 'label'=>Mage::helper('adminhtml')->__('Dark')),
        );
    }
}