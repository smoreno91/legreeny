<?php
class EM_Multidealpro_Model_Update extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('multidealpro/update');
    }
	
	public function version($ver="")
    {
		if($ver != ""){
			$func = "version_".str_replace(".","",$ver);
			$this->$func();
		}
		return true;
    }

	protected function version_101()
    {
		$helper = Mage::helper("multidealpro");
		$collection =  Mage::getModel('multidealpro/multidealpro')->getCollection();

		if($collection->getSize() > 0){
			foreach($collection as $value){
				$model 	= Mage::getModel('multidealpro/multidealpro')->load($value->getId());
				$product = Mage::getModel('catalog/product')->load($value->getProductId());
				$qty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
				
				if($model->getStatus() == 0){
					$str = explode("tmp",base64_decode($model->getHasNew()));

					$model->setPrice($str[0])
						->setQty($qty)
						->setDateFrom($helper->dealtime($str[1]))
						->setDateTo($helper->dealtime($str[2]));
				}
				elseif($model->getStatus() == 1){
					$model->setPrice($product->getSpecialPrice())
						->setQty($qty)
						->setDateFrom($helper->dealtime($product->getSpecialFromDate()))
						->setDateTo($helper->dealtime($product->getSpecialToDate()));

					$product->setSpecialPrice("")
						->setSpecialFromDate("")
						->setSpecialToDate("");

					Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
					$product->save();
				}else{
					$str = explode("tmp",base64_decode($model->getHasEnd()));

					$model->setPrice($str[0]);
					$model->setQty($qty);
					$model->setDateFrom($helper->dealtime($str[1]));
					$model->setDateTo($helper->dealtime($str[2]));
				}
				$model->save();
			}
		}

		return true;
    }

}