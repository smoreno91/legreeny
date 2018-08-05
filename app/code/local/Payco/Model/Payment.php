<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_GooglePayments
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Payco_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    protected $_code  = 'Payco';
    protected $_formBlockType = 'Payco/form';

    const ACTION_AUTHORIZE = 0;
    const ACTION_AUTHORIZE_CAPTURE = 1;

    public function __construct()
    {
        echo 'hello';
    }

    public function getOrderPlaceRedirectUrl()
    {
        return $this->getRedirectUrl();
    }

    public function getRedirectUrl()
    {
        #Mage::exception($this, 'worldpay');
        #throw new Exception('qwe')
        die('test2');
        $_url = Mage::getUrl('Payco/redirect');
        echo "url: {$_url}<br />";
        return $_url;
    }
}