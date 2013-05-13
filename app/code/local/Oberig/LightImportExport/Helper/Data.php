<?php

class Oberig_LightImportExport_Helper_Data extends Mage_Core_Helper_Abstract
{
	static $aAttributeSetInUse = array(10,11,13,14);
    public function getEntityAttributeSetValues(){
    	$entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();
    	
		$aValues = Mage::getResourceModel('eav/entity_attribute_set_collection')
						->setEntityTypeFilter($entityType->getId())
		                ->load()
		                ->toOptionArray();
		
		foreach ($aValues as $k=>$v){			
			if(!in_array($v['value'],self::$aAttributeSetInUse)){
				unset($aValues[$k]);
			}
		}
		
		return $aValues;                
    }
}
