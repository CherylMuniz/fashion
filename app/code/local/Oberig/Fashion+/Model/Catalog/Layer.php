<?php

class Oberig_Fashion_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
	public function apply()
	{
		parent::apply();

		$filters = $this->getState()->getFilters();
		if (!empty($filters)) {
			$select = $this->getProductCollection()->getSelect();
			$fromPart = $select->getPart(Zend_Db_Select::FROM);
			if (!empty($fromPart['cat_index']['joinCondition'])) {
				$fromPart['cat_index']['joinCondition'] = str_replace('cat_index.visibility IN(2, 4)', 'cat_index.visibility IN(2, 3, 4)', $fromPart['cat_index']['joinCondition']);
			}
			$select->setPart(Zend_Db_Select::FROM, $fromPart);
		}
		return $this;
	}
}