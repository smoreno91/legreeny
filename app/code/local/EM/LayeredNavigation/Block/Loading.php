<?php
class EM_LayeredNavigation_Block_Loading extends Mage_Core_Block_Template
{
	/**
	 * Check if ajax is disabled. (system > config > EM Layered Navigation)
	 */
	public function isAjaxEnabled() {
		return Mage::helper('layerednavigation')->isAjaxEnabled();
	}
	
	/**
	 * Get base url used in ajax post
	 */
	public function getEndpointUrl() {
		if ($this->getRequest()->getModuleName()=='catalogsearch')
			return Mage::getUrl('layernav/index/search/', array('_secure'=>true));
		else
			return Mage::getUrl('layernav/index/view/', array('_secure'=>true));
	}
}
