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
 * @package    Mage_PagosOnLine
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


$installer = $this;
/* @var $installer Mage_Payco_Model_Mysql4_Setup */



$installer->startSetup();
$installer->run("
    INSERT INTO  `{$this->getTable('sales/order_status')}` (
        `status` ,
        `label`
    ) VALUES (
        'aceptada',  'Aceptada'
    );
    INSERT INTO  `{$this->getTable('sales/order_status')}` (
        `status` ,
        `label`
    ) VALUES (
        'rechazada',  'Rechazada'
    );

    INSERT INTO  `{$this->getTable('sales/order_status')}` (
        `status` ,
        `label`
    ) VALUES (
        'pendiente',  'Pendiente'
    );

    INSERT INTO  `{$this->getTable('sales/order_status_state')}` (
        `status` ,
        `state` ,
        `is_default`
    ) VALUES (
        'aceptada',  'aceptada',  '0001'
    );


    INSERT INTO  `{$this->getTable('sales/order_status_state')}` (
        `status` ,
        `state` ,
        `is_default`
    ) VALUES (
        'rechazada',  'Rechazada',  '0002'
    );

    INSERT INTO  `{$this->getTable('sales/order_status_state')}` (
        `status` ,
        `state` ,
        `is_default`
    ) VALUES (
        'pendiente',  'Pendiente',  '0003'
    );
");



$installer->run("
CREATE TABLE `{$this->getTable('Payco_api_debug')}` (
  `debug_id` int(10) unsigned NOT NULL auto_increment,
  `transaction_id` varchar(255) NOT NULL default '',
  `debug_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `request_body` text,
  `response_body` text,
  PRIMARY KEY  (`debug_id`),
  KEY `debug_at` (`debug_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->endSetup();
