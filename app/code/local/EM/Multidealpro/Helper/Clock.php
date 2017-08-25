<?php
class EM_Multidealpro_Helper_Clock extends EM_Multidealpro_Helper_Data
{
	public function getClock($_product)
	{
		$now = $this->dealtime();
		if($_product->getDealStatus() == 0)	$date = $_product->getDealDateFrom();
		else	$date = $_product->getDealDateTo();

		$html = '			<div class="show_clock clock_style_1">';
		$html .= '				<div class="deal_data" style="display:none">'.$_product->getDealId().'</div>';
		$html .= '				<div class="deal_status" style="display:none">'.$_product->getDealStatus().'</div>';
		$html .= '				<div class="time" style="display:none">'.$date.'</div>';
		$html .= '				<div class="em-multideal-price-box">';
		$html .= '					<span class="regular-price"><span class="price-label">'.$this->__("Regular Price :").'</span>&nbsp;'.'<span class="price">'.Mage::helper('core')->currency($_product->getPrice()).'</span></span>';
		$html .= '					<span class="special-price"><span class="price-label">'.$this->__("Special Price :").'</span>&nbsp;'.'<span class="price">'.Mage::helper('core')->currency($_product->getDealPrice()).'</span></span>';
		$html .= '				</div>';

		$html .= '				<ul class="clock">';
		if($date-$now >= 86400){
			$html .= '				<li>';
			$html .= '					<span class="days">00</span>';
			$html .= '					<p class="timeRefDays">'.$this->__("days").'</p>';
			$html .= '				</li>';
		}
			$html .= '				<li>';
			$html .= '					<ul class="clock_sub">';
			$html .= '						<li>';
			$html .= '							<span class="hours">00</span>';
			$html .= '							<p class="timeRefHours">'.$this->__("hours").'</p>';
			$html .= '						</li>';
			$html .= '						<li>';
			$html .= '							<span class="minutes">00</span>';
			$html .= '							<p class="timeRefMinutes">'.$this->__("minutes").'</p>';
			$html .= '						</li>';
			$html .= '						<li>';
			$html .= '							<span class="seconds">00</span>';
			$html .= '							<p class="timeRefSeconds">'.$this->__("seconds").'</p>';
			$html .= '						</li>';
			$html .= '					</ul>';
			$html .= '				</li>';
		$html .= '				</ul>';
		$html .= '			</div>';

		return $html;
	}

	public function getHtmlClock($data,$type=3){
		if($type == 'short') $type = 2;
		else	$type = 3;

		$deal = Mage::getModel('multidealpro/multidealpro')->getCollection();
		$deal->addFieldToFilter("product_id",$data->getId());

		$html = '';
		if($this->checkEnable() == 1){
			if($deal->getSize() > 0){
				$deal	=	$deal->getFirstItem();
				$deal	=	$this->checkDeal($deal);

				$where = 'multidealpro.product_id = '.$data->getId().' AND multidealpro.is_active = 1 ';
				$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where);

				$_product	=	$collection->getFirstItem();
				if($_product){
					if($type == 2){
						//$html .=	'<div>';
						$html .=		$this->getQtyleft($_product);
						$html .=		'<div class="deal_info" style="display:none">'.$this->getInfo($type).'</div>';
						$html .=		$this->getClock($_product);
						//$html .=	'</div>';
					}elseif($type == 3){
						$html .=	'<div class="show_details">';
						if($_product->getDealStatus() == 0)
							$html .=		'<div class="title"><span style="display:none">'.$this->__("Get it before it's gone !").'</span><span class="qty_left soon">'.$this->__("Time left to buy !").'</span></div>';
						else
							$html .=		'<div class="title"><span>'.$this->__("Get it before it's gone !").'</span></div>';
						$html .=		'<div class="deal_info" style="display:none">'.$this->getInfo($type).'</div>';
						$html .=		$this->getClock($_product);
						$html .=	'</div>';
						if($_product->getDealStatus() == 0)
							$html .=	'<div class="deal_qty"></div>';
						else
							$html .=		$this->getQtyleft($_product);
					}
				}
			}
		}
		return $html;
	}
}