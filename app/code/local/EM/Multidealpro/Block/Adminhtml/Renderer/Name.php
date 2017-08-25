<?php
class EM_Multidealpro_Block_Adminhtml_Renderer_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
	 /**
     * Render a grid cell as options
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
		$product = Mage::getModel('catalog/product')->load($value);
		
		return $product->getName();
    }
}
?>