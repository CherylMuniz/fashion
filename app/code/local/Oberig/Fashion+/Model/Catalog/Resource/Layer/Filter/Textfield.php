<?php

class Oberig_Fashion_Model_Catalog_Resource_Layer_Filter_Textfield extends Mage_Core_Model_Mysql4_Abstract
{
	
	protected $textfieldTable = 'catalog_product_entity_varchar';
    /**
     * Initialize connection and define main table name
     *
     */
    protected function _construct()
    {
        $this->_init('catalog/product_index_eav', 'entity_id');
    }

    /**
     * Apply attribute filter to product collection
     *
     */
    public function applyFilterToCollection($filter, $value)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $attribute  = $filter->getAttributeModel();
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.value = ?", $value),
           // $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        );
		
        $collection->getSelect()->join(
            array($tableAlias => $this->textfieldTable),
            join(' AND ', $conditions),
            array()
        );    
        
         return $this;
    }

    /**
     * Retrieve array with products counts per attribute option
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter
     * @return array
     */
    public function getCount($filter)
    {
        // clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        // reset columns, order and limitation conditions
                
		//
		$fromPart = $select->getPart(Zend_Db_Select::FROM);
		if (!empty($fromPart['cat_index']['joinCondition'])) {
			$fromPart['cat_index']['joinCondition'] = str_replace('cat_index.visibility IN(2, 4)', 'cat_index.visibility IN(2, 3, 4)', $fromPart['cat_index']['joinCondition']);
		}
		$select->setPart(Zend_Db_Select::FROM, $fromPart);
		//
        
        
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $connection = $this->_getReadAdapter();
        $attribute  = $filter->getAttributeModel();
        $tableAlias = $attribute->getAttributeCode() . '_idx';

    
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
           	//$connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        );

        $select
            ->join(
                array($tableAlias => $this->textfieldTable),
                join(' AND ', $conditions),
                array('value', 'count' => "COUNT({$tableAlias}.entity_id)"))
            ->group("{$tableAlias}.value");     

        return $connection->fetchPairs($select);
    }
}
