<?php
class EM_Quickshop_IndexController extends Mage_Core_Controller_Front_Action
{
	protected $_cacheTags = array(Mage_Core_Block_Abstract::CACHE_GROUP);
	protected $_cacheLifetime = 86400;
	
    public function indexAction()
    {
    }
	
	    /**
     * Current applied design settings
     *
     * @deprecated after 1.4.2.0-beta1
     * @var array
     */
    protected $_designProductSettingsApplied = array();

    /**
     * Initialize requested product object
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _initProduct()
    {
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $this->getRequest()->getParam('id');

        $params = new Varien_Object();
        $params->setCategoryId($categoryId);

        return Mage::helper('catalog/product')->initProduct($productId, $this, $params);
    }

    /**
     * Initialize product view layout
     *
     * @param   Mage_Catalog_Model_Product $product
     * @return  Mage_Catalog_ProductController
     */
    protected function _initProductLayout($product)
    {
        Mage::helper('catalog/product')->initProductLayout($product, $this);
        return $this;
    }

    /**
     * Recursively apply custom design settings to product if it's container
     * category custom_use_for_products option is setted to 1.
     * If not or product shows not in category - applyes product's internal settings
     *
     * @deprecated after 1.4.2.0-beta1, functionality moved to Mage_Catalog_Model_Design
     * @param Mage_Catalog_Model_Category|Mage_Catalog_Model_Product $object
     * @param Mage_Core_Model_Layout_Update $update
     */
    protected function _applyCustomDesignSettings($object, $update)
    {
        if ($object instanceof Mage_Catalog_Model_Category) {
            // lookup the proper category recursively
            if ($object->getCustomUseParentSettings()) {
                $parentCategory = $object->getParentCategory();
                if ($parentCategory && $parentCategory->getId() && $parentCategory->getLevel() > 1) {
                    $this->_applyCustomDesignSettings($parentCategory, $update);
                }
                return;
            }

            // don't apply to the product
            if (!$object->getCustomApplyToProducts()) {
                return;
            }
        }

        if ($this->_designProductSettingsApplied) {
            return;
        }

        $date = $object->getCustomDesignDate();
        if (array_key_exists('from', $date) && array_key_exists('to', $date)
            && Mage::app()->getLocale()->isStoreDateInInterval(null, $date['from'], $date['to'])
        ) {
            if ($object->getPageLayout()) {
                $this->_designProductSettingsApplied['layout'] = $object->getPageLayout();
            }
            $this->_designProductSettingsApplied['update'] = $object->getCustomLayoutUpdate();
        }
    }

