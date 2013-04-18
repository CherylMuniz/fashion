<?php
class Oberig_Fashion_Model_Catalog_Resource_Layer_Filter_Attribute extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
{
	public function getCount($filter) {
		// clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
		
		//
		$fromPart = $select->getPart(Zend_Db_Select::FROM);
		if (!empty($fromPart['cat_index']['joinCondition'])) {
			$fromPart['cat_index']['joinCondition'] = str_replace('cat_index.visibility IN(2, 4)', 'cat_index.visibility IN(2, 3, 4)', $fromPart['cat_index']['joinCondition']);
		}
		$select->setPart(Zend_Db_Select::FROM, $fromPart);
		//
		
        // reset columns, order and limitation conditions
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
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        );

        $select
            ->join(
                array($tableAlias => $this->getMainTable()),
                join(' AND ', $conditions),
                array('value', 'count' => "COUNT({$tableAlias}.entity_id)"))
            ->group("{$tableAlias}.value");

        return $connection->fetchPairs($select);
	}
}