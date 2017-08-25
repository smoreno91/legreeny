<?php
class EM_Blog_Block_Post_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    protected $_pageVarName     = 'page';
    protected function _construct()
    {
        parent::_construct();
        $this->_orderField  = 'created_at';
        /* load avaiable limit */
        $arr= explode(',',Mage::getStoreConfig('blog/info/pagesize'));
		foreach($arr as $value){
			$value = trim($value,' ');
			$this->_availableLimit['detail'][$value] =$value;
			$this->_availableLimit['simple'][$value] = $value;
		}
		$this->setDefaultDirection('desc');
        $this->_availableOrder=array('created_at'=>$this->__('Time'),'title'=>$this->__('Name'));
        if(Mage::registry('current_cat'))
            $this->_availableOrder['position'] = $this->__('Position');
        $this->_availableMode = array('detail' =>  $this->__('Detail'),'simple' => $this->__('Simple'));
        $this->setPageVarName('page');
        /* $this->getCollection($this->getCollection()); */
        $this->setTemplate('em_blog/post/list/toolbar.phtml');
    }
    public function getAvailableLimit()
    {
        $currentMode = $this->getCurrentMode();
        if (in_array($currentMode, array('detail', 'simple'))) {
            return $this->_availableLimit[$currentMode];
        } else {
            return $this->_defaultAvailableLimit;
        }
    }
	
	/**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params=array())
    {
        $urlParams = array();
		$urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
		
		if(!$requestPath = Mage::registry('request_path')){
			$requestPath = str_replace("/blog/","",strstr($this->getRequest()->getPathInfo(),"/blog/"));
		}	
		return str_replace('.html/?','.html?',Mage::getUrl('blog/'.$requestPath, $urlParams));	
    }

    public function getPagerHtml()
    {
        $pagerBlock = $this->getChild('em_blog_pager');

        if ($pagerBlock instanceof Varien_Object) {

            /* @var $pagerBlock Mage_Page_Block_Html_Pager */
            $pagerBlock->setAvailableLimit($this->getAvailableLimit());

            $pagerBlock->setShowPerPage(false);
            $pagerBlock->setUseContainer(false)
                ->setShowAmounts(false);
            $pagerBlock->setLimitVarName($this->getLimitVarName())
                ->setPageVarName('page')
                ->setLimit($this->getLimit())
                /*->setFrameLength(Mage::getStoreConfig('design/pagination/pagination_frame'))
                ->setJump(Mage::getStoreConfig('design/pagination/pagination_frame_skip')) */
                ->setCollection($this->getCollection());

            return $pagerBlock->toHtml();
        }

        return '';
    }

	/**
     * Returns url model class name
     *
     * @return string
     */
    protected function _getUrlModelClass()
    {
        return 'blog/url';
    }
}
