<?php

class Oberig_Fashion_Block_Catalog_Layer_Filter_Textfield extends Mage_Catalog_Block_Layer_Filter_Abstract
{
    /**
     * Initialize Textfield filter module
     *
     */
    public function __construct()
    {

        parent::__construct();
        $this->_filterModelName = 'catalog/layer_filter_textfield';
    }

    /**
     * Prepare filter process
     *
     * @return Mage_Catalog_Block_Layer_Filter_Textfield
     */
    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());
        return $this;
    }
}
