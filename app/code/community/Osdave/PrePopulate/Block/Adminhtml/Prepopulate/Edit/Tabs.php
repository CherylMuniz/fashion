<?php
class Osdave_PrePopulate_Block_Adminhtml_Prepopulate_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('prepopulate_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('prepopulate')->__('Message'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('prepopulate')->__('Message'),
            'title'     => Mage::helper('prepopulate')->__('Message'),
            'content'   => $this->getLayout()->createBlock('prepopulate/adminhtml_prepopulate_edit_tab_form')->toHtml(),
        ));
       
        return parent::_beforeToHtml();
    }
}