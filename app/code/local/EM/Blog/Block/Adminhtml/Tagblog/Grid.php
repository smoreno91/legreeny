<?php
class EM_Blog_Block_Adminhtml_Tagblog_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {  
        parent::__construct();
        $this->setId('tagGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
  
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('blog/tag_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
  
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('blog')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'id',
        ));
  
        $this->addColumn('name', array(
            'header'    => Mage::helper('blog')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
        
        $this->addColumn('tag_identifier', array(
            'header'    => Mage::helper('blog')->__('Identifier'),
            'align'     =>'left',
            'index'     => 'tag_identifier',
        ));
        
         $this->addColumn('status', array(
            'header'    => Mage::helper('blog')->__('Status'),
            'align'     =>'left',
            'type'      =>'options',  
            'index'     =>'status',
            'options'   =>array(0 => 'Enable',1 => 'disable'),
        ));
  	
  		return parent::_prepareColumns();
    }
  
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('tag');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('blog')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('blog')->__('Are you sure?')
        ));

        $statuses = array(
                        array('label'=>'Disabled', 'value'=>'1'),
                        array('label'=>'Enable', 'value'=>'0'),
        );  
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('blog')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('blog')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
  	
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
