<?php
class EM_Multidealpro_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	
		$setting 	=	Mage::helper('multidealpro'); 
		if($setting->checkEnable() == 1){
			$this->loadLayout();
			$this->initLayoutMessages(array('catalog/session', 'tag/session', 'checkout/session'));

			$layout = Mage::helper("multidealpro")->getMainDeal_Layout();
			switch ($layout) {
				case 'one_column': $template="page/1column.phtml";break;
				case 'two_columns_left': $template="page/2columns-left.phtml";break;
				case 'two_columns_right': $template="page/2columns-right.phtml";break;
				default: $template="page/3columns.phtml";break;
			}
			$this->getLayout()->getBlock('root')->setTemplate($template);

			$this->renderLayout();
		}else $this->_redirectUrl(Mage::getBaseUrl());
    }
	
	public function editAction()
	{
		$helper 	=	Mage::helper('multidealpro');
		$post		=	Mage::app()->getRequest()->getPost();
		$deal_id	=	$post['id'];

		$deal = Mage::getModel('multidealpro/multidealpro')->load($deal_id);
		$deal = $helper->checkDeal($deal);
		if($deal->getStatus() == 1 )			$result['check'] = 1;
		elseif($deal->getStatus() == 2 )		$result['check'] = 2;
		else								$result['check'] = 0;

		if($result['check'] == 1){
			$where = 'multidealpro.status = 1 AND multidealpro.deal_id = '.$deal_id;
			$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where);

			$_product	=	$collection->getFirstItem();
			$theProductBlock = new Mage_Catalog_Block_Product;

			$result['qty']		= $helper->getQtyleft($_product);
			$result['label']	= $helper->getLabel($_product);

			$result['clock']	=		$helper->getClock($_product);
			$result['btn_cart']	.= 	'<p><button type="button" title="'.$this->__('Add to Cart').'" class="button btn-cart" onclick="setLocation(\''.$theProductBlock->getAddToCartUrl($_product).'\')"><span><span>'.$this->__('Add to Cart').'</span></span></button></p>';

			$result['addto']	= '<ul class="add-to-links">';
			if (Mage::helper('wishlist')->isAllow())
				$result['addto']	.=		'<li><a href="'.Mage::helper('wishlist')->getAddUrl($_product).'" class="link-wishlist" title="'.$this->__('Add to Wishlist').'">'.$this->__('Add to Wishlist').'</a></li>';
			if($_compareUrl=Mage::helper('catalog/product_compare')->getAddUrl($_product))
				$result['addto']	.=		'<li><span class="separator">|</span> <a href="'.$_compareUrl.'" class="link-compare" title="'.$this->__('Add to Compare').'">'.$this->__('Add to Compare').'</a></li>';
			$result['addto']	.=	'</ul>';

		}else{
			$result['html'] = '<div class="msg_soldout">'.$this->__('Time Out').'</div>';
		}

		if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT']))
		{	// if IE
			echo json_encode($result, JSON_HEX_TAG);exit;
		}else{	// other browser
			echo json_encode($result);exit;
		}
	}
	
	public function testcronAction(){
		Mage::getModel('multidealpro/multidealpro')->refeshData();
	}

	public function resetDemoAction(){
		$check = 1111;
		if($check == 1){
			$now 	= Mage::helper('multidealpro')->dealtime();
			$collection = Mage::getModel('multidealpro/multidealpro')->getCollection();

			if(count($collection->getData()) > 0){
				foreach($collection as $key => $value){
					$value = Mage::getModel('multidealpro/multidealpro')->load($value->getId());
					$product = Mage::getModel('catalog/product')->load($value->getProductId());
					$i = rand(1,2);
					if($i == 1){
						$rand_day = (rand(15,90)*24*60*60)-rand(1,60);
						$from = $now + $rand_day;
						$to = $from + $rand_day;
						$value->setStatus(0);
					}else{
						$rand_day = (rand(15,90)*24*60*60)-rand(1,60);
						$from 	= $now - (24*60*60);
						$to		= $now + $rand_day;
						$value->setStatus(1);
					}
					$value->setDateFrom($from);
					$value->setDateTo($to);
					$value->save();

					echo "Reset......".$product->getName()."<br>";
				}
			}
			echo "<br><br>######  Reset All Completed   ######";
		}else{
			header("Location: ".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));exit;
		}
	}

	public function resetSessionAction()
    {
        $reset = Mage::app()->getRequest()->getParam('reset');
		$session = Mage::getSingleton('core/session');
		$session->setEmTutorialDeal($reset);
		print_r($session->getEmTutorialDeal());exit;
    }
}