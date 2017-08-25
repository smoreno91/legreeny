<?php
class EM_Minislideshow_Model_Navtype extends Varien_Object
{
    static public function getOptionArray()
    {
        return array(
            array('value' => 'nav:true', 'label'=>Mage::helper('adminhtml')->__('Next/Pre')),
            array('value' => 'dots:true', 'label'=>Mage::helper('adminhtml')->__('Bullet')),
            /*array('value' => 'dots:true,dotData:true', 'label'=>Mage::helper('adminhtml')->__('Number')),*/
            /*array('value' => 'thumbnail', 'label'=>Mage::helper('adminhtml')->__('Thumb')),*/  
        );
    }
}