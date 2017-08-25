<?php
class EM_Multidealpro_Block_Widgets extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
	protected function getProductCollection()
	{
		// Deal type
		$status = 0;
		if($this->getDealType())
			$status = $this->getDealType();

		$quick = 0;
		if($this->getData('quickdeal')){
			$tmp = $this->getData('quickdeal');
			$tmp = intval($tmp);
			if(is_int($tmp)){
				$quick =  $tmp;
				$now = Mage::helper("multidealpro")->dealtime();
				$limit = $now+($quick*60*60);
			}
		}

		$where = 'multidealpro.status IN ('.$status.') AND multidealpro.is_active=1';
		if($this->getData('category')) $where .= ' AND category.category_id IN ('.$this->getData('category').')';
		if($quick > 0) $where .= ' AND multidealpro.date_to <= "'.$limit.'"';
		$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where,1);
		$collection->getSelect()->group('multidealpro.deal_id');

		if($quick > 0){
			$collection->getSelect()->order('multidealpro.date_to ASC');
		}else{
			// Sort Order
			$sort = $this->getOrderBy();
			if(isset($sort))
			   $orders = explode(' ',$sort);
			if(count($orders))
				$collection->addAttributeToSort($orders[0],$orders[1]);
			else
				$collection->getSelect()->order('name ASC');
		}

		// limit products
		$collection->setPageSize($this->getLimitCount())->setcurPage(1);

		$this->_addProductAttributesAndPrices($collection);
		return $collection;
	}

	public function _prepareLayout(){
		return parent::_prepareLayout();
    }

	public function getQuickTime(){
		$quick = 0;
		if($this->getData('quickdeal')){
			$tmp = $this->getData('quickdeal');
			$tmp = intval($tmp);
			if(is_int($tmp))
				$quick =  $tmp;
		}
		return $quick;
	}

	public function getDealType(){
		$quicktime = $this->getQuickTime();

		if($quicktime > 0)
			$deal_type = 1;
		else
			$deal_type=  $this->getData('deal_type');
		return $deal_type;
	}

	public function getLimitCount($limit=10){
		if($this->getData('limit_count')) $limit = $this->getData('limit_count');
		return $limit;
	}

	public function getColumnCount(){
		return $this->getData('column_count');
	}

	public function getOrderBy(){
		return $this->getData('order_by');
	}

	public function getCustomClass(){
		return $this->getData('custom_class');
	}

	public function getFrontendTitle(){
		return $this->getData('frontend_title');
	}

	public function getItemClass(){
		return $this->getData('item_class');
	}

	public function getFrontendDescription(){
		return $this->getData('frontend_description');
	}

	public function getItemWidth(){
		return $this->getData('item_width');
	}

	public function getItemHeight(){
		return $this->getData('item_height');
	}

	public function getItemSpacing(){
		return $this->getData('item_spacing');
	}

	public function getThumbnailWidth(){
        $tempwidth = $this->getData('thumbnail_width');
        if (!(is_numeric($tempwidth)))
            $tempwidth = 150;
        return $tempwidth;
	}

    public function getThumbnailHeight(){
        $tempheight = $this->getData('thumbnail_height');
       if (!(is_numeric($tempheight)))
            $tempheight = 150;
        return $tempheight;
	}

	public function ShowProductName(){
		return $this->getData('show_product_name');
	}

	public function ShowPrice(){
		return $this->getData('show_price');
	}

	public function ShowClock(){
		return $this->getData('show_clock');
	}

	public function ShowQty(){
		return $this->getData('show_qty');
	}

	public function ShowAddtocart(){
		return $this->getData('show_addtocart');
	}

	public function ShowAddto(){
		return $this->getData('show_addto');
	}

	public function ShowLabel(){
		return $this->getData('show_label');
	}

    protected function _toHtml()
	{
		if($this->getData('choose_template')	==	'custom_template'){
			if($this->getData('custom_theme'))
				$this->setTemplate($this->getData('custom_theme'));
			else
				$this->setTemplate('em_multidealpro/custom_template.phtml');
		}
		else
			$this->setTemplate($this->getData('choose_template'));

		return parent::_toHtml();
	}

}