<?php
class EM_Blog_Model_System_Config_Source_Comments_Orderby
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'social', 'label'=>Mage::helper('adminhtml')->__('Social')),
            array('value' => 'reverse_time', 'label'=>Mage::helper('adminhtml')->__('Reverse Time')),
            array('value' => 'time', 'label'=>Mage::helper('adminhtml')->__('Time')),
        );
    }
}