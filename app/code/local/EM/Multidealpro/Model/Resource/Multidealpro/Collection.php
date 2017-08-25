<?php
class EM_Multidealpro_Model_Resource_Multidealpro_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	/**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('multidealpro/multidealpro');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Returns pairs block_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('deal_id', 'title');
    }

    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Mage_Cms_Model_Resource_Block_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        if (!is_array($store)) {
            $store = array($store);
        }

        if ($withAdmin) {
            $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
        }

        $this->addFilter('store', array('in' => $store), 'public');

        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }

    /**
     * Join store relation table if there is store filter
     */
    /*protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('multidealpro/deal_store')),
                'main_table.deal_id = store_table.deal_id',
                array()
            )->group('main_table.deal_id');

            /*
             * Allow analytic functions usage because of one field grouping
             *
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }*/

	protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('multidealpro/deal_store')),
                'main_table.deal_id = store_table.deal_id',
                array('store_id')
            )
			->group('main_table.product_id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }

}