    /**
     * Product view action
     */
    public function viewAction()
    {
		$html = $this->_loadCache();

		if ($html === false) {
			// Get initial data from request
			$categoryId = (int) $this->getRequest()->getParam('category', false);
			$productId  = (int) $this->getRequest()->getParam('id');
				
			$path  = (string) $this->getRequest()->getParam('path');
			$path	=	str_replace("_!_","/",$path);
			$path[0] == "\/" ? $path = substr($path, 1, strlen($path)) : $path;		
			$tableName = Mage::getSingleton('core/resource')->getTableName('core_url_rewrite'); 
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
				
			$query = $write->select()->from(array("main" => $tableName)) 
			->columns(array('product_id' => 'main.product_id')) 
			->where('main.request_path = (?)', $path);
			$readresult=$write->query($query);
			if ($row = $readresult->fetch() ) {
				$productId=$row['product_id'];
			}		
			$this->_cacheTags[] = Mage_Catalog_Model_Product::CACHE_TAG.'_'.$productId;
			//print_r($this->getCacheTags());
			$specifyOptions = $this->getRequest()->getParam('options');

			// Prepare helper and params
			$viewHelper = Mage::helper('quickshop/product_view');


			$params = new Varien_Object();
			$params->setCategoryId($categoryId);
			$params->setSpecifyOptions($specifyOptions);

			// Render page
			try {
				$viewHelper->prepareAndRender($productId, $this, $params);               
				$html = $this->getLayout()->getBlock('root')->toHtml();
				$this->_saveCache($html);
				$this->getResponse()->setBody($html);
			} catch (Exception $e) {
				if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
					if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
						$this->_redirect('');
					} elseif (!$this->getResponse()->isRedirect()) {
						$this->_forward('noRoute');
					}
				} else {
					Mage::logException($e);
					$this->_forward('noRoute');
				}
			}
		} else {
			$this->getResponse()->setBody($html);
		}
    }
	
	protected function getCacheLifetime(){
		return $this->_cacheLifetime;
	}
	
	/**
     * Get Key for caching block content
     *
     * @return string
     */
	public function getCacheKey(){
		return implode('|',array(
			'em_quickshop',
			Mage::app()->getStore()->getId(),
			(int)Mage::app()->getStore()->isCurrentlySecure(),
			Mage::app()->getStore()->getCurrentCurrencyCode(),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
			(int) $this->getRequest()->getParam('category', false),
			(int) $this->getRequest()->getParam('id'),
			$this->getRequest()->getParam('path')
		));
	}
	
	/**
     * Get tags array for saving cache
     *
     * @return array
     */
	protected function getCacheTags(){
		return $this->_cacheTags;
	}
	
	/**
     * Load block html from cache storage
     *
     * @return string | false
     */
    protected function _loadCache()
    {
        if (is_null($this->getCacheLifetime()) || !Mage::app()->useCache(Mage_Core_Block_Abstract::CACHE_GROUP)) {
            return false;
        }
        $cacheKey = $this->getCacheKey();
        /** @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');
        $cacheData = Mage::app()->loadCache($cacheKey);
        if ($cacheData) {
            $cacheData = str_replace(
                $this->_getSidPlaceholder($cacheKey),
                $session->getSessionIdQueryParam() . '=' . $session->getEncryptedSessionId(),
                $cacheData
            );
        }
        return $cacheData;
    }
	
	/**
     * Save block content to cache storage
     *
     * @param string $data
     * @return EM_Quickshop_IndexController
     */
    protected function _saveCache($data)
    {
        if (is_null($this->getCacheLifetime()) || !Mage::app()->useCache(Mage_Core_Block_Abstract::CACHE_GROUP)) {
            return false;
        }
        $cacheKey = $this->getCacheKey();
        /** @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');
        $data = str_replace(
            $session->getSessionIdQueryParam() . '=' . $session->getEncryptedSessionId(),
            $this->_getSidPlaceholder($cacheKey),
            $data
        );

        Mage::app()->saveCache($data, $cacheKey, $this->getCacheTags(), $this->getCacheLifetime());
        return $this;
    }
	
	/**
     * Get SID placeholder for cache
     *
     * @param null|string $cacheKey
     * @return string
     */
    protected function _getSidPlaceholder($cacheKey = null)
    {
        if (is_null($cacheKey)) {
            $cacheKey = $this->getCacheKey();
        }

        return '<!--SID=' . $cacheKey . '-->';
    }

    /**
     * View product gallery action
     */
    public function galleryAction()
    {
        if (!$this->_initProduct()) {
            if (isset($_GET['store']) && !$this->getResponse()->isRedirect()) {
                $this->_redirect('');
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
            }
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Display product image action
     */

	public function imageAction()
    {
        $size = (string) $this->getRequest()->getParam('size');
        if ($size) {
            $imageFile = preg_replace("#.*/catalog/product/image/size/[0-9]*x[0-9]*#", '', $this->getRequest()->getRequestUri());
        } else {
            $imageFile = preg_replace("#.*/catalog/product/image#", '', $this->getRequest()->getRequestUri());
        }

        if (!strstr($imageFile, '.')) {
            $this->_forward('noRoute');
            return;
        }

        try {
            $imageModel = Mage::getModel('catalog/product_image');
            $imageModel->setSize($size)
                ->setBaseFile($imageFile)
                ->resize()
                ->setWatermark( Mage::getStoreConfig('catalog/watermark/image') )
                ->saveFile()
                ->push();
        } catch( Exception $e ) {
            $this->_forward('noRoute');
        }
    }
}
