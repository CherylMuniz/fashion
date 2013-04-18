<?php

class DevNick_Rotate360_Block_Adminhtml_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    private $parent;
    
    protected function _prepareLayout()
    {
        $this->parent = parent::_prepareLayout();
        
        $this->addTab('img360', array(
                    'label'     => Mage::helper('catalog')->__('360&ordm; Gallery'),
                    'content'   => $this->_translateHtml($this->getLayout()
                        ->createBlock('rotate360/adminhtml_tabs_img360')->toHtml()),
                ));
        return $this->parent;
    }
}