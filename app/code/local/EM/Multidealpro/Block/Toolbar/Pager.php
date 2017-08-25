<?php
class EM_Multidealpro_Block_Toolbar_Pager extends Mage_Page_Block_Html_Pager
{

	protected function _construct()
    {
        parent::_construct();
        $this->_availableLimit = array(Mage::helper("multidealpro")->getMainDeal_ColumnCount()*Mage::helper("multidealpro")->getMainDeal_RowCount() => Mage::helper("multidealpro")->getMainDeal_ColumnCount()*Mage::helper("multidealpro")->getMainDeal_RowCount());
        
    }
}