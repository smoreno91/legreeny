<?php
class EM_Em0141settings_Model_Config_Shopby
{
	/**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'left', 'label'=>Mage::helper('adminhtml')->__('Left')),
            array('value' => 'right', 'label'=>Mage::helper('adminhtml')->__('Right')),
            array('value' => 'em_layer', 'label'=>Mage::helper('adminhtml')->__('Content')),
        );
    }
}
?>