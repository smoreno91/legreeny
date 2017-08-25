<?php
class EM_LayeredNavigation_Block_Adminhtml_Image extends Mage_Adminhtml_Block_Template {
	public function getUploadUrl() {
		return $this->getUrl('*/layerednavigation_image/add');
	}
}