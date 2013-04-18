<?php

class DevNick_Rotate360_Block_Adminhtml_Tabs_Img360 extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('devnick/product/img360.phtml');
    }
    
    /*protected function _prepareLayout()
    {
        $this->setChild('uploader',
            $this->getLayout()->createBlock('adminhtml/media_uploader')
        );

        $this->getUploader()->getConfig()
            ->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*//*catalog_product_gallery/upload'))
            ->setFileField('img360')
            ->setFilters(array(
                'img360s' => array(
                    'label' => Mage::helper('adminhtml')->__('Images (.gif, .jpg, .png)'),
                    'files' => array('*.gif', '*.jpg','*.jpeg', '*.png')
                )
            ));

        return parent::_prepareLayout();
    }
    
    public function getUploader()
    {
        return $this->getChild('uploader');
    }
    
    public function getUploaderHtml()
    {
        return $this->getChildHtml('uploader');
    }*/
}