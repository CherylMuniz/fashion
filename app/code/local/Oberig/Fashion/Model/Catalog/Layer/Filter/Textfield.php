<?php

class Oberig_Fashion_Model_Catalog_Layer_Filter_Textfield extends Mage_Catalog_Model_Layer_Filter_Abstract
{

    /**
     * Resource instance
     *
     */
    protected $_resource;

    /**
     * Construct textfield filter
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_requestVar = 'attribute';
    }

    /**
     * Retrieve resource instance
     *
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getResourceModel('catalog/layer_filter_textfield');
        }
        return $this->_resource;
    }

    /**
     * Apply textfield option filter to product collection
     *
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = $request->getParam($this->_requestVar);
        if (is_array($filter)) {
            return $this;
        }

        if ($filter) {
            $this->_getResource()->applyFilterToCollection($this, $filter);
            $this->getLayer()->getState()->addFilter($this->_createItem($filter, $filter));
            $this->_items = array();
        }
    
        return $this;
    }

    /**
     * Check whether specified attribute can be used in LN
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @return bool
     */
    protected function _getIsFilterableAttribute($attribute)
    {
        return $attribute->getIsFilterable();
    }

    /**
     * Get data array for building attribute filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();

        $key = $this->getLayer()->getStateKey().'_'.$this->_requestVar;
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $options = $attribute->getFrontend()->getSelectOptions();
            $optionsCount = $this->_getResource()->getCount($this);

            $data = array();            
            
            // my
            foreach ($optionsCount as $key=>$val) {
                         $data[] = array(
                            'label' => $key,
                            'value' => $key,
                            'count' => isset($val) ? $val : 0,
                        );           			
            }            
            // my
            $tags = array(
                Mage_Eav_Model_Entity_Attribute::CACHE_TAG.':'.$attribute->getId()
            );

            $tags = $this->getLayer()->getStateTags($tags);
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
}
