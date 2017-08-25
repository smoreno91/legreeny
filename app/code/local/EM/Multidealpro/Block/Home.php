<?php
class EM_Multidealpro_Block_Home extends Mage_Catalog_Block_Product_Abstract
{
	protected $_defaultToolbarBlock = 'multidealpro/toolbar';
    protected $_productCollection;

	public function checkHomepage()
    {
		$check = 0;	// disabled
		if(Mage::helper('multidealpro')->getGeneral_EnableMultideal() == 1){		// enabled
			if (Mage::helper('multidealpro')->getGeneral_UseHome() == 1) $check = 1;
		}
		return $check;
    }

	public function getProductCollection()
    {
		$status = 0;
		if(Mage::helper("multidealpro")->getMainDeal_TypeDeal())
			$status = Mage::helper("multidealpro")->getMainDeal_TypeDeal();

		$where = 'multidealpro.status IN ('.$status.') AND multidealpro.is_active=1';
		$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where);

		$collection->setPageSize(Mage::helper("multidealpro")->getMainDeal_ColumnCount()*Mage::helper("multidealpro")->getMainDeal_RowCount())->setcurPage($this->getRequest()->getParam('p',1));

		$this->setCollection($collection);
		$this->_defaultToolbarBlock = 'multidealpro/toolbar';

		return $collection;
    }

	public function setCollection($collection)
    {
        $this->_productCollection = $collection;
        return $this;
    }

    public function getMode()
    {
		$mode	=	$this->getRequest()->getParam('mode','grid');
        //return $this->getChild('toolbar')->getCurrentMode();
        return $mode;
    }

	public function getToolbarHtml()
    {
		$toolbar = $this->getToolbarBlock();

        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }

        $toolbar->enableExpanded();
        $toolbar->setAvailableOrders(array(
			'ordered_qty'  => $this->__('Most Purchased'),
			'name'      => $this->__('Name'),
			'price'     => $this->__('Price')
        ))
        ->setDefaultOrder('ordered_qty')
        ->setDefaultDirection('desc')
        ->setCollection($this->_productCollection);

        $pager = $this->getLayout()->createBlock('multidealpro/toolbar_pager', 'Pager');
        $pager->setCollection($this->_productCollection);
        $toolbar->setChild('multideal_list_toolbar_pager',$pager);

        return $toolbar->_toHtml();
    }

    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock('multidealpro/toolbar', microtime());
        return $block;
    }
}