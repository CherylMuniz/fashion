<?php

class Oberig_Fashion_Model_Catalog_Resource_Layer_Filter_Decimal extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Decimal
{
	protected function _getSelect($filter) {
		$select = parent::_getSelect($filter);
		
		$fromPart = $select->getPart(Zend_Db_Select::FROM);
		if (!empty($fromPart['cat_index']['joinCondition'])) {
			$fromPart['cat_index']['joinCondition'] = str_replace('cat_index.visibility IN(2, 4)', 'cat_index.visibility IN(2, 3, 4)', $fromPart['cat_index']['joinCondition']);
		}
		$select->setPart(Zend_Db_Select::FROM, $fromPart);
		
		return $select;
	}
}