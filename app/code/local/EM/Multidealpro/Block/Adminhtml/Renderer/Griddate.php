<?php
class EM_Multidealpro_Block_Adminhtml_Renderer_Griddate extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function render(Varien_Object $row)
    {
		$value =  $row->getData($this->getColumn()->getIndex());
		return Mage::helper("multidealpro")->dateformat($value);
    }
}
?>