<?php
class EM_Multidealpro_Adminhtml_MultidealproController extends Mage_Adminhtml_Controller_Action
{
	/**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/delete');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro');
                break;
        }
    }

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('multidealpro/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}

	public function indexAction() {
		if(Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro')){
			$this->_initAction()
				->renderLayout();
		}else{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__("You don't have permission to save item. Maybe this is a demo store."));
			$this->_redirect('*/dashboard/');
		}
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('multidealpro/multidealpro')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			if($model->getId()){
				$_product = Mage::getModel('catalog/product')->load($model->getProductId());
				$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($model->getProductId());

				$str['name']  	= $_product->getName();
				$str['price'] 	= Mage::helper('core')->currency($_product->getPrice(), true, false);
				$str['qty'] 	= intval($stock->getQty());

				$model->setData('product_name',Zend_Json::encode($str));
			}

			Mage::register('multidealpro_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('multidealpro/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit'))
				->_addLeft($this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if(Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/save')){
			if ($data = $this->getRequest()->getPost('data')) {
				$data['date_from'] = Mage::helper("multidealpro")->dealtime($data['date_from']);
				$data['date_to'] = Mage::helper("multidealpro")->dealtime($data['date_to']);

				$model = Mage::getModel('multidealpro/multidealpro');
				$model->setData($data)
					->setId($this->getRequest()->getParam('id'));

				try {
					if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
						$model->setCreatedTime(now())
							->setUpdateTime(now());
					} else {
						$model->setUpdateTime(now());
					}

					$model->save();

					Mage::helper('multidealpro')->checkDeal($model);

					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('multidealpro')->__('Item was successfully saved'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);

					if ($this->getRequest()->getParam('back')) {
						$this->_redirect('*/*/edit', array('id' => $model->getId()));
						return;
					}
					$this->_redirect('*/*/');
					return;
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					Mage::getSingleton('adminhtml/session')->setFormData($data);
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					return;
				}
			}
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__('Unable to find item to save'));
		}else{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__("You don't have permission to save item. Maybe this is a demo store."));
		}
        $this->_redirect('*/*/');
	}

	public function deleteAction() {
		if(Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/delete')){
			if( $this->getRequest()->getParam('id') > 0 ) {
				try {
					$model = Mage::getModel('multidealpro/multidealpro');
					 
					$model->setId($this->getRequest()->getParam('id'))
						->delete();
						 
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
					$this->_redirect('*/*/');
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				}
			}
		} else
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__("You don't have permission to delete item. Maybe this is a demo store."));
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
		if(Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/delete')){
			$multidealproIds = $this->getRequest()->getParam('multidealpro');
			if(!is_array($multidealproIds)) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
			} else {
				try {
					foreach ($multidealproIds as $multidealproId) {
						$multidealpro = Mage::getModel('multidealpro/multidealpro')->load($multidealproId);
						$multidealpro->delete();
					}
					Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('adminhtml')->__(
							'Total of %d record(s) were successfully deleted', count($multidealproIds)
						)
					);
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				}
			}
		} else
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__("You don't have permission to delete item. Maybe this is a demo store."));
		$this->_redirect('*/*/index');
    }
	
    public function massStatusAction(){
		if(Mage::getSingleton('admin/session')->isAllowed('emthemes/multidealpro/save')){
			$multidealproIds = $this->getRequest()->getParam('multidealpro');
			if(!is_array($multidealproIds)) {
				Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
			} else {
				try {
					foreach ($multidealproIds as $multidealproId) {
						$multidealpro = Mage::getModel('multidealpro/multidealpro')
							->load($multidealproId)
							->setIsActive($this->getRequest()->getParam('is_active'))
							->setIsMassupdate(true)
							->save();
					}
					$this->_getSession()->addSuccess(
						$this->__('Total of %d record(s) were successfully updated', count($multidealproIds))
					);
				} catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		} else
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multidealpro')->__("You don't have permission to save item. Maybe this is a demo store."));
        $this->_redirect('*/*/index');
    }

	/**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit_tab_product', 'category.product.grid')
                ->toHtml()
        );
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}