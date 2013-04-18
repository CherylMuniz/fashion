<?php
class Osdave_PrePopulate_Block_Adminhtml_Prepopulate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
      $this->_controller = 'adminhtml_prepopulate';
      $this->_blockGroup = 'prepopulate';
      $this->_headerText = Mage::helper('prepopulate')->__('PrePopulated Messages');
      $this->_addButtonLabel = Mage::helper('prepopulate')->__('Create New Message');
      parent::__construct();
    }
}