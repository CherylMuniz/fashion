<?php

class Oberig_Fashion_Helper_Product_Configuration extends Mage_Catalog_Helper_Product_Configuration
{
	public function getFormattedOptionValue($optionValue, $params = null)
    {
    	if (empty($params)) {
    		$params = array('max_length' => null);
    	} else {
    		$params['max_length'] = null;
    	}
    	return parent::getFormattedOptionValue($optionValue, $params);
    }
}