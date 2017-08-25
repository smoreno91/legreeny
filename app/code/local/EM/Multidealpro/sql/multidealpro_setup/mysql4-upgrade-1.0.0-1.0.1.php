<?php
/** @var $installer Mage_Catalog_Model_Resource_Setup */
$installer  = $this;

	$pathFile = Mage::getBaseDir('var').DS.'[EM_Multidealpro]update_1-0-1.txt';
	if(file_exists($pathFile)){
		echo 'Updating EM Multidealpro version 1.0.1 , please come back in some minutes ...';
		exit;
	}
	file_put_contents($pathFile,'Updating EM Multideal version 1.0.1');

	$installer->getConnection()->addColumn(
		$installer->getTable('multidealpro/deal'),
		'price',
		'DECIMAL(12,2) NULL'
	);

	$installer->getConnection()->addColumn(
		$installer->getTable('multidealpro/deal'),
		'qty',
		'DECIMAL(12,2) NULL'
	);

	$installer->getConnection()->addColumn(
		$installer->getTable('multidealpro/deal'),
		'date_from',
		'VARCHAR(30) NULL'
	);

	$installer->getConnection()->addColumn(
		$installer->getTable('multidealpro/deal'),
		'date_to',
		'VARCHAR(30) NULL'
	);

	Mage::getModel("multidealpro/update")->version("1.0.1");

	$installer->run("
		ALTER TABLE `{$this->getTable('multidealpro/deal')}` DROP `has_new`
	");

	$installer->run("
		ALTER TABLE `{$this->getTable('multidealpro/deal')}` DROP `has_end`
	");

	unlink($pathFile);

$installer->endSetup(); 