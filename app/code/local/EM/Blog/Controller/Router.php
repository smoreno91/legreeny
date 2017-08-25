<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    AW_Blog
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class EM_Blog_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
	const REWRITE_REQUEST_PATH_ALIAS = 'rewrite_request_path';
    
    public function analyticUrl($url)
    {
		$urlArray = explode('/',$url);
		if(count($urlArray) > 1){
			$requestArray = array($url,$urlArray[count($urlArray)-1]);
			$urlCollection = Mage::getModel('blog/url')->getCollection()
                            ->addFieldToFilter('request_path',array('in'=>$requestArray));
			if($urlCollection->count() > 1){
				foreach($urlCollection as $url){
					if($url->getPostId()){
						$post = Mage::getModel('blog/post')->load($url->getPostId());
						if($post->getUrlKey().'.html'==$requestArray[1])
							return $url;
						continue;	
					}
					if($url->getTagId()){
						$tag = Mage::getModel('blog/tag')->load($url->getTagId());
						if($tag->getTagIdentifier().'.html' == $requestArray[1])
							return $url;
						continue;	
					}	
					$tmp = $url;
				}
				return $tmp;
			}
			else{
				return $urlCollection->getFirstItem();
			}			
		}

		$urlData = Mage::getModel('blog/url')->getCollection()
						->addFieldToFilter('request_path',$url)
						->getFirstItem();
        return $urlData;
    }

    public function match(Zend_Controller_Request_Http $request)
    {
		if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
		$frontName = Mage::getStoreConfig('blog/info/url_key');
		$pathInfo = Mage::app()->getRequest()->getPathInfo();
        
		if(in_array($pathInfo,array("/{$frontName}/","/{$frontName}"))){
            $request->setModuleName('blog');
            $request->setControllerName('index');
            $request->setActionName('index');
            return true;
        }
		$uri =  trim(str_replace("/{$frontName}/","",strstr($pathInfo,"/{$frontName}/")),'/');

		if(!Mage::registry('request_path'))
			Mage::register('request_path',$uri);
		$request->setAlias(self::REWRITE_REQUEST_PATH_ALIAS, "{$frontName}/".$uri);
        if(trim($uri,"/") == "taglist")/* go to page view all tag */
        {
             $request->setControllerName('tag');
             $request->setActionName('taglist');
             return true;
        }

        if($uri)
        {
			$requestInfo = trim(trim($uri),'/');
			$tmp = explode("_", $requestInfo);
			if($tmp[0] == "adminhtml")
				return true;

			$urlData = $this->analyticUrl($requestInfo);
            $request->setModuleName('blog');
			if($postId = $urlData->getPostId())/* detail post page */
			{
				$request->setControllerName('post');
				$request->setActionName('view');
				$request->setParam('id',$postId);
				$contentUrl = explode("/",$uri);
				if(count($contentUrl)>1){
					unset($contentUrl[count($contentUrl)-1]);
					Mage::app()->getRequest()->setParam('cat_id',$this->analyticUrl(implode('/',$contentUrl).'.html')->getCategoryId());
				}	
				return true;
			}
			elseif($tagId = $urlData->getTagId())
			{
				$request->setControllerName('tag');
				$request->setActionName('view');
				$request->setParam('tag_id',$tagId);
				return true;
			}
			elseif($catId = $urlData->getCategoryId())
			{
				$request->setControllerName('category');
				$request->setActionName('view');
				$request->setParam('id',$catId);
				return true;
			} else {
                $tmp = explode('/',$uri);
                if(count($tmp) > 2 && (count($tmp)%2 == 1))
                    return false;
                $pathController = dirname(__FILE__).DS.'..'.DS.'controllers'.DS.ucfirst($tmp[0]).'Controller.php';
                if(!file_exists($pathController))
                    return false;
                require_once $pathController;
                $request->setControllerName($tmp[0]);
                $className = 'EM_Blog_'.ucfirst($tmp[0]).'Controller';
                if(count($tmp) > 2){
                    if(!method_exists($className,$tmp[1].'Action'))
                        return false;
                    $request->setActionName($tmp[1]);
                    for($i = 2;$i < count($tmp);$i++){
                        if (!empty($tmp[$i + 1])) {
                            $request->setParam($tmp[$i],$tmp[$i+1]);
                        }
                    }
                } else if(count($tmp) == 2){
					if(empty($tmp[1]))
						$tmp[1] = 'index';
                    if(!method_exists($className,$tmp[1].'Action'))
                        return false;
                    $request->setActionName($tmp[1]);
                } else {
                    $request->setActionName('index');
                }
                return true;
            }
        }
		return false;
    }
}
