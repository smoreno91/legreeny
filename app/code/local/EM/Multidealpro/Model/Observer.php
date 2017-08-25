<?php
class EM_Multidealpro_Model_Observer 
{
	private static $_handleCustomerFirstOrderCounter = 1;

	/* Update custom layout */
    public function changeLayoutEvent($observer){
		$setting	=	Mage::helper("multidealpro");

		$enable 	= $setting->getRecentDeal_EnableRecent();
		$reference 	= $setting->getRecentDeal_BlockReference();

		if($enable == 1){
			if($reference != 'none'){
				$type 		= $setting->getSidebar_BlockData_RecentDeal_Type();
				$name 		= $setting->getSidebar_BlockData_RecentDeal_Name();
				$template 	= $setting->getSidebar_BlockData_RecentDeal_Template();
				$position 	= $setting->getRecentDeal_Position();

				if(!preg_match("/^(before|after)\=\"[a-zA-Z0-9_.-]+\"/",$position))
					$position = "";

				if($reference == 'all'){
					$xml = 		'<reference name="left">';
					$xml .= 		'<block type="'.$type.'" '.$position.' name="'.$name.'" template="'.$template.'"/>';
					$xml .= 	'</reference>';
					$xml .= 	'<reference name="right">';
					$xml .= 		'<block type="'.$type.'" '.$position.' name="'.$name.'" template="'.$template.'"/>';
					$xml .= 	'</reference>';
				}else{
					$xml = 		'<reference name="'.$reference.'">';
					$xml .= 		'<block type="'.$type.'" '.$position.' name="'.$name.'" template="'.$template.'"/>';
					$xml .= 	'</reference>';
				}

				$update = $observer->getEvent()->getLayout()->getUpdate();
				$update->addUpdate($xml);
			}
		}
        return $this;
    }

	public function handleCartAddComplete($observer){
		$now = Mage::helper("multidealpro")->dealtime();
		$cart = Mage::getSingleton('checkout/cart');
		foreach($cart->getItems() as $item){
			$collection = Mage::getModel('multidealpro/multidealpro')->load($item->getProductId(),"product_id");
			if($collection->getId()){
				if($collection->getStatus() == 1 && $collection->getDateFrom() < $now && $now < $collection->getDateTo()){
					$item->setCustomPrice($collection->getprice());
					$item->setOriginalCustomPrice($collection->getprice());

					if($item->getQty() > $collection->getQty()){
						$item->setQty($collection->getQty());
						
					}
					$item->save();
				}
			}
		}
		return true;
	}

	public function handleUpdateCart($observer){
		$cartHelper = Mage::helper('checkout/cart');
		$items = $cartHelper->getCart()->getItems();
		foreach($items as $key=>$item){
			$collection = Mage::getModel('multidealpro/multidealpro')->load($item->getProductId(),"product_id");
			if($collection->getId()){
				$item->setCustomPrice($collection->getprice());
				$item->setOriginalCustomPrice($collection->getprice());
				if($item->getQty() > $collection->getQty()){
					$item->setQty($collection->getQty());
				}
				$item->save();
			}
		}
		return true;
	}

	public function handleCustomerFirstOrder($observer){
		if (self::$_handleCustomerFirstOrderCounter > 1) {
			return $this;
		}
		$cartHelper = Mage::helper('checkout/cart');
		$items = $cartHelper->getCart()->getItems();
		foreach ($items as $item) {
			$collection = Mage::getModel('multidealpro/multidealpro')->load($item->getProductId(),"product_id");
			if($collection->getId()){
				$qty	 	= $collection->getQty()-$item->getQty();
				if($qty <= 0){
					$collection->setStatus(2);
					$collection->setIsActive(2);
				}
				$collection->setQty($qty);
				$collection->setQtySold($collection->getQtySold()+$item->getQty());
				$collection->save();
			}
		}
		self::$_handleCustomerFirstOrderCounter++;
		Mage::dispatchEvent('customer_first_order', array('order' => $observer->getEvent()->getOrder()));
        return $this;
    }

}