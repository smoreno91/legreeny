<?php
class EM_Multidealpro_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function __call($name, $args) {
		if (method_exists($this, $name))
			call_user_func_array(array($this, $name), $args);
		elseif (preg_match('/^get([^_][a-zA-Z0-9_]+)$/', $name, $m)) {
			$segs = explode('_', $m[1]);
			foreach ($segs as $i => $seg){
				//$segs[$i] = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $seg));
				$seg = preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $seg);
				$seg = preg_replace('/([A-Z])([A-Z])/', '$1_$2', $seg);
				$segs[$i] = strtolower(preg_replace('/([A-Z])([A-Z])/', '$1_$2', $seg));
			}
			$value = Mage::getStoreConfig('multidealpro/'.implode('/', $segs));
			if (!$value) $value = @$args[0];
			return $value;
		}
		else 
			call_user_func_array(array($this, $name), $args);
	}

	public function checkEnable(){
		$check = 0;	// disabled
		if($this->getGeneral_EnableMultideal() == 1) $check = 1;		// enabled
		return $check;
	}

	public function getQtyleft($product){
		$result = 0;
		if($product->isSaleable()){
			$result = $product->getDealQty();
		}

		$html = "<div class=\"deal_qty\">";
		if($product->getDealStatus() == 0)
			$html .=	"<span class=\"qty_left soon\">".$this->__("Time left to buy !")."</span>";
		else
			$html .=	"<span class=\"qty_left soldin\">".$this->__("QTY : %d item(s) left !",$result)."</span>";
		$html .= "</div>";

		return $html;
	}

	public function getLabel($_product) {
		$specialPrice = $_product->getSpecialPrice();
		$regularPrice = $_product->getPrice();
		$status = $_product->getDealStatus();
		$html = "";

		if($status == 0){
			$html	.=	"<div class='sale_off queued'>".$this->__("Coming <span>Soon</span>")."</div>";
		}elseif($status == 1){
			if($specialPrice > 0 && $regularPrice != 0){
				if($specialPrice < $regularPrice){
					$off	=	 number_format(100*(float)($regularPrice-$specialPrice)/$regularPrice,0);
					$html	.=	"<div class='sale_off'>".$this->__("off <span>%d%s</span>",$off,"%")."</div>";
				}
			}
		}
		return $html;
	}

	public function getClock($_product){
		return Mage::helper("multidealpro/clock")->getClock($_product);
	}

	public function getHtmlClock($data,$type=3){
		return Mage::helper("multidealpro/clock")->getHtmlClock($data,$type);
	}

	public function checkAllDeals(){
		$collection = Mage::getModel('multidealpro/multidealpro')->getCollection()->addFieldToFilter("is_active",1);
		foreach($collection as $item)
			$this->checkDeal($item);
	}

	public function checkDeal($deal){
		if($deal->getIsActive() == 1){
			$tmp_status = 	$deal->getStatus();
			$now 	= 	$this->dealtime();
			$from	=	$deal->getDateFrom();
			$to		=	$deal->getDateTo();

			if($from > $now ){		// queued
				$status	=	0;
			}elseif($from <= $now ){
				if($to > $now){		// running
					$status	=	1;
				}else{				// end
					$status	=	2;
				}
			}

			if($tmp_status === "" or $status != $tmp_status){
				$resource = Mage::getSingleton('core/resource'); // Get the resource model
				$writeConnection = $resource->getConnection('core_write'); // Retrieve the write connection
				$table = $resource->getTableName('multidealpro/deal'); // Retrieve our table name

				$query = "UPDATE {$table} SET status = '".$status."'";
				$query .= " WHERE deal_id = ". (int)$deal->getId();
				$writeConnection->query($query); // Execute the query

				$deal->setStatus($status);
			}
		}
		return $deal;
	}

	public function getInfo($type=0,$label=0,$price=0,$cart=0,$addto=0){
		$data['type'] 	= $type;
		$data['label'] 	= $label;
		$data['price'] 	= $price;
		$data['cart'] 	= $cart;
		$data['addto'] 	= $addto;
		
		return json_encode($data);
	}

	public function dealtime($time=''){
		if($time == '') $time = now();
		$tmp_now = 	Mage::app()->getLocale()->storeDate(
						Mage::app()->getStore(),
						Varien_Date::toTimestamp($time),
						true
					);
		return $tmp_now->getTimestamp();
	}

	public function dateformat($date){
		$format = $this->getGeneral_FormatDate('m/d/Y H:i:s');
		return date($format,$date);
	}
}